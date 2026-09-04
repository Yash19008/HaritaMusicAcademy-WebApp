<?php

namespace App\Mail;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(ClassBooking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Class Reschedule Rejected',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reschedule-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
