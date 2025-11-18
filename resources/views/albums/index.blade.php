@extends('layouts.app')
@section('title','Ferias · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-12">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Ferias</h1>

  @if($items->count() === 0)
    <div class="mt-6 text-slate-600">Aún no hay ferias registradas.</div>
  @else
    <div class="grid md:grid-cols-3 gap-6 mt-6">
      @foreach($items as $a)
        <a href="{{ route('albums.show',$a->slug) }}"
           class="rounded-xl border bg-white overflow-hidden hover:shadow transition">
          <div class="aspect-[4/3]">
            <img src="{{ $a->cover_url }}" class="w-full h-full object-cover" alt="{{ $a->title }}">
          </div>
          <div class="p-4">
            <div class="font-semibold text-borgo truncate">{{ $a->title }}</div>
            <div class="text-sm text-slate-600">
              {{ $a->place ?? '—' }}
              @if($a->date) · {{ $a->date->format('d M Y') }} @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $items->links() }}
    </div>
  @endif
</div>
@endsection
