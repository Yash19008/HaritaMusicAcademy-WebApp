<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherLeave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Notifications\LeaveStatusUpdatedNotification;

class LeaveController extends Controller
{
    public function index(): View
    {
        $leaves = TeacherLeave::with('teacher')->orderBy('from_date', 'desc')->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(TeacherLeave $teacherLeave): RedirectResponse
    {
        $teacherLeave->update(['status' => 'approved']);

        // Generate Opportunities for affected classes
        $bookings = \App\Models\ClassBooking::where('teacher_id', $teacherLeave->teacher_id)
            ->where('status', 'scheduled')
            ->whereBetween('starts_at', [
                \Carbon\Carbon::parse($teacherLeave->from_date)->startOfDay(),
                \Carbon\Carbon::parse($teacherLeave->to_date)->endOfDay(),
            ])->get();

        $delaySeconds = 0;
        foreach ($bookings as $booking) {
            \App\Models\Opportunity::create([
                'class_booking_id' => $booking->id,
                'original_teacher_id' => $teacherLeave->teacher_id,
                'status' => 'pending',
                'active_at' => now(),
                'expires_at' => $booking->starts_at, // Continuous until class starts or accepted/rejected
            ]);
        }

        if ($teacherLeave->teacher && $teacherLeave->teacher->user) {
            $teacherLeave->teacher->user->notify(new LeaveStatusUpdatedNotification(['leave' => $teacherLeave]));
        }

        return back()->with('success', 'Leave approved and ' . $bookings->count() . ' opportunities broadcasted.');
    }

    public function reject(TeacherLeave $teacherLeave): RedirectResponse
    {
        $teacherLeave->update(['status' => 'rejected']);
        if ($teacherLeave->teacher && $teacherLeave->teacher->user) {
            $teacherLeave->teacher->user->notify(new LeaveStatusUpdatedNotification(['leave' => $teacherLeave]));
        }
        return back()->with('success', 'Leave rejected.');
    }
}
