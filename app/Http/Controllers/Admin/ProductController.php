<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $items = Product::with('community')->latest()->paginate(12);
        return view('admin.products.index', compact('items'));
    }
    
    public function create(){
        $product = new Product(['is_active'=>1]);
        $communities = Community::orderBy('name')->pluck('name','id');
        return view('admin.products.form', compact('product','communities'));
    }
    
    public function store(Request $r){
        $data = $r->validate([
            'name'=>'required|max:180',
            'slug'=>'nullable|max:180|unique:products,slug',
            'type'=>'nullable|max:50',
            'price_bs'=>'nullable|numeric|min:0',
            'community_id'=>'nullable|exists:communities,id',
            'is_active'=>'boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $product = Product::create($data);
        return redirect()->route('admin.products.edit',$product)->with('ok','Creado');
    }
    
    public function edit(Product $product){
        $communities = Community::orderBy('name')->pluck('name','id');
        return view('admin.products.form', compact('product','communities'));
    }

    public function update(Request $r, Product $product){
        $data = $r->validate([
            'name'=>'required|max:180',
            'slug'=>"required|max:180|unique:products,slug,{$product->id}",
            'type'=>'nullable|max:50',
            'price_bs'=>'nullable|numeric|min:0',
            'community_id'=>'nullable|exists:communities,id',
            'is_active'=>'boolean',
        ]);
        $product->update($data);
        return back()->with('ok','Guardado');
    }

    public function destroy(Product $product){
        $product->delete();
        return back()->with('ok','Eliminado');
    }
}
