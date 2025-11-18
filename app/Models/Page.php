<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $guarded = [];

    // Si más adelante añades campos como 'cover_path' no falla.
    protected $casts = [
        // nada especial por ahora
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug) && !blank($m->title)) {
                $m->slug = Str::slug($m->title);
            }
        });
        static::updating(function ($m) {
            // Si cambias el título y quieres regenerar el slug SOLO si está vacío
            if ($m->isDirty('title') && blank($m->slug)) {
                $m->slug = Str::slug($m->title);
            }
        });
    }

    /**
     * URL pública del cover si existe (cover_path puede ser absoluto o relativo).
     * Si no existe, retorna null.
     */
    public function getCoverUrlAttribute(): ?string
    {
        $path = $this->attributes['cover_path'] ?? null;
        if (!$path) return null;

        return preg_match('#^(https?://|/)#', $path)
            ? $path
            : Storage::url($path);
    }
}
