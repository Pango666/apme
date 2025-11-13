@extends('layouts.app')
@section('title', $album->title.' · APME')
@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-12">
  <h1 class="text-2xl font-display font-extrabold text-borgo">{{ $album->title }}</h1>
  <p class="text-slate-600">{{ $album->place }} @if($album->date) · {{ $album->date->format('d M Y') }}@endif</p>
  @if($album->summary)<p class="mt-2">{{ $album->summary }}</p>@endif

  <div class="grid md:grid-cols-3 gap-6 mt-6">
    @foreach($album->photos as $ph)
      <a href="{{ $ph->path }}" target="_blank" class="block aspect-[4/3] rounded-xl overflow-hidden border hover:shadow">
        <img src="{{ $ph->path }}" class="w-full h-full object-cover" alt="{{ $ph->caption }}">
      </a>
    @endforeach
  </div>
</div>
@endsection
