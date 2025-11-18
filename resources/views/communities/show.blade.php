@extends('layouts.app')
@section('title', $community->name . ' · APME - Miel Ecológica')

@section('content')
@php
  $heroUrl    = $community->hero_image_url ?? $community->hero_image ?? '/hero-miel.webp';
  $heroTitle  = $community->hero_title ?: $community->name;
  $heroSub    = $community->hero_subtitle ?: null;

  $hasCoords  = filled($community->latitude) && filled($community->longitude);
  $gmapsUrl   = $hasCoords ? ('https://www.google.com/maps?q=' . $community->latitude . ',' . $community->longitude) : null;

  $totalProductos = method_exists($productos, 'total') ? $productos->total() : $productos->count();
@endphp

{{-- BREADCRUMB --}}
<nav class="bg-crema/30 border-b">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-3">
    <ol class="flex items-center gap-2 text-sm text-slate-600">
      <li><a class="hover:text-borgo" href="{{ route('home') }}">Inicio</a></li>
      <li class="text-slate-400">/</li>
      <li><a class="hover:text-borgo" href="{{ route('comunidades.index') }}">Comunidades</a></li>
      <li class="text-slate-400">/</li>
      <li class="text-borgo font-medium truncate">{{ $community->name }}</li>
    </ol>
  </div>
</nav>

{{-- HERO comunidad (versión original) --}}
<section class="relative bg-gradient-to-br from-borgo to-borgo2">
  <div class="absolute inset-0">
    <img 
      src="{{ $heroUrl }}" 
      alt="{{ $community->name }}"
      class="w-full h-full object-cover opacity-40"
    >
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="text-center text-white">
      <div class="max-w-4xl mx-auto">

        {{-- Badge provincia --}}
        @if($community->province)
          <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-6">
            {{ $community->province }}
          </span>
        @endif

        <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-2">
          {{ $heroTitle }}
        </h1>

        @if($heroSub)
          <p class="text-lg md:text-xl text-white/90 mb-4 max-w-2xl mx-auto leading-relaxed">
            {{ $heroSub }}
          </p>
        @endif

        {{-- Chips de info/acciones (translúcidos, sin borde) --}}
        <div class="flex flex-wrap justify-center gap-3 mt-2">
          <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white/15 backdrop-blur-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Z"/>
            </svg>
            <span class="font-medium">{{ $productos->total() }} productos</span>
          </span>

          @if($gmapsUrl)
            <a href="{{ $gmapsUrl }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white transition">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7Z M12 11.5A2.5 2.5 0 1 0 12 6a2.5 2.5 0 0 0 0 5.5Z"/>
              </svg>
              Ver en Google Maps
            </a>
          @endif

          <a href="#productos"
             class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition">
            Ver productos
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M10 6 8.6 7.4 13.2 12l-4.6 4.6L10 18l6-6z"/>
            </svg>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- CONTENIDO + SIDEBAR --}}
