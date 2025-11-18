@extends('layouts.app')
@section('title', $page->title .' · APME')

@section('content')
  {{-- HERO opcional si hay cover --}}
  @if(!empty($coverUrl))
    <section class="relative w-full h-[34vh] min-h-[260px]">
      <img src="{{ $coverUrl }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $page->title }}">
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="relative z-10 max-w-[1100px] mx-auto px-5 h-full flex flex-col justify-end pb-6">
        <h1 class="font-display text-white text-3xl md:text-4xl font-extrabold drop-shadow">
          {{ $page->title }}
        </h1>
        @if($page->excerpt)
          <p class="text-white/90 mt-1">{{ $page->excerpt }}</p>
        @endif
      </div>
    </section>
  @endif

  <section class="max-w-[1100px] mx-auto px-5 py-10">
    @if(empty($coverUrl))
      <header class="mb-6">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold text-borgo">
          {{ $page->title }}
        </h1>
        @if($page->excerpt)
          <p class="text-slate-600 mt-1">{{ $page->excerpt }}</p>
        @endif
      </header>
    @endif

    {{-- Contenido rico: permite HTML del CMS --}}
    <article class="prose prose-slate max-w-none">
      {!! $page->body !!}
    </article>

    {{-- Meta opcional (última actualización) --}}
    <div class="mt-8 text-xs text-slate-500">
      Actualizado {{ optional($page->updated_at)->format('d/m/Y H:i') }}
    </div>
  </section>

  {{-- Estilos suaves para mejorar legibilidad del contenido libre --}}
  <style>
    .prose img { border-radius: .75rem; border: 1px solid rgba(15,23,42,.08); }
    .prose table { width: 100%; border-collapse: collapse; }
    .prose table th, .prose table td { border: 1px solid rgba(15,23,42,.08); padding: .5rem .75rem; }
    .prose pre, .prose code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .prose a { text-decoration: none; }
    .prose a:hover { text-decoration: underline; }
  </style>
@endsection
