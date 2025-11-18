<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // Publicadas primero por fecha, luego las sin fecha
        $items = Post::orderByRaw('published_at IS NULL')   // false(0)=publicadas primero
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12);

        return view('admin.posts.index', compact('items'));
    }

    public function create()
    {
        $post = new Post([
            'published_at' => now(),
        ]);

        return view('admin.posts.form', compact('post'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => ['required', 'max:180'],
            'slug'         => ['nullable', 'max:180', 'unique:posts,slug'],
            'excerpt'      => ['nullable', 'string'],
            'body'         => ['nullable', 'string'],
            'cover'        => ['nullable', 'image'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Slug auto si viene vacío
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        // Portada
        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('posts', 'public'); // posts/xxx.jpg
            $data['cover_path'] = "/storage/{$path}";
        }

        $post = Post::create($data);

        return redirect()->route('admin.posts.edit', $post)->with('ok', 'Creado');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $r, Post $post)
    {
        $data = $r->validate([
            'title'        => ['required', 'max:180'],
            'slug'         => ['nullable', 'max:180', Rule::unique('posts', 'slug')->ignore($post->id)],
            'excerpt'      => ['nullable', 'string'],
            'body'         => ['nullable', 'string'],
            'cover'        => ['nullable', 'image'],
            'remove_cover' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Si te olvidas de enviar slug, lo regeneramos (no obligatorio, pero útil)
        $data['slug'] = $data['slug'] ?: $post->slug ?: Str::slug($data['title']);

        // Quitar portada si lo pides
        if ($r->boolean('remove_cover') && $post->cover_path) {
            $this->deleteCoverFile($post->cover_path);
            $data['cover_path'] = null;
        }

        // Reemplazar portada
        if ($r->hasFile('cover')) {
            // borrar anterior si existe
            if ($post->cover_path) {
                $this->deleteCoverFile($post->cover_path);
            }
            $path = $r->file('cover')->store('posts', 'public');
            $data['cover_path'] = "/storage/{$path}";
        }

        $post->update($data);

        return back()->with('ok', 'Guardado');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_path) {
            $this->deleteCoverFile($post->cover_path);
        }
        $post->delete();
        return back()->with('ok', 'Eliminado');
    }

    private function deleteCoverFile(string $publicPath): void
    {
        // Convierte "/storage/archivo" -> "archivo" dentro del disk "public"
        $relative = ltrim(str_replace('/storage/', '', $publicPath), '/');
        if ($relative && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
