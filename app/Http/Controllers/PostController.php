<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $items = Post::whereNotNull('published_at')
            ->latest('published_at')
            ->latest('id')
            ->paginate(9);

        return view('posts.index', compact('items'));
    }

    public function show(Post $noticia)
    {
        abort_unless($noticia->published_at, 404);
        return view('posts.show', ['post' => $noticia]);
    }
}
