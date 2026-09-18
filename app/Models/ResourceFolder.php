<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceFolder extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    public function files()
    {
        return $this->hasMany(ResourceFile::class)->orderBy('sort_order');
    }
}
