<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentGroup extends Model
{
    protected $guarded = [];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_group_members');
    }
}
