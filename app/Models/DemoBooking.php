<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoBooking extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
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
