@extends('layouts.app')
@section('title','Inicio · APME')

@section('content')
@php
  // ========= Settings y fallbacks =========
  $settings  = $settings ?? [];
  $hero      = $hero ?? [];
  $heroTitle = $hero['title']    ?? ($settings['hero.title']    ?? 'Miel justa de nuestras comunidades');
  $heroSub   = $hero['subtitle'] ?? ($settings['hero.subtitle'] ?? 'Asociación de Productores de Miel Ecológica');
  $heroImg   = $hero['image']    ?? ($settings['hero.image']    ?? null);

  // Normaliza ruta local -> URL pública
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

{{-- ================= HERO (sin recorte, fondo blur) ================= --}}
<section class="relative w-full h-[62vh] md:h-[68vh] min-h-[520px] overflow-hidden">
  {{-- Capa de relleno: blur + cover para ocupar todo --}}
  <img
      src="{{ $heroImg }}"
      alt=""
      class="absolute inset-0 w-full h-full object-cover scale-105 blur-sm opacity-60"
      aria-hidden="true"
  />

  {{-- Imagen principal sin recortes --}}
  <img
      src="{{ $heroImg }}"
      alt="Portada"
      class="absolute inset-0 w-full h-full object-contain z-10"
      style="object-position:center"
  />

  {{-- Oscurecido para legibilidad --}}
  <div class="absolute inset-0 bg-black/35 z-10"></div>

  {{-- Contenido --}}
  <div class="relative z-20 max-w-[1200px] mx-auto px-5 h-full flex flex-col justify-center">
    <h1 class="font-display text-white drop-shadow text-4xl md:text-6xl font-extrabold leading-tight"
        data-aos="fade-up">
      {{ $heroTitle }}
    </h1>
    <p class="text-white/90 max-w-2xl mt-3" data-aos="fade-up" data-aos-delay="100">{{ $heroSub }}</p>
    <div class="mt-6 flex gap-3" data-aos="fade-up" data-aos-delay="200">
      <a href="{{ route('page.show','quienes-somos') }}"
         class="bg-white text-borgo font-semibold px-4 py-2 rounded-full hover:brightness-110">Conócenos</a>
      <a href="{{ route('productos.index') }}"
         class="bg-miel text-tinta font-semibold px-4 py-2 rounded-full hover:brightness-110">Ver productos</a>
    </div>
  </div>
</section>

