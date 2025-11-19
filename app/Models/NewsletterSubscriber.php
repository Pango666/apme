<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'name', 'status', 'token', 'confirmed_at'];
    protected $casts = ['confirmed_at' => 'datetime'];

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->token = $m->token ?: Str::random(40);
        });
    }
}
