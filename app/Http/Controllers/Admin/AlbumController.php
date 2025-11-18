<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    public function index()
    {
        $items = Album::withCount('photos')->latest('date')->latest('id')->paginate(12);
        return view('admin.albums.index', compact('items'));
    }

    public function create()
    {
        $album = new Album(['type' => 'feria']);
        return view('admin.albums.form', compact('album'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'   => ['required', 'string', 'max:180'],
            'slug'    => ['nullable', 'string', 'max:180', Rule::unique('albums', 'slug')],
            'type'    => ['required', Rule::in(['feria', 'portafolio', 'galeria'])],
            'date'    => ['nullable', 'date'],
            'place'   => ['nullable', 'string', 'max:150'],
            'summary' => ['nullable', 'string'],

            // fotos múltiples
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['image', 'max:8192'], // 8MB c/u
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        DB::transaction(function () use ($r, $data, &$album) {
            $album = Album::create($data);

            if ($r->hasFile('photos')) {
                foreach ($r->file('photos') as $i => $file) {
                    $path = $file->store('albums', 'public');
                    AlbumPhoto::create([
                        'album_id' => $album->id,
                        'path'     => "/storage/{$path}",
                        'order'    => $i + 1,
                    ]);
                }
            }
        });

        return redirect()->route('admin.albums.edit', $album)->with('ok', 'Creado');
    }

    public function edit(Album $album)
    {
        $album->load(['photos' => fn($q) => $q->orderBy('order')->orderBy('id')]);
        return view('admin.albums.form', compact('album'));
    }

    public function update(Request $r, Album $album)
    {
        $data = $r->validate([
            'title'   => ['required', 'string', 'max:180'],
            'slug'    => ['required', 'string', 'max:180', Rule::unique('albums', 'slug')->ignore($album->id)],
            'type'    => ['required', Rule::in(['feria', 'portafolio', 'galeria'])],
            'date'    => ['nullable', 'date'],
            'place'   => ['nullable', 'string', 'max:150'],
            'summary' => ['nullable', 'string'],

            // nuevas fotos
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['image', 'max:8192'],

            // edición de fotos existentes
            'photos_update'                 => ['nullable', 'array'],
            'photos_update.*.caption'       => ['nullable', 'string', 'max:255'],
            'photos_update.*.order'         => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photos_update.*.delete'        => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($r, $album, $data) {
            // 1) actualizar datos del álbum
            $album->update($data);

            // 2) aplicar cambios a fotos existentes
            if ($r->filled('photos_update')) {
                foreach ($r->input('photos_update') as $photoId => $row) {
                    /** @var AlbumPhoto $ph */
                    $ph = AlbumPhoto::where('album_id', $album->id)->where('id', $photoId)->first();
                    if (!$ph) continue;

                    // eliminar
                    if (!empty($row['delete'])) {
                        $ph->delete();
                        continue;
                    }

                    // actualizar caption / order
                    $ph->caption = $row['caption'] ?? $ph->caption;
                    if (isset($row['order']) && is_numeric($row['order'])) {
                        $ph->order = (int)$row['order'];
                    }
                    $ph->save();
                }
            }

            // 3) agregar nuevas fotos (se apilan al final)
            if ($r->hasFile('photos')) {
                $start = (int)($album->photos()->max('order') ?? 0);
                foreach ($r->file('photos') as $i => $file) {
                    $path = $file->store('albums', 'public');
                    AlbumPhoto::create([
                        'album_id' => $album->id,
                        'path'     => "/storage/{$path}",
                        'order'    => $start + $i + 1,
                    ]);
                }
            }
        });

        return back()->with('ok', 'Guardado');
    }

    public function destroy(Album $album)
    {
        // borramos fotos hijas (DB). Si quieres borrar archivos físicos, añade lógica de Storage aquí.
        $album->photos()->delete();
        $album->delete();
        return back()->with('ok', 'Eliminado');
    }
}
