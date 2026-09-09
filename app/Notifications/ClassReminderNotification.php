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
        $startsAtRaw  = \Carbon\Carbon::parse($this->booking->$startsAtProp)->timezone($tz);
        $endsAtProp   = isset($this->booking->ends_at) ? 'ends_at' : null;
        $duration     = $endsAtProp
            ? $startsAtRaw->diffInMinutes(\Carbon\Carbon::parse($this->booking->ends_at)->timezone($tz))
            : ($this->booking->duration_minutes ?? 40);

        $isTeacher   = method_exists($notifiable, 'hasRole') && $notifiable->hasRole('teacher');
        $isDemo      = $this->booking instanceof \App\Models\DemoBooking;
        $instrument  = $this->booking->instrument ?? 'Music';
        $timeLabel   = $this->reminderTimeLabel();

        $recipientName = $this->isDemoStudent
            ? $this->demoStudentName
            : ($notifiable->name ?? 'Student');

        // Teacher name for student email, student name for teacher email
        $teacherName = null;
        $studentName = null;
        if ($isTeacher) {
            $studentName = $isDemo
                ? ($this->booking->student_name ?? null)
                : ($this->booking->student->name ?? ($this->booking->studentGroup->name ?? null));
        } else {
            $teacherName = $this->booking->teacher->user->name ?? null;
        }

        // Join URL
        $joinUrl = null;
        if (!empty($this->booking->google_meet_link)) {
            $joinUrl = $isTeacher ? ($this->booking->teacher_join_url ?? $this->booking->google_meet_link)
                                  : ($this->booking->student_join_url ?? $this->booking->google_meet_link);
        } elseif ($isDemo && !empty($this->booking->meet_link)) {
            $joinUrl = $this->booking->meet_link;
        }

        $html = view('emails.class_reminder', [
            'recipientName' => $recipientName,
            'instrument'    => $instrument,
            'isDemo'        => $isDemo,
            'isTeacher'     => $isTeacher,
            'timeLabel'     => $timeLabel,
            'startsAt'      => $startsAtRaw->format('l, F j, Y \a\t h:i A'),
            'timezone'      => $tz,
            'duration'      => $duration,
            'teacherName'   => $teacherName,
            'studentName'   => $studentName,
            'joinUrl'       => $joinUrl,
        ])->render();

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Reminder: Your ' . ($isDemo ? 'Demo ' : '') . $instrument . ' Class – Starting ' . $timeLabel)
            ->html($html);
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