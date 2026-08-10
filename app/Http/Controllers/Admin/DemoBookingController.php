<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoBooking;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DemoBookingController extends Controller
{
    public function index(): View
    {
        $demos     = DemoBooking::with('teacher')->latest()->get();
        $scheduled = $demos->where('status', 'scheduled')->count();
        $completed = $demos->where('status', 'completed')->count();
        $converted = $demos->where('status', 'converted')->count();
        $cancelled = $demos->where('status', 'cancelled')->count();

        $teachers = Teacher::all();
        $courses = \App\Models\Course::where('status', 'active')->get();

        return view('admin.demos.index', compact('demos', 'scheduled', 'completed', 'converted', 'cancelled', 'teachers', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lead_id'       => ['nullable', 'exists:payments,id'],
            'student_name'  => ['required', 'string'],
            'instrument'    => ['nullable', 'string'],
            'teacher_id'    => ['required', 'exists:teachers,id'],
            'scheduled_at'  => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:30', 'max:120'],
        ]);

        $email = '';
        $phone = '';

        // Get lead/payment info if available
        if (!empty($data['lead_id'])) {
            $payment = Payment::findOrFail($data['lead_id']);
            
            // Extract email and phone from contact field
            $contacts = $payment->contact ? explode('|', $payment->contact) : ['', ''];
            $email = $contacts[0] ?? '';
            $phone = $contacts[1] ?? '';
        }

        // Create demo booking
        DemoBooking::create([
            'payment_id'    => $data['lead_id'] ?? null,
            'student_name'  => $data['student_name'],
            'email'         => $email,
            'phone'         => $phone,
            'instrument'    => $data['instrument'],
            'teacher_id'    => $data['teacher_id'],
            'scheduled_at'  => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'],
            'status'        => 'scheduled',
        ]);

        return back()->with('success', 'Demo class scheduled successfully!');
    }

    public function updateStatus(Request $request, DemoBooking $demo): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:scheduled,completed,converted,cancelled,no-show']
        ]);

        $demo->update($data);

        return back()->with('success', 'Demo status updated successfully!');
    }

    public function convert(Request $request, DemoBooking $demo): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string',
            'enrolled_level' => 'required|string',
            'instrument' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
            'package' => 'required|string',
            'amount_paid' => 'required|numeric',
            'payment_mode' => 'required|string',
        ]);

        // Extract credits from package value (format: "12000|12")
        $packageParts = explode('|', $data['package']);
        $credits = isset($packageParts[1]) ? (int)$packageParts[1] : 10;

        // Find course by instrument name or use first active course
        $course = \App\Models\Course::where('name', 'LIKE', '%' . $data['instrument'] . '%')
                    ->orWhere('status', 'active')
                    ->first();
        
        if (!$course) {
            return back()->with('error', 'No active course found. Please create a course first.');
        }

        // Generate random password
        $password = \Str::random(10);

        // Create User account
        $user = \App\Models\User::create([
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
            'course_id' => $course->id,
            'teacher_id' => $data['teacher_id'],
            'credits' => $credits,
            'status' => 'active',
            'joining_date' => today(),
            'enrolled_format' => 'Individual',
        ]);

        // Update demo booking status to converted
        $demo->update([
            'status' => 'converted',
            'converted_student_id' => $student->id,
        ]);

        // If there's a linked payment/lead, update that too
        if ($demo->payment_id) {
            Payment::where('id', $demo->payment_id)->update([
                'amount' => $data['amount_paid'],
                'payment_mode' => $data['payment_mode'],
                'status' => 'converted',
                'transaction_date' => today(),
            ]);
        }

        // Send credentials email
        try {
            \Mail::to($user->email)->send(new \App\Mail\StudentCreatedMail($user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to send student credentials email: ' . $e->getMessage());
        }

        return back()->with('success', "Demo successfully converted to Student! Login credentials sent to {$user->email}");
    }
}
