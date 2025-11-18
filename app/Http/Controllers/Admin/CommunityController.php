<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function index()
    {
        $items = Community::withCount('products')->latest()->paginate(12);
        return view('admin.communities.index', compact('items'));
    }

    public function create()
    {
        $community = new Community();
        return view('admin.communities.form', compact('community'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'        => ['required', 'string', 'max:180'],
            'slug'        => ['nullable', 'string', 'max:180', Rule::unique('communities', 'slug')],
            'province'    => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],

            'hero_title'    => ['nullable', 'string', 'max:180'],
            'hero_subtitle' => ['nullable', 'string', 'max:220'],
            'hero_image'    => ['nullable', 'image', 'max:8192'], // 8MB

            'about_html' => ['nullable', 'string'],
            'blocks'     => ['nullable'], // JSON en textarea

            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // parse blocks si viene como texto
        if (isset($data['blocks']) && is_string($data['blocks'])) {
            $json = json_decode($data['blocks'], true);
            $data['blocks'] = $json ?? null;
        }

        // file upload
        if ($r->hasFile('hero_image')) {
            $path = $r->file('hero_image')->store('communities/hero', 'public');
            $data['hero_image'] = "/storage/{$path}";
        }

        $community = Community::create($data);

        return redirect()->route('admin.communities.edit', $community)->with('ok', 'Creado');
    }

    public function edit(Community $community)
    {
        return view('admin.communities.form', compact('community'));
    }

    public function update(Request $r, Community $community)
    {
        $data = $r->validate([
            'name'        => ['required', 'string', 'max:180'],
            'slug'        => ['required', 'string', 'max:180', Rule::unique('communities', 'slug')->ignore($community->id)],
            'province'    => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],

            'hero_title'    => ['nullable', 'string', 'max:180'],
            'hero_subtitle' => ['nullable', 'string', 'max:220'],
            'hero_image'    => ['nullable', 'image', 'max:8192'],

            'about_html' => ['nullable', 'string'],
            'blocks'     => ['nullable'],

            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (isset($data['blocks']) && is_string($data['blocks'])) {
            $json = json_decode($data['blocks'], true);
            $data['blocks'] = $json ?? null;
        }

        if ($r->hasFile('hero_image')) {
            // borrar anterior si era del storage
            if ($community->hero_image && str_starts_with($community->hero_image, '/storage/')) {
                $old = str_replace('/storage/', '', $community->hero_image);
                Storage::disk('public')->delete($old);
            }
            $path = $r->file('hero_image')->store('communities/hero', 'public');
            $data['hero_image'] = "/storage/{$path}";
        }

        $community->update($data);

        return back()->with('ok', 'Guardado');
    }

    public function destroy(Community $community)
    {
        if ($community->hero_image && str_starts_with($community->hero_image, '/storage/')) {
            $old = str_replace('/storage/', '', $community->hero_image);
            Storage::disk('public')->delete($old);
        }
        $community->delete();
        return back()->with('ok', 'Eliminado');
    }
}
