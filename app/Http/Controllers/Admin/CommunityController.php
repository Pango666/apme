<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(){
        $items = Community::latest()->paginate(12);
        return view('admin.communities.index', compact('items'));
    }
    
    public function create(){
        $community = new Community();
        return view('admin.communities.form', compact('community'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'name'=>'required|max:180',
            'slug'=>'nullable|max:180|unique:communities,slug',
            'province'=>'nullable|max:150',
            'description'=>'nullable|string',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $community = Community::create($data);
        return redirect()->route('admin.communities.edit',$community)->with('ok','Creado');
    }
    
    public function edit(Community $community){
        return view('admin.communities.form', compact('community'));
    }

    public function update(Request $r, Community $community){
        $data = $r->validate([
            'name'=>'required|max:180',
            'slug'=>"required|max:180|unique:communities,slug,{$community->id}",
            'province'=>'nullable|max:150',
            'description'=>'nullable|string',
        ]);
        $community->update($data);
        return back()->with('ok','Guardado');
    }
    
    public function destroy(Community $community){
        $community->delete();
        return back()->with('ok','Eliminado');
    }
}
