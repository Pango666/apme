<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::with('community')->latest()->paginate(12);
        return view('admin.products.index', compact('items'));
    }

    public function create()
    {
        $product = new Product(['is_active' => 1]);
        $communities = Community::orderBy('name')->pluck('name', 'id');
        return view('admin.products.form', compact('product', 'communities'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'         => 'required|max:180',
            'slug'         => 'nullable|max:180|unique:products,slug',
            'type'         => 'nullable|max:80',
            'description'  => 'nullable|string',
            'price_bs'     => 'nullable|numeric|min:0',
            'community_id' => 'nullable|exists:communities,id',
            'is_active'    => 'boolean',

            // landing corta
            'hero_title'                    => 'nullable|string|max:190',
            'hero_subtitle'                 => 'nullable|string|max:190',
            'hero_image'                    => 'nullable|image|max:5120',
            'hero_button_text'              => 'nullable|string|max:190',
            'hero_button_url'               => 'nullable|string|max:190',
            'hero_button_color'             => 'nullable|string|max:190',
            'hero_button_text_color'        => 'nullable|string|max:190',
            'hero_button_background_color'  => 'nullable|string|max:190',
            'about_html'                    => 'nullable|string',
            'blocks'                        => 'nullable', // JSON en textarea
        ]);

        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']);

        if ($r->hasFile('hero_image')) {
            $path = $r->file('hero_image')->store('products', 'public');
            $data['hero_image'] = $path; // guardamos path relativo
        }

        if (!empty($data['blocks'])) {
            $decoded = json_decode($data['blocks'], true);
            if (json_last_error() === JSON_ERROR_NONE) $data['blocks'] = $decoded;
            else unset($data['blocks']);
        }

        $product = \App\Models\Product::create($data);
        return redirect()->route('admin.products.edit', $product)->with('ok', 'Creado');
    }

    public function edit(Product $product)
    {
        $communities = Community::orderBy('name')->pluck('name', 'id');
        return view('admin.products.form', compact('product', 'communities'));
    }

    public function update(Request $r, \App\Models\Product $product)
    {
        $data = $r->validate([
            'name'         => 'required|max:180',
            'slug'         => "required|max:180|unique:products,slug,{$product->id}",
            'type'         => 'nullable|max:80',
            'description'  => 'nullable|string',
            'price_bs'     => 'nullable|numeric|min:0',
            'community_id' => 'nullable|exists:communities,id',
            'is_active'    => 'boolean',

            'hero_title'                    => 'nullable|string|max:190',
            'hero_subtitle'                 => 'nullable|string|max:190',
            'hero_image'                    => 'nullable|image|max:5120',
            'hero_button_text'              => 'nullable|string|max:190',
            'hero_button_url'               => 'nullable|string|max:190',
            'hero_button_color'             => 'nullable|string|max:190',
            'hero_button_text_color'        => 'nullable|string|max:190',
            'hero_button_background_color'  => 'nullable|string|max:190',
            'about_html'                    => 'nullable|string',
            'blocks'                        => 'nullable',
        ]);

        if ($r->hasFile('hero_image')) {
            $path = $r->file('hero_image')->store('products', 'public');
            $data['hero_image'] = $path;
        }

        if (!empty($data['blocks'])) {
            $decoded = json_decode($data['blocks'], true);
            if (json_last_error() === JSON_ERROR_NONE) $data['blocks'] = $decoded;
            else unset($data['blocks']);
        }

        $product->update($data);
        return back()->with('ok', 'Guardado');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('ok', 'Eliminado');
    }
}
