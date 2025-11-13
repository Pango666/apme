<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value'];
    public $timestamps = false;

    protected $casts = [
        'value' => 'json',
    ];
    
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string)$value]
        );
        cache()->forget('site.settings');
    }

    public static function allPairs(): array
    {
        return cache()->remember('site.settings', 3600, function () {
            return static::query()->get()->mapWithKeys(function($s){
                $json = json_decode($s->value, true);
                return [$s->key => $json ?? $s->value];
            })->toArray();
        });
    }
}
