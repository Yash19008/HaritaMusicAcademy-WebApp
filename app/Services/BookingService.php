<?php

namespace App\Services;

use App\Models\ClassBooking;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\Student;
use App\Models\CreditTransaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Get all available 40-minute timeslots for a teacher on a specific date.
     */
    public function getAvailableSlots(Teacher $teacher, string $date): array
    {
        $targetDate = Carbon::parse($date);
        
        // 1. Check week_off (e.g. "MON,TUE")
        $dayOfWeek = strtoupper($targetDate->format('D')); // MON, TUE, etc.
        if ($teacher->week_off) {
            $weekOffs = explode(',', strtoupper($teacher->week_off));
            if (in_array($dayOfWeek, $weekOffs)) {
                return []; // Teacher is on week-off
            }
        }

        // 2. Check approved leaves
        $isOnLeave = TeacherLeave::where('teacher_id', $teacher->id)
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $targetDate)
            ->whereDate('to_date', '>=', $targetDate)
            ->exists();
            
        if ($isOnLeave) {
            return []; // Teacher is on leave
        }

        // 3. Generate all slots (8 AM to 2 AM next day, 40-min intervals)
        $startTime = $targetDate->copy()->setTime(8, 0, 0);
        $endTime = $targetDate->copy()->addDay()->setTime(2, 0, 0);
        
        $slots = [];
        $current = $startTime->copy();
        
        while ($current->copy()->addMinutes(40)->lessThanOrEqualTo($endTime)) {
            $slots[] = [
                'start' => $current->copy(),
                'end' => $current->copy()->addMinutes(40)
            ];
            $current->addMinutes(40);
        }

        // 4. Get existing bookings for this teacher around this date
        $existingBookings = ClassBooking::where('teacher_id', $teacher->id)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('starts_at', [$startTime, $endTime])
                      ->orWhereBetween('ends_at', [$startTime, $endTime]);
            })
            ->get();
            
        // Get existing demo bookings for this teacher
        $existingDemos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$startTime, $endTime])
            ->get();

        // 5. Filter out overlapping slots
        $availableSlots = [];
        
        // Also ensure we don't return slots in the past
        $now = Carbon::now();
        
        foreach ($slots as $slot) {
            if ($slot['start']->lessThanOrEqualTo($now)) {
                continue; // Skip past slots
            }
            
            $isOverlapping = false;
            foreach ($existingBookings as $booking) {
                // Check for overlap
                // Slot start < Booking end AND Slot end > Booking start
                if ($slot['start']->lessThan($booking->ends_at) && $slot['end']->greaterThan($booking->starts_at)) {
                    $isOverlapping = true;
                    break;
                }
            }
            
            if (!$isOverlapping) {
                foreach ($existingDemos as $demo) {
                    $demoStart = Carbon::parse($demo->scheduled_at);
                    $demoEnd = $demoStart->copy()->addMinutes((int) $demo->duration_minutes);
                    if ($slot['start']->lessThan($demoEnd) && $slot['end']->greaterThan($demoStart)) {
                        $isOverlapping = true;
                        break;
                    }
                }
            }
            
            if (!$isOverlapping) {
                $availableSlots[] = [
                    'start_time' => $slot['start']->format('Y-m-d H:i:s'),
                    'end_time' => $slot['end']->format('Y-m-d H:i:s'),
                    'display_time' => $slot['start']->format('h:i A') . ' - ' . $slot['end']->format('h:i A'),
                    'date_display' => $slot['start']->format('D, M d, Y')
                ];
            }
        }

        return $availableSlots;
    }
    
    /**
     * Check if a specific slot is available.
     */
    public function isSlotAvailable(Teacher $teacher, Carbon $startsAt, Carbon $endsAt): bool
    {
        // Check week_off
        $dayOfWeek = strtoupper($startsAt->format('D'));
        if ($teacher->week_off && in_array($dayOfWeek, explode(',', strtoupper($teacher->week_off)))) {
            return false;
        }
        
        // Check leave
        $isOnLeave = TeacherLeave::where('teacher_id', $teacher->id)
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $startsAt->toDateString())
            ->whereDate('to_date', '>=', $startsAt->toDateString())
            ->exists();
            
        if ($isOnLeave) {
            return false;
        }
        
        // Check existing bookings
        $hasOverlap = ClassBooking::where('teacher_id', $teacher->id)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where(function($q) use ($startsAt, $endsAt) {
                    $q->where('starts_at', '<', $endsAt)
                      ->where('ends_at', '>', $startsAt);
                });
            })
            ->exists();
            
        if (!$hasOverlap) {
            $demos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
                ->where('status', 'scheduled')
                ->whereDate('scheduled_at', $startsAt->toDateString())
                ->get();
                
            foreach ($demos as $demo) {
                $demoStart = Carbon::parse($demo->scheduled_at);
                $demoEnd = $demoStart->copy()->addMinutes((int) $demo->duration_minutes);
                if ($startsAt->lessThan($demoEnd) && $endsAt->greaterThan($demoStart)) {
                    $hasOverlap = true;
                    break;
                }
            }
        }
            
        return !$hasOverlap;
    }

    /**
     * Process a one-time booking.
     */
    public function bookOneTime(Student $student, Teacher $teacher, Carbon $startsAt, Carbon $endsAt, string $notes = null): ClassBooking
    {
        return DB::transaction(function () use ($student, $teacher, $startsAt, $endsAt, $notes) {
            // 1. Verify availability again inside transaction
            if (!$this->isSlotAvailable($teacher, $startsAt, $endsAt)) {
                throw new \Exception("The selected time slot is no longer available.");
            }
            
            // 2. Verify credits
            if ($student->credits < 1) {
                throw new \Exception("Insufficient credits to book this class.");
            }
            
            // 3. Deduct credit
            $student->decrement('credits', 1);
            
            CreditTransaction::create([
                'student_id' => $student->id,
                'action' => 'Deducted',
                'quantity' => 1,
                'reason' => 'One-time Class Booking for ' . $startsAt->format('M d, Y h:i A')
            ]);
            
            // 4. Create Booking
            $booking = ClassBooking::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'instrument' => $student->course->name ?? 'Music Class',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'duration_minutes' => 40,
                'type' => 'one-time',
                'status' => 'scheduled',
                'notes' => $notes
            ]);
            
            // 5. Create Google Calendar Event — will be handled by queue
            // $this->createGoogleCalendarEvent($booking);
            
            return $booking;
        });
    }

    /**
     * Process a demo booking.
     */
    public function bookDemo(array $data, Teacher $teacher, Carbon $startsAt, Carbon $endsAt): \App\Models\DemoBooking
    {
        // 1. Create booking inside transaction
        $booking = DB::transaction(function () use ($data, $teacher, $startsAt, $endsAt) {
            if (!$this->isSlotAvailable($teacher, $startsAt, $endsAt)) {
                throw new \Exception("The selected time slot is no longer available.");
            }
            
            return \App\Models\DemoBooking::create([
                'payment_id'    => $data['lead_id'] ?? null,
                'student_name'  => $data['student_name'],
                'email'         => $data['email'] ?? '',
                'phone'         => $data['phone'] ?? '',
                'instrument'    => $data['instrument'] ?? 'Music',
                'teacher_id'    => $teacher->id,
                'scheduled_at'  => $startsAt,
                'duration_minutes' => $startsAt->diffInMinutes($endsAt),
                'status'        => 'scheduled',
            ]);
        });
        
        // 2. Call external API outside of transaction
        $result = app(\App\Services\GoogleCalendarService::class)->createMeetEvent($booking);
        
        // 3. Update booking with sync results
        $booking->update([
            'google_sync_status'  => $result->status,
            'google_sync_message' => $result->message,
            'google_event_id'     => $result->eventId,
            'google_calendar_id'  => $result->eventId ? config('services.google.calendar_id', 'primary') : null,
            'google_meet_link'    => $result->meetLink,
            'google_event_payload'=> $result->payload,
        ]);
        
        if ($result->status !== 'synced') {
            Log::info('Google Calendar createMeetEvent (Demo) [' . $result->status . ']: ' . $result->message);
        }
        
        return $booking;
    }

    /**
     * Process recurring booking.
     * $weekDays: array of day abbreviations ('MON', 'TUE')
     * $timeString: "14:30" (24hr format)
     */
    public function bookRecurring(Student $student, Teacher $teacher, array $weekDays, string $timeString, string $notes = null): array
    {
        return DB::transaction(function () use ($student, $teacher, $weekDays, $timeString, $notes) {
            $creditsAvailable = $student->credits;
            
            if ($creditsAvailable <= 0) {
                throw new \Exception("Insufficient credits for recurring booking.");
            }
            
            $bookedClasses = [];
            $creditsToDeduct = 0;
            
            // Start searching from tomorrow
            $currentDate = Carbon::now()->addDay()->startOfDay();
            
            // Normalize week days to standard Carbon dayOfWeek numbers (0-6)
            $dayMap = ['SUN'=>0, 'MON'=>1, 'TUE'=>2, 'WED'=>3, 'THU'=>4, 'FRI'=>5, 'SAT'=>6];
            $targetDays = array_map(function($day) use ($dayMap) {
                return $dayMap[strtoupper($day)] ?? -1;
            }, $weekDays);
            
            // Loop until we run out of credits
            $safetyCounter = 0; // Prevent infinite loop
            
            $maxDate = $currentDate->copy()->addDays(365);
            $existingBookings = ClassBooking::where('teacher_id', $teacher->id)
                ->whereIn('status', ['scheduled', 'rescheduled'])
                ->whereBetween('starts_at', [$currentDate, $maxDate])
                ->get();
            $existingDemos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
                ->where('status', 'scheduled')
                ->whereBetween('scheduled_at', [$currentDate, $maxDate])
                ->get();
            $leaves = TeacherLeave::where('teacher_id', $teacher->id)
                ->where('status', 'approved')
                ->where('from_date', '<=', $maxDate->toDateString())
                ->where('to_date', '>=', $currentDate->toDateString())
                ->get();
            $weekOffs = $teacher->week_off ? explode(',', strtoupper($teacher->week_off)) : [];
            
            while ($creditsToDeduct < $creditsAvailable && $safetyCounter < 365) {
                if (in_array($currentDate->dayOfWeek, $targetDays)) {
                    // This is a target day, construct the full datetime
                    $startsAt = $currentDate->copy()->setTimeFromTimeString($timeString);
                    $endsAt = $startsAt->copy()->addMinutes(40);
                    
                    // Is this slot available?
                    if ($this->checkAvailabilityInMemory($teacher, $startsAt, $endsAt, $existingBookings, $existingDemos, $leaves, $weekOffs)) {
                        
                        // Book it!
                        $booking = ClassBooking::create([
                            'student_id' => $student->id,
                            'teacher_id' => $teacher->id,
                            'instrument' => $student->course->name ?? 'Music Class',
                            'starts_at' => $startsAt,
                            'ends_at' => $endsAt,
                            'duration_minutes' => 40,
                            'type' => 'recurring',
                            'status' => 'scheduled',
                            'notes' => $notes
                        ]);
                        
                        // Google Calendar Event will be handled by queue
                        // $this->createGoogleCalendarEvent($booking);
                        
                        $bookedClasses[] = $booking;
                        $creditsToDeduct++;
                    }
                }
                
                $currentDate->addDay();
                $safetyCounter++;
            }
            
            if ($creditsToDeduct > 0) {
                $student->decrement('credits', $creditsToDeduct);
                
                CreditTransaction::create([
                    'student_id' => $student->id,
                    'action' => 'Deducted',
                    'quantity' => $creditsToDeduct,
                    'reason' => 'Recurring Class Bookings (' . $creditsToDeduct . ' classes)'
                ]);
            }
            
            return $bookedClasses;
        });
    }

    public function bookOneTimeGroup(\App\Models\StudentGroup $group, Teacher $teacher, Carbon $startsAt, Carbon $endsAt, string $notes = null): ClassBooking
    {
        return DB::transaction(function () use ($group, $teacher, $startsAt, $endsAt, $notes) {
            if (!$this->isSlotAvailable($teacher, $startsAt, $endsAt)) {
                throw new \Exception("The selected time slot is no longer available.");
            }
            
            $members = $group->members;
            if ($members->isEmpty()) {
                throw new \Exception("The group has no members.");
            }

            foreach ($members as $student) {
                if ($student->credits < 1) {
                    throw new \Exception("Student {$student->user->name} has insufficient credits to book this class.");
                }
            }
            
            foreach ($members as $student) {
                $student->decrement('credits', 1);
                CreditTransaction::create([
                    'student_id' => $student->id,
                    'action' => 'Deducted',
                    'quantity' => 1,
                    'reason' => 'One-time Group Class Booking for ' . $startsAt->format('M d, Y h:i A')
                ]);
            }
            
            $booking = ClassBooking::create([
                'student_group_id' => $group->id,
                'teacher_id' => $teacher->id,
                'instrument' => 'Group Music Class',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'duration_minutes' => 40,
                'type' => 'one-time',
                'status' => 'scheduled',
                'notes' => $notes
            ]);
            
            // Google Calendar Event will be handled by queue
            // $this->createGoogleCalendarEvent($booking);
            
            return $booking;
        });
    }

    public function bookRecurringGroup(\App\Models\StudentGroup $group, Teacher $teacher, array $weekDays, string $timeString, string $notes = null): array
    {
        return DB::transaction(function () use ($group, $teacher, $weekDays, $timeString, $notes) {
            $members = $group->members;
            if ($members->isEmpty()) {
                throw new \Exception("The group has no members.");
            }

            $minCredits = $members->min('credits');
            
            if ($minCredits <= 0) {
                throw new \Exception("One or more students have insufficient credits for recurring booking.");
            }
            
            $bookedClasses = [];
            $classesBookedCount = 0;
            
            $currentDate = Carbon::now()->addDay()->startOfDay();
            $dayMap = ['SUN'=>0, 'MON'=>1, 'TUE'=>2, 'WED'=>3, 'THU'=>4, 'FRI'=>5, 'SAT'=>6];
            $targetDays = array_map(function($day) use ($dayMap) { return $dayMap[strtoupper($day)] ?? -1; }, $weekDays);
            
            $safetyCounter = 0;
            
            $maxDate = $currentDate->copy()->addDays(365);
            $existingBookings = ClassBooking::where('teacher_id', $teacher->id)
                ->whereIn('status', ['scheduled', 'rescheduled'])
                ->whereBetween('starts_at', [$currentDate, $maxDate])
                ->get();
            $existingDemos = \App\Models\DemoBooking::where('teacher_id', $teacher->id)
                ->where('status', 'scheduled')
                ->whereBetween('scheduled_at', [$currentDate, $maxDate])
                ->get();
            $leaves = TeacherLeave::where('teacher_id', $teacher->id)
                ->where('status', 'approved')
                ->where('from_date', '<=', $maxDate->toDateString())
                ->where('to_date', '>=', $currentDate->toDateString())
                ->get();
            $weekOffs = $teacher->week_off ? explode(',', strtoupper($teacher->week_off)) : [];
            
            while ($classesBookedCount < $minCredits && $safetyCounter < 365) {
                if (in_array($currentDate->dayOfWeek, $targetDays)) {
                    $startsAt = $currentDate->copy()->setTimeFromTimeString($timeString);
                    $endsAt = $startsAt->copy()->addMinutes(40);
                    
                    if ($this->checkAvailabilityInMemory($teacher, $startsAt, $endsAt, $existingBookings, $existingDemos, $leaves, $weekOffs)) {
                        $booking = ClassBooking::create([
                            'student_group_id' => $group->id,
                            'teacher_id' => $teacher->id,
                            'instrument' => 'Group Music Class',
                            'starts_at' => $startsAt,
                            'ends_at' => $endsAt,
                            'duration_minutes' => 40,
                            'type' => 'recurring',
                            'status' => 'scheduled',
                            'notes' => $notes
                        ]);
                        
                        // Google Calendar Event will be handled by queue
                        // $this->createGoogleCalendarEvent($booking);
                        $bookedClasses[] = $booking;
                        $classesBookedCount++;
                    }
                }
                
                $currentDate->addDay();
                $safetyCounter++;
            }
            
            if ($classesBookedCount > 0) {
                foreach ($members as $student) {
                    $student->decrement('credits', $classesBookedCount);
                    CreditTransaction::create([
                        'student_id' => $student->id,
                        'action' => 'Deducted',
                        'quantity' => $classesBookedCount,
                        'reason' => 'Recurring Group Class Bookings (' . $classesBookedCount . ' classes)'
                    ]);
                }
            }
            
            return $bookedClasses;
        });
    }

    /**
     * Create a Google Calendar event and persist ALL result fields to the booking.
     */
    private function createGoogleCalendarEvent(ClassBooking $booking): void
    {
        $result = app(GoogleCalendarService::class)->createMeetEvent($booking);

        $booking->update([
            'google_sync_status'  => $result->status,
            'google_sync_message' => $result->message,
            'google_event_id'     => $result->eventId,
            'google_calendar_id'  => $result->eventId ? config('services.google.calendar_id', 'primary') : null,
            'google_meet_link'    => $result->meetLink,
            'google_event_payload'=> $result->payload,
        ]);

        if ($result->message) {
            Log::info('Google Calendar createMeetEvent [' . $result->status . ']: ' . $result->message);
        }
    }

    /**
     * Update an existing Google Calendar event.
     * If the event ID is not found or update fails, creates a new one.
     */
    public function updateGoogleCalendarEvent(ClassBooking $booking): void
    {
        $gcal = app(GoogleCalendarService::class);

        if ($booking->google_event_id) {
            $result = $gcal->updateEvent($booking);

            $booking->update([
                'google_sync_status'   => $result->status,
                'google_sync_message'  => $result->message,
                'google_meet_link'     => $result->meetLink ?? $booking->google_meet_link,
                'google_event_payload' => $result->payload ?? $booking->google_event_payload,
            ]);

            if ($result->status === 'synced') {
                return;
            }

            Log::warning('GCal updateEvent failed [' . $result->status . '], recreating. Message: ' . $result->message);
        }

        // Fallback: create a fresh event
        $this->createGoogleCalendarEvent($booking);
    }

    /**
     * Checks if a slot is available using pre-fetched bookings to avoid N+1 queries.
     */
    private function checkAvailabilityInMemory(Teacher $teacher, Carbon $startsAt, Carbon $endsAt, $existingBookings, $existingDemos, $leaves, array $weekOffs): bool
    {
        if (in_array(strtoupper($startsAt->format('D')), $weekOffs)) {
            return false;
        }

        foreach ($leaves as $leave) {
            if ($startsAt->toDateString() >= $leave->from_date && $startsAt->toDateString() <= $leave->to_date) {
                return false;
            }
        }

        foreach ($existingBookings as $booking) {
            if ($startsAt->lessThan($booking->ends_at) && $endsAt->greaterThan($booking->starts_at)) {
                return false;
            }
        }

        foreach ($existingDemos as $demo) {
            $demoStart = Carbon::parse($demo->scheduled_at);
            $demoEnd = $demoStart->copy()->addMinutes((int) $demo->duration_minutes);
            if ($startsAt->lessThan($demoEnd) && $endsAt->greaterThan($demoStart)) {
                return false;
            }
        }

        return true;
    }
}

