<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Page;
use App\Models\Product;
use App\Models\Community;
use App\Models\Album;
use App\Models\Post;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Página principal (/) – Route::get('/', HomeController::class)
     */
    public function __invoke()
    {
        // ===============================
        // Settings (aplica CAST; no pluck)
        // ===============================
        $settings = Setting::all()
            ->mapWithKeys(fn($s) => [$s->key => $s->value])
            ->toArray();

        // ===============================
        // Hero (con fallbacks)
        // ===============================
        $hero = [
            'title'    => $settings['hero.title']    ?? 'Miel justa de nuestras comunidades',
            'subtitle' => $settings['hero.subtitle'] ?? 'Asociación de Productores de Miel Ecológica',
            'image'    => $settings['hero.image']    ?? null,
        ];

        // Normaliza si vino como array
        if (is_array($hero['image'])) {
            $hero['image'] = reset($hero['image']) ?: null;
        }

        // Convierte a URL pública si es path interno
        if (!empty($hero['image']) && !Str::startsWith($hero['image'], ['http://', 'https://', '/', 'storage/'])) {
            $hero['image'] = Storage::url($hero['image']); // -> /storage/hero/...
        }

        // ===============================
        // Páginas institucionales
        // ===============================
        $qs     = Page::where('slug', 'quienes-somos')->first();
        $mision = Page::where('slug', 'mision')->first();
        $vision = Page::where('slug', 'vision')->first();

        // ===============================
        // Comunidades / Productos
        // ===============================
        $comunidades = Community::query()
            ->latest('id')
            ->take(12)
            ->get();

        $productos = Product::query()
            ->where('is_active', true)
            ->latest('id')
            ->take(12)
            ->get();

        // ===============================
        // Ferias (álbumes) con foto
        // ===============================
        $ferias = Album::query()
            ->with(['photos' => fn($q) => $q->orderBy('order')->orderBy('id')])
            ->where('type', 'feria')
            ->latest('date')
            ->latest('id')
            ->take(12)
            ->get();

        // ===============================
        // Noticias
        // ===============================
        $posts = Post::query()
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(12)
            ->get();

        // ===============================
        // Aliados
        // ===============================
        $partners = Partner::query()
            ->latest('id')
            ->take(12)
            ->get();

        return view('home', compact(
            'settings',
            'hero',
            'qs',
            'mision',
            'vision',
            'comunidades',
            'productos',
            'ferias',
            'posts',
            'partners'
        ));
    }
}
