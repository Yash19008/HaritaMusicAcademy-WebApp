<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\Feedback;
use App\Models\Referral;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\TeacherPayroll;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Notifications\LeaveRequestedNotification;

class TeacherController extends Controller
{
    private function teacher(): ?Teacher
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    public function dashboard(): View
    {
        $teacher      = $this->teacher();
        $todayClasses = collect();
        $todayDemos   = collect();

        if ($teacher) {
            $todayClasses = ClassBooking::where('teacher_id', $teacher->id)
                ->whereDate('starts_at', today())
                ->where('status', 'scheduled')
                ->with('student')
                ->get();
            
            $todayDemos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
                ->whereDate('scheduled_at', today())
                ->where('status', 'scheduled')
                ->get();
        }

        return view('teacher.dashboard', compact('teacher', 'todayClasses', 'todayDemos'));
    }

    public function myClasses(): View
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassBooking::where('teacher_id', $teacher->id)->with('student')->latest('starts_at')->paginate(15);
        $demos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)->latest('scheduled_at')->paginate(15);
        
        $reschedulesThisMonth = \App\Models\ClassBooking::where('teacher_id', $teacher->id)
            ->where('status', 'rescheduled')
            ->whereMonth('reschedule_requested_datetime', now()->month)
            ->whereYear('reschedule_requested_datetime', now()->year)
            ->count();
            
        $lockHours = (int) \App\Models\Setting::get('reschedule_lock_hours', 24);

        return view('teacher.my-classes', compact('classes', 'demos', 'reschedulesThisMonth', 'lockHours'));
    }

    public function demoClasses(): View
    {
        $teacher = auth()->user()->teacher;
        $demos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)->latest('scheduled_at')->paginate(15);
        return view('teacher.demo-classes', compact('demos'));
    }

    public function leaves(): View
    {
        $teacher = auth()->user()->teacher;
        $leaves = TeacherLeave::where('teacher_id', $teacher->id)->latest()->get();
        $teachers = Teacher::where('id', '!=', $teacher->id)->get();
        return view('teacher.leaves', compact('leaves', 'teachers'));
    }

    public function applyLeave(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'from_date'     => ['required', 'date'],
            'to_date'       => ['required', 'date', 'after_or_equal:from_date'],
            'reason'        => ['required', 'string'],
            'cover_teacher' => ['nullable', 'string'],
        ]);

        $teacher = auth()->user()->teacher;
        $leave = TeacherLeave::create($data + ['teacher_id' => $teacher->id, 'status' => 'pending']);
        
        $admin = User::role('admin')->first();
        if ($admin) {
            $admin->notify(new LeaveRequestedNotification(['leave' => $leave]));
        }

        return back()->with('success', 'Leave applied successfully.');
    }

    public function payroll(): View
    {
        $teacher = $this->teacher();
        $currentMonthName = now()->format('F Y');
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $currentPayroll = TeacherPayroll::firstOrNew([
            'teacher_id' => $teacher->id,
            'month' => $currentMonthName,
        ]);

        if (!$currentPayroll->exists || $currentPayroll->status === 'pending') {
            $classesTaken = \App\Models\ClassBooking::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereBetween('starts_at', [$startOfMonth, $endOfMonth])
                ->count();

            $demoOpportunities = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
                ->count();

            $referralOpportunities = \App\Models\Referral::where('referrer_id', $teacher->user_id)
                ->where('referrer_role', 'teacher')
                ->where('status', 'approved')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->count() * 5;

            $opportunityTaken = $demoOpportunities + $referralOpportunities;

            if (!$currentPayroll->exists || $currentPayroll->per_class_rate == 0) {
                $currentPayroll->per_class_rate = 500; 
            }

            $rate = $currentPayroll->per_class_rate;
            $currentPayroll->classes_taken = $classesTaken;
            $currentPayroll->opportunity_taken = $opportunityTaken;
            $currentPayroll->formula_salary = ($rate * 10) + (0.20 * $rate * 5);
            $currentPayroll->calculated_salary = ($rate * $classesTaken) + (0.20 * $rate * $opportunityTaken);
            
            if (!$currentPayroll->exists) {
                $currentPayroll->status = 'pending';
            }
            $currentPayroll->save();
        }

        $payrolls = TeacherPayroll::where('teacher_id', $teacher->id)->latest('month')->get();
        
        // Get completed classes for current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $recentClasses = \App\Models\ClassBooking::with('student.user')
                            ->where('teacher_id', $teacher->id)
                            ->where('status', 'completed')
                            ->whereBetween('starts_at', [$startOfMonth, $endOfMonth])
                            ->orderBy('starts_at', 'desc')
                            ->get();

        return view('teacher.payroll', compact('payrolls', 'currentPayroll', 'currentMonthName', 'recentClasses', 'teacher'));
    }

    public function feedbacks(): View
    {
        $teacher = auth()->user()->teacher;
        $feedbacks = Feedback::where('teacher_id', $teacher->id)->with('student')->latest()->get();
        return view('teacher.feedbacks', compact('feedbacks'));
    }

    public function referrals(): View
    {
        $referrals = Referral::where('referrer_id', auth()->id())->latest()->get();
        return view('teacher.referrals', compact('referrals'));
    }

    public function storeReferral(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'referred_name'  => ['required', 'string'],
            'referred_email' => ['required', 'email'],
            'interest_role'  => ['required', 'string'],
        ]);

        $bonusRs = (int) \App\Models\Setting::get('referral_bonus_teacher_rs', 500);

        Referral::create($data + [
            'referrer_id' => auth()->id(),
            'referrer_role' => 'teacher',
            'bonus_reward' => "Rs {$bonusRs} Bonus",
            'status' => 'pending'
        ]);
        return back()->with('success', 'Referral submitted.');
    }

    public function profile(): View
    {
        $teacher = auth()->user()->teacher;
        return view('teacher.profile', compact('teacher'));
    }

    public function settings(): View
    {
        $user = auth()->user();
        return view('teacher.settings', compact('user'));
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

    public function calculateLoss(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        if (!$start || !$end) {
            return response()->json(['loss' => 0, 'rate' => 0, 'classes' => 0]);
        }

        $startDate = \Carbon\Carbon::parse($start);
        $endDate = \Carbon\Carbon::parse($end);
        
        $days = $startDate->diffInDays($endDate) + 1;
        
        // 8am to 2am (next day) = 18 hours. 18 hours * 60 = 1080 mins. 1080 / 40 = 27 classes per day
        $classes = $days * 27;

        $teacher = auth()->user()->teacher;
        $currentMonthName = now()->format('F Y');
        
        $payroll = TeacherPayroll::where('teacher_id', $teacher->id)
                                 ->where('month', $currentMonthName)
                                 ->first();
                                 
        $rate = $payroll ? $payroll->per_class_rate : 500;
        
        $loss = $classes * $rate;

        return response()->json([
            'loss' => $loss,
            'rate' => $rate,
            'classes' => $classes
        ]);
    }

    public function resources(): View
    {
        $directory = 'teacher_resources';
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }
        
        $files = $disk->files($directory);
        
        $resources = [];
        foreach ($files as $file) {
            $resources[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => round($disk->size($file) / 1024, 2), // KB
                'last_modified' => date("Y-m-d H:i:s", $disk->lastModified($file)),
                'extension' => pathinfo($file, PATHINFO_EXTENSION),
            ];
        }

        return view('teacher.resources.index', compact('resources'));
    }

    public function downloadResource($filename)
    {
        $path = 'teacher_resources/' . $filename;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($path);
        }
        return abort(404, 'File not found');
    }
}
