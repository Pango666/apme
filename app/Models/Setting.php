<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value'];
    public $timestamps = false;

    /**
     * Guarda valor como string (si envías array/obj se guarda JSON).
     */
    public static function set(string $key, $value): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        } elseif (!is_null($value)) {
            $value = (string) $value;
        } else {
            $value = '';
        }

        static::updateOrCreate(['key' => $key], ['value' => $value]);
        cache()->forget('site.settings');
    }

    /**
     * Devuelve pares clave => valor.
     * Si el value es JSON válido, lo convierte a array; si no, queda como string.
     */
    public static function allPairs(): array
    {
        return cache()->remember('site.settings', 3600, function () {
            return static::query()->get()->mapWithKeys(function ($s) {
                $decoded = json_decode($s->value, true);
                return [$s->key => (json_last_error() === JSON_ERROR_NONE ? $decoded : $s->value)];
            })->toArray();
        });
    }
}
