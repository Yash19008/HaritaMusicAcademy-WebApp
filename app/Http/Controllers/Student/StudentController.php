<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\CreditTransaction;
use App\Models\Feedback;
use App\Models\Referral;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\BookingService;
use App\Mail\ClassBookedMail;
use App\Mail\RecurringClassesBookedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    private function student(): ?Student
    {
        return Student::where('user_id', auth()->id())->first();
    }

    public function dashboard(): View
    {
        $student   = $this->student();
        $groupIds = $student ? $student->groups()->pluck('student_groups.id')->toArray() : [];
        $nextClass = $student
            ? ClassBooking::where(function($query) use ($student, $groupIds) {
                  $query->where('student_id', $student->id)
                        ->orWhereIn('student_group_id', $groupIds);
              })
                ->where('status', 'scheduled')
                ->with('teacher.user')
                ->orderBy('starts_at')->first()
            : null;

        $completedClassesCount = $student
            ? ClassBooking::where(function($query) use ($student, $groupIds) {
                  $query->where('student_id', $student->id)
                        ->orWhereIn('student_group_id', $groupIds);
              })->where('status', 'completed')->count()
            : 0;
            
        $totalClassesCount = $student ? max($student->credits + $completedClassesCount, 1) : 1;
        
        $transactions = $student
            ? CreditTransaction::where('student_id', $student->id)->latest()->take(5)->get()
            : collect();

        return view('student.dashboard', compact('student', 'nextClass', 'completedClassesCount', 'totalClassesCount', 'transactions'));
    }

    public function myClasses(Request $request): View
    {
        $student  = $this->student();
        $groupIds = $student ? $student->groups()->pluck('student_groups.id')->toArray() : [];
        
        $query = ClassBooking::where(function($query) use ($student, $groupIds) {
                  $query->where('student_id', $student->id)
                        ->orWhereIn('student_group_id', $groupIds);
              })->with('teacher.user')->orderBy('starts_at', 'asc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('starts_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } else {
            $query->whereDate('starts_at', today());
        }

        $bookings = $student ? $query->get() : collect();

        $teachers = \App\Models\Teacher::with('user')->get();

        $reschedulesThisMonth = $student
            ? \App\Models\ClassBooking::where(function($query) use ($student, $groupIds) {
                  $query->where('student_id', $student->id)
                        ->orWhereIn('student_group_id', $groupIds);
              })
                ->where('rescheduled_by', 'Student')
                ->whereMonth('reschedule_requested_datetime', now()->month)
                ->whereYear('reschedule_requested_datetime', now()->year)
                ->count()
            : 0;

        $studentCountry = $student->country ?? '';
        $isIndian = stripos($studentCountry, 'India') !== false;
        
        $lockHours = $isIndian 
            ? (int) \App\Models\Setting::get('indian_reschedule_cutoff_hours', 10)
            : (int) \App\Models\Setting::get('intl_reschedule_cutoff_hours', 12);

        return view('student.my-classes', compact('bookings', 'student', 'teachers', 'reschedulesThisMonth', 'lockHours'));
    }

    public function getAvailableSlots(Teacher $teacher, Request $request, BookingService $bookingService)
    {
        $request->validate(['date' => 'required|date']);
        $slots = $bookingService->getAvailableSlots($teacher, $request->date);
        return response()->json(['slots' => $slots]);
    }

    public function bookClass(Request $request, BookingService $bookingService): RedirectResponse
    {
        return back()->with('error', 'Students cannot book classes on their own. Please contact admin.');
    }

    public function requestReschedule(Request $request, ClassBooking $booking): RedirectResponse
    {
        $student = $this->student();
        $isOwner = $booking->student_id === $student->id;
        $isGroupMember = $booking->student_group_id && $student->groups()->where('student_groups.id', $booking->student_group_id)->exists();
        
        abort_if(!$student || (!$isOwner && !$isGroupMember), 403, 'Unauthorized access to reschedule this booking.');

        $request->validate([
            'reschedule_date' => 'required|date',
            'reschedule_time' => 'required|string',
            'reschedule_reason' => 'required|string',
        ]);

        $newDateTime = \Carbon\Carbon::parse($request->reschedule_date . ' ' . $request->reschedule_time);
        
        $studentCountry = $student->country ?? '';
        $isIndian = stripos($studentCountry, 'India') !== false;
        
        $lockHours = $isIndian 
            ? (int) \App\Models\Setting::get('indian_reschedule_cutoff_hours', 10)
            : (int) \App\Models\Setting::get('intl_reschedule_cutoff_hours', 12);

        if ($booking->starts_at && now()->diffInHours($booking->starts_at, false) < $lockHours) {
            return back()->withErrors(['error' => "You cannot reschedule within {$lockHours} hours of the scheduled class time."]);
        }

        $booking->update([
            'status' => 'reschedule_requested',
            'rescheduled_by' => 'student',
            'reschedule_requested_datetime' => $newDateTime,
            'reschedule_reason' => $request->reschedule_reason,
        ]);

        return back()->with('success', 'Reschedule request submitted and awaiting approval.');
    }

    public function credits(): View
    {
        $student      = $this->student();
        $balance      = $student->credits ?? 0;
        $transactions = $student
            ? CreditTransaction::where('student_id', $student->id)->latest()->get()
            : collect();
        return view('student.credits', compact('student', 'balance', 'transactions'));
    }

    public function feedback(): View
    {
        $student = $this->student();
        $feedbacks = $student ? \App\Models\Feedback::where('student_id', $student->id)->latest()->get() : collect();
        $teachers = \App\Models\Teacher::all();
        return view('student.feedback', compact('feedbacks', 'teachers'));
    }

    public function storeFeedback(Request $request): RedirectResponse
    {
        $student = $this->student();
        if (! $student) return back()->withErrors(['error' => 'Student profile not found.']);

        $data = $request->validate([
            'category'       => ['required', 'string'],
            'target_element' => ['nullable', 'string'],
            'teacher_id'     => ['nullable', 'exists:teachers,id'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'message'        => ['nullable', 'string'],
        ]);

        if ($data['category'] === 'Mentor') {
            $data['target_element'] = null;
        } else {
            $data['teacher_id'] = null;
        }

        \App\Models\Feedback::create(['student_id' => $student->id] + $data);

        return back()->with('success', 'Feedback submitted. Thank you!');
    }

    public function referrals(): View
    {
        $referrals = Referral::where('referrer_id', auth()->id())->latest()->get();
        $total     = $referrals->count();
        $approved  = $referrals->where('status', 'approved')->count();
        return view('student.referrals', compact('referrals', 'total', 'approved'));
    }

    public function storeReferral(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'referred_name'  => ['required', 'string'],
            'referred_email' => ['required', 'email'],
            'interest_role'  => ['nullable', 'string'],
        ]);

        $quantity = (int) \App\Models\Setting::get('referral_bonus_student_credits', 2);

        Referral::create([
            'referrer_id'   => auth()->id(),
            'referrer_role' => 'student',
            'bonus_reward'  => "{$quantity} Free Class" . ($quantity > 1 ? 'es' : ''),
        ] + $data);

        return back()->with('success', 'Referral submitted!');
    }

    public function profile(): View
    {
        $student = $this->student();
        $user    = auth()->user();
        $payments = $student ? \App\Models\Payment::where('converted_student_id', $student->id)->orderBy('transaction_date', 'desc')->get() : collect();
        return view('student.profile', compact('student', 'user', 'payments'));
    }

    public function settings(): View
    {
        $user    = auth()->user();
        $student = $this->student();
        return view('student.settings', compact('user', 'student'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $user->name = $request->input('name', $user->name);
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        return back()->with('success', 'Settings saved.');
    }

    public function uploadIntroVideo(Request $request): RedirectResponse
    {
        $student = $this->student();
        if (! $student) return back()->withErrors(['error' => 'Student profile not found.']);

        $request->validate([
            'intro_video' => ['required', 'file', 'mimes:mp4,mov,avi,webm', 'max:102400'],
        ]);

        // Delete old video if exists
        if ($student->intro_video_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->intro_video_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->intro_video_path);
        }

        $path = $request->file('intro_video')->store('intro-videos', 'public');

        $student->update([
            'intro_video_path'        => $path,
            'intro_video_uploaded_at' => now(),
        ]);

        return back()->with('success', 'Your intro video has been uploaded successfully!');
    }

    public function submitRenewalInterest(Request $request): \Illuminate\Http\JsonResponse
    {
        $student = $this->student();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $interest = $request->input('interest'); // 'interested' or 'declined'

        if (in_array($interest, ['interested', 'declined'])) {
            $student->update(['renewal_interest' => $interest]);

            if ($interest === 'interested') {
                $adminEmail = \App\Models\Setting::get('admin_email', 'admin@harita.com');
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\RenewalInterestMail($student));
            }
        }

        return response()->json(['success' => true]);
    }
}
