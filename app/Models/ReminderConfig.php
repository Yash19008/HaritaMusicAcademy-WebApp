<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderConfig extends Model
{
    protected $fillable = [
        'label',
        'minutes_before',
        'notify_student',
        'notify_teacher',
        'enabled',
    ];

    protected $casts = [
        'notify_student' => 'boolean',
        'notify_teacher' => 'boolean',
        'enabled' => 'boolean',
    ];

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
