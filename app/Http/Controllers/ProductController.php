<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::where('is_active', 1)->latest()->paginate(12);
        return view('products.index', compact('items'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
