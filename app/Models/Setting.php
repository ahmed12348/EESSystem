<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getValue($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            return self::where('key', $key)->value('value') ?? $default;
        });
    }

  
    public static function setValue($key, $value)
    {
        Cache::forget("setting_{$key}");

        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

  
    public static function deleteValue($key)
    {
        Cache::forget("setting_{$key}");
        return self::where('key', $key)->delete();
    }

   
    public static function getAll()
    {
        return Cache::remember('all_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    
    public static function clearCache()
    {
        Cache::forget('all_settings');
    }
}
