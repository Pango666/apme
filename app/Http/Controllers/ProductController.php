<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::with('community')
            ->where('is_active', true)
            ->latest('id')
            ->paginate(12);

        return view('products.index', compact('items'));
    }

    public function show(Product $product)
    {
        // Relacionados: misma comunidad, activos, distintos al actual
        $rel = Product::where('is_active', true)
            ->where('community_id', $product->community_id)
            ->where('id', '<>', $product->id)
            ->latest('id')
            ->take(8)
            ->get();

        return view('products.show', [
            'product' => $product,
            'rel'     => $rel,
        ]);
    }
}
