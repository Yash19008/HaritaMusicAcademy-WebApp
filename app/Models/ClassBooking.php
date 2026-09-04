<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassBooking extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'instrument', 'starts_at', 'ends_at', 
        'duration_minutes', 'type', 'status', 'google_meet_link', 'student_attended', 
        'teacher_attended', 'notes', 'student_group_id', 'google_event_id', 
        'recurrence_rule', 'google_sync_status', 'google_sync_error', 'parent_booking_id', 
        'reschedule_requested_datetime', 'reschedule_requested_starts_at', 
        'reschedule_requested_ends_at', 'reschedule_requested_reason', 
        'reschedule_requested_by', 'reschedule_status'
    ];

    protected function casts(): array
    {
        return [
            'starts_at'                      => 'datetime',
            'ends_at'                        => 'datetime',
            'reschedule_requested_datetime'  => 'datetime',
            'reschedule_requested_starts_at' => 'datetime',
            'reschedule_requested_ends_at'   => 'datetime',
            'google_event_payload'           => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
