<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\TeacherLeave;

class LeaveRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leave;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->leave = $data['leave'];
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
        return ['title' => 'Leave Requested', 'message' => 'Teacher ' . $this->leave->teacher->name . ' requested a leave.', 'leave_id' => $this->leave->id, 'url' => '/admin/leaves', 'icon' => '🏖️'];
    }
}