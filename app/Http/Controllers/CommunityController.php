<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $items = Community::orderBy('name')->paginate(12);
        return view('communities.index', compact('items'));
    }

    public function show(Community $community)
    {
        $productos = $community->products()->where('is_active', 1)->paginate(9);
        return view('communities.show', compact('community', 'productos'));
    }


    public function whatWeDo(Community $community)
    {
        return view('communities.tabs.whatwedo', compact('community'));
    }

    public function takeAction(Community $community)
    {
        return view('communities.tabs.takeaction', compact('community'));
    }

    public function donate(Community $community)
    {
        return view('communities.tabs.donate', compact('community'));
    }

    // pestañas de contenidos
    public function products(Community $community)
    {
        $productos = $community->products()->where('is_active', 1)->paginate(12);
        return view('communities.tabs.products', compact('community', 'productos'));
    }
}
