<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Por si luego agregas cover_path y quieres un hero bonito
        $coverUrl = $page->cover_url; // accessor seguro (devuelve null si no existe)

        return view('pages.show', compact('page', 'coverUrl'));
    }
}
