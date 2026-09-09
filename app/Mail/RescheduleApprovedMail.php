<?php

namespace App\Mail;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $isTeacher;

    public function __construct(ClassBooking $booking, bool $isTeacher = false)
    {
        $this->booking = $booking;
        $this->isTeacher = $isTeacher;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Class Reschedule Approved',
        );
    }

    public function content(): Content
    {
        $joinUrl = route('attendance.join', [
            'type' => $this->isTeacher ? 'teacher' : 'student',
            'token' => $this->isTeacher ? $this->booking->teacher_join_token : $this->booking->student_join_token,
        ]);

        return new Content(
            view: 'emails.reschedule-approved',
            with: [
                'booking' => $this->booking,
                'isTeacher' => $this->isTeacher,
                'joinUrl' => $joinUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
