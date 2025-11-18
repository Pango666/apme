@extends('layouts.app')
@section('title','Comunidades Productoras · APME - Miel Ecológica')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  {{-- Header mejorado --}}
  <div class="text-center mb-12">
    <h1 class="font-display text-4xl font-bold text-borgo mb-4">Comunidades Productoras</h1>
    <p class="text-lg text-slate-600 max-w-2xl mx-auto">Conoce las comunidades que trabajan con nosotros en la producción de miel ecológica de calidad</p>
  </div>

  {{-- Estadísticas --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
    <div class="bg-crema/50 rounded-xl p-6 text-center">
      <div class="text-3xl font-bold text-borgo mb-2">{{ $items->count() }}</div>
      <div class="text-sm text-slate-600">Comunidades</div>
    </div>
    <div class="bg-crema/50 rounded-xl p-6 text-center">
      <div class="text-3xl font-bold text-borgo mb-2">{{ $items->pluck('province')->unique()->count() }}</div>
      <div class="text-sm text-slate-600">Provincias</div>
    </div>
    <div class="bg-crema/50 rounded-xl p-6 text-center">
      <div class="text-3xl font-bold text-borgo mb-2">{{ \App\Models\Product::whereIn('community_id', $items->pluck('id'))->count() }}</div>
      <div class="text-sm text-slate-600">Productos</div>
    </div>
    <div class="bg-crema/50 rounded-xl p-6 text-center">
      <div class="text-3xl font-bold text-borgo mb-2">100%</div>
      <div class="text-sm text-slate-600">Sostenible</div>
    </div>
  </div>

  @if($items->count() === 0)
    {{-- Estado vacío mejorado --}}
    <div class="text-center py-16">
      <svg class="w-24 h-24 text-slate-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
      </svg>
      <h3 class="text-xl font-semibold text-slate-600 mb-2">Próximamente más comunidades</h3>
      <p class="text-slate-500 mb-6">Estamos trabajando en ampliar nuestra red de comunidades productoras</p>
    </div>
  @else
    {{-- Grid de comunidades --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
      @foreach($items as $community)
        @php
          $communityImage = $community->hero_image_url ?: '/placeholder-community.jpg';
        @endphp
        
        <a class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 group block"
           href="{{ route('comunidades.show', $community->slug) }}">
          
          {{-- Imagen de la comunidad --}}
          <div class="aspect-[4/3] relative overflow-hidden">
            <img 
              src="{{ $communityImage }}" 
              alt="{{ $community->name }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            
            {{-- Badge provincia --}}
            @if($community->province)
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-borgo">
                  {{ $community->province }}
                </span>
              </div>
            @endif
          </div>

          {{-- Contenido --}}
          <div class="p-6">
            <h3 class="font-semibold text-xl text-borgo mb-3 group-hover:text-borgo2 transition-colors line-clamp-2">
              {{ $community->name }}
            </h3>
            
            @if($community->description)
              <p class="text-slate-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                {{ Str::limit($community->description, 120) }}
              </p>
            @endif

            {{-- Información adicional --}}
            <div class="flex items-center justify-between text-sm text-slate-500">
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Zm0 2.2 6 2.3v3.3l-6-2.3-6 2.3V6.5l6-2.3ZM6 7.5l4.1 1.5L6 10.5V7.5Zm12 0v3l-4.1-1.5L18 7.5Z"/>
                </svg>
                <span>{{ $community->products_count ?? 0 }} productos</span>
              </div>
              
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <span>Ver detalles</span>
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    {{-- Paginación mejorada --}}
    @if($items->hasPages())
      <div class="flex justify-center">
        <div class="flex items-center gap-2 bg-white rounded-lg border border-slate-200 p-2">
          {{-- Previous Page Link --}}
          @if($items->onFirstPage())
            <span class="px-4 py-2 text-slate-400 cursor-not-allowed rounded-lg">
              ‹ Anterior
            </span>
          @else
            <a href="{{ $items->previousPageUrl() }}" class="px-4 py-2 text-borgo hover:bg-slate-50 rounded-lg transition-colors">
              ‹ Anterior
            </a>
          @endif

          {{-- Pagination Elements --}}
          @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
            @if($page == $items->currentPage())
              <span class="px-4 py-2 bg-borgo text-white rounded-lg font-semibold">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">{{ $page }}</a>
            @endif
          @endforeach

          {{-- Next Page Link --}}
          @if($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}" class="px-4 py-2 text-borgo hover:bg-slate-50 rounded-lg transition-colors">
              Siguiente ›
            </a>
          @else
            <span class="px-4 py-2 text-slate-400 cursor-not-allowed rounded-lg">
              Siguiente ›
            </span>
          @endif
        </div>
      </div>
    @endif
  @endif
</div>

<style>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .shadow-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
</style>
@endsection