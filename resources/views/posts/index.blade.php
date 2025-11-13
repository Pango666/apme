@extends('layouts.app')
@section('title','Noticias · APME')
@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-12">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Noticias</h1>
  <div class="grid md:grid-cols-3 gap-6 mt-6">
    @foreach($items as $post)
      <a href="{{ route('noticias.show',$post->slug) }}" class="rounded-xl border bg-white overflow-hidden hover:shadow">
        <div class="aspect-[16/9]"><img src="{{ $post->cover_path }}" class="w-full h-full object-cover" alt=""></div>
        <div class="p-5">
          <h3 class="font-semibold">{{ $post->title }}</h3>
          <p class="text-sm text-slate-600 mt-1">{{ $post->excerpt }}</p>
          <div class="text-xs text-slate-500 mt-3">{{ optional($post->published_at)->format('d/m/Y') }}</div>
        </div>
      </a>
    @endforeach
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection
