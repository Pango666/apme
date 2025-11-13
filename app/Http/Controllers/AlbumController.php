<?php

namespace App\Http\Controllers;

use App\Models\Album;

class AlbumController extends Controller
{
    public function index()
    {
        $items = Album::withCount('photos')->latest('date')->paginate(12);
        return view('albums.index', compact('items'));
    }
    
    public function show(Album $album)
    {
        $album->load('photos');
        return view('albums.show', compact('album'));
    }
}
