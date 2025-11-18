@extends('layouts.app')
@section('title','Qué hacemos · '.$community->name.' · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-10">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Qué hacemos — {{ $community->name }}</h1>

  @if($community->about_html)
    <div class="prose max-w-none mt-6">{!! $community->about_html !!}</div>
  @elseif($community->description)
    <p class="mt-6 text-slate-700">{{ $community->description }}</p>
  @else
    <p class="mt-6 text-slate-600">Sin información cargada.</p>
  @endif

  @if(!empty($community->blocks) && is_array($community->blocks))
    <div class="mt-10 space-y-10">
      @include('communities.partials.blocks', ['community' => $community])
    </div>
  @endif
</div>
@endsection
