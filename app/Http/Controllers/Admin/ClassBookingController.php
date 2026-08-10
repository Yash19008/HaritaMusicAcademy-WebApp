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

class ClassBookingController extends Controller
{
    public function index()
    {
        $bookings = ClassBooking::with(['student.user', 'teacher.user'])->orderBy('starts_at', 'desc')->get();
        $students = Student::with(['user', 'teacher.user', 'course'])->where('status', 'active')->get();
        $teachers = Teacher::with('user')->get();
        return view('admin.bookings.index', compact('bookings', 'students', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'instrument' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:15',
            'recurrence_mode' => 'nullable|in:one-time,recurring',
            'weeks_count' => 'nullable|integer|min:1|max:52',
        ]);

        $baseStartsAt = Carbon::parse($validated['starts_at']);
        $duration = (int) ($validated['duration_minutes'] ?? 40);
        
        $isRecurring = ($validated['recurrence_mode'] ?? 'one-time') === 'recurring';
        $weeksCount = (int) ($isRecurring ? ($validated['weeks_count'] ?? 1) : 1);

        $createdCount = 0;
        $conflictCount = 0;

        DB::transaction(function () use ($validated, $baseStartsAt, $duration, $weeksCount, &$createdCount, &$conflictCount) {
            for ($i = 0; $i < $weeksCount; $i++) {
                $startsAt = $baseStartsAt->copy()->addWeeks($i);
                $endsAt = $startsAt->copy()->addMinutes($duration);

                // Check for teacher double booking conflict for this specific occurrence
                $conflict = ClassBooking::where('teacher_id', $validated['teacher_id'])
                    ->where('status', 'scheduled')
                    ->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt)
                    ->exists();

                $onLeave = \App\Models\TeacherLeave::where('teacher_id', $validated['teacher_id'])
                    ->where('status', 'approved')
                    ->where('from_date', '<=', $startsAt->toDateString())
                    ->where('to_date', '>=', $startsAt->toDateString())
                    ->exists();

                if ($conflict || $onLeave) {
                    $conflictCount++;
                    continue; // Skip this occurrence
                }

                ClassBooking::create([
                    'student_id' => $validated['student_id'],
                    'teacher_id' => $validated['teacher_id'],
                    'instrument' => $validated['instrument'],
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'duration_minutes' => $duration,
                    'status' => 'scheduled',
                ]);
                $createdCount++;
            }
        });

        if ($conflictCount > 0 && $createdCount == 0) {
            return back()->with('error', 'The selected teacher is already booked during this time slot. No classes were scheduled.');
        } elseif ($conflictCount > 0) {
            return back()->with('warning', "Successfully scheduled {$createdCount} class(es), but {$conflictCount} occurrence(s) failed due to teacher scheduling conflicts.");
        }

        return back()->with('success', "Successfully scheduled {$createdCount} class(es).");
    }

    public function updateStatus(Request $request, ClassBooking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['scheduled', 'completed', 'cancelled'])],
        ]);

        if ($booking->status === 'completed') {
            return back()->with('error', 'Class is already marked as completed. Credits cannot be modified again.');
        }

        if ($validated['status'] === 'completed') {
            DB::transaction(function () use ($booking) {
                // Deduct credit
                $student = $booking->student;
                $student->decrement('credits', 1);

                // Create transaction record
                CreditTransaction::create([
                    'student_id' => $student->id,
                    'amount' => -1,
                    'type' => 'deduction',
                    'description' => 'Class attended on ' . $booking->starts_at->format('M d, Y'),
                ]);

                $booking->update(['status' => 'completed']);
            });

            return back()->with('success', 'Class marked as completed. 1 Credit automatically deducted.');
        } else {
            $booking->update(['status' => $validated['status']]);
            return back()->with('success', 'Class status updated to ' . $validated['status']);
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

        return back()->with('success', 'Class rescheduled successfully.');
    }
}
