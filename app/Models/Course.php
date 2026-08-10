<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
