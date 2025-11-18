@extends('layouts.app')
@section('title', $product->name . ' · APME - Miel Ecológica')

@section('content')
@php
  $heroTitle = $product->hero_title ?: $product->name;
  $heroSub   = $product->hero_subtitle ?: ($product->type ? ucfirst($product->type) : '');
  $heroImg   = $product->hero_image;

  if ($heroImg && !preg_match('#^(https?://|/)#', $heroImg)) {
      $heroImg = \Illuminate\Support\Facades\Storage::url($heroImg);
  }

  $btnText   = $product->hero_button_text;
  $btnUrl    = $product->hero_button_url ?: '#';
  
  // Obtener imágenes para miniaturas
  $additionalImages = [];
  if (!empty($product->blocks)) {
    foreach($product->blocks as $block) {
      if ($block['type'] === 'gallery' && !empty($block['data']['items'])) {
        $additionalImages = array_slice($block['data']['items'], 0, 3);
        break;
      }
    }
  }
@endphp

{{-- BREADCRUMB NORMAL --}}
<nav class="bg-crema/30 border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex items-center space-x-2 text-sm">
      <a href="{{ route('home') }}" class="text-slate-600 hover:text-borgo transition-colors">Inicio</a>
      <span class="text-slate-400">/</span>
      <a href="{{ route('productos.index') }}" class="text-slate-600 hover:text-borgo transition-colors">Productos</a>
      <span class="text-slate-400">/</span>
      <span class="text-borgo font-medium truncate">{{ $product->name }}</span>
    </div>
  </div>
</nav>

{{-- STICKY HEADER (se activa al hacer scroll) --}}
<div id="stickyHeader" class="hidden fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-b border-slate-200 z-50 shadow-sm transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between py-3">
      <div class="flex items-center space-x-4 min-w-0">
        {{-- Imagen miniatura --}}
        <div class="w-10 h-10 rounded-lg border border-slate-200 overflow-hidden flex-shrink-0">
          <img 
            src="{{ $heroImg ?: '/placeholder-product.jpg' }}" 
            alt="{{ $product->name }}"
            class="w-full h-full object-cover"
          >
        </div>
        
        {{-- Información del producto --}}
        <div class="min-w-0 flex-1">
          <div class="flex items-center space-x-2">
            <h1 class="font-semibold text-borgo truncate text-sm lg:text-base">
              {{ $product->name }}
            </h1>
            @if($product->type)
              <span class="px-2 py-1 bg-miel/20 text-borgo rounded-full text-xs font-medium whitespace-nowrap">
                {{ $product->type }}
              </span>
            @endif
          </div>
          
          @if($product->community)
            <p class="text-xs text-slate-600 truncate">
              {{ $product->community->name }} · {{ $product->community->province }}
            </p>
          @endif
        </div>
      </div>

      {{-- Precio y acción en sticky --}}
      <div class="flex items-center space-x-4">
        @if($product->price_bs)
          <div class="text-right">
            <div class="font-bold text-miel text-lg">{{ number_format($product->price_bs, 2) }} Bs</div>
          </div>
        @endif
        
        @if($btnText)
          <a href="{{ $btnUrl }}" 
             class="bg-miel text-tinta font-semibold px-4 py-2 rounded-lg hover:shadow transition-all text-sm whitespace-nowrap">
            {{ $btnText }}
          </a>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- PRODUCTO LAYOUT E-COMMERCE --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
  <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
    
    {{-- COLUMNA IZQUIERDA: IMAGEN ÚNICA --}}
    <div class="space-y-4">
      {{-- Imagen principal --}}
      <div class="bg-white rounded-2xl shadow-card border border-slate-200 overflow-hidden">
        <img 
          src="{{ $heroImg ?: '/placeholder-product.jpg' }}" 
          alt="{{ $product->name }}"
          class="w-full h-96 lg:h-[500px] object-cover"
          loading="eager"
        >
      </div>

      {{-- Miniaturas si hay imágenes adicionales --}}
      @if(count($additionalImages) > 0)
        <div class="grid grid-cols-4 gap-3">
          {{-- Miniatura principal --}}
          <div class="aspect-square rounded-lg border-2 border-miel overflow-hidden bg-white">
            <img 
              src="{{ $heroImg ?: '/placeholder-product.jpg' }}" 
              alt="{{ $product->name }}"
              class="w-full h-full object-cover"
            >
          </div>

          {{-- Miniaturas adicionales --}}
          @foreach($additionalImages as $image)
            @php $src = \App\Models\Product::urlify($image['src'] ?? null); @endphp
            @if($src)
              <div class="aspect-square rounded-lg border border-slate-200 overflow-hidden bg-white">
                <img 
                  src="{{ $src }}" 
                  alt="Vista adicional"
                  class="w-full h-full object-cover"
                  loading="lazy"
                >
              </div>
            @endif
          @endforeach
        </div>
      @endif
    </div>

    {{-- COLUMNA DERECHA: DETALLES DEL PRODUCTO --}}
    <div class="space-y-6">
      {{-- Header --}}
      <div>
        {{-- Comunidad arriba del nombre --}}
        @if($product->community)
          <div class="flex items-center gap-2 text-sm text-slate-600 mb-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <span class="font-medium text-borgo">{{ $product->community->name }}</span>
            <span class="text-slate-500">· {{ $product->community->province }}</span>
          </div>
        @endif
        
        @if($product->type)
          <span class="inline-block px-3 py-1 bg-miel/20 text-borgo rounded-full text-sm font-medium mb-4">
            {{ $product->type }}
          </span>
        @endif
        
        <h1 class="font-display text-3xl lg:text-4xl font-bold text-borgo mb-3">
          {{ $heroTitle }}
        </h1>
        
        @if($heroSub)
          <p class="text-lg text-slate-600 mb-4">{{ $heroSub }}</p>
        @endif

        {{-- Precio --}}
        @if($product->price_bs)
          <div class="flex items-center gap-3 mb-6">
            <span class="text-4xl font-bold text-miel">{{ number_format($product->price_bs, 2) }} Bs</span>
            <span class="text-slate-500">por unidad</span>
          </div>
        @endif
      </div>

      {{-- Especificaciones rápidas --}}
      <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl">
        <div class="text-center">
          <div class="text-2xl font-bold text-borgo">100%</div>
          <div class="text-sm text-slate-600">Natural</div>
        </div>
        <div class="text-center">
          <svg class="w-6 h-6 text-borgo mx-auto mb-1" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
          </svg>
          <div class="text-sm text-slate-600">Calidad Premium</div>
        </div>
      </div>

      {{-- Botones de acción --}}
      <div class="space-y-4">
        @if($btnText)
          <a href="{{ $btnUrl }}" 
             class="w-full bg-gradient-to-r from-miel to-ambar text-tinta font-semibold py-4 px-6 rounded-xl hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center gap-3 text-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2 2 2 0 0 0 2-2 2 2 0 0 0-2-2zM7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2 2 2 0 0 0 2-2 2 2 0 0 0-2-2zm0-3l1-5h10l1 5H7zM17 2a2 2 0 0 1 2 2c0 .74-.41 1.38-1.02 1.73L17 7.13V14c0 .55-.45 1-1 1s-1-.45-1-1V8.13l-3-1.8V14c0 .55-.45 1-1 1s-1-.45-1-1V6.33l-3.98 2.4c-.61-.35-1.02-.99-1.02-1.73a2 2 0 0 1 2-2h10z"/>
            </svg>
            {{ $btnText }}
          </a>
        @endif
        
        <div class="grid grid-cols-2 gap-3">
          <button class="flex items-center justify-center gap-2 py-3 px-4 border-2 border-borgo text-borgo font-semibold rounded-xl hover:bg-borgo hover:text-white transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Guardar
          </button>
          
          <button class="flex items-center justify-center gap-2 py-3 px-4 border-2 border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-100 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
            </svg>
            Compartir
          </button>
        </div>
      </div>

      {{-- Envío --}}
      <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center gap-3 text-green-800">
          <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
          </svg>
          <div class="text-sm">
            <span class="font-semibold">Envío disponible</span>
            <span class="text-green-600"> - Consulta por entregas a domicilio</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SECCIÓN HTML LIBRE (LANDING) --}}
