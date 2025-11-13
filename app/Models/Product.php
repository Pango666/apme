<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['community_id', 'name', 'slug', 'type', 'description', 'price_bs', 'is_active'];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (blank($m->slug)) $m->slug = Str::slug($m->name);
        });
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
