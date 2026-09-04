<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ClassBooking;
use App\Models\StudentGroup;
use App\Services\BookingService;
use App\Mail\ClassBookedMail;
use App\Mail\RecurringClassesBookedMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ClassBookedNotification;
use App\Models\User;

class ProcessBookingBatchJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public array $bookingIds;
    public bool $isRecurring;

    /**
     * Create a new job instance.
     */
    public function __construct(array $bookingIds, bool $isRecurring = false)
    {
        $this->bookingIds = $bookingIds;
        $this->isRecurring = $isRecurring;
    }

    /**
     * Execute the job.
     */
    public function handle(BookingService $bookingService): void
    {
        if (empty($this->bookingIds)) {
            return;
        }

        $bookings = ClassBooking::whereIn('id', $this->bookingIds)->get();
        if ($bookings->isEmpty()) {
            return;
        }

        // 1. Sync with Google Calendar for all bookings that don't have it yet
        foreach ($bookings as $booking) {
            if (!$booking->google_event_id) {
                try {
                    $bookingService->createGoogleCalendarEvent($booking);
                } catch (\Exception $e) {
                    \Log::error('ProcessBookingBatchJob: Failed to create google calendar event for booking ' . $booking->id . ': ' . $e->getMessage());
                }
            }
        }

        // 2. Send emails
        $firstBooking = $bookings->first();
        if ($this->isRecurring) {
            if ($firstBooking->student_group_id) {
                $group = StudentGroup::with('members')->find($firstBooking->student_group_id);
                if ($group) {
                    foreach ($group->members as $member) {
                        Mail::to($member->email)->send(new RecurringClassesBookedMail($bookings->all(), false, $member));
                    }
                }
            } else {
                if ($firstBooking->student) {
                    Mail::to($firstBooking->student->email)->send(new RecurringClassesBookedMail($bookings->all(), false, $firstBooking->student));
                }
            }
            if ($firstBooking->teacher && $firstBooking->teacher->user && $firstBooking->teacher->user->email) {
                Mail::to($firstBooking->teacher->user->email)->send(new RecurringClassesBookedMail($bookings->all(), true));
            }
        } else {
            // One-time
            if ($firstBooking->student_group_id) {
                $group = StudentGroup::with('members')->find($firstBooking->student_group_id);
                if ($group) {
                    foreach ($group->members as $member) {
                        Mail::to($member->email)->send(new ClassBookedMail($firstBooking, false, $member));
                    }
                }
            } else {
                if ($firstBooking->student) {
                    Mail::to($firstBooking->student->email)->send(new ClassBookedMail($firstBooking, false, $firstBooking->student));
                }
            }
            if ($firstBooking->teacher && $firstBooking->teacher->user && $firstBooking->teacher->user->email) {
                Mail::to($firstBooking->teacher->user->email)->send(new ClassBookedMail($firstBooking, true));
            }
        }

        // 3. Send Notifications
        $admin = User::role('admin')->first();
        if ($admin) {
            $admin->notify(new ClassBookedNotification(['booking' => $firstBooking]));
        }

        if ($firstBooking->student_group_id) {
            $group = StudentGroup::with('members.user')->find($firstBooking->student_group_id);
            if ($group) {
                foreach ($group->members as $member) {
                    if ($member->user) {
                        $member->user->notify(new ClassBookedNotification(['booking' => $firstBooking]));
                    }
                }
            }
        } else {
            if ($firstBooking->student && $firstBooking->student->user) {
                $firstBooking->student->user->notify(new ClassBookedNotification(['booking' => $firstBooking]));
            }
        }

        if ($firstBooking->teacher && $firstBooking->teacher->user) {
            $firstBooking->teacher->user->notify(new ClassBookedNotification(['booking' => $firstBooking]));
        }
    }
}
