<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    protected $fillable = ['title', 'slug', 'type', 'date', 'place', 'summary'];
    protected $casts    = ['date' => 'date'];

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->slug = $m->slug ?: Str::slug($m->title);
        });
    }

    public function photos()
    {
        return $this->hasMany(AlbumPhoto::class)->orderBy('order');
    }
}
