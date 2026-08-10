<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherPayroll extends Model
{
    protected $table = 'teacher_payroll';

    protected $guarded = [];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
