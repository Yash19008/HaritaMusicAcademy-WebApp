<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunity extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'active_at'  => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(ClassBooking::class, 'class_booking_id');
    }

    public function originalTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    public function acceptedTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'accepted_teacher_id');
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(OpportunityRejection::class);
    }
}
