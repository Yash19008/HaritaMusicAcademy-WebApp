<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassBooking;
use App\Models\DemoBooking;
use App\Models\ReminderConfig;
use App\Notifications\ClassReminderNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        $now = now();
        $configs = ReminderConfig::enabled()->orderBy('minutes_before')->get();
        $totalSent = 0;

        foreach ($configs as $config) {
            $targetTimeStart = $now->copy()->addMinutes($config->minutes_before)->startOfMinute();
            $targetTimeEnd = $targetTimeStart->copy()->addMinute();

            $bookings = ClassBooking::with(['teacher.user', 'student.user', 'studentGroup.members.user'])
                ->where('status', 'scheduled')
                ->where('starts_at', '>=', $targetTimeStart)
                ->where('starts_at', '<', $targetTimeEnd)
                ->get();

            $demos = DemoBooking::with(['teacher.user'])
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', $targetTimeStart)
                ->where('scheduled_at', '<', $targetTimeEnd)
                ->get();

            // Process Regular Classes
            foreach ($bookings as $booking) {
                if ($config->notify_teacher && $booking->teacher && $booking->teacher->user) {
                    $totalSent += $this->notifyOnce($booking->teacher->user, $booking, $config) ? 1 : 0;
                }

                if ($config->notify_student) {
                    if ($booking->student_group_id) {
                        foreach ($booking->studentGroup?->members ?? [] as $member) {
                            if ($member->user) {
                                $totalSent += $this->notifyOnce($member->user, $booking, $config) ? 1 : 0;
                            }
                        }
                    } else {
                        if ($booking->student && $booking->student->user) {
                            $totalSent += $this->notifyOnce($booking->student->user, $booking, $config) ? 1 : 0;
                        }
                    }
                }
            }

            // Process Demo Classes
            foreach ($demos as $demo) {
                if ($config->notify_teacher && $demo->teacher && $demo->teacher->user) {
                    $totalSent += $this->notifyOnce($demo->teacher->user, $demo, $config) ? 1 : 0;
                }

                if ($config->notify_student && $demo->email) {
                    $totalSent += $this->notifyDemoStudentOnce($demo, $config) ? 1 : 0;
                }
            }
        }

        $this->info("Sent " . $totalSent . " reminders based on " . $configs->count() . " active configurations.");

        return self::SUCCESS;
    }

    private function notifyOnce($user, Model $booking, ReminderConfig $config): bool
    {
        $alreadySent = $user->notifications()
            ->where('type', ClassReminderNotification::class)
            ->where('data->booking_id', $booking->id)
            ->where('data->booking_type', $booking instanceof DemoBooking ? 'demo' : 'class')
            ->where('data->reminder_config_id', $config->id)
            ->exists();

        if ($alreadySent) {
            return false;
        }

        $bookingType = $booking instanceof DemoBooking ? 'demo' : 'class';
        $cacheKey = "class-reminder:{$bookingType}:{$booking->id}:{$config->id}:{$user->id}";
        $lockExpiry = now()->addMinutes(max((int) $config->minutes_before + 1440, 1440));

        if (!Cache::add($cacheKey, true, $lockExpiry)) {
            return false;
        }

        try {
            $user->notify(new ClassReminderNotification([
                'booking' => $booking,
                'config' => $config,
            ]));
        } catch (\Throwable $exception) {
            Cache::forget($cacheKey);
            throw $exception;
        }

        return true;
    }

    private function notifyDemoStudentOnce(\App\Models\DemoBooking $demo, ReminderConfig $config): bool
    {
        $cacheKey = "demo-reminder:{$demo->id}:{$config->id}";
        $lockExpiry = now()->addMinutes(max((int) $config->minutes_before + 1440, 1440));

        if (!Cache::add($cacheKey, true, $lockExpiry)) {
            return false;
        }

        try {
            \Illuminate\Support\Facades\Notification::route('mail', $demo->email)
                ->notify(new ClassReminderNotification([
                    'booking' => $demo,
                    'config' => $config,
                    'is_demo_student' => true,
                    'demo_student_name' => $demo->student_name,
                ]));
        } catch (\Throwable $exception) {
            Cache::forget($cacheKey);
            throw $exception;
        }

        return true;
    }
}
