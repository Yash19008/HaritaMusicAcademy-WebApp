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
