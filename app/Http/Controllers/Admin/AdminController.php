<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Models\CreditTransaction;
use App\Models\DemoBooking;
use App\Models\Feedback;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentGroup;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\TeacherPayroll;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard(): View
    {
        $totalStudents  = Student::count();
        $totalTeachers  = Teacher::count();
        $todayClasses   = ClassBooking::whereDate('starts_at', today())->where('status', 'scheduled')->count();
        $monthlySales   = Payment::whereMonth('transaction_date', now()->month)
                                 ->whereYear('transaction_date', now()->year)
                                 ->sum('amount');

        $recentActivity = ClassBooking::with(['student', 'teacher'])
                            ->latest()->limit(5)->get();

        $topTeachers = Teacher::withCount('classBookings')
                         ->orderByDesc('class_bookings_count')
                         ->limit(3)->get();

        // Chart data – last 6 months
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $chartLabels     = $months->map(fn ($m) => $m->format('M'))->values()->toArray();
        $revenueData     = $months->map(fn ($m) => (int) Payment::whereYear('transaction_date', $m->year)->whereMonth('transaction_date', $m->month)->sum('amount'))->values()->toArray();
        $studentsData    = $months->map(fn ($m) => Student::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->values()->toArray();
        $teachersData    = $months->map(fn ($m) => Teacher::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->values()->toArray();
        $instrumentData  = ['Vocal', 'Sitar', 'Violin', 'Flute', 'Tabla'];
        $instrumentCount = array_map(fn ($i) => ClassBooking::where('instrument', $i)->count(), $instrumentData);

        $recentLeads = \App\Models\Payment::latest()->limit(5)->get();

        $renewalInterests = Student::where('renewal_interest', 'interested')->latest('updated_at')->get();

        return view('admin.dashboard.index', compact(
            'totalStudents', 'totalTeachers', 'todayClasses', 'monthlySales',
            'recentActivity', 'topTeachers', 'recentLeads', 'renewalInterests',
            'chartLabels', 'revenueData', 'studentsData', 'teachersData',
            'instrumentData', 'instrumentCount'
        ));
    }

    // ─── Students ─────────────────────────────────────────────────────────────

    public function students(): View
    {
        $students = Student::with(['courses', 'teacher', 'groups'])->latest()->get();
        $teachers = Teacher::select('id', 'name')->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        $groups   = StudentGroup::with('members')->withCount('members')->get();
        $creditPackages = \App\Models\CreditPackage::all();
        return view('admin.students.index', compact('students', 'teachers', 'groups', 'courses', 'creditPackages'));
    }

    public function storeStudent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:students,email', 'unique:users,email'],
            'phone'      => ['nullable', 'string'],
            'age'        => ['nullable', 'integer', 'min:1'],
            'country'    => ['nullable', 'string'],
            'timezone'   => ['nullable', 'string'],
            'courses'    => ['nullable', 'array'],
            'courses.*'  => ['exists:courses,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'credits'    => ['nullable', 'integer', 'min:0'],
            'status'     => ['required', 'in:active,inactive'],
            'joining_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date'],
            'enrolled_level' => ['nullable', 'string'],
            'referral_source' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'enrolled_format' => ['required', 'in:Individual,Group'],
            'assigned_group' => ['nullable', 'exists:student_groups,id'],
        ]);

        $password = \Str::random(10);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($password),
            'timezone' => $data['timezone'] ?? 'Asia/Kolkata',
            'status' => $data['status'],
        ]);

        $user->assignRole('student');

        $studentData = collect($data)->except(['assigned_group', 'timezone', 'courses'])->toArray();
        $studentData['user_id'] = $user->id;
        $student = Student::create($studentData);
        
        if ($data['enrolled_format'] === 'Group' && !empty($data['assigned_group'])) {
            $student->groups()->attach($data['assigned_group']);
        }
        if (!empty($data['courses'])) {
            $student->courses()->attach($data['courses']);
        }

        try {
            \Mail::to($user->email)->send(new \App\Mail\StudentCreatedMail($user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to send student credentials email: ' . $e->getMessage());
        }

        return back()->with('success', 'Student added successfully. Login credentials sent to ' . $user->email);
    }

    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        $emailRules = ['required', 'email', 'unique:students,email,' . $student->id];
        if ($student->user_id) {
            $emailRules[] = 'unique:users,email,' . $student->user_id;
        }

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => $emailRules,
            'phone'      => ['nullable', 'string'],
            'age'        => ['nullable', 'integer', 'min:1'],
            'country'    => ['nullable', 'string'],
            'timezone'   => ['nullable', 'string'],
            'courses'    => ['nullable', 'array'],
            'courses.*'  => ['exists:courses,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'credits'    => ['nullable', 'integer', 'min:0'],
            'status'     => ['required', 'in:active,inactive'],
            'joining_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date'],
            'enrolled_level' => ['nullable', 'string'],
            'referral_source' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'enrolled_format' => ['required', 'in:Individual,Group'],
            'assigned_group' => ['nullable', 'exists:student_groups,id'],
        ]);

        if ($student->user) {
            $student->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'timezone' => $data['timezone'] ?? 'Asia/Kolkata',
                'status' => $data['status'],
            ]);
        }

        $student->update(collect($data)->except(['assigned_group', 'timezone', 'courses'])->toArray());
        
        if ($data['enrolled_format'] === 'Group' && !empty($data['assigned_group'])) {
            $student->groups()->sync([$data['assigned_group']]);
        } else {
            $student->groups()->detach();
        }

        if (isset($data['courses'])) {
            $student->courses()->sync($data['courses']);
        } else {
            $student->courses()->detach();
        }

        return back()->with('success', 'Student updated successfully.');
    }

    public function destroyStudent(Student $student): RedirectResponse
    {
        $user = $student->user;
        $student->delete();
        if ($user) {
            $user->delete();
        }
        return back()->with('success', 'Student removed.');
    }

    public function resendCredentials(Student $student): RedirectResponse
    {
        if (!$student->user) {
            return back()->with('error', 'Student does not have an associated user account.');
        }

        $password = \Str::random(10);
        $student->user->update([
            'password' => \Hash::make($password)
        ]);

        try {
            \Mail::to($student->user->email)->send(new \App\Mail\StudentCreatedMail($student->user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to resend student credentials email: ' . $e->getMessage());
            return back()->with('error', 'Password updated but failed to send email.');
        }

        return back()->with('success', 'New credentials generated and sent to ' . $student->user->email);
    }

    public function bulkImportStudents(Request $request)
    {
        $request->validate(['csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']]);

        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = array_map('trim', fgetcsv($handle)); // first row = headers

        // Pre-load lookups for matching by name (case-insensitive)
        $courses  = \App\Models\Course::all()->keyBy(fn($c)  => strtolower(trim($c->name)));
        $teachers = Teacher::all()->keyBy(fn($t) => strtolower(trim($t->name)));

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;
            $data = array_combine($header, array_map('trim', $row));

            $email = strtolower($data['email'] ?? '');
            if (empty($email)) {
                $skipped++;
                $errors[] = "Row missing email address.";
                continue;
            }
            if (Student::withTrashed()->where('email', $email)->exists()) {
                $skipped++;
                $errors[] = "Row ({$email}): Email already exists.";
                continue;
            }

            // Resolve Course by name column
            $course_id = null;
            if (!empty($data['course'])) {
                $course_id = $courses[strtolower($data['course'])]->id ?? null;
            }

            // Resolve Teacher by name column
            $teacher_id = null;
            if (!empty($data['teacher'])) {
                $teacher_id = $teachers[strtolower($data['teacher'])]->id ?? null;
            }

            try {
                Student::create([
                    'name'       => $data['name']       ?? 'Unknown',
                    'email'      => $email,
                    'phone'      => $data['phone']       ?? null,
                    'course_id'  => $course_id,
                    'teacher_id' => $teacher_id,
                    'status'     => strtolower($data['status'] ?? 'active'),
                    'credits'    => (int) ($data['credits'] ?? 0),
                    'enrolled_level'  => $data['level']   ?? null,
                    'referral_source' => $data['referral'] ?? null,
                    'enrolled_format' => $data['format']  ?? 'Individual',
                    'emergency_contact_name'  => $data['emergency_name'] ?? null,
                    'emergency_contact_phone' => $data['emergency_phone'] ?? null,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $skipped++;
                $errors[] = "Row ({$email}): " . $e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'imported' => $imported,
            'skipped'  => $skipped,
            'errors'   => $errors,
        ]);
    }

    public function storeGroup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string'],
            'status'     => ['required', 'in:active,inactive'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $group = StudentGroup::create([
            'name' => $data['name'],
            'status' => $data['status'],
            'teacher_id' => $data['teacher_id'] ?? null
        ]);
        if (! empty($data['student_ids'])) {
            $group->members()->sync($data['student_ids']);
        }

        return back()->with('success', 'Group created.');
    }

    public function updateGroup(Request $request, StudentGroup $studentGroup): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string'],
            'status'     => ['required', 'in:active,inactive'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $studentGroup->update([
            'name' => $data['name'],
            'status' => $data['status'],
            'teacher_id' => $data['teacher_id'] ?? null
        ]);
        if (! empty($data['student_ids'])) {
            $studentGroup->members()->sync($data['student_ids']);
        } else {
            $studentGroup->members()->detach();
        }

        return back()->with('success', 'Group updated.');
    }

    public function destroyGroup(StudentGroup $studentGroup): RedirectResponse
    {
        $studentGroup->delete();
        return back()->with('success', 'Group removed.');
    }

    // ─── Teachers ─────────────────────────────────────────────────────────────

    public function teachers(): View
    {
        $teachers = Teacher::with('course')->latest()->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        return view('admin.teachers.index', compact('teachers', 'courses'));
    }

    public function storeTeacher(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:teachers,email', 'unique:users,email'],
            'phone'          => ['nullable', 'string'],
            'course_id'      => ['nullable', 'exists:courses,id'],
            'week_off'       => ['nullable', 'array'],
            'status'         => ['required', 'in:active,inactive,on_leave'],
            'per_class_rate' => ['nullable', 'numeric', 'min:0'],
            'bio'            => ['nullable', 'string'],
            'youtube_url'    => ['nullable', 'string'],
            'certifications' => ['nullable', 'string'],
            'experience'     => ['nullable', 'string'],
            'specialization' => ['nullable', 'string'],
            'joining_date'   => ['nullable', 'date'],
            'rating'         => ['nullable', 'numeric', 'min:0', 'max:5'],
            'level'          => ['nullable', 'string'],
            'emergency_contact_name'  => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
        ]);

        if (isset($data['week_off'])) {
            $data['week_off'] = implode(',', $data['week_off']);
        }

        $password = \Illuminate\Support\Str::random(10);
        
        $user = \App\Models\User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => \Hash::make($password),
            'status'   => $data['status'] === 'active' ? 'active' : 'inactive',
        ]);
        
        $user->assignRole('teacher');

        $data['user_id'] = $user->id;

        Teacher::create($data);

        try {
            \Mail::to($user->email)->send(new \App\Mail\TeacherCreatedMail($user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to send teacher credentials email: ' . $e->getMessage());
        }

        return back()->with('success', 'Teacher added successfully.');
    }

    public function updateTeacher(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:teachers,email,' . $teacher->id],
            'phone'          => ['nullable', 'string'],
            'course_id'      => ['nullable', 'exists:courses,id'],
            'week_off'       => ['nullable', 'array'],
            'status'         => ['required', 'in:active,inactive,on_leave'],
            'per_class_rate' => ['nullable', 'numeric', 'min:0'],
            'bio'            => ['nullable', 'string'],
            'youtube_url'    => ['nullable', 'string'],
            'certifications' => ['nullable', 'string'],
            'experience'     => ['nullable', 'string'],
            'specialization' => ['nullable', 'string'],
            'joining_date'   => ['nullable', 'date'],
            'rating'         => ['nullable', 'numeric', 'min:0', 'max:5'],
            'level'          => ['nullable', 'string'],
            'emergency_contact_name'  => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
        ]);

        if (isset($data['week_off'])) {
            $data['week_off'] = implode(',', $data['week_off']);
        } else {
            $data['week_off'] = null;
        }

        $teacher->update($data);

        return back()->with('success', 'Teacher updated.');
    }

    public function destroyTeacher(Teacher $teacher): RedirectResponse
    {
        $user = $teacher->user;
        $teacher->delete();
        if ($user) {
            $user->delete();
        }
        return back()->with('success', 'Teacher removed.');
    }

    // ─── Class Booking ────────────────────────────────────────────────────────

    public function classBooking(): View
    {
        $students      = Student::select('id', 'name', 'course_id', 'teacher_id', 'credits')->with(['teacher:id,name', 'course:id,name'])->get();
        $activeBookings = ClassBooking::with(['student', 'teacher'])->where('status', 'scheduled')->orderBy('starts_at')->get();
        return view('admin.class-booking.index', compact('students', 'activeBookings'));
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'instrument' => ['required', 'string'],
            'starts_at'  => ['required', 'date'],
            'ends_at'    => ['required', 'date', 'after:starts_at'],
            'type'       => ['required', 'in:one-time,recurring'],
            'notes'      => ['nullable', 'string'],
        ]);

        ClassBooking::create($data + ['status' => 'scheduled', 'duration_minutes' => 40]);

        // Deduct one credit for one-time; recurring deducts per class
        $student = Student::find($data['student_id']);
        if ($student->credits > 0) {
            $student->decrement('credits');
            CreditTransaction::create([
                'student_id' => $student->id,
                'action'     => 'Deducted',
                'quantity'   => -1,
                'reason'     => 'Class booked',
            ]);
        }

        return back()->with('success', 'Class booked successfully.');
    }

    public function cancelBooking(Request $request, ClassBooking $classBooking): RedirectResponse
    {
        $classBooking->update(['status' => 'cancelled', 'notes' => $request->input('reason', 'Cancelled by admin')]);
        return back()->with('success', 'Class cancelled.');
    }



    // ─── Sales ────────────────────────────────────────────────────────────────

    public function sales(): View
    {
        $leads      = Payment::with('latestDemo')->latest()->get();
        $grossSales = Payment::where('status', 'confirmed')->sum('amount');
        $enrolled   = Payment::where('status', 'confirmed')->count();
        $avgTx      = $enrolled > 0 ? round($grossSales / $enrolled) : 0;

        $months   = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $labels   = $months->map(fn ($m) => $m->format('M'))->values()->toArray();
        $revenue  = $months->map(fn ($m) => (int) Payment::whereYear('transaction_date', $m->year)->whereMonth('transaction_date', $m->month)->where('status', 'confirmed')->sum('amount'))->values()->toArray();

        $teachers = Teacher::with('user')->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        $creditPackages = \App\Models\CreditPackage::all();

        return view('admin.sales.index', compact(
            'leads', 'grossSales', 'enrolled', 'avgTx', 'labels', 'revenue', 'teachers', 'courses', 'creditPackages'
        ));
    }

    public function storeLead(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_name'     => ['required', 'string'],
            'contact'          => ['nullable', 'string'],
            'instrument'       => ['nullable', 'string'],
            'amount'           => ['nullable', 'numeric', 'min:0'],
            'payment_mode'     => ['nullable', 'string'],
            'transaction_date' => ['nullable', 'date'],
            'status'           => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        Payment::create($data);

        return back()->with('success', 'Lead added.');
    }

    public function updateLead(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'status'           => ['nullable', 'in:pending,confirmed,cancelled'],
            'payment_mode'     => ['nullable', 'string', 'max:50'],
            'amount'           => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'transaction_date' => ['nullable', 'date'],
        ]);
        $payment->update($data);
        return back()->with('success', 'Lead updated.');
    }

    public function convertLeadToStudent(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string',
            'enrolled_level' => 'required|string',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'credits' => 'required|integer|min:1',
            'amount' => 'required|numeric',
            'payment_mode' => 'required|string',
            'end_date' => 'nullable|date',
        ]);

        // Generate random password
        $password = \Str::random(10);

        // Create User account
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($password),
            'status' => 'active',
        ]);

        // Assign student role
        $user->assignRole('student');

        // Create student
        $student = Student::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'enrolled_level' => $data['enrolled_level'],
            'teacher_id' => $data['teacher_id'],
            'credits' => $data['credits'],
            'status' => 'active',
            'joining_date' => today(),
            'end_date' => $data['end_date'] ?? null,
            'enrolled_format' => 'Individual',
        ]);

        if (!empty($data['courses'])) {
            $student->courses()->attach($data['courses']);
        }

        // Update payment/lead to represent the conversion
        $payment->update([
            'amount' => $data['amount'],
            'payment_mode' => $data['payment_mode'],
            'status' => 'converted',
            'transaction_date' => today(),
        ]);

        // If there's a linked demo booking, update it too
        $demo = DemoBooking::where('payment_id', $payment->id)->first();
        if ($demo) {
            $demo->update([
                'status' => 'converted',
                'converted_student_id' => $student->id,
            ]);
            \Log::info("Updated demo booking #{$demo->id} status to converted for student #{$student->id}");
        } else {
            \Log::info("No demo booking found for payment #{$payment->id}");
        }

        // Send credentials email
        try {
            \Mail::to($user->email)->send(new \App\Mail\StudentCreatedMail($user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to send student credentials email: ' . $e->getMessage());
        }

        return back()->with('success', "Lead successfully converted to Student! Login credentials sent to {$user->email}");
    }

    public function destroyLead(Payment $payment): RedirectResponse
    {
        $payment->delete();
        return back()->with('success', 'Lead removed.');
    }

    // ─── Demos ────────────────────────────────────────────────────────────────

    public function demos(): View
    {
        $demos     = DemoBooking::with('teacher')->latest()->get();
        $scheduled = $demos->where('status', 'scheduled')->count();
        $completed = $demos->where('status', 'completed')->count();
        $converted = $demos->where('status', 'converted')->count();
        $cancelled = $demos->where('status', 'cancelled')->count();

        return view('admin.demos.index', compact('demos', 'scheduled', 'completed', 'converted', 'cancelled'));
    }

    public function updateDemoStatus(Request $request, DemoBooking $demoBooking): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:scheduled,completed,converted,cancelled']]);
        $demoBooking->update($data);
        return back()->with('success', 'Demo status updated.');
    }

    // ─── Reports ──────────────────────────────────────────────────────────────

    public function reports(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $totalStudents    = Student::count();
        
        $activeQuery = Student::where('status', 'active');
        if ($startDate && $endDate) {
            $activeQuery->whereBetween('joining_date', [$startDate, $endDate]);
        }
        $activeStudents   = $activeQuery->count();
        $activeRatio      = $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100, 1) : 0;

        // Calculate yearly enrollment growth
        $thisYearStudents = Student::whereYear('created_at', now()->year)->count();
        $lastYearStudents = Student::whereYear('created_at', now()->subYear()->year)->count();
        if ($lastYearStudents > 0) {
            $enrollmentGrowth = round((($thisYearStudents - $lastYearStudents) / $lastYearStudents) * 100, 1);
        } else {
            $enrollmentGrowth = $thisYearStudents > 0 ? 100 : 0;
        }

        $pastBookingsQuery = ClassBooking::where('starts_at', '<', now());
        if ($startDate && $endDate) {
            $pastBookingsQuery->whereBetween('starts_at', [$startDate, $endDate . ' 23:59:59']);
        }
        $pastBookings     = $pastBookingsQuery->get();
        
        $total            = $pastBookings->count();
        $completed        = $pastBookings->where('status', 'completed')->count();
        $showRate         = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

        $leavesTotal      = TeacherLeave::count();
        $leavesCovered    = TeacherLeave::whereNotNull('cover_teacher')->count();
        $leaveCoverRate   = $leavesTotal > 0 ? round(($leavesCovered / $leavesTotal) * 100) : 100;

        $months           = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $labels           = $months->map(fn ($m) => $m->format('M'))->values()->toArray();
        $signupsData      = $months->map(fn ($m) => Student::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->values()->toArray();
        $demosData        = $months->map(fn ($m) => DemoBooking::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->values()->toArray();
        $conversionData   = $months->map(fn ($m) => DemoBooking::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->where('status', 'converted')->count())->values()->toArray();

        $instruments      = \App\Models\Course::where('status', 'active')->pluck('name')->toArray();
        
        $hoursData = [];
        foreach($instruments as $i) {
            $q = ClassBooking::where('instrument', $i);
            if ($startDate && $endDate) {
                $q->whereBetween('starts_at', [$startDate, $endDate . ' 23:59:59']);
            }
            $hoursData[] = $q->sum('duration_minutes') / 60;
        }

        $historyQuery = ClassBooking::with(['student.user', 'teacher.user', 'studentGroup'])->latest();
        if ($startDate && $endDate) {
            $historyQuery->whereBetween('starts_at', [$startDate, $endDate . ' 23:59:59']);
        }
        $classHistory = $historyQuery->limit(100)->get();

        return view('admin.reports.index', compact(
            'enrollmentGrowth', 'activeRatio', 'showRate', 'leaveCoverRate',
            'labels', 'signupsData', 'demosData', 'conversionData',
            'instruments', 'hoursData', 'classHistory',
            'startDate', 'endDate'
        ));
    }

    public function exportReports(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $fileName = 'harita_academy_report_' . date('Y-m-d') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReportsExport($startDate, $endDate), $fileName);
    }



    // ─── Payroll ──────────────────────────────────────────────────────────────

    public function payroll(): View
    {
        $currentMonth = now()->format('F Y');
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Auto-generate/update pending payrolls on page load for the current month
        $teachers = Teacher::where('status', 'Active')->get();
        foreach ($teachers as $teacher) {
            $payroll = TeacherPayroll::firstOrNew([
                'teacher_id' => $teacher->id,
                'month' => $currentMonth,
            ]);

            // Only auto-update if it's pending (not yet paid)
            if (!$payroll->exists || $payroll->status === 'pending') {
                $classesTaken = ClassBooking::where('teacher_id', $teacher->id)
                    ->where('status', 'completed')
                    ->whereBetween('starts_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $demoOpportunities = DemoBooking::where('teacher_id', $teacher->id)
                    ->where('status', 'completed')
                    ->whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $approvedReferrals = \App\Models\Referral::where('referrer_id', $teacher->user_id)
                    ->where('referrer_role', 'teacher')
                    ->where('status', 'approved')
                    ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $acceptedOpportunities = \App\Models\Opportunity::where('accepted_teacher_id', $teacher->id)
                    ->where('status', 'accepted')
                    ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $opportunityTaken = $demoOpportunities + $approvedReferrals + $acceptedOpportunities;

                if (!$payroll->exists || $payroll->per_class_rate == 0) {
                    $payroll->per_class_rate = 500; 
                }

                $rate = $payroll->per_class_rate;
                $payroll->classes_taken = $classesTaken;
                $payroll->demo_classes = $demoOpportunities;
                $payroll->emergency_classes = $acceptedOpportunities;
                $payroll->referrals = $approvedReferrals;
                $payroll->opportunity_taken = $opportunityTaken;
                
                $demoPct = (float) \App\Models\Setting::get('opportunity_teacher_pct', 20);
                $refBonusRs = (float) \App\Models\Setting::get('referral_bonus_teacher_rs', 500);
                $oppBonusRs = (float) \App\Models\Setting::get('opportunity_bonus_rs', 100);
                
                $demoSalary = $demoOpportunities * ($demoPct / 100) * $rate;
                $referralSalary = $approvedReferrals * $refBonusRs;
                $emergencyOppSalary = $acceptedOpportunities * $oppBonusRs;
                
                $payroll->formula_salary = ($rate * 10) + (($demoPct / 100) * $rate * 5);
                $payroll->calculated_salary = ($rate * $classesTaken) + $demoSalary + $referralSalary + $emergencyOppSalary;
                
                if (!$payroll->exists) {
                    $payroll->status = 'pending';
                }
                $payroll->save();
            }
        }

        $payrolls     = TeacherPayroll::with('teacher')->where('month', $currentMonth)->get();
        $totalPayout  = $payrolls->sum('calculated_salary');
        $totalTeachers = $payrolls->count();
        $totalOpportunity = $payrolls->sum('opportunity_taken');

        return view('admin.payroll.index', compact('payrolls', 'totalPayout', 'totalTeachers', 'totalOpportunity', 'currentMonth'));
    }

    public function updatePayrollRate(Request $request, TeacherPayroll $payroll): RedirectResponse
    {
        $request->validate([
            'per_class_rate' => ['required', 'numeric', 'min:1']
        ]);

        $rate = $request->per_class_rate;
        $payroll->per_class_rate = $rate;
        
        $demoPct = (float) \App\Models\Setting::get('opportunity_teacher_pct', 20);
        $refBonusRs = (float) \App\Models\Setting::get('referral_bonus_teacher_rs', 500);
        $oppBonusRs = (float) \App\Models\Setting::get('opportunity_bonus_rs', 100);
        
        $demoSalary = $payroll->demo_classes * ($demoPct / 100) * $rate;
        $referralSalary = $payroll->referrals * $refBonusRs;
        $emergencyOppSalary = $payroll->emergency_classes * $oppBonusRs;

        $payroll->formula_salary = ($rate * 10) + (($demoPct / 100) * $rate * 5);
        $payroll->calculated_salary = ($rate * $payroll->classes_taken) + $demoSalary + $referralSalary + $emergencyOppSalary;
        
        $payroll->save();

        return back()->with('success', 'Class rate updated and salary recalculated!');
    }

    public function disbursePayroll(TeacherPayroll $payroll): RedirectResponse
    {
        $payroll->update(['status' => 'paid']);
        return back()->with('success', 'Payroll disbursed successfully!');
    }

    public function disburseAllPayroll(): RedirectResponse
    {
        $currentMonth = now()->format('F Y');
        TeacherPayroll::where('month', $currentMonth)
            ->where('status', 'pending')
            ->update(['status' => 'paid']);

        return back()->with('success', 'All pending payrolls for ' . $currentMonth . ' disbursed successfully!');
    }

    // ─── Referrals ────────────────────────────────────────────────────────────

    public function referrals(): View
    {
        $referrals   = Referral::with('referrer')->latest()->get();
        $total       = $referrals->count();
        $pending     = $referrals->where('status', 'pending')->count();
        $approved    = $referrals->where('status', 'approved')->count();
        $rate        = $total > 0 ? round(($approved / $total) * 100) : 0;

        return view('admin.referrals.index', compact('referrals', 'total', 'pending', 'approved', 'rate'));
    }

    public function updateReferral(Request $request, Referral $referral): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        
        // Auto-grant reward if status changed to approved
        if ($data['status'] === 'approved' && $referral->status !== 'approved') {
            if ($referral->referrer_role === 'student') {
                $student = \App\Models\Student::where('user_id', $referral->referrer_id)->first();
                if ($student) {
                    $quantity = (int) \App\Models\Setting::get('referral_bonus_student_credits', 2);

                    $student->increment('credits', $quantity);
                    
                    \App\Models\CreditTransaction::create([
                        'student_id' => $student->id,
                        'action'     => 'Added',
                        'quantity'   => $quantity,
                        'reason'     => 'Referral Reward (Auto-granted)',
                    ]);
                    
                    $referral->bonus_reward = "{$quantity} Credits";
                }
            } elseif ($referral->referrer_role === 'teacher') {
                $bonusRs = (int) \App\Models\Setting::get('referral_bonus_teacher_rs', 500);
                $referral->bonus_reward = "₹{$bonusRs}";
            }
        }

        $referral->update($data);
        return back()->with('success', 'Referral updated. Reward has been granted if applicable.');
    }

    // ─── Feedbacks ────────────────────────────────────────────────────────────

    public function feedbacks(): View
    {
        $feedbacks = Feedback::with('student')->latest()->get();
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function updateFeedbackStatus(Request $request, Feedback $feedback): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending,reviewed,resolved']]);
        $feedback->update($data);
        return back()->with('success', 'Feedback status updated.');
    }

    // ─── Roles & Users ────────────────────────────────────────────────────────

    public function roles(): View
    {
        $users       = User::latest()->get();
        $roles       = Role::all();
        $permissions = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        return view('admin.roles.index', compact('users', 'roles', 'permissions'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => strtolower($data['status']),
        ]);

        $user->assignRole($data['role']);

        return back()->with('success', 'User created.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'role'   => ['required', 'exists:roles,name'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $updateData = [
            'name'   => $data['name'],
            'status' => $data['status'],
        ];
        if (isset($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);
        $user->syncRoles([$data['role']]);

        return back()->with('success', 'User updated.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        \App\Models\Teacher::where('user_id', $user->id)->delete();
        \App\Models\Student::where('user_id', $user->id)->delete();
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // ─── Settings ─────────────────────────────────────────────────────────────

    public function settings(): View
    {
        $settings = Setting::all()->pluck('value', 'key');
        $creditPackages = \App\Models\CreditPackage::all();
        $user     = auth()->user();
        return view('admin.settings.index', compact('settings', 'user', 'creditPackages'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $allowed = [
            'academy_name', 'contact_email', 'support_phone', 'address',
            'class_duration', 'reschedule_lock_hours', 'require_approval',
            'auto_deduct_credits', 'opportunity_teacher_pct', 'opportunity_bonus_rs', 'referral_bonus_student_credits',
            'referral_bonus_teacher_rs',
            'indian_reschedule_cutoff_hours', 'intl_reschedule_cutoff_hours',
            'demo_price_inr', 'demo_price_intl',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Also handle profile update
        if ($request->filled('my_name') || $request->filled('my_email')) {
            $user = auth()->user();
            $user->name = $request->input('my_name', $user->name);
            $user->email = $request->input('my_email', $user->email);
            if ($request->filled('my_password')) {
                $user->password = Hash::make($request->input('my_password'));
            }
            $user->save();
        }

        return back()->with('success', 'Settings saved.');
    }

    // ─── Profile ──────────────────────────────────────────────────────────────

    public function profile(): View
    {
        $user = auth()->user()->load('teacher', 'student');
        return view('admin.profile.index', compact('user'));
    }
}
