<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $items = Page::latest()->paginate(12);
        return view('admin.pages.index', compact('items'));
    }

    public function create()
    {
        $page = new Page();
        return view('admin.pages.form', compact('page'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'   => ['required','string','max:180'],
            'slug'    => ['nullable','string','max:180', Rule::unique('pages','slug')],
            'excerpt' => ['nullable','string'],
            'body'    => ['nullable','string'],
        ]);

        // normalizar
        $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $page = Page::create($data);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('ok','Creado');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $r, Page $page)
    {
        $data = $r->validate([
            'title'   => ['required','string','max:180'],
            'slug'    => ['required','string','max:180', Rule::unique('pages','slug')->ignore($page->id)],
            'excerpt' => ['nullable','string'],
            'body'    => ['nullable','string'],
        ]);

        $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);

        $page->update($data);

        return back()->with('ok','Guardado');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('ok','Eliminado');
    }
}
