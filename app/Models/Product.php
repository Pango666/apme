<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'community_id',
        'name',
        'slug',
        'type',
        'description',
        'price_bs',
        'is_active',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_button_text',
        'hero_button_url',
        'hero_button_color',
        'hero_button_text_color',
        'hero_button_background_color',
        'about_html',
        'blocks'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_bs'  => 'decimal:2',
        'blocks'    => 'array',     // JSON con bloques opcionales
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug)) $m->slug = Str::slug($m->name);
        });
    }

    /** Relaciones */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /** URL pública segura de la portada */
    public function getCoverUrlAttribute(): string
    {
        $path = $this->cover_path ?? '';
        if (!$path) return '/placeholder.webp';

        return preg_match('#^(https?://|/)#', $path)
            ? $path
            : Storage::url($path);
    }

    /** Helper para convertir una ruta (relativa) a URL pública */
    public static function urlify(?string $path): ?string
    {
        if (!$path) return null;
        return preg_match('#^(https?://|/)#', $path) ? $path : Storage::url($path);
    }
}
