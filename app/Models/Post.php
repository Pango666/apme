<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'cover_path', 'published_at'];
    protected $casts    = ['published_at' => 'datetime'];

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->slug = $m->slug ?: Str::slug($m->title);
        });
    }
}
