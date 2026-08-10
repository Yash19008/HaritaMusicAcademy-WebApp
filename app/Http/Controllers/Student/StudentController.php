<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\CreditTransaction;
use App\Models\Feedback;
use App\Models\Referral;
use App\Models\Student;
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
        $nextClass = $student
            ? ClassBooking::where('student_id', $student->id)
                ->where('status', 'scheduled')
                ->with('teacher.user')
                ->orderBy('starts_at')->first()
            : null;

        $completedClassesCount = $student
            ? ClassBooking::where('student_id', $student->id)->where('status', 'completed')->count()
            : 0;
            
        $totalClassesCount = $student ? max($student->credits + $completedClassesCount, 1) : 1;
        
        $transactions = $student
            ? CreditTransaction::where('student_id', $student->id)->latest()->take(5)->get()
            : collect();

        return view('student.dashboard', compact('student', 'nextClass', 'completedClassesCount', 'totalClassesCount', 'transactions'));
    }

    public function myClasses(): View
    {
        $student  = $this->student();
        $bookings = $student
            ? ClassBooking::where('student_id', $student->id)->with('teacher.user')->orderBy('starts_at', 'desc')->get()
            : collect();
            
        $teachers = \App\Models\Teacher::with('user')->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        return view('student.my-classes', compact('bookings', 'student', 'teachers', 'courses'));
    }

    public function bookClass(Request $request): RedirectResponse
    {
        $student = $this->student();
        if (!$student) return back()->withErrors(['error' => 'Student not found.']);
        
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'instrument' => 'required|string',
            'starts_at'  => 'required|date',
        ]);
        
        if ($student->credits <= 0) {
            return back()->withErrors(['error' => 'Insufficient credits. Please purchase a package.']);
        }

        $startsAt = \Carbon\Carbon::parse($request->starts_at);
        $endsAt = $startsAt->copy()->addMinutes(40);

        // Check for teacher double booking conflict
        $conflict = ClassBooking::where('teacher_id', $request->teacher_id)
            ->where('status', 'scheduled')
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();

        $onLeave = \App\Models\TeacherLeave::where('teacher_id', $request->teacher_id)
            ->where('status', 'approved')
            ->where('from_date', '<=', $startsAt->toDateString())
            ->where('to_date', '>=', $startsAt->toDateString())
            ->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'The selected teacher is already booked during this time slot. Please choose another time.']);
        }
        
        if ($onLeave) {
            return back()->withErrors(['error' => 'The selected teacher is on an approved leave on this date. Please choose another date.']);
        }

        // Deduct credit
        $student->decrement('credits');
        
        // Log transaction
        CreditTransaction::create([
            'student_id' => $student->id,
            'action' => 'Deducted',
            'quantity' => -1,
            'reason' => 'Booked Class (' . $request->instrument . ')'
        ]);

        ClassBooking::create([
            'student_id' => $student->id,
            'teacher_id' => $request->teacher_id,
            'instrument' => $request->instrument,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'duration_minutes' => 40,
            'status' => 'scheduled',
            'type' => 'one-time'
        ]);

        return back()->with('success', 'Class booked successfully! 1 credit deducted.');
    }

    public function requestReschedule(Request $request, ClassBooking $booking): RedirectResponse
    {
        $student = $this->student();
        if (!$student || $booking->student_id !== $student->id) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'reschedule_date' => 'required|date',
            'reschedule_time' => 'required|string',
            'reschedule_reason' => 'required|string',
        ]);

        $newDateTime = \Carbon\Carbon::parse($request->reschedule_date . ' ' . $request->reschedule_time);

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

        Referral::create([
            'referrer_id'   => auth()->id(),
            'referrer_role' => 'student',
            'bonus_reward'  => '1 Free Class',
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
}
