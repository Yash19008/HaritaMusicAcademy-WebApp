<?php

namespace App\Services;

class GoogleCalendarEventResult
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $eventId = null,
        public readonly ?string $meetLink = null,
        public readonly ?array $payload = null,
        public readonly ?string $message = null,
    ) {}
}
