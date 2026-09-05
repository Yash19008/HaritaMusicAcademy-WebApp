<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Get a setting value by key */
    public static function get(string $key, mixed $default = null): mixed
    {
        return \Illuminate\Support\Facades\Cache::remember("setting_{$key}", 300, fn() => 
            static::where('key', $key)->value('value') ?? $default
        );
    }

    /** Set (upsert) a setting value */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        \Illuminate\Support\Facades\Cache::forget("setting_{$key}");
    }
}
