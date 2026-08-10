<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['transaction_date' => 'date'];
    }

    public function convertedStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'converted_student_id');
    }

    public function demoBookings(): HasMany
    {
        return $this->hasMany(DemoBooking::class);
    }

    public function latestDemo()
    {
        return $this->hasOne(DemoBooking::class)->latestOfMany();
    }
}
