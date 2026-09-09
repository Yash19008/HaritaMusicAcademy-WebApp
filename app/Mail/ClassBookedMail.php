<?php

namespace App\Mail;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClassBookedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $isTeacher;
    public $targetStudent;

    /**
     * Create a new message instance.
     */
    public function __construct(ClassBooking $booking, bool $isTeacher = false, $targetStudent = null)
    {
        $this->booking = $booking;
        $this->isTeacher = $isTeacher;
        $this->targetStudent = $targetStudent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Class Booking Confirmation - Harita Music Academy',
        );
    }

    public function content(): Content
    {
        $joinUrl = route('attendance.join', [
            'type' => $this->isTeacher ? 'teacher' : 'student',
            'token' => $this->isTeacher ? $this->booking->teacher_join_token : $this->booking->student_join_token,
        ]);

        return new Content(
            view: 'emails.class_booked',
            with: [
                'booking' => $this->booking,
                'isTeacher' => $this->isTeacher,
                'targetStudent' => $this->targetStudent,
                'joinUrl' => $joinUrl,
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