@if($product->about_html)
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t">
    <div class="prose prose-lg max-w-none">
      {!! $product->about_html !!}
    </div>
  </section>
@endif

{{-- BLOQUES DINÁMICOS --}}
@if(!empty($product->blocks) && is_array($product->blocks))
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t">
    @include('products.partials.blocks', ['product' => $product])
  </section>
@endif

{{-- BOTÓN VOLVER --}}
<section class="bg-slate-50 border-t">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center">
      <a href="{{ route('productos.index') }}" 
         class="inline-flex items-center gap-2 bg-white text-slate-700 font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300 border border-slate-200">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
        </svg>
        Volver a todos los productos
      </a>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const stickyHeader = document.getElementById('stickyHeader');
    const mainHeader = document.querySelector('header');
    
    function toggleStickyHeader() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const mainHeaderBottom = mainHeader.offsetTop + mainHeader.offsetHeight;
      
      if (scrollTop > mainHeaderBottom) {
        stickyHeader.classList.remove('hidden');
        stickyHeader.classList.add('flex');
      } else {
        stickyHeader.classList.add('hidden');
        stickyHeader.classList.remove('flex');
      }
    }

    // Escuchar el scroll
    window.addEventListener('scroll', toggleStickyHeader);
    
    // Inicializar
    toggleStickyHeader();
  });
</script>

<style>
  .prose {
    color: #374151;
    line-height: 1.75;
  }
  
  .prose h2, .prose h3 {
    color: #7B2321;
    font-weight: 600;
  }
  
  .prose p {
    margin-bottom: 1.5em;
  }
  
  .shadow-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
</style>
@endsection