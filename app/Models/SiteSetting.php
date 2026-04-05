<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember('site_setting.' . $key, now()->addHours(24), function () use ($key, $default) {
            $record = static::query()->where('key', $key)->first();
            return $record ? $record->value : $default;
        });
    }

    public static function setValue(string $key, $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
        \Illuminate\Support\Facades\Cache::forget('site_setting.' . $key);
    }
}
