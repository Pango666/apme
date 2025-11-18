@extends('layouts.app')
@section('title','Inicio · APME - Asociación de Productores de Miel Ecológica')

@section('content')
@php
  $settings  = $settings ?? [];
  $hero      = $hero ?? [];
  $heroTitle = $hero['title']    ?? ($settings['hero.title']    ?? 'Miel ecológica de nuestras comunidades');
  $heroSub   = $hero['subtitle'] ?? ($settings['hero.subtitle'] ?? 'Trazabilidad, calidad y comercio justo desde Bolivia');
  $heroImg   = $hero['image']    ?? ($settings['hero.image']    ?? null);

  if ($heroImg && !preg_match('#^(https?://|/)#', $heroImg)) {
      $heroImg = \Illuminate\Support\Facades\Storage::url($heroImg);
  }
  $heroImg = $heroImg ?: '/hero-miel.webp';

  $qs        = $qs        ?? null;
  $mision    = $mision    ?? null;
  $vision    = $vision    ?? null;
  $comunidades = ($comunidades ?? collect())->sortByDesc('id')->take(6);
  $productos   = ($productos   ?? collect())->sortByDesc('id')->take(8);
  $ferias      = ($ferias      ?? collect())->sortByDesc('date')->take(5);
  $posts       = ($posts       ?? collect())->sortByDesc('published_at')->take(6);
  $partners    = ($partners    ?? collect())->take(8);
@endphp

{{-- HERO SECTION MEJORADA --}}
<section class="relative w-full min-h-[85vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-borgo/95 to-borgo2">
  <div class="absolute inset-0">
    <img src="{{ $heroImg }}" alt="Producción de miel ecológica APME" class="w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-borgo/80 to-borgo2/60"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
    <div class="max-w-4xl mx-auto">
      <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 animate-fade-in">
        {{ $heroTitle }}
      </h1>
      <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto leading-relaxed animate-fade-in" style="animation-delay: 0.2s">
        {{ $heroSub }}
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in" style="animation-delay: 0.4s">
        <a href="{{ route('productos.index') }}" 
           class="bg-gradient-to-r from-miel to-ambar text-tinta font-semibold px-8 py-4 rounded-full hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 text-lg">
          Descubrir Productos
        </a>
        <a href="{{ route('page.show','quienes-somos') }}" 
           class="border-2 border-white text-white font-semibold px-8 py-4 rounded-full hover:bg-white hover:text-borgo transition-all duration-300 text-lg">
          Conocer APME
        </a>
      </div>
    </div>
  </div>

  {{-- Indicador scroll --}}
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
    <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
    </svg>
  </div>
</section>

{{-- VALORES Y SERVICIOS --}}
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="font-display text-3xl md:text-4xl font-bold text-borgo mb-4">Nuestro Compromiso</h2>
      <p class="text-lg text-slate-600 max-w-2xl mx-auto">Trabajamos por una apicultura sostenible que beneficie a productores, consumidores y el medio ambiente</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      {{-- Calidad --}}
      <div class="text-center group" data-aos="fade-up">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-miel/20 to-ambar/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg class="w-10 h-10 text-borgo" fill="currentColor" viewBox="0 0 24 24"><path d="m18 7-1.4-1.4-6.6 6.6-2.6-2.6L6 11l4 4 8-8Zm-6-5a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Z"/></svg>
        </div>
        <h3 class="font-semibold text-lg text-borgo mb-3">Calidad Certificada</h3>
        <p class="text-slate-600 text-sm leading-relaxed">Estrictos controles de calidad y trazabilidad desde la colmena hasta el consumidor final</p>
      </div>

      {{-- Sostenibilidad --}}
      <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-hoja/20 to-green-600/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg class="w-10 h-10 text-hoja" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Zm0 2.2 6 2.3v3.3l-6-2.3-6 2.3V6.5l6-2.3ZM6 7.5l4.1 1.5L6 10.5V7.5Zm12 0v3l-4.1-1.5L18 7.5Z"/></svg>
        </div>
        <h3 class="font-semibold text-lg text-borgo mb-3">Sostenibilidad</h3>
        <p class="text-slate-600 text-sm leading-relaxed">Prácticas apícolas responsables que protegen la biodiversidad y los ecosistemas locales</p>
      </div>

      {{-- Comunidades --}}
      <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-borgo/20 to-purple-600/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg class="w-10 h-10 text-borgo" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4Zm0 2c-2.7 0-8 1.3-8 4v2h16v-2c0-2.7-5.3-4-8-4Z"/></svg>
        </div>
        <h3 class="font-semibold text-lg text-borgo mb-3">Desarrollo Comunitario</h3>
        <p class="text-slate-600 text-sm leading-relaxed">Fortalecemos las capacidades de productores locales mediante formación y acceso a mercados</p>
      </div>

      {{-- Comercio Justo --}}
      <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-ambar/20 to-orange-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg class="w-10 h-10 text-ambar" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9h2V7h-2v2Zm1 11c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8Zm0-18C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2Zm-1 15h2v-6h-2v6Z"/></svg>
        </div>
        <h3 class="font-semibold text-lg text-borgo mb-3">Comercio Justo</h3>
        <p class="text-slate-600 text-sm leading-relaxed">Precios equitativos que valoran el trabajo de los apicultores y garantizan calidad premium</p>
      </div>
    </div>
  </div>
