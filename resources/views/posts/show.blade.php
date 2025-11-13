@extends('layouts.app')
@section('title', $post->title.' · APME')
@section('content')
<article class="max-w-[900px] mx-auto px-5 py-12">
  <div class="aspect-[16/9] rounded-xl overflow-hidden border">
    <img src="{{ $post->cover_path }}" class="w-full h-full object-cover" alt="">
  </div>
  <h1 class="text-3xl font-display font-extrabold text-borgo mt-6">{{ $post->title }}</h1>
  <div class="text-slate-500 text-sm">{{ optional($post->published_at)->format('d/m/Y') }}</div>
  <div class="prose max-w-none mt-6">{!! nl2br(e($post->body)) !!}</div>
</article>
@endsection
