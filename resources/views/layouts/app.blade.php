<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','APME · Asociación de Productores de Miel Ecológica')</title>
  <meta name="description" content="Asociación de Productores de Miel Ecológica. Promovemos apicultura sostenible, trazabilidad y comercio justo en Bolivia.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-slate-800 font-body antialiased">

  @php
    $pairs    = \App\Models\Setting::query()->pluck('value','key')->toArray();
    $whatsapp = $pairs['contact.whatsapp'] ?? '+591 681 86701';
    $email    = $pairs['contact.email']    ?? 'info@apme.bo';
    $address  = $pairs['contact.address']  ?? 'La Paz – Bolivia';
    $social   = is_array($pairs['social'] ?? null) ? $pairs['social'] : ['facebook'=>null,'instagram'=>null,'tiktok'=>null];
  @endphp

  {{-- BARRA SUPERIOR MEJORADA --}}
  <div class="bg-borgo text-white text-sm shrink-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-10 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $whatsapp) }}" class="flex items-center gap-1.5 hover:text-miel transition-colors">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.4 2.4.8 3.4L5.7 11.4c-.5-1.2-.8-2.5-.9-3.8L5 6zm14 12c-3.9 0-7.5-1.6-10.1-4.3l1.4-1.4c.8.7 1.6 1.3 2.5 1.8.3.2.7.2 1 0l2.2-2.2c.3-.3.4-.7.2-1-.5-1.6-.8-3.3-.8-5 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 5.2 2.7 9.8 6.7 12.4l-1.4 1.4C19.6 19.5 17.9 20 16 20z"/></svg>
          <span>{{ $whatsapp }}</span>
        </a>
        <span class="text-white/60">|</span>
        <a href="mailto:{{ $email }}" class="hover:text-miel transition-colors">{{ $email }}</a>
      </div>

      <div class="flex items-center space-x-4">
        @auth
          <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-miel transition-colors">Panel Administrativo</a>
          <span class="text-white/40">|</span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm hover:text-miel transition-colors">Cerrar Sesión</button>
          </form>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="text-sm hover:text-miel transition-colors">Acceso Socios</a>
        @endguest
      </div>
    </div>
  </div>

  {{-- HEADER MEJORADO --}}
  <header class="bg-white shadow-sm sticky top-0 z-40 shrink-0 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
          <div class="w-10 h-10 bg-gradient-to-br from-borgo to-borgo2 rounded-lg flex items-center justify-center shadow-sm">
            <span class="text-white font-display font-bold text-lg">A</span>
          </div>
          <div class="hidden sm:block">
            <div class="font-display font-bold text-borgo text-lg tracking-tight">APME</div>
            <div class="text-xs text-slate-500 -mt-1">Miel Ecológica</div>
          </div>
        </a>

        {{-- Navegación Desktop --}}
        <nav class="hidden lg:flex items-center space-x-8">
          <a href="{{ route('page.show','quienes-somos') }}" class="text-slate-700 hover:text-borgo font-medium transition-colors relative py-2">Institucional</a>
          <a href="{{ route('comunidades.index') }}" class="text-slate-700 hover:text-borgo font-medium transition-colors relative py-2">Comunidades</a>
          <a href="{{ route('productos.index') }}" class="text-slate-700 hover:text-borgo font-medium transition-colors relative py-2">Productos</a>
          <a href="{{ route('albums.index') }}" class="text-slate-700 hover:text-borgo font-medium transition-colors relative py-2">Galería</a>
          <a href="{{ route('noticias.index') }}" class="text-slate-700 hover:text-borgo font-medium transition-colors relative py-2">Noticias</a>
          <a href="{{ route('contacto') }}" class="ml-4 bg-gradient-to-r from-miel to-ambar text-tinta font-semibold px-5 py-2 rounded-full hover:shadow-md transition-all duration-200">Contacto</a>
        </nav>

        {{-- Menú móvil --}}
        <div class="lg:hidden">
          <button id="mobile-menu-button" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    {{-- Menú móvil desplegable --}}
    <div id="mobile-menu" class="lg:hidden hidden bg-white border-t">
      <div class="px-4 py-3 space-y-1">
        <a href="{{ route('page.show','quienes-somos') }}" class="block py-2 px-3 text-slate-700 hover:bg-slate-50 rounded-lg">Institucional</a>
        <a href="{{ route('comunidades.index') }}" class="block py-2 px-3 text-slate-700 hover:bg-slate-50 rounded-lg">Comunidades</a>
        <a href="{{ route('productos.index') }}" class="block py-2 px-3 text-slate-700 hover:bg-slate-50 rounded-lg">Productos</a>
        <a href="{{ route('albums.index') }}" class="block py-2 px-3 text-slate-700 hover:bg-slate-50 rounded-lg">Galería</a>
        <a href="{{ route('noticias.index') }}" class="block py-2 px-3 text-slate-700 hover:bg-slate-50 rounded-lg">Noticias</a>
        <a href="{{ route('contacto') }}" class="block py-2 px-3 bg-miel text-tinta font-semibold rounded-lg mt-2 text-center">Contacto</a>
      </div>
    </div>
  </header>

  {{-- CONTENIDO PRINCIPAL --}}
  <main class="flex-1">
    @yield('content')
  </main>

  {{-- FOOTER MEJORADO --}}
  <footer class="bg-gradient-to-b from-borgo to-borgo2 text-white mt-16 shrink-0">
    <div class="relative">
      {{-- Textura sutil de panal --}}
      <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(circle_at_1px_1px,#fff_1px,transparent_0)] bg-[length:20px_20px]"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 md:grid-cols-4">
        {{-- Marca y descripción --}}
        <div class="md:col-span-1">
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
              <span class="text-borgo font-display font-bold text-lg">A</span>
            </div>
            <div>
              <div class="font-display font-bold text-xl">APME</div>
              <div class="text-white/70 text-sm">Miel Ecológica</div>
            </div>
          </div>
          <p class="text-white/80 text-sm leading-relaxed">
            Asociación de Productores de Miel Ecológica. Trabajamos por la apicultura sostenible, trazabilidad y comercio justo en Bolivia.
          </p>
        </div>

        {{-- Contacto --}}
        <div>
          <h3 class="font-semibold text-lg mb-4">Contacto</h3>
          <ul class="space-y-3 text-white/80 text-sm">
            <li class="flex items-start space-x-2">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.4 2.4.8 3.4L5.7 11.4c-.5-1.2-.8-2.5-.9-3.8L5 6zm14 12c-3.9 0-7.5-1.6-10.1-4.3l1.4-1.4c.8.7 1.6 1.3 2.5 1.8.3.2.7.2 1 0l2.2-2.2c.3-.3.4-.7.2-1-.5-1.6-.8-3.3-.8-5 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 5.2 2.7 9.8 6.7 12.4l-1.4 1.4C19.6 19.5 17.9 20 16 20z"/></svg>
              <span>{{ $whatsapp }}</span>
            </li>
            <li class="flex items-start space-x-2">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
              <a href="mailto:{{ $email }}" class="hover:text-miel transition-colors">{{ $email }}</a>
            </li>
            <li class="flex items-start space-x-2">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
              <span>{{ $address }}</span>
            </li>
          </ul>

          {{-- Redes sociales --}}
          <div class="mt-6 flex space-x-3">
            @if(!empty($social['facebook']))
              <a class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors" href="{{ $social['facebook'] }}" target="_blank" aria-label="Facebook" rel="noopener">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
              </a>
            @endif
            @if(!empty($social['instagram']))
              <a class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors" href="{{ $social['instagram'] }}" target="_blank" aria-label="Instagram" rel="noopener">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153.555.556.9 1.112 1.153 1.772.247.639.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772c-.556.555-1.112.9-1.772 1.153-.639.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.639-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm6.5-.25a1.25 1.25 0 1 0-2.5 0 1.25 1.25 0 0 0 2.5 0zM12 9a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
              </a>
            @endif
            @if(!empty($social['tiktok']))
              <a class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors" href="{{ $social['tiktok'] }}" target="_blank" aria-label="TikTok" rel="noopener">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
              </a>
            @endif
          </div>
        </div>

        {{-- Enlaces rápidos --}}
        <div>
          <h3 class="font-semibold text-lg mb-4">Enlaces</h3>
          <ul class="space-y-2 text-white/80 text-sm">
            <li><a class="hover:text-miel transition-colors" href="{{ route('comunidades.index') }}">Comunidades Productoras</a></li>
            <li><a class="hover:text-miel transition-colors" href="{{ route('productos.index') }}">Nuestros Productos</a></li>
            <li><a class="hover:text-miel transition-colors" href="{{ route('albums.index') }}">Ferias y Eventos</a></li>
            <li><a class="hover:text-miel transition-colors" href="{{ route('noticias.index') }}">Noticias y Actualidad</a></li>
            <li><a class="hover:text-miel transition-colors" href="{{ route('page.show','quienes-somos') }}">Sobre APME</a></li>
          </ul>
        </div>

        {{-- Boletín informativo --}}
<div>
  <h3 class="font-semibold text-lg mb-4">Boletín Informativo</h3>
  <p class="text-white/80 text-sm mb-4">Recibe noticias, eventos y novedades sobre apicultura sostenible.</p>

  <form class="space-y-3" method="POST" action="{{ route('newsletter.subscribe') }}">
    @csrf
    <input
      class="w-full rounded-lg bg-white/10 text-white placeholder-white/60 px-4 py-3 outline-none focus:ring-2 ring-miel border border-white/20"
      type="email"
      name="email"
      value="{{ old('email') }}"
      placeholder="tu@correo.com"
      required
    >
    <button class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-miel to-ambar text-tinta font-semibold hover:shadow-md transition-all">
      Suscribirme
    </button>
  </form>

  {{-- mensajes --}}
  @if(session('ok'))
    <p class="mt-2 text-sm text-emerald-200">{{ session('ok') }}</p>
  @endif

  @error('email')
    <p class="mt-2 text-sm text-rose-200">{{ $message }}</p>
  @enderror
</div>
      </div>

      {{-- Línea inferior --}}
      <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
            <div class="text-white/70 text-sm text-center md:text-left">
              © {{ date('Y') }} APME — Asociación de Productores de Miel Ecológica. Todos los derechos reservados.
            </div>
            <div class="flex space-x-6 text-sm">
              <a href="{{ route('contacto') }}" class="text-white/70 hover:text-miel transition-colors">Contacto</a>
              <a href="{{ route('noticias.index') }}" class="text-white/70 hover:text-miel transition-colors">Prensa</a>
              <a href="#" class="text-white/70 hover:text-miel transition-colors">Privacidad</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  {{-- Script para menú móvil --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      
      if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
        });
      }
    });
  </script>
</body>
</html>