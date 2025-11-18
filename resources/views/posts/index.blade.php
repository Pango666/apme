@extends('layouts.app')
@section('title','Noticias · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-12">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Noticias</h1>

  @if($items->count() === 0)
    <div class="mt-6 text-slate-600">Aún no hay noticias publicadas.</div>
  @else
    <div class="grid md:grid-cols-3 gap-6 mt-6">
      @foreach($items as $post)
        <a href="{{ route('noticias.show',$post->slug) }}"
           class="rounded-xl border bg-white overflow-hidden hover:shadow transition">
          <div class="aspect-[16/9]">
            <img src="{{ $post->cover_url }}" class="w-full h-full object-cover" alt="{{ $post->title }}">
          </div>
          <div class="p-5">
            <h3 class="font-semibold line-clamp-2">{{ $post->title }}</h3>
            @if($post->excerpt)
              <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $post->excerpt }}</p>
            @endif
            <div class="text-xs text-slate-500 mt-3">
              {{ optional($post->published_at)->format('d/m/Y') }}
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $items->links() }}
    </div>
  @endif
</div>
@endsection
