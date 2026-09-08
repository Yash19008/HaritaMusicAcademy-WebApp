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
    public $config;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->booking = $data['booking'];
        $this->config = $data['config'] ?? null;
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
        $tz = config('app.timezone', 'Asia/Kolkata');
        if ($notifiable->hasRole('student') && $notifiable->student && $notifiable->student->timezone) {
            $tz = $notifiable->student->timezone;
        } elseif ($notifiable->hasRole('teacher') && $notifiable->teacher && $notifiable->teacher->timezone) {
            $tz = $notifiable->teacher->timezone;
        }

        $startsAt = \Carbon\Carbon::parse($this->booking->starts_at)->timezone($tz)->format('M d, Y h:i A');
        $label = $this->config ? $this->config->label : 'in 30 Minutes';
        $timeStr = str_replace(' Before', '', $label);

        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Reminder: Upcoming Class ' . $timeStr)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('This is a quick reminder that your ' . $this->booking->instrument . ' class is scheduled to start ' . strtolower($timeStr) . '.')
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
        $label = $this->config ? $this->config->label : 'in 30 Minutes';
        $timeStr = str_replace(' Before', '', $label);
        
        $url = '#';
        if ($notifiable->hasRole('admin') || $notifiable->hasRole('student')) {
            $url = '/admin/class-booking';
        } elseif ($notifiable->hasRole('teacher')) {
            $url = '/teacher/class-booking';
        }

        return [
            'title' => 'Class Reminder', 
            'message' => 'Your ' . $this->booking->instrument . ' class starts ' . strtolower($timeStr) . '.', 
            'booking_id' => $this->booking->id, 
            'url' => $url,
            'icon' => '⏰'
        ];
    }
}