<?php

namespace App\Mail;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $requestedBy; // 'student' or 'teacher'

    public function __construct(ClassBooking $booking, string $requestedBy)
    {
        $this->booking = $booking;
        $this->requestedBy = $requestedBy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Class Reschedule Requested',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reschedule-requested',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
