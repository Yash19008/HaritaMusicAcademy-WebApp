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
    protected $description = 'Send dynamic reminders before classes start based on configs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $configs = \App\Models\ReminderConfig::enabled()->get();
        $totalSent = 0;

        foreach ($configs as $config) {
            // Target classes starting between config minutes and config minutes + 1
            $targetTimeStart = now()->addMinutes($config->minutes_before)->startOfMinute();
            $targetTimeEnd = now()->addMinutes($config->minutes_before + 1)->endOfMinute();

            $bookings = ClassBooking::where('status', 'scheduled')
                ->whereBetween('starts_at', [$targetTimeStart, $targetTimeEnd])
                ->get();

            foreach ($bookings as $booking) {
                // Notify Teacher
                if ($config->notify_teacher && $booking->teacher && $booking->teacher->user) {
                    $booking->teacher->user->notify(new ClassReminderNotification([
                        'booking' => $booking,
                        'config' => $config
                    ]));
                    $totalSent++;
                }

                // Notify Students
                if ($config->notify_student) {
                    if ($booking->student_group_id) {
                        $group = StudentGroup::with('members.user')->find($booking->student_group_id);
                        if ($group) {
                            foreach ($group->members as $member) {
                                if ($member->user) {
                                    $member->user->notify(new ClassReminderNotification([
                                        'booking' => $booking,
                                        'config' => $config
                                    ]));
                                    $totalSent++;
                                }
                            }
                        }
                    } else {
                        if ($booking->student && $booking->student->user) {
                            $booking->student->user->notify(new ClassReminderNotification([
                                'booking' => $booking,
                                'config' => $config
                            ]));
                            $totalSent++;
                        }
                    }
                }
            }
        }

        $this->info("Sent " . $totalSent . " reminders based on " . $configs->count() . " active configurations.");
    }
}
