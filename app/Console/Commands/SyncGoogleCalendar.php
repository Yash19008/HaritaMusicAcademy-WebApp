<?php

namespace App\Console\Commands;

use App\Models\ClassBooking;
use App\Services\BookingService;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SyncGoogleCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-google-calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs pending class bookings to Google Calendar using priority queue and link reuse logic.';

    /**
     * Execute the console command.
     */
    public function handle(GoogleCalendarService $gcalService, BookingService $bookingService)
    {
        $batchSize = config('services.google.sync_batch_size', 2);
        $now = Carbon::now();

        // --- STEP 1: Process Recurring Groups ---
        $this->processRecurringGroups($gcalService, $now);

        // --- STEP 2: Process One-Time Bookings ---
        $bookings = DB::transaction(function () use ($batchSize, $now) {
            return ClassBooking::whereIn('google_sync_status', ['pending', 'failed'])
                ->whereNull('recurrence_group_id') // Only one-time
                ->where('google_sync_attempts', '<', 5)
                ->where('starts_at', '>', $now)
                ->where(function ($query) use ($now) {
                    $query->whereNull('next_retry_at')
                          ->orWhere('next_retry_at', '<=', $now);
                })
                ->orderBy('starts_at', 'asc') // Priority Queue: Soonest first
                ->limit($batchSize)
                ->lockForUpdate()
                ->get();
        });

        if ($bookings->isEmpty()) {
            $this->info("No pending one-time bookings to sync.");
            return self::SUCCESS;
        }

        $this->info("Processing {$bookings->count()} one-time bookings...");

        foreach ($bookings as $booking) {
            // Double check status in case it changed between fetch and processing
            $booking->refresh();
            if (!in_array($booking->google_sync_status, ['pending', 'failed'])) {
                $this->info("Booking ID {$booking->id} status changed to {$booking->google_sync_status}. Skipping.");
                continue;
            }

            $booking->increment('google_sync_attempts');
            $attempt = $booking->google_sync_attempts;

            $this->info("Processing booking ID {$booking->id} (Attempt {$attempt})...");

            try {
                // Determine if we can reuse a link
                $sourceBooking = $bookingService->resolveMeetLinkForReuse($booking);

                if ($sourceBooking && $sourceBooking->google_meet_link) {
                    // We found a link to reuse
                    $this->info("Reusing meet link from booking ID {$sourceBooking->id}");
                    $result = $gcalService->createEventWithoutMeet($booking);

                    if ($result->status === 'synced') {
                        $booking->update([
                            'google_sync_status'          => 'synced',
                            'google_sync_message'         => null,
                            'google_event_id'             => $result->eventId,
                            'google_calendar_id'          => config('services.google.calendar_id', 'primary'),
                            'google_meet_link'            => $sourceBooking->google_meet_link,
                            'google_event_payload'        => $result->payload,
                            'meet_link_source_booking_id' => $sourceBooking->id,
                            'meet_link_generated_at'      => $sourceBooking->meet_link_generated_at,
                            'next_retry_at'               => null,
                        ]);
                    } else {
                        $this->handleFailure($booking, $attempt, $result->message);
                    }
                } else {
                    // Generate new link
                    $this->info("Generating new meet link");
                    $result = $gcalService->createMeetEvent($booking);

                    if ($result->status === 'synced') {
                        $booking->update([
                            'google_sync_status'          => 'synced',
                            'google_sync_message'         => null,
                            'google_event_id'             => $result->eventId,
                            'google_calendar_id'          => config('services.google.calendar_id', 'primary'),
                            'google_meet_link'            => $result->meetLink,
                            'google_event_payload'        => $result->payload,
                            'meet_link_source_booking_id' => $booking->id,
                            'meet_link_generated_at'      => Carbon::now(),
                            'next_retry_at'               => null,
                        ]);
                    } else {
                        $this->handleFailure($booking, $attempt, $result->message);
                    }
                }
            } catch (\Exception $e) {
                $this->handleFailure($booking, $attempt, $e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    private function processRecurringGroups(GoogleCalendarService $gcalService, Carbon $now)
    {
        // Find master bookings that are pending
        $masters = DB::transaction(function () use ($now) {
            return ClassBooking::where('is_recurring_master', true)
                ->whereIn('google_sync_status', ['pending', 'failed'])
                ->where('google_sync_attempts', '<', 5)
                ->where(function ($query) use ($now) {
                    $query->whereNull('next_retry_at')
                          ->orWhere('next_retry_at', '<=', $now);
                })
                ->lockForUpdate()
                ->get();
        });

        if ($masters->isEmpty()) {
            $this->info("No pending recurring groups to sync.");
            return;
        }

        foreach ($masters as $master) {
            $master->increment('google_sync_attempts');
            $attempt = $master->google_sync_attempts;

            $this->info("Processing recurring group ID {$master->recurrence_group_id} (Attempt {$attempt})...");

            try {
                // Fetch all sibling bookings in the group
                $siblings = ClassBooking::where('recurrence_group_id', $master->recurrence_group_id)
                    ->orderBy('starts_at', 'asc')
                    ->get();

                $result = $gcalService->createRecurringEvent($master, $siblings);

                if ($result->status === 'synced') {
                    $now = Carbon::now();
                    // Update all siblings with the same event info
                    ClassBooking::where('recurrence_group_id', $master->recurrence_group_id)
                        ->update([
                            'google_sync_status'          => 'synced',
                            'google_sync_message'         => null,
                            'google_event_id'             => $result->eventId,
                            'google_calendar_id'          => config('services.google.calendar_id', 'primary'),
                            'google_meet_link'            => $result->meetLink,
                            'meet_link_source_booking_id' => $master->id,
                            'meet_link_generated_at'      => $now,
                            'next_retry_at'               => null,
                        ]);
                        
                    // Also save the payload on the master for reference
                    $master->updateQuietly(['google_event_payload' => $result->payload]);
                } else {
                    $this->handleFailure($master, $attempt, $result->message);
                }
            } catch (\Exception $e) {
                $this->handleFailure($master, $attempt, $e->getMessage());
            }
        }
    }

    private function handleFailure(ClassBooking $booking, int $attempt, ?string $message)
    {
        Log::error("Google Calendar Sync failed for booking ID {$booking->id} on attempt {$attempt}: {$message}");
        
        $now = Carbon::now();
        $startsAt = Carbon::parse($booking->starts_at);
        $hoursUntilClass = $now->diffInHours($startsAt, false);

        if ($attempt >= 5) {
            $this->markPermanentFailure($booking, "Exhausted 5 attempts. Last Error: {$message}");
            return;
        }

        if ($attempt < 3) {
            // Immediate retry on next cron run (next minute)
            $booking->update([
                'google_sync_status'  => 'failed',
                'google_sync_message' => substr($message, 0, 500),
                'next_retry_at'       => null, 
            ]);
            $this->error("Booking {$booking->id} failed. Retrying next minute.");
        } else {
            // Attempt 3 or 4: check 6-hour deadline
            if ($hoursUntilClass <= 6) {
                $this->markPermanentFailure($booking, "Less than 6 hours until class on attempt {$attempt}. Aborting. Error: {$message}");
            } else {
                // Wait 90 minutes
                $booking->update([
                    'google_sync_status'  => 'failed',
                    'google_sync_message' => substr($message, 0, 500),
                    'next_retry_at'       => $now->copy()->addMinutes(90),
                ]);
                $this->error("Booking {$booking->id} failed. Delaying 90 minutes.");
            }
        }
    }

    private function markPermanentFailure(ClassBooking $booking, string $message)
    {
        $booking->update([
            'google_sync_status'  => 'failed_permanent',
            'google_sync_message' => substr($message, 0, 500),
            'next_retry_at'       => null,
        ]);
        $this->error("Booking {$booking->id} marked as failed_permanent: {$message}");
    }
}
