@extends('layouts.app')
@section('title','Productos · APME - Miel Ecológica')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  {{-- Header mejorado --}}
  <div class="text-center mb-12">
    <h1 class="font-display text-4xl font-bold text-borgo mb-4">Nuestros Productos</h1>
    <p class="text-lg text-slate-600 max-w-2xl mx-auto">Descubre la excelencia de nuestra miel ecológica y derivados, producidos con los más altos estándares de calidad</p>
  </div>

  {{-- Filtros y ordenamiento --}}
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 p-6 bg-crema/30 rounded-xl">
    <div class="flex items-center gap-4">
      <span class="text-sm font-medium text-slate-700">Filtrar por:</span>
      <select class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-miel focus:border-transparent">
        <option value="">Todos los tipos</option>
        <option value="miel">Miel</option>
        <option value="polen">Polen</option>
        <option value="propoleo">Propóleo</option>
        <option value="jalea">Jalea Real</option>
      </select>
    </div>
    
    <div class="flex items-center gap-4">
      <span class="text-sm font-medium text-slate-700">Ordenar por:</span>
      <select class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-miel focus:border-transparent">
        <option value="newest">Más recientes</option>
        <option value="price_asc">Precio: menor a mayor</option>
        <option value="price_desc">Precio: mayor a menor</option>
        <option value="name">Nombre A-Z</option>
      </select>
    </div>
  </div>

  {{-- Grid de productos --}}
  <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
    @forelse($items as $product)
      @php
        // Obtener la imagen correctamente
        $productImage = $product->hero_image;
        if ($productImage && !preg_match('#^(https?://|/)#', $productImage)) {
            $productImage = \Illuminate\Support\Facades\Storage::url($productImage);
        }
        $productImage = $productImage ?: '/placeholder-product.jpg';
      @endphp
      
      {{-- Toda la card es clickeable --}}
      <a href="{{ route('productos.show', $product->slug) }}" 
         class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 group block">
        
        {{-- Imagen del producto --}}
        <div class="aspect-square bg-gradient-to-br from-crema to-miel/10 relative overflow-hidden">
          <img 
            src="{{ $productImage }}" 
            alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
          >
          
          {{-- Badge tipo --}}
          <div class="absolute top-3 left-3">
            <span class="px-2 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-borgo">
              {{ $product->type ?: 'Producto' }}
            </span>
          </div>
        </div>

        {{-- Contenido --}}
        <div class="p-5">
          <h3 class="font-semibold text-lg text-borgo mb-2 group-hover:text-borgo2 transition-colors line-clamp-2">
            {{ $product->name }}
          </h3>
          
          @if($product->community)
            <p class="text-sm text-slate-600 mb-3 flex items-center gap-1">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
              </svg>
              {{ $product->community->name }}
            </p>
          @endif

          @if($product->price_bs)
            <div class="flex items-center justify-between mt-4">
              <span class="text-2xl font-bold text-miel">{{ number_format($product->price_bs, 2) }} Bs</span>
              <span class="text-sm text-slate-500">unidad</span>
            </div>
          @endif

          {{-- Acciones (ahora solo botones secundarios) --}}
          <div class="mt-4 flex gap-2" onclick="event.preventDefault(); event.stopPropagation();">
            <button class="flex-1 bg-slate-100 text-slate-700 text-center py-2 px-4 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              Guardar
            </button>
            <button class="p-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors" title="Compartir" onclick="event.preventDefault(); event.stopPropagation();">
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
              </svg>
            </button>
          </div>
        </div>
      </a>
    @empty
      {{-- Estado vacío --}}
      <div class="col-span-full text-center py-16">
        <svg class="w-24 h-24 text-slate-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Zm0 2.2 6 2.3v3.3l-6-2.3-6 2.3V6.5l6-2.3ZM6 7.5l4.1 1.5L6 10.5V7.5Zm12 0v3l-4.1-1.5L18 7.5Z"/>
        </svg>
        <h3 class="text-lg font-semibold text-slate-600 mb-2">Próximamente más productos</h3>
        <p class="text-slate-500 mb-6">Estamos trabajando en ampliar nuestro catálogo</p>
        <a href="{{ route('contacto') }}" class="bg-miel text-tinta font-semibold px-6 py-3 rounded-lg hover:shadow-lg transition-all">
          Contáctanos para más información
        </a>
      </div>
    @endforelse
  </div>

  {{-- Paginación mejorada --}}
  @if($items->hasPages())
    <div class="flex justify-center">
      <div class="flex items-center gap-2 bg-white rounded-lg border border-slate-200 p-2">
        {{-- Previous Page Link --}}
        @if($items->onFirstPage())
          <span class="px-3 py-2 text-slate-400 cursor-not-allowed rounded">
            ‹ Anterior
          </span>
        @else
          <a href="{{ $items->previousPageUrl() }}" class="px-3 py-2 text-borgo hover:bg-slate-50 rounded transition-colors">
            ‹ Anterior
          </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
          @if($page == $items->currentPage())
            <span class="px-3 py-2 bg-borgo text-white rounded font-semibold">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="px-3 py-2 text-slate-700 hover:bg-slate-50 rounded transition-colors">{{ $page }}</a>
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if($items->hasMorePages())
          <a href="{{ $items->nextPageUrl() }}" class="px-3 py-2 text-borgo hover:bg-slate-50 rounded transition-colors">
            Siguiente ›
          </a>
        @else
          <span class="px-3 py-2 text-slate-400 cursor-not-allowed rounded">
            Siguiente ›
          </span>
        @endif
      </div>
    </div>
  @endif
</div>

<style>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .shadow-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  /* Mejorar el cursor para indicar que es clickeable */
  .group {
    cursor: pointer;
  }
</style>

<script>
  // Prevenir que los botones dentro de la card activen el enlace principal
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Aquí puedes agregar la funcionalidad específica de cada botón
        if (this.title === 'Compartir') {
          // Lógica para compartir
          console.log('Compartir producto');
        } else if (this.textContent.includes('Guardar')) {
          // Lógica para guardar
          console.log('Guardar producto');
        }
      });
    });
  });
</script>
@endsection