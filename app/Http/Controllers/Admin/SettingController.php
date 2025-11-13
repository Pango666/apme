<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        // No usar pluck para respetar casts
        $map = Setting::all()
            ->mapWithKeys(fn ($s) => [$s->key => $s->value])
            ->toArray();

        $hero = [
            'title'    => $map['hero.title']    ?? 'Miel justa de nuestras comunidades',
            'subtitle' => $map['hero.subtitle'] ?? 'Asociación de Productores de Miel Ecológica',
            'image'    => $map['hero.image']    ?? null,
        ];

        // Normaliza preview
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
     * (las rutas NO cambian, usas la tuya)
     */
    public function update(Request $request)
    {
        $data = $request->all();

        // ---------------- HERO ----------------
        if (isset($data['hero']['title'])) {
            Setting::updateOrCreate(['key' => 'hero.title'],    ['value' => (string) $data['hero']['title']]);
        }
        if (isset($data['hero']['subtitle'])) {
            Setting::updateOrCreate(['key' => 'hero.subtitle'], ['value' => (string) $data['hero']['subtitle']]);
        }

        // Imagen (opcional)
        if ($request->hasFile('hero.image')) {
            $path = $request->file('hero.image')->store('hero', 'public'); // hero/xxx.jpg
            Setting::updateOrCreate(['key' => 'hero.image'], ['value' => $path]);
        }

        // ---------------- CONTACT ----------------
        Setting::updateOrCreate(['key' => 'contact.whatsapp'], ['value' => (string)($data['contact']['whatsapp'] ?? '')]);
        Setting::updateOrCreate(['key' => 'contact.email'],    ['value' => (string)($data['contact']['email']    ?? '')]);
        Setting::updateOrCreate(['key' => 'contact.address'],  ['value' => (string)($data['contact']['address']  ?? '')]);

        // ---------------- SOCIAL ----------------
        Setting::updateOrCreate(['key' => 'social.facebook'],  ['value' => (string)($data['social']['facebook']  ?? '')]);
        Setting::updateOrCreate(['key' => 'social.instagram'], ['value' => (string)($data['social']['instagram'] ?? '')]);
        Setting::updateOrCreate(['key' => 'social.tiktok'],    ['value' => (string)($data['social']['tiktok']    ?? '')]);

        return back()->with('ok', 'Ajustes guardados.');
    }
}
