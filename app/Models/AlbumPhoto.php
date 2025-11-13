<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumPhoto extends Model
{
    protected $fillable = ['album_id', 'path', 'caption', 'order'];
    
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