{{-- ============== QUÉ HACEMOS (tarjetas con icono) ============== --}}
<section class="max-w-[1200px] mx-auto px-5 py-10">
  <div class="grid md:grid-cols-4 gap-5">
    {{-- 1 --}}
    <article class="bg-white rounded-xl shadow-sm border p-6" data-aos="fade-up">
      <div class="w-12 h-12 rounded-xl grid place-items-center bg-miel/20 text-borgo mb-3">
        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><path d="M3 7h8V3H3v4Zm0 7h8V10H3v4Zm10 0h8V10h-8v4Zm0 7h8v-4h-8v4ZM3 24h8v-4H3v4Z"/></svg>
      </div>
      <h3 class="font-semibold text-borgo">Calidad y trazabilidad</h3>
      <p class="text-sm text-slate-600 mt-1">Promovemos estándares para miel, polen y derivados, con enfoque en trazabilidad.</p>
      @if($mision)
        <a href="{{ route('page.show',$mision->slug) }}" class="mt-3 inline-block text-borgo font-medium">Ver más →</a>
      @endif
    </article>

    {{-- 2 --}}
    <article class="bg-white rounded-xl shadow-sm border p-6" data-aos="fade-up" data-aos-delay="100">
      <div class="w-12 h-12 rounded-xl grid place-items-center bg-hoja/15 text-hoja mb-3">
        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><path d="M12 2C7.58 2 4 5.58 4 10v4a8 8 0 0 0 16 0v-4c0-4.42-3.58-8-8-8Zm0 4a4 4 0 0 1 4 4v4a4 4 0 0 1-8 0v-4a4 4 0 0 1 4-4Z"/></svg>
      </div>
      <h3 class="font-semibold text-borgo">Apoyo al productor</h3>
      <p class="text-sm text-slate-600 mt-1">Asesoría, organización y defensa del productor apícola local.</p>
      @if($qs)
        <a href="{{ route('page.show',$qs->slug) }}" class="mt-3 inline-block text-borgo font-medium">Conócenos →</a>
      @endif
    </article>

    {{-- 3 --}}
    <article class="bg-white rounded-xl shadow-sm border p-6" data-aos="fade-up" data-aos-delay="200">
      <div class="w-12 h-12 rounded-xl grid place-items-center bg-ambar/20 text-ambar mb-3">
        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><path d="M19 2H8a3 3 0 0 0-3 3v15a2 2 0 0 0 2 2h13V2Zm-2 15H8a1 1 0 0 0-1 1V5a1 1 0 0 1 1-1h9v13Z"/></svg>
      </div>
      <h3 class="font-semibold text-borgo">Formación y unidad</h3>
      <p class="text-sm text-slate-600 mt-1">Buenas prácticas, inocuidad y capacitación técnica para apicultores.</p>
      @if($vision)
        <a href="{{ route('page.show',$vision->slug) }}" class="mt-3 inline-block text-borgo font-medium">Nuestra visión →</a>
      @endif
    </article>

    {{-- 4 --}}
    <article class="bg-white rounded-xl shadow-sm border p-6" data-aos="fade-up" data-aos-delay="300">
      <div class="w-12 h-12 rounded-xl grid place-items-center bg-borgo/15 text-borgo mb-3">
        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><path d="M12 12a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm-9 8a7 7 0 0 1 14 0H3Z"/></svg>
      </div>
      <h3 class="font-semibold text-borgo">Compromiso social</h3>
      <p class="text-sm text-slate-600 mt-1">Comercio justo y desarrollo comunitario en las regiones productoras.</p>
      <a href="{{ route('contacto') }}" class="mt-3 inline-block text-borgo font-medium">Contacto →</a>
    </article>
  </div>
</section>

{{-- ============== SOBRE APME (split) ============== --}}
<section class="bg-borgo text-white relative">
  <div class="absolute inset-0 opacity-[.05] bg-[radial-gradient(#fff_0.6px,transparent_0.7px)] bg-[length:14px_14px]"></div>
  <div class="relative max-w-[1200px] mx-auto px-5 py-12 grid md:grid-cols-2 gap-10 items-center">
    <div data-aos="fade-right">
      <h2 class="font-display text-3xl md:text-4xl font-extrabold">
        Hoy tenemos la fuerza para defender nuestro <span class="text-miel">mañana</span> APME
      </h2>
      <p class="mt-4 text-white/90">
        {{ $qs?->excerpt ?? 'Caminamos junto a los productores de miel, promoviendo unidad, mercados justos y respeto por nuestra cultura.' }}
      </p>
      <ul class="mt-5 space-y-2 text-white/90">
        <li>✔️ Comercio justo y trazabilidad.</li>
        <li>✔️ Defensa de la apicultura sostenible.</li>
        <li>✔️ Capacitación técnica y seguridad alimentaria.</li>
        <li>✔️ Presencia activa en ferias y actividades.</li>
      </ul>
      <div class="mt-6 flex gap-3">
        <a href="{{ route('page.show','quienes-somos') }}" class="px-4 py-2 rounded-full bg-miel text-tinta font-semibold hover:brightness-110">Leer más</a>
        <a href="{{ route('albums.index') }}" class="px-4 py-2 rounded-full border border-white/40 hover:bg-white/10">Ferias y galería</a>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
      <div class="rounded-xl overflow-hidden shadow border border-white/10">
        <img src="{{ $heroImg }}" class="w-full h-full object-cover" alt="">
      </div>
      <div class="rounded-xl overflow-hidden shadow border border-white/10 translate-y-6">
        <img src="{{ $heroImg }}" class="w-full h-full object-cover" alt="">
      </div>
    </div>
  </div>