</section>

{{-- SOBRE APME --}}
<section class="py-16 bg-gradient-to-br from-crema to-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-borgo mb-6">
          Uniendo esfuerzos por la <span class="text-miel">apicultura sostenible</span>
        </h2>
        <p class="text-lg text-slate-700 mb-6 leading-relaxed">
          La Asociación de Productores de Miel Ecológica (APME) representa el esfuerzo colectivo de apicultores comprometidos con la calidad, trazabilidad y sostenibilidad de la producción apícola en Bolivia.
        </p>
        
        <div class="space-y-4 mb-8">
          <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-miel flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="m10 16.4-4-4L7.4 11l2.6 2.6L16.6 7 18 8.4l-8 8Z"/></svg>
            <div>
              <h4 class="font-semibold text-borgo">Certificación y Calidad</h4>
              <p class="text-slate-600 text-sm">Sistemas de trazabilidad que garantizan origen y procesos</p>
            </div>
          </div>
          <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-miel flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="m10 16.4-4-4L7.4 11l2.6 2.6L16.6 7 18 8.4l-8 8Z"/></svg>
            <div>
              <h4 class="font-semibold text-borgo">Capacitación Continua</h4>
              <p class="text-slate-600 text-sm">Formación técnica en buenas prácticas apícolas</p>
            </div>
          </div>
          <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-miel flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="m10 16.4-4-4L7.4 11l2.6 2.6L16.6 7 18 8.4l-8 8Z"/></svg>
            <div>
              <h4 class="font-semibold text-borgo">Acceso a Mercados</h4>
              <p class="text-slate-600 text-sm">Comercialización conjunta y ferias especializadas</p>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap gap-4">
          <a href="{{ route('page.show','quienes-somos') }}" 
             class="bg-gradient-to-r from-borgo to-borgo2 text-white font-semibold px-6 py-3 rounded-full hover:shadow-lg transition-all">
            Conocer Nuestra Historia
          </a>
          <a href="{{ route('contacto') }}" 
             class="border-2 border-borgo text-borgo font-semibold px-6 py-3 rounded-full hover:bg-borgo hover:text-white transition-all">
            Unirse a APME
          </a>
        </div>
      </div>

      <div class="relative" data-aos="fade-left">
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-4">
            <div class="rounded-2xl overflow-hidden shadow-lg">
              <img src="{{ $heroImg }}" alt="Producción apícola APME" class="w-full h-48 object-cover">
            </div>
            <div class="rounded-2xl overflow-hidden shadow-lg">
              <img src="{{ $heroImg }}" alt="Comunidades productoras" class="w-full h-32 object-cover">
            </div>
          </div>
          <div class="space-y-4 pt-8">
            <div class="rounded-2xl overflow-hidden shadow-lg">
              <img src="{{ $heroImg }}" alt="Miel ecológica APME" class="w-full h-32 object-cover">
            </div>
            <div class="rounded-2xl overflow-hidden shadow-lg">
              <img src="{{ $heroImg }}" alt="Procesos de calidad" class="w-full h-48 object-cover">
            </div>
          </div>
        </div>
        
        {{-- Elemento decorativo --}}
        <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-miel/20 rounded-full blur-xl"></div>
        <div class="absolute -top-6 -right-6 w-32 h-32 bg-borgo/10 rounded-full blur-xl"></div>
      </div>
    </div>
  </div>
