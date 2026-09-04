<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassBooking extends Model
{
    protected $guarded = [];

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
