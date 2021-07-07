<?php

namespace App\Models;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $primaryKey = 'key';

    public static function getSettings($key = null): \Illuminate\Support\Collection
    {
        $settings = $key ? self::whereKey($key)->first() : self::get();
        $collect = collect();
        foreach ($settings as $setting) {
            // I don't understand why, but $setting->key, $setting->getAttribute('key) and $setting['key'] get "0"
            $collect->put($setting->getAttributes()['key'], $setting->value);
        }
        return $collect;
    }
}
