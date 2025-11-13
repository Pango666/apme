<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index() {
        $items = Page::latest()->paginate(12);
        return view('admin.pages.index', compact('items'));
    }

    public function create() {
        $page = new Page();
        return view('admin.pages.form', compact('page'));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'title'=>'required|string|max:180',
            'slug'=>'nullable|string|max:180|unique:pages,slug',
            'excerpt'=>'nullable|string',
            'body'=>'nullable|string',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $page = Page::create($data);
        return redirect()->route('admin.pages.edit',$page)->with('ok','Creado');
    }

    public function edit(Page $page) {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $r, Page $page) {
        $data = $r->validate([
            'title'=>'required|string|max:180',
            'slug'=>"required|string|max:180|unique:pages,slug,{$page->id}",
            'excerpt'=>'nullable|string',
            'body'=>'nullable|string',
        ]);
        $page->update($data);
        return back()->with('ok','Guardado');
    }

    public function destroy(Page $page) {
        $page->delete();
        return back()->with('ok','Eliminado');
    }
}
