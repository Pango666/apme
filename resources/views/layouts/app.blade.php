<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','APME · Miel ecológica')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-slate-800 font-body">

  @php
    // Carga rápida de settings (claves usadas en este layout)
    $pairs    = \App\Models\Setting::query()->pluck('value','key')->toArray();
    $whatsapp = $pairs['contact.whatsapp'] ?? '+591 681 86701';
    $email    = $pairs['contact.email']    ?? 'info@apme.bo';
    $address  = $pairs['contact.address']  ?? 'La Paz – Bolivia';
    $social   = is_array($pairs['social'] ?? null) ? $pairs['social'] : ['facebook'=>null,'instagram'=>null,'tiktok'=>null];
  @endphp

  {{-- CINTA SUPERIOR --}}
  <div class="bg-borgo text-white text-[13px] shrink-0">
    <div class="max-w-[1200px] mx-auto px-5 h-9 flex items-center gap-5">
      <span>✆ {{ $whatsapp }}</span>
      <a href="mailto:{{ $email }}" class="hover:underline">{{ $email }}</a>
      <span>{{ $address }}</span>

      <div class="ml-auto flex items-center gap-3">
        @auth
          <a href="{{ route('admin.dashboard') }}" class="opacity-90 hover:opacity-100">Admin</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="opacity-90 hover:opacity-100">Salir</button>
          </form>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="opacity-90 hover:opacity-100">Iniciar sesión</a>
        @endguest
      </div>
    </div>
  </div>

  {{-- HEADER / NAV --}}
  <header class="bg-borgo text-white sticky top-0 z-40 shrink-0">
    <div class="max-w-[1200px] mx-auto px-5 h-14 flex items-center justify-between">
      <a href="{{ route('home') }}" class="bg-white text-borgo px-4 py-1.5 rounded-full font-extrabold font-display">APME</a>
      <nav class="hidden md:flex items-center gap-6 text-[15px]">
        <a href="{{ route('page.show','quienes-somos') }}" class="opacity-90 hover:opacity-100">Institucional</a>
        <a href="{{ route('comunidades.index') }}" class="opacity-90 hover:opacity-100">Comunidades</a>
        <a href="{{ route('productos.index') }}" class="opacity-90 hover:opacity-100">Productos</a>
        <a href="{{ route('albums.index') }}" class="opacity-90 hover:opacity-100">Galería</a>
        <a href="{{ route('noticias.index') }}" class="opacity-90 hover:opacity-100">Noticias</a>
        <a href="{{ route('contacto') }}" class="ml-2 bg-miel text-tinta font-semibold px-4 py-1.5 rounded-full hover:brightness-110">Ver más</a>
      </nav>
    </div>
  </header>

  {{-- CONTENIDO --}}
  <main class="flex-1">
    @yield('content')
  </main>

  {{-- ===================  FOOTER  =================== --}}
  <footer class="mt-16 shrink-0">
    <div class="relative bg-borgo text-white">
      {{-- textura panal sutil --}}
      <div class="absolute inset-0 opacity-[.06] pointer-events-none bg-[radial-gradient(#fff_0.6px,transparent_0.7px)] bg-[length:14px_14px]"></div>

      <div class="relative max-w-[1200px] mx-auto px-5 py-12 grid gap-10 md:grid-cols-4">
        {{-- Marca / Descripción --}}
        <div>
          <div class="inline-flex items-center gap-2">
            <span class="inline-grid place-items-center w-9 h-9 rounded-lg bg-white text-borgo font-display font-extrabold">A</span>
            <span class="font-display text-2xl font-extrabold tracking-tight">APME</span>
          </div>
          <p class="mt-3 text-white/85 text-sm max-w-xs">
            Asociación de Productores de Miel Ecológica. Defendemos la apicultura sostenible, la trazabilidad y el comercio justo.
          </p>
        </div>

        {{-- Contacto --}}
        <div>
          <div class="font-semibold mb-3">Contacto</div>
          <ul class="space-y-2 text-white/85 text-sm">
            <li>WhatsApp: <b class="text-white">{{ $whatsapp }}</b></li>
            <li>Email: <a class="underline hover:text-miel" href="mailto:{{ $email }}">{{ $email }}</a></li>
            <li>Dirección: {{ $address }}</li>
          </ul>

          <div class="mt-4 flex items-center gap-3">
            @if(!empty($social['facebook']))
              <a class="w-9 h-9 grid place-items-center rounded-full bg-white/10 hover:bg-white/20" href="{{ $social['facebook'] }}" target="_blank" aria-label="Facebook" rel="noopener">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 9H15V6h-1.5C11.57 6 11 7.79 11 9.25V11H9v3h2v6h3v-6h1.8l.2-3H14v-1.5c0-.43.35-.5.8-.5Z"/></svg>
              </a>
            @endif
            @if(!empty($social['instagram']))
              <a class="w-9 h-9 grid place-items-center rounded-full bg-white/10 hover:bg-white/20" href="{{ $social['instagram'] }}" target="_blank" aria-label="Instagram" rel="noopener">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
              </a>
            @endif
            @if(!empty($social['tiktok']))
              <a class="w-9 h-9 grid place-items-center rounded-full bg-white/10 hover:bg-white/20" href="{{ $social['tiktok'] }}" target="_blank" aria-label="TikTok" rel="noopener">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 6.5a5.5 5.5 0 0 1-3.5-1.3V15a4.5 4.5 0 1 1-4.5-4.5c.17 0 .34 0 .5.03V13a2.5 2.5 0 1 0 2-2.45V3h2.17A7.5 7.5 0 0 0 20 6.5h-2.5Z"/></svg>
              </a>
            @endif
          </div>
        </div>

        {{-- Enlaces --}}
        <div>
          <div class="font-semibold mb-3">Enlaces</div>
          <ul class="space-y-2 text-white/85 text-sm">
            <li><a class="hover:text-miel" href="{{ route('comunidades.index') }}">Comunidades</a></li>
            <li><a class="hover:text-miel" href="{{ route('productos.index') }}">Productos</a></li>
            <li><a class="hover:text-miel" href="{{ route('albums.index') }}">Ferias / Galería</a></li>
            <li><a class="hover:text-miel" href="{{ route('noticias.index') }}">Noticias</a></li>
            <li><a class="hover:text-miel" href="{{ route('page.show','quienes-somos') }}">Institucional</a></li>
          </ul>
        </div>

        {{-- Mini newsletter (placeholder) --}}
        <div>
          <div class="font-semibold mb-3">Boletín</div>
          <p class="text-white/85 text-sm mb-3">Recibe noticias y actividades de APME.</p>
          <form onsubmit="event.preventDefault();" class="flex gap-2">
            <input class="w-full rounded-lg bg-white/10 text-white placeholder-white/60 px-3 py-2 outline-none focus:ring-2 ring-miel"
                   type="email" placeholder="tu@correo.com">
            <button class="px-4 py-2 rounded-lg bg-miel text-tinta font-semibold hover:brightness-110">Suscribirme</button>
          </form>
        </div>
      </div>

      {{-- Línea inferior --}}
      <div class="relative border-t border-white/10">
        <div class="max-w-[1200px] mx-auto px-5 py-4 text-white/70 text-xs flex flex-col md:flex-row items-center justify-between gap-2">
          <div>© {{ date('Y') }} APME — Asociación de Productores de Miel Ecológica</div>
          <div class="flex gap-4">
            <a href="{{ route('contacto') }}" class="hover:text-miel">Contacto</a>
            <a href="{{ route('noticias.index') }}" class="hover:text-miel">Prensa</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  {{-- ================ /FOOTER ================= --}}
</body>
</html>
