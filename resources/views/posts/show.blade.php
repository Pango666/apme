@extends('layouts.app')
@section('title', $post->title.' · APME')

@section('content')
<article class="max-w-[900px] mx-auto px-5 py-12">
  {{-- Portada --}}
  <div class="aspect-[16/9] rounded-xl overflow-hidden border bg-white">
    <img src="{{ $post->cover_url }}" class="w-full h-full object-cover" alt="{{ $post->title }}">
  </div>

  <h1 class="text-3xl font-display font-extrabold text-borgo mt-6">{{ $post->title }}</h1>
  <div class="text-slate-500 text-sm">{{ optional($post->published_at)->format('d/m/Y') }}</div>

  {{-- Contenido: soporta texto plano o HTML. Si tu body ya viene con HTML, se renderiza; 
       si viene en texto plano, se verá con saltos de línea. --}}
  <div class="prose prose-slate max-w-none mt-6">
    @php
      $body = $post->body ?? '';
      $isHtml = $body !== strip_tags($body);
    @endphp

    @if($isHtml)
      {!! $body !!}
    @else
      {!! nl2br(e($body)) !!}
    @endif
  </div>
</article>

{{-- Afinado visual mínimo --}}
<style>
  .prose img { border-radius: .75rem; border: 1px solid rgba(15,23,42,.08); }
  .prose a { text-decoration: none; }
  .prose a:hover { text-decoration: underline; }
</style>
@endsection
