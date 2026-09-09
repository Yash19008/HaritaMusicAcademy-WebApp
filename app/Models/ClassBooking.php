<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClassBooking extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'instrument', 'starts_at', 'ends_at',
        'duration_minutes', 'type', 'status', 'google_meet_link', 'student_attended',
        'teacher_attended', 'notes', 'student_group_id', 'google_event_id',
        'recurrence_rule', 'google_sync_status', 'google_sync_error', 'parent_booking_id',
        'reschedule_requested_datetime', 'reschedule_requested_starts_at',
        'reschedule_requested_ends_at', 'reschedule_requested_reason',
        'reschedule_requested_by', 'reschedule_status',
        'teacher_join_token', 'student_join_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (ClassBooking $booking) {
            if (!$booking->teacher_join_token) {
                $booking->teacher_join_token = Str::uuid()->toString();
            }
            if (!$booking->student_join_token) {
                $booking->student_join_token = Str::uuid()->toString();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'starts_at'                      => 'datetime',
            'ends_at'                        => 'datetime',
            'reschedule_requested_datetime'  => 'datetime',
            'reschedule_requested_starts_at' => 'datetime',
            'reschedule_requested_ends_at'   => 'datetime',
            'google_event_payload'           => 'array',
            'student_attended'               => 'boolean',
            'teacher_attended'               => 'boolean',
        ];
    }

    /**
     * Generate and store role-specific join tokens.
     * Teacher token => ONLY marks teacher_attended.
     * Student token => ONLY marks student_attended.
     */
    public function generateJoinTokens(): void
    {
        $this->teacher_join_token = Str::uuid()->toString();
        $this->student_join_token = Str::uuid()->toString();
        $this->saveQuietly();
    }

    public function getTeacherJoinUrlAttribute(): string
    {
        return route('attendance.join', ['type' => 'teacher', 'token' => $this->teacher_join_token]);
    }

    public function getStudentJoinUrlAttribute(): string
    {
        return route('attendance.join', ['type' => 'student', 'token' => $this->student_join_token]);
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
