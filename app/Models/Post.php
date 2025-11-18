<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'cover_path', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug) && !blank($m->title)) {
                $m->slug = Str::slug($m->title);
            }
        });
        static::updating(function ($m) {
            if ($m->isDirty('title') && blank($m->slug)) {
                $m->slug = Str::slug($m->title);
            }
        });
    }

    /** URL pública segura de la portada (soporta ruta relativa o absoluta) */
    public function getCoverUrlAttribute(): string
    {
        $path = $this->cover_path;
        if (!$path) return '/placeholder.webp';

        return preg_match('#^(https?://|/)#', $path)
            ? $path
            : Storage::url($path);
    }
}