<section class="py-12 bg-white">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10">
    {{-- Principal --}}
    <div class="lg:col-span-8 space-y-8">
      {{-- Sobre la comunidad --}}
      <article class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-6 lg:p-8">
          @if($community->about_html)
            <div class="community-prose prose max-w-none">
              {!! $community->about_html !!}
            </div>
          @elseif($community->description)
            <h2 class="text-2xl md:text-3xl font-bold text-borgo mb-4">Sobre {{ $community->name }}</h2>
            <div class="community-prose prose max-w-none text-slate-700">
              <p>{{ $community->description }}</p>
            </div>
          @else
            <div class="text-center text-slate-500 py-10">
              <svg class="w-14 h-14 text-slate-300 mx-auto mb-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7Z"/></svg>
              <p>Próximamente añadiremos más información sobre esta comunidad.</p>
            </div>
          @endif
        </div>
      </article>

      {{-- Bloques dinámicos --}}
      @if(!empty($community->blocks) && is_array($community->blocks))
        <section class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
          @foreach($community->blocks as $block)
            <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
              @if(!empty($block['image']))
                @php
                  $bimg = preg_match('#^(https?://|/)#', $block['image'])
                    ? $block['image']
                    : \Illuminate\Support\Facades\Storage::url($block['image']);
                @endphp
                <div class="mb-4 rounded-lg overflow-hidden border">
                  <img src="{{ $bimg }}" class="w-full h-40 object-cover" alt="">
                </div>
              @endif
              @if(!empty($block['title']))
                <h3 class="font-semibold text-borgo">{{ $block['title'] }}</h3>
              @endif
              @if(!empty($block['text']))
                <p class="text-sm text-slate-600 mt-1">{{ $block['text'] }}</p>
              @endif
            </div>
          @endforeach
        </section>
      @endif
    </div>

    {{-- Sidebar sticky --}}
    <aside class="lg:col-span-4">
      <div class="lg:sticky lg:top-24 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
          <div class="p-6">
            <h3 class="font-semibold text-lg text-borgo mb-4">Información de la comunidad</h3>
            <dl class="space-y-3 text-sm">
              <div class="flex justify-between gap-4 pb-3 border-b border-slate-100">
                <dt class="text-slate-600">Nombre</dt>
                <dd class="font-medium text-borgo text-right">{{ $community->name }}</dd>
              </div>
              @if($community->province)
                <div class="flex justify-between gap-4 pb-3 border-b border-slate-100">
                  <dt class="text-slate-600">Provincia</dt>
                  <dd class="font-medium text-slate-700 text-right">{{ $community->province }}</dd>
                </div>
              @endif
              @if($gmapsUrl)
                <div class="pt-3">
                  <a href="{{ $gmapsUrl }}" target="_blank"
                     class="w-full inline-flex justify-center items-center gap-2 bg-miel text-tinta font-semibold py-3 px-4 rounded-lg hover:shadow-md transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7Z"/></svg>
                    Ver en Google Maps
                  </a>
                </div>
              @endif
            </dl>
          </div>
        </div>

        <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-borgo to-borgo2 shadow-sm">
          <h3 class="font-semibold text-lg mb-1">¿Interesado en sus productos?</h3>
          <p class="text-white/85 text-sm mb-4">Contáctanos para más información sobre esta comunidad.</p>
          <a href="{{ route('contacto') }}?comunidad={{ urlencode($community->name) }}"
             class="w-full inline-flex justify-center px-4 py-3 rounded-lg bg-white text-borgo font-semibold hover:shadow-md transition">
            Solicitar información
          </a>
        </div>
      </div>
    </aside>
  </div>
</section>

{{-- PRODUCTOS --}}
<section id="productos" class="bg-crema/30 border-t">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
      <div>
        <h2 class="font-display text-3xl font-bold text-borgo">Productos de {{ $community->name }}</h2>
        <p class="text-slate-600">Descubre los productos exclusivos de esta comunidad</p>
      </div>
      <a href="#productos" class="inline-flex items-center text-borgo font-semibold hover:text-borgo2 transition">
        Ver todos los productos
        <svg class="w-4 h-4 ml-1" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6 8.6 7.4 13.2 12l-4.6 4.6L10 18l6-6z"/></svg>
      </a>
    </div>

    @if($productos->count())
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($productos as $product)
          @php
            $productImage = $product->hero_image ?? null;
            if ($productImage && !preg_match('#^(https?://|/)#', $productImage)) {
              $productImage = \Illuminate\Support\Facades\Storage::url($productImage);
            }
            $productImage = $productImage ?: '/placeholder-product.jpg';
          @endphp
          <a href="{{ route('productos.show', $product->slug) }}"
             class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition">
            <div class="relative aspect-square bg-gradient-to-br from-crema to-miel/10">
              <img src="{{ $productImage }}" alt="{{ $product->name }}"
                   class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
              @if($product->type)
                <span class="absolute top-3 left-3 px-2 py-1 text-xs font-medium rounded-full bg-white/90 backdrop-blur-sm text-borgo">
                  {{ $product->type }}
                </span>
              @endif
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-borgo group-hover:text-borgo2 transition-colors line-clamp-2">
                {{ $product->name }}
              </h3>
              @if($product->price_bs)
                <div class="mt-2 text-lg font-bold text-miel">
                  {{ number_format($product->price_bs, 2) }} Bs
                </div>
              @endif
            </div>
          </a>
        @endforeach
      </div>

      @if($productos->hasPages())
        <div class="mt-10">
          {{ $productos->onEachSide(1)->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Z"/></svg>
        <p class="text-slate-600">Esta comunidad aún no tiene productos publicados.</p>
      </div>
    @endif
  </div>
</section>

<style>
  /* -------- FIX: anula estilos globales que te pintan "cajitas" en cada párrafo -------- */
  .community-prose * {
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
  }
  .prose { color:#374151; line-height:1.75 }
  .prose h2, .prose h3 { color:#7B2321; font-weight:700 }
  .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden }
</style>
@endsection
