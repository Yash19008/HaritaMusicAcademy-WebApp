<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoBooking extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (DemoBooking $booking) {
            if (!$booking->teacher_join_token) {
                $booking->teacher_join_token = \Illuminate\Support\Str::uuid()->toString();
            }
            if (!$booking->student_join_token) {
                $booking->student_join_token = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'teacher_attended' => 'boolean',
            'student_attended' => 'boolean',
        ];
    }

    public function getTeacherJoinUrlAttribute()
    {
        return url('/join/teacher/' . $this->teacher_join_token);
    }

    public function getStudentJoinUrlAttribute()
    {
        return url('/join/student/' . $this->student_join_token);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function convertedStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'converted_student_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
