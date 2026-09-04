<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'teacher_id', 'name', 'email', 'phone', 'enrolled_level', 
        'course_id', 'referral_source', 'emergency_contact_name', 
        'emergency_contact_phone', 'enrolled_format', 'credits', 'status', 
        'joining_date', 'age', 'country', 'end_date', 'renewal_interest', 'intro_video'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }

    public function getCourseAttribute()
    {
        return $this->relationLoaded('courses') ? $this->courses->first() : $this->courses()->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classBookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function groups()
    {
        return $this->belongsToMany(StudentGroup::class, 'student_group_members');
    }
}
