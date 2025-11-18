@extends('layouts.app')
@section('title', $album->title.' · APME')

@section('content')
  {{-- HERO de la feria si tiene hero_image; si no, solo título --}}
  @php $heroUrl = $album->hero_image_url; @endphp
  @if($heroUrl)
    <section class="relative w-full h-[36vh] min-h-[320px]">
      <img src="{{ $heroUrl }}" class="absolute inset-0 w-full h-full object-cover" alt="Hero {{ $album->title }}">
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="relative z-10 max-w-[1200px] mx-auto px-5 h-full flex flex-col justify-end pb-6">
        <h1 class="font-display text-white text-3xl md:text-4xl font-extrabold drop-shadow">{{ $album->hero_title ?: $album->title }}</h1>
        @if($album->hero_subtitle)
          <p class="text-white/90">{{ $album->hero_subtitle }}</p>
        @endif
      </div>
    </section>
  @else
    <div class="max-w-[1200px] mx-auto px-5 pt-8">
      <h1 class="text-2xl md:text-3xl font-display font-extrabold text-borgo">{{ $album->title }}</h1>
    </div>
  @endif

  <div class="max-w-[1200px] mx-auto px-5 py-8">
    {{-- meta --}}
    <p class="text-slate-600">
      {{ $album->place ?? '—' }}
      @if($album->date) · {{ $album->date->format('d M Y') }} @endif
    </p>
    @if($album->summary)
      <p class="mt-2 text-slate-700">{{ $album->summary }}</p>
    @endif

    {{-- about_html si existe --}}
    @if($album->about_html)
      <div class="prose max-w-none mt-6">
        {!! $album->about_html !!}
      </div>
    @endif

    {{-- galería --}}
    @if($album->photos->count())
      <div class="grid md:grid-cols-3 gap-6 mt-8">
        @foreach($album->photos as $ph)
          <a href="{{ $ph->url }}" target="_blank"
             class="block aspect-[4/3] rounded-xl overflow-hidden border hover:shadow transition"
             aria-label="{{ $ph->caption }}">
            <img src="{{ $ph->url }}" class="w-full h-full object-cover" alt="{{ $ph->caption }}">
          </a>
        @endforeach
      </div>
    @else
      <div class="mt-6 text-slate-600">Aún no hay fotos para este álbum.</div>
    @endif
  </div>
@endsection
