<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Trae todo como pares clave => valor (decodifica si hay JSON válido)
        $map = Setting::allPairs();

        $hero = [
            'title'    => $map['hero.title']    ?? 'Miel justa de nuestras comunidades',
            'subtitle' => $map['hero.subtitle'] ?? 'Asociación de Productores de Miel Ecológica',
            'image'    => $map['hero.image']    ?? null,
        ];

        // Normaliza preview (si es ruta interna => Storage::url)
        if (!empty($hero['image']) && !str_starts_with($hero['image'], 'http') && !str_starts_with($hero['image'], '/')) {
            $hero['image'] = Storage::url($hero['image']);
        }

        $contact = [
            'whatsapp' => $map['contact.whatsapp'] ?? '',
            'email'    => $map['contact.email']    ?? '',
            'address'  => $map['contact.address']  ?? '',
        ];

        $social = [
            'facebook'  => $map['social.facebook']  ?? '',
            'instagram' => $map['social.instagram'] ?? '',
            'tiktok'    => $map['social.tiktok']    ?? '',
        ];

        return view('admin.settings.index', compact('hero', 'contact', 'social'));
    }

    /**
     * PUT /admin/settings/{any}
     * Nota: la firma acepta $any para calzar con tu ruta, aunque no se usa.
     */
    public function update(Request $request, $any = null)
    {
        $request->validate([
            'hero.title'       => ['nullable','string','max:255'],
            'hero.subtitle'    => ['nullable','string','max:255'],
            'hero.image'       => ['nullable','image'],
            'contact.whatsapp' => ['nullable','string','max:100'],
            'contact.email'    => ['nullable','email','max:150'],
            'contact.address'  => ['nullable','string','max:255'],
            'social.facebook'  => ['nullable','url'],
            'social.instagram' => ['nullable','url'],
            'social.tiktok'    => ['nullable','url'],
        ]);

        // Texto simple
        Setting::set('hero.title',        $request->input('hero.title'));
        Setting::set('hero.subtitle',     $request->input('hero.subtitle'));
        Setting::set('contact.whatsapp',  $request->input('contact.whatsapp'));
        Setting::set('contact.email',     $request->input('contact.email'));
        Setting::set('contact.address',   $request->input('contact.address'));
        Setting::set('social.facebook',   $request->input('social.facebook'));
        Setting::set('social.instagram',  $request->input('social.instagram'));
        Setting::set('social.tiktok',     $request->input('social.tiktok'));

        // Imagen (guardar SOLO path relativo; la vista/home lo resolverán con Storage::url)
        if ($request->hasFile('hero.image')) {
            $path = $request->file('hero.image')->store('hero', 'public'); // p.ej. hero/abc.jpg
            Setting::set('hero.image', $path);
        }

        return back()->with('ok', 'Ajustes guardados.');
    }
}
