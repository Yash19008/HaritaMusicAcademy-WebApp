<?php

namespace App\Notifications;

use App\Models\DemoBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DemoScheduledForTeacherNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DemoBooking $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemoBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $studentName = $this->booking->student_name ?? 'A student';
        $instrument  = $this->booking->instrument ?? 'Music';
        $dateTime    = $this->booking->starts_at
            ? \Carbon\Carbon::parse($this->booking->starts_at)->format('D, d M Y \a\t h:i A')
            : 'the scheduled time';

        return [
            'title'      => '🎵 Demo Class Assigned',
            'message'    => "You have a demo class with {$studentName} for {$instrument} on {$dateTime}. A Google Meet link has been shared via email.",
            'booking_id' => $this->booking->id,
            'url'        => url('/teacher/demo-classes'),
            'icon'       => '🎬',
        ];
    }
}
