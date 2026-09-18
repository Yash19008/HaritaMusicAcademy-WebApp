<?php

namespace App\Services;

use App\Models\ClassBooking;
use App\Models\DemoBooking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class GoogleCalendarService
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const SCOPE = 'https://www.googleapis.com/auth/calendar.events';

    /**
     * Create a Google Calendar event with a Meet link for a class booking.
     */
    public function createMeetEvent(ClassBooking|DemoBooking $booking): GoogleCalendarEventResult
    {
        if (! $this->configured()) {
            return new GoogleCalendarEventResult('not_configured', message: 'Google Calendar credentials are not configured.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');

            $calendarId = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $timezone   = config('services.google.timezone', config('app.timezone', 'Asia/Kolkata'));
            $token      = $this->accessToken($impersonateEmail);

            if (! $token) {
                return new GoogleCalendarEventResult('failed', message: 'Unable to create Google access token.');
            }

            $teacherName  = $booking->teacher->user->name ?? $booking->teacher->name ?? 'Teacher';
            $teacherEmail = $booking->teacher->user->email ?? $booking->teacher->email ?? null;

            $attendees = [];
            $attendees[] = $teacherEmail ? ['email' => $teacherEmail, 'displayName' => $teacherName] : null;

            $className = 'Class';
            if ($booking->student_group_id) {
                $className = $booking->studentGroup->name . ' Group Class';
                foreach ($booking->studentGroup->members as $member) {
                    $memberEmail = $member->user->email ?? $member->email ?? null;
                    if ($memberEmail) {
                        $attendees[] = ['email' => $memberEmail, 'displayName' => $member->user->name ?? 'Student'];
                    }
                }
            } else {
                $studentName  = $booking->student->user->name ?? $booking->student->name ?? 'Student';
                $studentEmail = $booking->student->user->email ?? $booking->student->email ?? null;
                $className = ($booking->instrument ?? 'Music') . ' Class - ' . $studentName;
                if ($studentEmail) {
                    $attendees[] = ['email' => $studentEmail, 'displayName' => $studentName];
                }
            }

            $attendees = array_values(array_filter($attendees));

            $start = $booking->starts_at ?? $booking->scheduled_at;
            $end = $booking->ends_at ?? (clone $start)->addMinutes($booking->duration_minutes ?? 40);

            $payload = [
                'summary'     => 'Harita - ' . $className,
                'description' => trim(($booking->notes ?? '') . "\n\nHarita Music Academy Class Booking"),
                'start'       => [
                    'dateTime' => $start->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'end'         => [
                    'dateTime' => $end->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'attendees'   => $attendees,
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => 'harita-' . $booking->id . '-' . Str::uuid(),
                    ],
                ],
            ];

            $response = Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'conferenceDataVersion' => 1,
                    'sendUpdates'           => config('services.google.send_updates', 'none'),
                ])
                ->post(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events',
                    $payload
                );

            if (! $response->successful()) {
                Log::error('Google Calendar createMeetEvent failed', ['response' => $response->json()]);
                return new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
            }

            $event = $response->json();

            return new GoogleCalendarEventResult(
                status:   'synced',
                eventId:  $event['id'] ?? null,
                meetLink: $event['hangoutLink'] ?? data_get($event, 'conferenceData.entryPoints.0.uri'),
                payload:  $event,
            );
        } catch (Throwable $throwable) {
            Log::error('Google Calendar createMeetEvent exception: ' . $throwable->getMessage());
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    /**
     * Create a standard Google Calendar event WITHOUT generating a new Meet link.
     * Used for link reuse where the Meet link is already known and distributed via email/panel.
     */
    public function createEventWithoutMeet(ClassBooking $booking): GoogleCalendarEventResult
    {
        if (! $this->configured()) {
            return new GoogleCalendarEventResult('not_configured', message: 'Google Calendar credentials are not configured.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');

            $calendarId = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $timezone   = config('services.google.timezone', config('app.timezone', 'Asia/Kolkata'));
            $token      = $this->accessToken($impersonateEmail);

            if (! $token) {
                return new GoogleCalendarEventResult('failed', message: 'Unable to create Google access token.');
            }

            $teacherName  = $booking->teacher->user->name ?? $booking->teacher->name ?? 'Teacher';
            $teacherEmail = $booking->teacher->user->email ?? $booking->teacher->email ?? null;

            $attendees = [];
            $attendees[] = $teacherEmail ? ['email' => $teacherEmail, 'displayName' => $teacherName] : null;

            $className = 'Class';
            if ($booking->student_group_id) {
                $className = $booking->studentGroup->name . ' Group Class';
                foreach ($booking->studentGroup->members as $member) {
                    $memberEmail = $member->user->email ?? $member->email ?? null;
                    if ($memberEmail) {
                        $attendees[] = ['email' => $memberEmail, 'displayName' => $member->user->name ?? 'Student'];
                    }
                }
            } else {
                $studentName  = $booking->student->user->name ?? $booking->student->name ?? 'Student';
                $studentEmail = $booking->student->user->email ?? $booking->student->email ?? null;
                $className = ($booking->instrument ?? 'Music') . ' Class - ' . $studentName;
                if ($studentEmail) {
                    $attendees[] = ['email' => $studentEmail, 'displayName' => $studentName];
                }
            }

            $attendees = array_values(array_filter($attendees));

            $start = $booking->starts_at ?? $booking->scheduled_at;
            $end = $booking->ends_at ?? (clone $start)->addMinutes($booking->duration_minutes ?? 40);

            $payload = [
                'summary'     => 'Harita - ' . $className,
                'description' => trim(($booking->notes ?? '') . "\n\nHarita Music Academy Class Booking"),
                'start'       => [
                    'dateTime' => $start->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'end'         => [
                    'dateTime' => $end->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'attendees'   => $attendees,
                // No conferenceData here
            ];

            $response = Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'sendUpdates' => config('services.google.send_updates', 'none'),
                ])
                ->post(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events',
                    $payload
                );

            if (! $response->successful()) {
                Log::error('Google Calendar createEventWithoutMeet failed', ['response' => $response->json()]);
                return new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
            }

            $event = $response->json();

            return new GoogleCalendarEventResult(
                status:   'synced',
                eventId:  $event['id'] ?? null,
                meetLink: null, // We didn't generate one
                payload:  $event,
            );
        } catch (Throwable $throwable) {
            Log::error('Google Calendar createEventWithoutMeet exception: ' . $throwable->getMessage());
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    /**
     * Update an existing Google Calendar event.
     */
    public function updateEvent(ClassBooking|DemoBooking $booking): GoogleCalendarEventResult
    {
        if (! $this->configured() || ! $booking->google_event_id) {
            return new GoogleCalendarEventResult('not_configured', message: 'No Google Calendar event to update.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');
            $calendarId       = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $timezone         = config('services.google.timezone', config('app.timezone', 'Asia/Kolkata'));
            $token            = $this->accessToken($impersonateEmail);

            if (! $token) {
                return new GoogleCalendarEventResult('failed', message: 'Unable to create Google access token.');
            }

            $studentName  = $booking->student->user->name ?? $booking->student->name ?? 'Student';
            $teacherName  = $booking->teacher->user->name ?? $booking->teacher->name ?? 'Teacher';
            $studentEmail = $booking->student->user->email ?? $booking->student->email ?? null;
            $teacherEmail = $booking->teacher->user->email ?? $booking->teacher->email ?? null;

            $start = $booking->starts_at ?? $booking->scheduled_at;
            $end = $booking->ends_at ?? (clone $start)->addMinutes($booking->duration_minutes ?? 40);

            $payload = [
                'summary'   => 'Harita - ' . ($booking->instrument ?? 'Music') . ' Class - ' . $studentName,
                'description' => trim(($booking->notes ?? '') . "\n\nHarita Music Academy Class Booking"),
                'start'     => [
                    'dateTime' => $start->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'end'       => [
                    'dateTime' => $end->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'attendees' => array_values(array_filter([
                    $studentEmail ? ['email' => $studentEmail, 'displayName' => $studentName] : null,
                    $teacherEmail ? ['email' => $teacherEmail, 'displayName' => $teacherName] : null,
                ])),
            ];

            $response = Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'sendUpdates' => config('services.google.send_updates', 'none'),
                ])
                ->patch(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events/' . rawurlencode($booking->google_event_id),
                    $payload
                );

            if (! $response->successful()) {
                return new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
            }

            $event = $response->json();

            return new GoogleCalendarEventResult(
                status:   'synced',
                eventId:  $event['id'] ?? null,
                meetLink: $event['hangoutLink'] ?? data_get($event, 'conferenceData.entryPoints.0.uri'),
                payload:  $event,
            );
        } catch (Throwable $throwable) {
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    /**
     * Cancel (delete) a Google Calendar event.
     */
    public function cancelEvent(ClassBooking|DemoBooking $booking): GoogleCalendarEventResult
    {
        if (! $this->configured() || ! $booking->google_event_id) {
            return new GoogleCalendarEventResult('not_configured', message: 'No Google Calendar event to cancel.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');
            $calendarId       = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $token            = $this->accessToken($impersonateEmail);

            $response = Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'sendUpdates' => config('services.google.send_updates', 'none'),
                ])
                ->delete(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events/' . rawurlencode($booking->google_event_id)
                );

            return $response->successful()
                ? new GoogleCalendarEventResult('cancelled')
                : new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
        } catch (Throwable $throwable) {
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    /**
     * Create a Google Calendar recurring event for a set of bookings.
     */
    public function createRecurringEvent(ClassBooking $masterBooking, \Illuminate\Database\Eloquent\Collection $bookings): GoogleCalendarEventResult
    {
        if (! $this->configured()) {
            return new GoogleCalendarEventResult('not_configured', message: 'Google Calendar credentials are not configured.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');
            $calendarId       = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $timezone         = config('services.google.timezone', config('app.timezone', 'Asia/Kolkata'));
            $token            = $this->accessToken($impersonateEmail);

            if (! $token) {
                return new GoogleCalendarEventResult('failed', message: 'Unable to create Google access token.');
            }

            $teacherName  = $masterBooking->teacher->user->name ?? $masterBooking->teacher->name ?? 'Teacher';
            $teacherEmail = $masterBooking->teacher->user->email ?? $masterBooking->teacher->email ?? null;

            $attendees = [];
            $attendees[] = $teacherEmail ? ['email' => $teacherEmail, 'displayName' => $teacherName] : null;

            $className = 'Recurring Class';
            if ($masterBooking->student_group_id) {
                $className = $masterBooking->studentGroup->name . ' Group Class';
                foreach ($masterBooking->studentGroup->members as $member) {
                    $memberEmail = $member->user->email ?? $member->email ?? null;
                    if ($memberEmail) {
                        $attendees[] = ['email' => $memberEmail, 'displayName' => $member->user->name ?? 'Student'];
                    }
                }
            } else {
                $studentName  = $masterBooking->student->user->name ?? $masterBooking->student->name ?? 'Student';
                $studentEmail = $masterBooking->student->user->email ?? $masterBooking->student->email ?? null;
                $className = ($masterBooking->instrument ?? 'Music') . ' Class - ' . $studentName;
                if ($studentEmail) {
                    $attendees[] = ['email' => $studentEmail, 'displayName' => $studentName];
                }
            }

            $attendees = array_values(array_filter($attendees));

            $start = $masterBooking->starts_at ?? $masterBooking->scheduled_at;
            $end = $masterBooking->ends_at ?? (clone $start)->addMinutes($masterBooking->duration_minutes ?? 40);

            // Compute RRULE based on the first booking and the collection of bookings
            // For simplicity, we can get unique days of the week from the bookings
            $daysMap = [0 => 'SU', 1 => 'MO', 2 => 'TU', 3 => 'WE', 4 => 'TH', 5 => 'FR', 6 => 'SA'];
            $byDay = [];
            foreach ($bookings as $booking) {
                $byDay[] = $daysMap[$booking->starts_at->dayOfWeek];
            }
            $byDay = array_unique($byDay);
            $byDayStr = implode(',', $byDay);
            
            $count = $bookings->count();

            $rrule = "RRULE:FREQ=WEEKLY;BYDAY={$byDayStr};COUNT={$count}";

            $payload = [
                'summary'     => 'Harita - ' . $className,
                'description' => trim(($masterBooking->notes ?? '') . "\n\nHarita Music Academy Recurring Class Booking"),
                'start'       => [
                    'dateTime' => $start->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'end'         => [
                    'dateTime' => $end->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'recurrence'  => [
                    $rrule
                ],
                'attendees'   => $attendees,
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => 'harita-rec-' . $masterBooking->id . '-' . \Illuminate\Support\Str::uuid(),
                    ],
                ],
            ];

            $response = \Illuminate\Support\Facades\Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'conferenceDataVersion' => 1,
                    'sendUpdates'           => config('services.google.send_updates', 'none'),
                ])
                ->post(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events',
                    $payload
                );

            if (! $response->successful()) {
                \Illuminate\Support\Facades\Log::error('Google Calendar createRecurringEvent failed', ['response' => $response->json()]);
                return new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
            }

            $event = $response->json();
            
            // Save the RRULE on the master booking just in case
            $masterBooking->updateQuietly(['recurrence_rule' => $rrule]);

            return new GoogleCalendarEventResult(
                status:   'synced',
                eventId:  $event['id'] ?? null,
                meetLink: $event['hangoutLink'] ?? data_get($event, 'conferenceData.entryPoints.0.uri'),
                payload:  $event,
            );
        } catch (Throwable $throwable) {
            \Illuminate\Support\Facades\Log::error('Google Calendar createRecurringEvent exception: ' . $throwable->getMessage());
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    /**
     * Cancel (delete) a single instance of a Google Calendar recurring event.
     */
    public function deleteEventInstance(ClassBooking $booking): GoogleCalendarEventResult
    {
        if (! $this->configured() || ! $booking->google_event_id) {
            return new GoogleCalendarEventResult('not_configured', message: 'No Google Calendar event to cancel.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');
            $calendarId       = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $token            = $this->accessToken($impersonateEmail);
            
            // For recurring event instances, the instance ID is {recurringEventId}_{originalStartTimeInUTC}
            // Where time format is YYYYMMDDTHHMMSSZ
            // The original start time should be used. For cancellation, starts_at hasn't changed.
            $originalStartTime = $booking->starts_at->copy()->setTimezone('UTC')->format('Ymd\THis\Z');
            $instanceId = $booking->google_event_id . '_' . $originalStartTime;

            $response = \Illuminate\Support\Facades\Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'sendUpdates' => config('services.google.send_updates', 'none'),
                ])
                ->delete(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events/' . rawurlencode($instanceId)
                );

            return $response->successful()
                ? new GoogleCalendarEventResult('cancelled')
                : new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
        } catch (Throwable $throwable) {
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }
    
    /**
     * Update a single instance of a recurring event (e.g., reschedule)
     * originalStartsAt MUST be provided if the booking's starts_at has already been updated in the DB
     */
    public function updateEventInstance(ClassBooking $booking, \Carbon\Carbon $originalStartsAt): GoogleCalendarEventResult
    {
        if (! $this->configured() || ! $booking->google_event_id) {
            return new GoogleCalendarEventResult('not_configured', message: 'No Google Calendar event to update.');
        }

        try {
            $impersonateEmail = config('services.google.impersonate_user');
            $calendarId       = $impersonateEmail ? 'primary' : config('services.google.calendar_id', 'primary');
            $timezone         = config('services.google.timezone', config('app.timezone', 'Asia/Kolkata'));
            $token            = $this->accessToken($impersonateEmail);

            if (! $token) {
                return new GoogleCalendarEventResult('failed', message: 'Unable to create Google access token.');
            }

            // Build instance ID using the original start time
            $originalStartTimeStr = $originalStartsAt->copy()->setTimezone('UTC')->format('Ymd\THis\Z');
            $instanceId = $booking->google_event_id . '_' . $originalStartTimeStr;
            
            $start = $booking->starts_at;
            $end = $booking->ends_at;

            $payload = [
                'start' => [
                    'dateTime' => $start->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $end->copy()->shiftTimezone($timezone)->toRfc3339String(),
                    'timeZone' => $timezone,
                ],
            ];

            // Use PATCH to update only the specific fields for this instance
            $response = \Illuminate\Support\Facades\Http::timeout(10)->retry(2, 500)->withToken($token)
                ->withQueryParameters([
                    'sendUpdates' => config('services.google.send_updates', 'none'),
                ])
                ->patch(
                    'https://www.googleapis.com/calendar/v3/calendars/' . rawurlencode($calendarId) . '/events/' . rawurlencode($instanceId),
                    $payload
                );

            if (! $response->successful()) {
                \Illuminate\Support\Facades\Log::error('Google Calendar updateEventInstance failed', ['response' => $response->json()]);
                return new GoogleCalendarEventResult('failed', payload: $response->json(), message: $response->body());
            }

            return new GoogleCalendarEventResult(
                status:   'synced',
                eventId:  $instanceId, // Return the instance ID just in case
                meetLink: null, // Keep existing
                payload:  $response->json(),
            );
        } catch (Throwable $throwable) {
            \Illuminate\Support\Facades\Log::error('Google Calendar updateEventInstance exception: ' . $throwable->getMessage());
            return new GoogleCalendarEventResult('failed', message: $throwable->getMessage());
        }
    }

    private function configured(): bool
    {
        return filled(config('services.google.service_account_path'))
            || filled(config('services.google.service_account_json'))
            || filled(config('services.google.access_token'));
    }

    private function accessToken(?string $impersonate = null): ?string
    {
        // Direct access token (for dev/testing)
        if ($token = config('services.google.access_token')) {
            return $token;
        }

        return Cache::remember('google_access_token_' . md5($impersonate ?? 'default'), 3500, function() use ($impersonate) {
            $json        = $this->serviceAccountJson();
            $credentials = json_decode($json ?: '', true);

            if (! is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
                return null;
            }

            $now    = time();
            $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));

            $claimsPayload = [
                'iss'   => $credentials['client_email'],
                'scope' => self::SCOPE,
                'aud'   => self::TOKEN_URL,
                'iat'   => $now,
                'exp'   => $now + 3600,
            ];

            if ($impersonate) {
                $claimsPayload['sub'] = $impersonate;
            }

            $claims      = $this->base64UrlEncode(json_encode($claimsPayload, JSON_UNESCAPED_SLASHES));
            $unsignedJwt = $header . '.' . $claims;

            // Ensure private key has correct newlines (handles cases where .env escapes \n)
            $privateKey = str_replace('\\n', "\n", $credentials['private_key']);

            openssl_sign($unsignedJwt, $signature, $privateKey, 'sha256WithRSAEncryption');

            $response = Http::timeout(10)->asForm()->post(self::TOKEN_URL, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $unsignedJwt . '.' . $this->base64UrlEncode($signature),
            ]);

            if (! $response->successful()) {
                Log::error('Google Service Account token error: ' . $response->body());
                // If impersonation failed, retry without sub claim (use service account calendar directly)
                if ($impersonate && str_contains($response->body(), 'invalid_grant')) {
                    // Temporarily bypass cache for fallback
                    Cache::forget('google_access_token_' . md5('default'));
                    return $this->accessToken(null);
                }
                return null;
            }

            return $response->json('access_token');
        });
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function serviceAccountJson(): ?string
    {
        $path = config('services.google.service_account_path');

        if ($path) {
            // Resolve relative paths relative to project root safely
            if (! str_starts_with($path, '/') && ! str_starts_with($path, 'C:') && ! str_starts_with($path, 'D:')) {
                $path = base_path($path);
            }

            $resolved = realpath($path);
            if ($resolved && is_readable($resolved) && str_starts_with($resolved, base_path())) {
                return file_get_contents($resolved) ?: null;
            }
        }

        return config('services.google.service_account_json');
    }
}
