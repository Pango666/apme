<?php

namespace App\Http\Controllers;

use App\Models\Album;

class AlbumController extends Controller
{
    public function index()
    {
        $items = Album::with(['photos' => fn($q) => $q->limit(1)])
            ->latest('date')
            ->latest('id')
            ->paginate(12);

        return view('albums.index', compact('items'));
    }

    public function show(Album $album)
    {
        $album->load('photos');
        return view('albums.show', compact('album'));
    }
}