</section>

{{-- ============== COMUNIDADES (grid) ============== --}}
<section class="max-w-[1200px] mx-auto px-5 py-12">
  <div class="flex items-center justify-between">
    <h2 class="font-display text-2xl font-extrabold text-borgo" data-aos="fade-up">Comunidades</h2>
    <a href="{{ route('comunidades.index') }}" class="text-sm text-borgo/80 hover:text-borgo font-medium" data-aos="fade-up" data-aos-delay="100">Ver todas →</a>
  </div>
  <div class="grid md:grid-cols-3 gap-6 mt-4">
    @forelse($comunidades as $c)
      <a href="{{ route('comunidades.show',$c->slug) }}"
         class="border rounded-xl p-5 hover:shadow bg-white transition"
         data-aos="fade-up">
        <div class="font-semibold">{{ $c->name }}</div>
        <div class="text-sm text-slate-600">{{ $c->province }}</div>
      </a>
    @empty
      <div class="text-slate-500">Aún no hay comunidades registradas.</div>
    @endforelse
  </div>
</section>

{{-- ============== PRODUCTOS (carrusel) ============== --}}
<section class="max-w-[1200px] mx-auto px-5 pb-12">
  <div class="flex items-center justify-between">
    <h2 class="font-display text-2xl font-extrabold text-borgo" data-aos="fade-up">Productos</h2>
    <div class="flex gap-2" data-aos="fade-up" data-aos-delay="100">
      <button class="carousel-btn" data-target="prod" data-dir="prev">‹</button>
      <button class="carousel-btn" data-target="prod" data-dir="next">›</button>
    </div>
  </div>
  <div id="carousel-prod" class="mt-4 overflow-x-auto no-scrollbar snap-x snap-mandatory">
    <div class="flex gap-6 w-max">
      @forelse($productos as $p)
        <a href="{{ route('productos.show',$p) }}"
           class="w-[300px] shrink-0 snap-start rounded-xl border p-5 hover:shadow bg-white transition"
           data-aos="fade-up">
          <div class="text-xs uppercase text-slate-500">{{ $p->type }}</div>
          <div class="font-semibold">{{ $p->name }}</div>
          @if($p->price_bs)
            <div class="text-borgo font-bold mt-1">{{ number_format($p->price_bs,2) }} Bs</div>
          @endif
        </a>
      @empty
        <div class="text-slate-500">Aún no hay productos.</div>
      @endforelse
    </div>
  </div>
</section>

{{-- ============== FERIAS (tarjetas horizontales) ============== --}}
<section class="bg-crema/40">
  <div class="max-w-[1200px] mx-auto px-5 py-12">
    <div class="flex items-center justify-between">
      <h2 class="font-display text-2xl font-extrabold text-borgo" data-aos="fade-up">Ferias</h2>
      <div class="flex gap-2" data-aos="fade-up" data-aos-delay="100">
        <button class="carousel-btn" data-target="ferias" data-dir="prev">‹</button>
        <button class="carousel-btn" data-target="ferias" data-dir="next">›</button>
      </div>
    </div>
    <div id="carousel-ferias" class="mt-4 overflow-x-auto no-scrollbar snap-x snap-mandatory">
      <div class="flex gap-6 w-max">
        @forelse($ferias as $f)
          <a href="{{ route('albums.show',$f->slug) }}"
             class="snap-start w-[480px] md:w-[560px] shrink-0 rounded-xl bg-white border hover:shadow transition overflow-hidden"
             data-aos="fade-up">
            <div class="flex items-center gap-4 p-4">
              <div class="w-16 h-16 rounded-full overflow-hidden border">
                <img src="{{ $f->photos->first()->path ?? '/placeholder.webp' }}" class="w-full h-full object-cover" alt="">
              </div>
              <div class="min-w-0">
                <div class="font-semibold text-borgo truncate">{{ $f->title }}</div>
                <div class="text-sm text-slate-600">
                  {{ $f->place ?? '—' }}
                  @if($f->date) · {{ \Illuminate\Support\Carbon::parse($f->date)->isoFormat('DD MMM YYYY') }} @endif
                </div>
              </div>
              <span class="ml-auto px-3 py-1 rounded-full text-xs bg-miel text-tinta font-semibold">Ver más</span>
            </div>
          </a>
        @empty
          <div class="text-slate-600">Aún no hay ferias registradas.</div>
        @endforelse
      </div>
    </div>
  </div>
