<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassBooking;
use App\Models\Teacher;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\RescheduleRequestedMail;
use App\Mail\RescheduleApprovedMail;
use App\Mail\RescheduleRejectedMail;
use App\Notifications\RescheduleRequestedNotification;

class RescheduleController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Get available slots for the given teacher and date
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date_format:Y-m-d'
        ]);

        $teacher = Teacher::findOrFail($request->teacher_id);
        $slots = $this->bookingService->getAvailableSlots($teacher, $request->date);
        
        return response()->json($slots);
    }

    /**
     * Student or Teacher requests a reschedule
     */
    public function requestReschedule(Request $request, ClassBooking $booking): RedirectResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date_format:Y-m-d',
            'time_slot' => 'required|string',
            'reschedule_reason' => 'required|string|max:500'
        ]);

        list($startTimeStr, $endTimeStr) = explode(' - ', $request->time_slot);
        
        // Basic slot format verification (expecting something like "08:00 AM - 08:40 AM")
        $requestedStartsAt = Carbon::parse($request->date . ' ' . $startTimeStr);
        $requestedEndsAt = Carbon::parse($request->date . ' ' . $endTimeStr);

        $lockHours = (int) \App\Models\Setting::get('reschedule_lock_hours', 24);
        if (now()->addHours($lockHours)->greaterThan($booking->starts_at)) {
            return back()->with('error', 'This class is within the ' . $lockHours . '-hour locking window and cannot be rescheduled.');
        }

        $rescheduledBy = auth()->user()->hasRole('teacher') ? 'Teacher' : 'Student';
        
        $rescheduleCount = \App\Models\ClassBooking::where('rescheduled_by', $rescheduledBy)
            ->whereMonth('reschedule_requested_datetime', now()->month)
            ->whereYear('reschedule_requested_datetime', now()->year);

        if ($rescheduledBy === 'Teacher') {
            $rescheduleCount->where('teacher_id', $booking->teacher_id);
        } else {
            $rescheduleCount->where('student_id', $booking->student_id);
        }

        if ($rescheduleCount->count() >= 2) {
            return back()->with('error', 'You have reached the maximum limit of 2 reschedules per month.');
        }

        $booking->update([
            'status'                         => 'reschedule_requested',
            'reschedule_status'              => 'pending',
            'reschedule_requested_teacher_id'=> $request->teacher_id,
            'reschedule_requested_starts_at' => $requestedStartsAt,
            'reschedule_requested_ends_at'   => $requestedEndsAt,
            'reschedule_reason'              => $request->reschedule_reason,
            'rescheduled_by'                 => $rescheduledBy,
            'reschedule_requested_datetime'  => now(),
        ]);

        // Send Email to Admin (RescheduleRequestedMail)
        $admin = User::role('Admin')->first();
        if ($admin) {
            Mail::to($admin->email)->send(new RescheduleRequestedMail($booking, $rescheduledBy));
            $admin->notify(new RescheduleRequestedNotification(['booking' => $booking]));
        }
        
        return back()->with('success', 'Reschedule request submitted successfully! Pending admin approval.');
    }

    /**
     * Admin force-reschedules a class directly
     */
    public function adminReschedule(Request $request, ClassBooking $booking): RedirectResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date_format:Y-m-d',
            'time_slot' => 'required|string',
            'reschedule_reason' => 'nullable|string|max:500'
        ]);

        list($startTimeStr, $endTimeStr) = explode(' - ', $request->time_slot);
        
        $newStartsAt = Carbon::parse($request->date . ' ' . $startTimeStr);
        $newEndsAt = Carbon::parse($request->date . ' ' . $endTimeStr);

        // Update the booking directly
        $booking->update([
            'teacher_id' => $request->teacher_id,
            'starts_at' => $newStartsAt,
            'ends_at' => $newEndsAt,
            'reschedule_status' => 'approved',
            'reschedule_reason' => $request->reschedule_reason ?? 'Rescheduled by Admin',
            'rescheduled_by' => 'Admin',
            'reschedule_requested_datetime' => now(),
        ]);

        // Update Google Calendar
        $this->bookingService->updateGoogleCalendarEvent($booking);

        // Send Email to Student & Teacher (RescheduleApprovedMail)
        if ($booking->student && $booking->student->user) {
            Mail::to($booking->student->user->email)->send(new RescheduleApprovedMail($booking, false));
        }
        if ($booking->teacher && $booking->teacher->user) {
            Mail::to($booking->teacher->user->email)->send(new RescheduleApprovedMail($booking, true));
        }

        return back()->with('success', 'Class rescheduled successfully!');
    }

    /**
     * Admin approves a pending request
     */
    public function approveRequest(ClassBooking $booking): RedirectResponse
    {
        if ($booking->reschedule_status !== 'pending') {
            return back()->with('error', 'Request is not pending.');
        }

        // Apply requested changes — also reset the main status back to scheduled
        $booking->update([
            'teacher_id'    => $booking->reschedule_requested_teacher_id ?? $booking->teacher_id,
            'starts_at'     => $booking->reschedule_requested_starts_at,
            'ends_at'       => $booking->reschedule_requested_ends_at,
            'status'        => 'scheduled',
            'reschedule_status' => 'approved',
        ]);

        // Refresh to get updated teacher relation
        $booking->refresh();

        // Update Google Calendar
        $this->bookingService->updateGoogleCalendarEvent($booking);

        // Send Email to Student & Teacher (RescheduleApprovedMail)
        if ($booking->student && $booking->student->user) {
            Mail::to($booking->student->user->email)->send(new RescheduleApprovedMail($booking, false));
        }
        if ($booking->teacher && $booking->teacher->user) {
            Mail::to($booking->teacher->user->email)->send(new RescheduleApprovedMail($booking, true));
        }

        return back()->with('success', 'Reschedule approved! Class rescheduled and notifications sent.');
    }

    /**
     * Admin rejects a pending request
     */
    public function rejectRequest(ClassBooking $booking): RedirectResponse
    {
        if ($booking->reschedule_status !== 'pending') {
            return back()->with('error', 'Request is not pending.');
        }

        // Reset status back to scheduled; mark reschedule as rejected
        $booking->update([
            'status'            => 'scheduled',
            'reschedule_status' => 'rejected',
        ]);

        // Send Email to Student/Teacher (RescheduleRejectedMail)
        if ($booking->student && $booking->student->user) {
            Mail::to($booking->student->user->email)->send(new RescheduleRejectedMail($booking));
        }
        if ($booking->teacher && $booking->teacher->user) {
            Mail::to($booking->teacher->user->email)->send(new RescheduleRejectedMail($booking));
        }

        return back()->with('success', 'Reschedule request rejected. Class remains on original schedule.');
    }
}
