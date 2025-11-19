<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','APME · Admin')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800">
  <div class="min-h-screen flex flex-col">

    {{-- TOPBAR --}}
    <header class="fixed inset-x-0 top-0 z-50 bg-borgo/95 backdrop-blur-sm text-white border-b border-white/10">
      <div class="mx-auto max-w-[1320px] px-5 h-14 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-white text-borgo grid place-items-center font-display font-extrabold">A</div>
          <div class="font-display font-extrabold tracking-tight">APME · Admin</div>
        </a>

        {{-- NAV TOPBAR (desktop) --}}
        <nav class="hidden md:flex items-center gap-6 text-[14px]">
          <a href="{{ route('admin.pages.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
            Páginas
          </a>
          <a href="{{ route('admin.communities.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8v8a2 2 0 0 1-2 2h-3v-6H8v6H5a2 2 0 0 1-2-2v-8z"/></svg>
            Comunidades
          </a>
          <a href="{{ route('admin.products.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4m16 10H4m0-10l2 10m14-10-2 10M7 7l1-3h8l1 3"/></svg>
            Productos
          </a>
          <a href="{{ route('admin.albums.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4zM8 9l3 3-3 3m5-6h3"/></svg>
            Álbumes
          </a>
          <a href="{{ route('admin.posts.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h7"/></svg>
            Noticias
          </a>

          {{-- NUEVO: Campañas --}}
          <a href="{{ route('admin.campaigns.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v12H5.5L4 18V4zM8 8h8M8 12h5"/>
            </svg>
            Campañas
          </a>

          {{-- NUEVO: Suscriptores --}}
          <a href="{{ route('admin.newsletter.index') }}" class="hover:opacity-100 opacity-90 inline-flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6l8 6 8-6v12H4V6zM4 6l8 6 8-6"/>
            </svg>
            Suscriptores
          </a>

          <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 font-semibold text-miel">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4h2l1 3 3 1-1 2 1 2-3 1-1 3h-2l-1-3-3-1 1-2-1-2 3-1 1-3z"/><circle cx="12" cy="12" r="2"/></svg>
            Ajustes
          </a>
        </nav>

        {{-- Acciones derechas --}}
        <div class="flex items-center gap-3 text-[14px]">
          <button id="btn-nav-mobile" class="md:hidden p-2 rounded-lg hover:bg-white/10" aria-label="Abrir menú">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <a href="{{ route('home') }}" class="opacity-90 hover:opacity-100 hidden md:inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 21V9h6v12"/></svg>
            Ir al sitio
          </a>
          <form method="POST" action="{{ route('logout') }}" class="hidden md:block">@csrf
            <button class="opacity-90 hover:opacity-100 inline-flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m12 0-4-4m4 4-4 4m6-10h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2"/></svg>
              Salir
            </button>
          </form>
        </div>
      </div>

      {{-- Drawer móvil --}}
      <div id="nav-mobile" class="md:hidden hidden border-t border-white/10 bg-borgo/95">
        <div class="max-w-[1320px] mx-auto px-5 py-3 grid gap-1 text-sm">
          <a href="{{ route('admin.pages.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Páginas</a>
          <a href="{{ route('admin.communities.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Comunidades</a>
          <a href="{{ route('admin.products.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Productos</a>
          <a href="{{ route('admin.albums.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Álbumes</a>
          <a href="{{ route('admin.posts.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Noticias</a>
          <a href="{{ route('admin.campaigns.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Campañas</a>
          <a href="{{ route('admin.newsletter.index') }}" class="px-3 py-2 rounded hover:bg-white/10">Suscriptores</a>
          <a href="{{ route('admin.settings.index') }}" class="px-3 py-2 rounded hover:bg-white/10 font-semibold text-miel">Ajustes</a>
          <div class="h-px bg-white/10 my-1"></div>
          <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-white/10">Ir al sitio</a>
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="w-full text-left px-3 py-2 rounded hover:bg-white/10">Salir</button>
          </form>
        </div>
      </div>
    </header>

    {{-- CONTENIDO (padding por topbar fijo) --}}
    <div class="flex-1 pt-14">
      <div class="mx-auto max-w-[1320px] px-5 py-6 grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-6">

        {{-- SIDEBAR (desktop) --}}
        <aside class="hidden lg:block">
          @php
            $items = [
              ['label'=>'Panel','route'=>'admin.dashboard','match'=>'admin.dashboard','icon'=>'M3 12l9-9 9 9M5 10v10h14V10'],
              ['label'=>'Páginas','route'=>'admin.pages.index','match'=>'admin.pages.*','icon'=>'M3 6h18M3 12h18M3 18h18'],
              ['label'=>'Comunidades','route'=>'admin.communities.index','match'=>'admin.communities.*','icon'=>'M3 12l9-8 9 8v8H3z'],
              ['label'=>'Productos','route'=>'admin.products.index','match'=>'admin.products.*','icon'=>'M7 7l1-3h8l1 3M4 7h16M4 17h16'],
              ['label'=>'Álbumes','route'=>'admin.albums.index','match'=>'admin.albums.*','icon'=>'M4 5h16v14H4z'],
              ['label'=>'Noticias','route'=>'admin.posts.index','match'=>'admin.posts.*','icon'=>'M4 6h16M4 12h10M4 18h7'],

              // NUEVOS
              ['label'=>'Campañas','route'=>'admin.campaigns.index','match'=>'admin.campaigns.*','icon'=>'M4 4h16v12H5.5L4 18V4M8 8h8M8 12h5'],
              ['label'=>'Suscriptores','route'=>'admin.newsletter.index','match'=>'admin.newsletter.*','icon'=>'M4 6l8 6 8-6v12H4V6z'],

              ['label'=>'Aliados','route'=>'admin.partners.index','match'=>'admin.partners.*','icon'=>'M8 11a4 4 0 1 1 8 0v2h1a3 3 0 0 1 3 3v3H4v-3a3 3 0 0 1 3-3h1v-2z'],
              ['label'=>'Ajustes','route'=>'admin.settings.index','match'=>'admin.settings.*','icon'=>'M11 4h2l1 3 3 1-1 2 1 2-3 1-1 3h-2l-1-3-3-1 1-2-1-2 3-1 1-3z'],
            ];
          @endphp
          <nav class="sticky top-20">
            <div class="space-y-1">
              @foreach($items as $it)
                @php $active = request()->routeIs($it['match']); @endphp
                <a href="{{ route($it['route']) }}"
                   class="px-3 py-2 rounded-lg block text-sm transition border
                     {{ $active ? 'bg-white border-slate-200 font-medium shadow-sm'
                                : 'border-transparent hover:bg-white hover:border-slate-200' }}">
                  <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="{{ $it['icon'] }}" />
                    </svg>
                    <span>{{ $it['label'] }}</span>
                  </span>
                </a>
              @endforeach
            </div>
          </nav>
        </aside>

        {{-- MAIN --}}
        <main class="min-w-0">
          @hasSection('header')
            <div class="mb-5">
              @yield('header')
            </div>
          @endif

          <div class="bg-white rounded-xl border shadow-sm p-6">
            @yield('content')
          </div>
        </main>
      </div>
    </div>

    {{-- FOOTER --}}
    <footer class="shrink-0 py-5 text-center text-xs text-slate-500 border-t">
      © {{ date('Y') }} APME · Panel de administración
    </footer>
  </div>

  {{-- JS: toggle menú móvil --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('btn-nav-mobile');
      const nav = document.getElementById('nav-mobile');
      btn?.addEventListener('click', () => nav?.classList.toggle('hidden'));
    });
  </script>
</body>
</html>
