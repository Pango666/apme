<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index() {
        $items = Post::latest('published_at')->paginate(12);
        return view('admin.posts.index', compact('items'));
    }
    
    public function create(){
        $post = new Post(['published_at'=>now()]);
        return view('admin.posts.form', compact('post'));
    }
    
    public function store(Request $r){
        $data = $r->validate([
            'title'=>'required|max:180',
            'slug'=>'nullable|max:180|unique:posts,slug',
            'excerpt'=>'nullable|string',
            'body'=>'nullable|string',
            'cover'=>'nullable|image',
            'published_at'=>'nullable|date',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('posts','public');
            $data['cover_path'] = "/storage/{$path}";
        }
        $post = Post::create($data);
        return redirect()->route('admin.posts.edit',$post)->with('ok','Creado');
    }
    
    public function edit(Post $post){
        return view('admin.posts.form', compact('post'));
    }
    
    public function update(Request $r, Post $post){
        $data = $r->validate([
            'title'=>'required|max:180',
            'slug'=>"required|max:180|unique:posts,slug,{$post->id}",
            'excerpt'=>'nullable|string',
            'body'=>'nullable|string',
            'cover'=>'nullable|image',
            'published_at'=>'nullable|date',
        ]);
        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('posts','public');
            $data['cover_path'] = "/storage/{$path}";
        }
        $post->update($data);
        return back()->with('ok','Guardado');
    }

    public function destroy(Post $post){
        $post->delete();
        return back()->with('ok','Eliminado');
    }
}
