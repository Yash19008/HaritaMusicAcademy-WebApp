<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    protected $guarded = [];

    public function folder()
    {
        return $this->belongsTo(ResourceFolder::class, 'resource_folder_id');
    }

    public function getFileSizeForHumansAttribute()
    {
        if (!$this->file_size) return 'Unknown size';
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
