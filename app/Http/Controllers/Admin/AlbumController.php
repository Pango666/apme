<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\AlbumPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index(){
        $items = Album::withCount('photos')->latest('date')->paginate(12);
        return view('admin.albums.index', compact('items'));
    }
    
    public function create(){
        $album = new Album(['type'=>'feria']);
        return view('admin.albums.form', compact('album'));
    }
    
    public function store(Request $r){
        $data = $r->validate([
            'title'=>'required|max:180',
            'slug'=>'nullable|max:180|unique:albums,slug',
            'type'=>'required|in:feria,portafolio,galeria',
            'date'=>'nullable|date',
            'place'=>'nullable|max:150',
            'summary'=>'nullable|string',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $album = Album::create($data);

        // fotos (opcional, múltiples)
        if ($r->hasFile('photos')) {
            foreach ($r->file('photos') as $i=>$file) {
                $path = $file->store('albums','public');
                AlbumPhoto::create([
                    'album_id'=>$album->id,
                    'path'=>"/storage/{$path}",
                    'order'=>$i+1
                ]);
            }
        }
        return redirect()->route('admin.albums.edit',$album)->with('ok','Creado');
    }
    
    public function edit(Album $album){
        $album->load('photos');
        return view('admin.albums.form', compact('album'));
    }
    
    public function update(Request $r, Album $album){
        $data = $r->validate([
            'title'=>'required|max:180',
            'slug'=>"required|max:180|unique:albums,slug,{$album->id}",
            'type'=>'required|in:feria,portafolio,galeria',
            'date'=>'nullable|date',
            'place'=>'nullable|max:150',
            'summary'=>'nullable|string',
        ]);
        $album->update($data);

        if ($r->hasFile('photos')) {
            $start = (int)($album->photos()->max('order')??0);
            foreach ($r->file('photos') as $i=>$file) {
                $path = $file->store('albums','public');
                AlbumPhoto::create([
                    'album_id'=>$album->id,
                    'path'=>"/storage/{$path}",
                    'order'=>$start + $i + 1
                ]);
            }
        }
        return back()->with('ok','Guardado');
    }

    public function destroy(Album $album){
        $album->delete();
        return back()->with('ok','Eliminado');
    }
}
