<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Community extends Model
{
    protected $fillable = ['name', 'slug', 'province', 'description', 'latitude', 'longitude'];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug)) $m->slug = Str::slug($m->name);
        });
        static::updating(function ($m) {
            if ($m->isDirty('name') && blank($m->slug)) $m->slug = Str::slug($m->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
