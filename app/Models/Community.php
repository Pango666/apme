<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Community extends Model
{
    protected $guarded = [];

    protected $casts = [
        'blocks' => 'array', // para guardar/leer JSON como array
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug)) $m->slug = Str::slug($m->name);
        });
        static::updating(function ($m) {
            if ($m->isDirty('name') && blank($m->slug)) $m->slug = Str::slug($m->name);
        });
    }

    /** Relación */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /** URL pública del hero_image (si es ruta relativa la convierte con Storage::url) */
    public function getHeroImageUrlAttribute(): ?string
    {
        if (!$this->hero_image) return null;
        return preg_match('#^(https?://|/)#', $this->hero_image)
            ? $this->hero_image
            : Storage::url($this->hero_image);
    }

    /** Compatibilidad si tu tabla tiene latitude/longitude en vez de lat/lng */
    public function getLatAttribute()
    {
        return $this->attributes['lat'] ?? $this->attributes['latitude'] ?? null;
    }
    public function getLngAttribute()
    {
        return $this->attributes['lng'] ?? $this->attributes['longitude'] ?? null;
    }
}
