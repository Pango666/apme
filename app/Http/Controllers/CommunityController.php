<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Support\Str;

class CommunityController extends Controller{

    protected $fillable = [
        'name','slug','province','description',
        'hero_title','hero_subtitle','hero_image',
        'about_html','blocks',
        'latitude','longitude',
    ];

    protected $casts = [
        'blocks' => 'array',
        'latitude'  => 'float',
        'longitude' => 'float',
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

    
    public function index()
    {
        // mostramos nombre/provincia; si quieres mini cover podríamos tomar hero_image
        $items = Community::orderBy('name')->paginate(12);
        return view('communities.index', compact('items'));
    }

    public function show(Community $community)
    {
        // productos destacados de la comunidad (primeros 9)
        $productos = $community->products()
            ->where('is_active', 1)
            ->latest('id')
            ->paginate(9);

        return view('communities.show', compact('community', 'productos'));
    }

    // Pestañas (si quieres contenido libre por pestaña podemos montar blocks o simples vistas)
    public function whatWeDo(Community $community)
    {
        return view('communities.tabs.whatwedo', compact('community'));
    }

    public function takeAction(Community $community)
    {
        return view('communities.tabs.takeaction', compact('community'));
    }

    public function donate(Community $community)
    {
        return view('communities.tabs.donate', compact('community'));
    }

    public function products(Community $community)
    {
        $productos = $community->products()
            ->where('is_active', 1)
            ->latest('id')
            ->paginate(12);

        return view('communities.tabs.products', compact('community', 'productos'));
    }
}
