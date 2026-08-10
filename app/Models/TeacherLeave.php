<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherLeave extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'from_date' => 'date',
            'to_date'   => 'date',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
