<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecurringClassesBookedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bookings;
    public $isTeacher;
    public $targetStudent;

    /**
     * Create a new message instance.
     */
    public function __construct(array $bookings, bool $isTeacher = false, $targetStudent = null)
    {
        $this->bookings = $bookings;
        $this->isTeacher = $isTeacher;
        $this->targetStudent = $targetStudent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recurring Class Booking Summary - Harita Music Academy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recurring_classes_booked',
            with: [
                'bookings' => $this->bookings,
                'isTeacher' => $this->isTeacher,
                'firstBooking' => $this->bookings[0] ?? null,
                'totalClasses' => count($this->bookings),
                'targetStudent' => $this->targetStudent,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
