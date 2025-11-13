@extends('layouts.app')
@section('title', $page->title .' · APME')

@section('content')
  <section class="max-w-[1100px] mx-auto px-5 py-10">
    <header class="mb-6">
      <h1 class="font-display text-3xl md:text-4xl font-extrabold text-borgo">
        {{ $page->title }}
      </h1>
      @if($page->excerpt)
        <p class="text-slate-600 mt-1">{{ $page->excerpt }}</p>
      @endif
    </header>

    {{-- IMPORTANTE: imprimir SIN escapar para renderizar HTML del campo --}}
    <div class="page-content">
      {!! $page->body !!}
    </div>
  </section>
@endsection
