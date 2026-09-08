<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'categories', 'week_off',
        'status', 'bio', 'youtube_url', 'certifications', 'per_class_rate',
        'experience', 'specialization', 'joining_date', 'rating', 'level',
        'emergency_contact_name', 'emergency_contact_phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function classBookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(TeacherLeave::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(TeacherPayroll::class);
    }

    /** Instruments as array from comma-separated string */
    public function getCategoriesArrayAttribute(): array
    {
        return $this->categories
            ? array_map('trim', explode(',', $this->categories))
            : [];
    }
}
