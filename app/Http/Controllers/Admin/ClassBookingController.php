<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Services\BookingService;
use App\Mail\ClassBookedMail;
use App\Mail\RecurringClassesBookedMail;
use App\Jobs\ProcessBookingBatchJob;
use Illuminate\Support\Facades\Mail;

class ClassBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassBooking::with(['student.user', 'teacher.user'])->orderBy('starts_at', 'asc');
        
        $today = Carbon::today();
        
        if ($request->filled('start_date')) {
            $query->where('starts_at', '>=', Carbon::parse($request->start_date)->startOfDay());
        } elseif (!$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status')) {
            // Default behavior when no filters are applied
            $query->where('starts_at', '>=', $today);
        }

        if ($request->filled('end_date')) {
            $query->where('starts_at', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();
        $students = Student::with(['user:id,name', 'teacher:id,user_id', 'teacher.user:id,name', 'course:id,name'])
            ->select('id', 'user_id', 'teacher_id', 'course_id', 'credits', 'status')
            ->where('status', 'active')->get();
        $teachers = Teacher::with('user:id,name')->select('id', 'user_id', 'name')->get();
        $groups = \App\Models\StudentGroup::with(['members:id,user_id', 'members.user:id,name', 'teacher:id,user_id', 'teacher.user:id,name'])
            ->select('id', 'name', 'teacher_id', 'status')
            ->where('status', 'active')->get();
        return view('admin.bookings.index', compact('bookings', 'students', 'teachers', 'groups'));
    }

    public function getAvailableSlots(Teacher $teacher, Request $request, BookingService $bookingService)
    {
        $request->validate(['date' => 'required|date']);
        $slots = $bookingService->getAvailableSlots($teacher, $request->date);
        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request, BookingService $bookingService)
    {
        $validated = $request->validate([
            'booking_mode' => 'required|in:individual,group',
            'student_id' => 'nullable|required_if:booking_mode,individual|exists:students,id',
            'student_group_id' => 'nullable|required_if:booking_mode,group|exists:student_groups,id',
            'teacher_id' => 'required|exists:teachers,id',
            'instrument' => 'nullable|string|max:255',
            'recurrence_mode' => 'required|in:one-time,recurring',
        ]);

        $teacher = Teacher::findOrFail($validated['teacher_id']);
        $isGroup = $validated['booking_mode'] === 'group';
        
        $student = null;
        $group = null;
        
        if ($isGroup) {
            $group = \App\Models\StudentGroup::with('members.user')->findOrFail($validated['student_group_id']);
        } else {
            $student = Student::findOrFail($validated['student_id']);
        }

        try {
            if ($validated['recurrence_mode'] === 'one-time') {
                $request->validate([
                    'starts_at' => 'required|date|after:now',
                ]);
                $startsAt = Carbon::parse($request->starts_at);
                $endsAt = $startsAt->copy()->addMinutes(40);
                
                if ($isGroup) {
                    $booking = $bookingService->bookOneTimeGroup($group, $teacher, $startsAt, $endsAt, $request->notes);
                } else {
                    $booking = $bookingService->bookOneTime($student, $teacher, $startsAt, $endsAt, $request->notes);
                }
                
                $queue = $startsAt->diffInHours(now()) < 48 ? 'high' : 'default';
                ProcessBookingBatchJob::dispatch([$booking->id], false)->onQueue($queue);
                
                return back()->with('success', 'Class booked successfully!');
            } else {
                // recurring
                $request->validate([
                    'week_days' => 'required|array|min:1',
                    'time_slot' => 'required|string',
                ]);
                
                if ($isGroup) {
                    $bookings = $bookingService->bookRecurringGroup($group, $teacher, $request->week_days, $request->time_slot, $request->notes);
                } else {
                    $bookings = $bookingService->bookRecurring($student, $teacher, $request->week_days, $request->time_slot, $request->notes);
                }
                
                if (count($bookings) > 0) {
                    $firstStartsAt = Carbon::parse($bookings[0]->starts_at);
                    $queue = $firstStartsAt->diffInHours(now()) < 48 ? 'high' : 'default';
                    $bookingIds = collect($bookings)->pluck('id')->toArray();
                    
                    ProcessBookingBatchJob::dispatch($bookingIds, true)->onQueue($queue);
                    
                    return back()->with('success', 'Successfully scheduled ' . count($bookings) . ' recurring classes.');
                } else {
                    return back()->withErrors(['error' => 'No recurring classes could be booked. Check availability or credits.']);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Class Booking failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Booking failed. Please check availability or try again.']);
        }
    }

    public function updateStatus(Request $request, ClassBooking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['scheduled', 'completed', 'cancelled'])],
        ]);

        if ($booking->status === 'completed' || $booking->status === 'cancelled') {
            $msg = 'Class is already ' . $booking->status . '. Credits cannot be modified again.';
            return request()->ajax() || request()->wantsJson() 
                ? response()->json(['error' => $msg], 400) 
                : back()->with('error', $msg);
        }

        if ($validated['status'] === 'cancelled') {
            DB::transaction(function () use ($booking) {
                // Refund credit
                if ($booking->student_group_id) {
                    $group = \App\Models\StudentGroup::with('members')->find($booking->student_group_id);
                    if ($group) {
                        foreach ($group->members as $member) {
                            $member->increment('credits', 1);
                            CreditTransaction::create([
                                'student_id' => $member->id,
                                'action' => 'Refunded',
                                'quantity' => 1,
                                'reason' => 'Group Class cancellation refund for ' . $booking->starts_at->format('M d, Y'),
                            ]);
                        }
                    }
                } else {
                    $student = $booking->student;
                    if ($student) {
                        $student->increment('credits', 1);
                        CreditTransaction::create([
                            'student_id' => $student->id,
                            'action' => 'Refunded',
                            'quantity' => 1,
                            'reason' => 'Class cancellation refund for ' . $booking->starts_at->format('M d, Y'),
                        ]);
                    }
                }

                $booking->update(['status' => 'cancelled']);
            });

            $msg = 'Class cancelled. 1 Credit automatically refunded.';
            return request()->ajax() || request()->wantsJson()
                ? response()->json(['success' => true, 'message' => $msg, 'student_id' => $booking->student_id, 'refunded' => true])
                : back()->with('success', $msg);
        } else {
            $booking->update(['status' => $validated['status']]);
            $msg = 'Class status updated to ' . ucfirst($validated['status']);
            return request()->ajax() || request()->wantsJson()
                ? response()->json(['success' => true, 'message' => $msg])
                : back()->with('success', $msg);
        }
    }

    public function reschedule(Request $request, ClassBooking $booking)
    {
        if ($booking->status === 'completed') {
            return back()->with('error', 'Cannot reschedule a completed class.');
        }

        $validated = $request->validate([
            'starts_at' => 'required|date',
        ]);

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMinutes($booking->duration_minutes);

        // Check for teacher double booking conflict
        $conflict = ClassBooking::where('teacher_id', $booking->teacher_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'scheduled')
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();
            
        $onLeave = \App\Models\TeacherLeave::where('teacher_id', $booking->teacher_id)
            ->where('status', 'approved')
            ->where('from_date', '<=', $startsAt->toDateString())
            ->where('to_date', '>=', $startsAt->toDateString())
            ->exists();

        if ($conflict) {
            return back()->with('error', 'The selected teacher is already booked during this time slot. Please choose another time.');
        }
        
        if ($onLeave) {
            return back()->with('error', 'The selected teacher is on an approved leave on this date. Please choose another date.');
        }

        $booking->update([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'scheduled', // Reset to scheduled if it was cancelled
        ]);

        \Illuminate\Support\Facades\Log::info("Booking {$booking->id} was rescheduled by admin " . auth()->id());

        return back()->with('success', 'Class rescheduled successfully.');
    }

    public function updateAttendance(Request $request, ClassBooking $booking)
    {
        $validated = $request->validate([
            'teacher_attended' => 'nullable|boolean',
            'student_attended' => 'nullable|boolean',
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully.',
            'booking' => $booking
        ]);
    }
}
