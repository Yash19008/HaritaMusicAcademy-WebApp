<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassBooking;
use App\Notifications\ClassReminderNotification;
use App\Models\StudentGroup;
use Carbon\Carbon;

class SendClassReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-class-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders 30 minutes before classes start';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Target classes starting between 30 and 31 minutes from now
        $targetTimeStart = now()->addMinutes(30)->startOfMinute();
        $targetTimeEnd = now()->addMinutes(31)->endOfMinute();

        $bookings = ClassBooking::where('status', 'scheduled')
            ->whereBetween('starts_at', [$targetTimeStart, $targetTimeEnd])
            ->get();

        foreach ($bookings as $booking) {
            // Notify Teacher
            if ($booking->teacher && $booking->teacher->user) {
                $booking->teacher->user->notify(new ClassReminderNotification(['booking' => $booking]));
            }

            // Notify Students
            if ($booking->student_group_id) {
                $group = StudentGroup::with('members.user')->find($booking->student_group_id);
                if ($group) {
                    foreach ($group->members as $member) {
                        if ($member->user) {
                            $member->user->notify(new ClassReminderNotification(['booking' => $booking]));
                        }
                    }
                }
            } else {
                if ($booking->student && $booking->student->user) {
                    $booking->student->user->notify(new ClassReminderNotification(['booking' => $booking]));
                }
            }
        }

        $this->info("Sent " . $bookings->count() . " reminders.");
    }
}