</section>

{{-- COMUNIDADES PRODUCTORAS --}}
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h2 class="font-display text-3xl font-bold text-borgo mb-2">Comunidades Productoras</h2>
        <p class="text-slate-600 max-w-2xl">Conoce las comunidades que trabajan con nosotros en la producción de miel ecológica de calidad</p>
      </div>
      <a href="{{ route('comunidades.index') }}" 
         class="mt-4 md:mt-0 inline-flex items-center text-borgo font-semibold hover:text-borgo2 transition-colors">
        Ver todas las comunidades
        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24"><path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41Z"/></svg>
      </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($comunidades as $comunidad)
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 group" data-aos="fade-up">
          <div class="h-48 bg-gradient-to-br from-borgo/10 to-miel/10 flex items-center justify-center">
            <svg class="w-16 h-16 text-borgo/40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          </div>
          <div class="p-6">
            <h3 class="font-semibold text-lg text-borgo mb-2 group-hover:text-borgo2 transition-colors">{{ $comunidad->name }}</h3>
            <p class="text-slate-600 text-sm mb-4">{{ $comunidad->province }}</p>
            <a href="{{ route('comunidades.show', $comunidad->slug) }}" 
               class="inline-flex items-center text-sm font-medium text-miel hover:text-ambar transition-colors">
              Conocer más
              <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41Z"/></svg>
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-3 text-center py-12">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          <p class="text-slate-500">Próximamente más información sobre nuestras comunidades productoras</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- PRODUCTOS DESTACADOS --}}
<section class="py-16 bg-gradient-to-br from-borgo to-borgo2 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Nuestros Productos</h2>
      <p class="text-xl text-white/80 max-w-2xl mx-auto">Descubre la excelencia de la miel ecológica y sus derivados, producidos con los más altos estándares de calidad</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      @forelse($productos as $producto)
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group" data-aos="fade-up">
          <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Zm0 2.2 6 2.3v3.3l-6-2.3-6 2.3V6.5l6-2.3ZM6 7.5l4.1 1.5L6 10.5V7.5Zm12 0v3l-4.1-1.5L18 7.5Z"/></svg>
          </div>
          <h3 class="font-semibold text-lg mb-2">{{ $producto->name }}</h3>
          <p class="text-white/70 text-sm mb-4">{{ $producto->type }}</p>
          @if($producto->price_bs)
            <div class="text-miel font-bold text-lg">{{ number_format($producto->price_bs, 2) }} Bs</div>
          @endif
          <a href="{{ route('productos.show', $producto) }}" 
             class="inline-flex items-center text-sm font-medium text-white hover:text-miel transition-colors mt-4">
            Ver detalles
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41Z"/></svg>
          </a>
        </div>
      @empty
        <div class="col-span-4 text-center py-8">
          <svg class="w-16 h-16 text-white/30 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-8-3Zm0 2.2 6 2.3v3.3l-6-2.3-6 2.3V6.5l6-2.3ZM6 7.5l4.1 1.5L6 10.5V7.5Zm12 0v3l-4.1-1.5L18 7.5Z"/></svg>
          <p class="text-white/60">Próximamente nuestro catálogo de productos</p>
        </div>
      @endforelse
    </div>

    <div class="text-center mt-12">
      <a href="{{ route('productos.index') }}" 
         class="bg-white text-borgo font-semibold px-8 py-4 rounded-full hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center">
        Explorar Catálogo Completo
        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24"><path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41Z"/></svg>
      </a>
    </div>
  </div>
