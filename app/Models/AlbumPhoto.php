<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlbumPhoto extends Model
{
    protected $guarded = [];
    protected $fillable = ['album_id', 'path', 'caption', 'order'];
    
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function getUrlAttribute(): string
    {
        return preg_match('#^(https?://|/)#', $this->path) ? $this->path : Storage::url($this->path);
    }
}