</section>

{{-- ============== NOTICIAS (grid) ============== --}}
<section class="max-w-[1200px] mx-auto px-5 py-12">
  <h2 class="font-display text-2xl font-extrabold text-borgo" data-aos="fade-up">Noticias</h2>
  <div class="grid md:grid-cols-3 gap-6 mt-4">
    @forelse($posts as $post)
      <a href="{{ route('noticias.show',$post->slug) }}"
         class="rounded-xl border bg-white overflow-hidden hover:shadow transition"
         data-aos="fade-up">
        <div class="aspect-[16/9]">
          <img src="{{ $post->cover_path ?? '/placeholder.webp' }}" class="w-full h-full object-cover" alt="">
        </div>
        <div class="p-5">
          <h3 class="font-semibold">{{ $post->title }}</h3>
          <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $post->excerpt }}</p>
        </div>
      </a>
    @empty
      <div class="text-slate-500">Aún no hay noticias publicadas.</div>
    @endforelse
  </div>
</section>

{{-- ============== ALIADOS (grid) ============== --}}
<section class="max-w-[1200px] mx-auto px-5 pb-14">
  <h2 class="font-display text-2xl font-extrabold text-borgo" data-aos="fade-up">Aliados</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4">
    @forelse($partners as $pr)
      <a href="{{ $pr->url ?? '#' }}"
         class="border rounded-xl bg-white p-6 grid place-items-center hover:shadow transition"
         data-aos="fade-up">
        <img src="{{ $pr->logo_path ?? '/placeholder.webp' }}" class="h-12 object-contain" alt="{{ $pr->name }}">
      </a>
    @empty
      <div class="text-slate-500">Aún no hay aliados registrados.</div>
    @endforelse
  </div>
</section>

{{-- ===== Estilos mínimos & scripts (carrusel + AOS/fallback) ===== --}}
<style>
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .carousel-btn{
    @apply w-8 h-8 grid place-items-center rounded-full border text-borgo bg-white hover:bg-slate-50;
  }

  /* Fallback simple si no hay AOS: .aos-init -> fade-up */
  .aos-init{opacity:0; transform:translateY(12px); transition:opacity .6s ease, transform .6s ease;}
  .aos-animate{opacity:1; transform:none;}
</style>

<script>
  // Carruseles
  document.querySelectorAll('.carousel-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id  = btn.dataset.target;
      const dir = btn.dataset.dir === 'next' ? 1 : -1;
      const el  = document.getElementById('carousel-' + id);
      if (!el) return;
      const step = el.clientWidth * 0.85;
      el.scrollBy({ left: dir * step, behavior: 'smooth' });
    });
  });

  // AOS si está cargado (ya instalaste dependencias)
  if (window.AOS) {
    AOS.init({ once: true, duration: 700, easing: 'ease-out-cubic' });
  } else {
    // Fallback: aplica una animación básica
    const els = document.querySelectorAll('[data-aos]');
    els.forEach(el => el.classList.add('aos-init'));
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) e.target.classList.add('aos-animate');
      });
    }, {threshold: 0.12});
    els.forEach(el => io.observe(el));
  }
</script>
@endsection
