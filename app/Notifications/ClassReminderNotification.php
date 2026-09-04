<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ClassBooking;
use Illuminate\Queue\SerializesModels;

class ClassReminderNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $tz = config('app.timezone', 'UTC');
        if ($notifiable->hasRole('student') && $notifiable->student && $notifiable->student->timezone) {
            $tz = $notifiable->student->timezone;
        } elseif ($notifiable->hasRole('teacher') && $notifiable->teacher && $notifiable->teacher->timezone) {
            $tz = $notifiable->teacher->timezone;
        }

        $startsAt = \Carbon\Carbon::parse($this->booking->starts_at)->timezone($tz)->format('M d, Y h:i A');

        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Reminder: Upcoming Class in 30 Minutes')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('This is a quick reminder that your ' . $this->booking->instrument . ' class is starting in exactly 30 minutes.')
                    ->line('Scheduled Time: ' . $startsAt . ' (' . $tz . ')')
                    ->action('Join Google Meet', $this->booking->google_meet_link ?? '#')
                    ->line('Please be on time and prepared for the class.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return ['title' => 'Class Reminder', 'message' => 'Your class ' . $this->booking->instrument . ' starts in 30 minutes!', 'booking_id' => $this->booking->id, 'icon' => '⏰'];
    }
}