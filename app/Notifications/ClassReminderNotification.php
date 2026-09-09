<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ClassBooking;
use App\Models\DemoBooking;
use Illuminate\Queue\SerializesModels;

class ClassReminderNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $config;
    public $isDemoStudent;
    public $demoStudentName;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->booking = $data['booking'];
        $this->config = $data['config'] ?? null;
        $this->isDemoStudent = $data['is_demo_student'] ?? false;
        $this->demoStudentName = $data['demo_student_name'] ?? null;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($this->isDemoStudent) {
            return ['mail'];
        }
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $tz = config('app.timezone', 'Asia/Kolkata');
        if (method_exists($notifiable, 'hasRole')) {
            if ($notifiable->hasRole('student') && $notifiable->student && $notifiable->student->timezone) {
                $tz = $notifiable->student->timezone;
            } elseif ($notifiable->hasRole('teacher') && $notifiable->teacher && $notifiable->teacher->timezone) {
                $tz = $notifiable->teacher->timezone;
            }
        }

        $startsAtProp = isset($this->booking->scheduled_at) ? 'scheduled_at' : 'starts_at';
        $startsAt = \Carbon\Carbon::parse($this->booking->$startsAtProp)->timezone($tz)->format('M d, Y h:i A');
        $timeStr = $this->reminderTimeLabel();
        
        $greetingName = $this->isDemoStudent ? $this->demoStudentName : ($notifiable->name ?? 'Student');

        $mail = (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Reminder: Upcoming Class ' . $timeStr)
                    ->greeting('Hello ' . $greetingName . '!')
                    ->line('This is a quick reminder that your ' . $this->booking->instrument . ' class is scheduled to start ' . strtolower($timeStr) . '.')
                    ->line('Scheduled Time: ' . $startsAt . ' (' . $tz . ')')
                    ->line('Please be on time and prepared for the class.');

        if ($this->booking->google_meet_link) {
            $isTeacher = method_exists($notifiable, 'hasRole') && $notifiable->hasRole('teacher');
            $joinUrl = $isTeacher ? $this->booking->teacher_join_url : $this->booking->student_join_url;
            $mail->action('Join Class', $joinUrl);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $timeStr = $this->reminderTimeLabel();
        
        $url = null;
        if ($notifiable->hasRole('admin')) {
            $url = route('admin.class-booking');
        } elseif ($notifiable->hasRole('student')) {
            $url = route('student.my-classes');
        } elseif ($notifiable->hasRole('teacher')) {
            $url = $this->booking instanceof DemoBooking
                ? route('teacher.demo-classes')
                : route('teacher.my-classes');
        }

        return [
            'title' => 'Class Reminder', 
            'message' => 'Your ' . $this->booking->instrument . ' class starts ' . strtolower($timeStr) . '.', 
            'booking_id' => $this->booking->id,
            'booking_type' => $this->booking instanceof DemoBooking ? 'demo' : 'class',
            'reminder_config_id' => $this->config?->id,
            'url' => $url,
            'icon' => '⏰'
        ];
    }

    private function reminderTimeLabel(): string
    {
        $label = $this->config?->label ?? '30 Minutes Before';

        return preg_replace('/\s+before\s*$/i', '', trim($label)) ?: '30 Minutes';
    }
}