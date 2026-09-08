<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ClassBooking;

class RescheduleRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->booking = $data['booking'];
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
        return ['title' => 'Reschedule Requested', 'message' => 'A reschedule was requested for ' . $this->booking->instrument . '.', 'booking_id' => $this->booking->id, 'url' => '/admin/class-booking', 'icon' => '🔄'];
    }
}