</section>

{{-- NOTICIAS Y ACTUALIDAD --}}
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <div>
        <h2 class="font-display text-3xl font-bold text-borgo mb-2">Noticias y Actualidad</h2>
        <p class="text-slate-600 max-w-2xl">Mantente informado sobre nuestras actividades, eventos y novedades del sector apícola</p>
      </div>
      <a href="{{ route('noticias.index') }}" 
         class="mt-4 md:mt-0 inline-flex items-center text-borgo font-semibold hover:text-borgo2 transition-colors">
        Ver todas las noticias
        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24"><path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41Z"/></svg>
      </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @forelse($posts as $post)
        <article class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 group" data-aos="fade-up">
          <div class="aspect-video bg-gradient-to-br from-borgo/10 to-miel/10 overflow-hidden">
            <img src="{{ $post->cover_path ?? '/placeholder.webp' }}" 
                 alt="{{ $post->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
          </div>
          <div class="p-6">
            <h3 class="font-semibold text-lg text-borgo mb-3 group-hover:text-borgo2 transition-colors line-clamp-2">{{ $post->title }}</h3>
            <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ $post->excerpt }}</p>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">
                {{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at)->isoFormat('D MMM YYYY') : 'Próximamente' }}
              </span>
              <a href="{{ route('noticias.show', $post->slug) }}" 
                 class="text-sm font-medium text-miel hover:text-ambar transition-colors">
                Leer más
              </a>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-3 text-center py-12">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          <p class="text-slate-500">Próximamente publicaremos nuestras noticias y actividades</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ALIADOS ESTRATÉGICOS --}}
<section class="py-16 bg-crema/50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="font-display text-3xl font-bold text-borgo mb-4">Aliados Estratégicos</h2>
      <p class="text-slate-600 max-w-2xl mx-auto">Instituciones y organizaciones que colaboran con nosotros en el desarrollo de la apicultura sostenible</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      @forelse($partners as $partner)
        <div class="bg-white rounded-xl p-6 border border-slate-200 hover:shadow-md transition-all duration-300 flex items-center justify-center h-32" data-aos="fade-up">
          <img src="{{ $partner->logo_path ?? '/placeholder.webp' }}" 
               alt="{{ $partner->name }}" 
               class="max-h-12 object-contain opacity-70 hover:opacity-100 transition-opacity">
        </div>
      @empty
        <div class="col-span-4 text-center py-8">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8 opacity-40">
            @for($i = 0; $i < 4; $i++)
              <div class="bg-white rounded-xl p-6 border border-slate-200 flex items-center justify-center h-32">
                <div class="w-20 h-8 bg-slate-200 rounded"></div>
              </div>
            @endfor
          </div>
          <p class="text-slate-500 mt-6">Estableciendo alianzas estratégicas para el crecimiento del sector</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- CTA FINAL --}}
<section class="py-16 bg-gradient-to-r from-borgo to-borgo2 text-white">
  <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
    <h2 class="font-display text-3xl md:text-4xl font-bold mb-6" data-aos="fade-up">
      ¿Listo para colaborar con nosotros?
    </h2>
    <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
      Únete a nuestra asociación, conviértete en proveedor o descubre cómo puedes apoyar la apicultura sostenible
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
      <a href="{{ route('contacto') }}" 
         class="bg-white text-borgo font-semibold px-8 py-4 rounded-full hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
        Contactar APME
      </a>
      <a href="{{ route('productos.index') }}" 
         class="border-2 border-white text-white font-semibold px-8 py-4 rounded-full hover:bg-white hover:text-borgo transition-all duration-300">
        Ver Productos
      </a>
    </div>
  </div>
</section>

<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
  }
  
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
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Inicializar AOS si está disponible
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 800,
        once: true,
        offset: 100
      });
    }
    
    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  });
</script>
@endsection