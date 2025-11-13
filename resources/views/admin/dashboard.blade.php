@extends('layouts.admin')
@section('title','Administración · APME')

@section('header')
  <h1 class="text-xl font-bold">Panel de administración</h1>
@endsection

@section('content')
  @php
    $stats = [
      ['Páginas',        \App\Models\Page::count(),        'admin.pages.index'],
      ['Comunidades',    \App\Models\Community::count(),   'admin.communities.index'],
      ['Productos',      \App\Models\Product::count(),     'admin.products.index'],
      ['Álbumes',        \App\Models\Album::count(),       'admin.albums.index'],
      ['Noticias',       \App\Models\Post::count(),        'admin.posts.index'],
      ['Aliados',        \App\Models\Partner::count(),     'admin.partners.index'],
    ];
  @endphp

  <div class="grid md:grid-cols-3 gap-4">
    @foreach($stats as [$t,$n,$r])
      <a href="{{ route($r) }}" class="rounded-xl border p-5 hover:shadow-sm">
        <div class="text-slate-500 text-sm">{{ $t }}</div>
        <div class="text-3xl font-extrabold text-borgo">{{ $n }}</div>
        <div class="mt-2 text-sm text-borgo/80">Administrar</div>
      </a>
    @endforeach
  </div>
@endsection
