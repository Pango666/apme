<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    use HasFactory;

    protected $table = 'content_templates';

    // Ajusta si prefieres fillable
    protected $guarded = [];

    protected $casts = [
        'hero'   => 'array',   // { title, subtitle, image }
        'blocks' => 'array',   // arreglo de bloques
    ];

    public function scopeForEntity($query, string $entity)
    {
        return $query->where('entity', $entity);
    }
}
