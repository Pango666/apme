@extends('layouts.app')
@section('title','Apoya · '.$community->name.' · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-10">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Apoya — {{ $community->name }}</h1>
  <p class="mt-4 text-slate-700">
    Si deseas apoyar a {{ $community->name }}, contáctanos para coordinar donaciones o colaboración técnica.
  </p>
  <div class="mt-6 rounded-xl border bg-white p-5">
    <div class="font-semibold">Contacto APME</div>
    <div class="text-sm text-slate-600 mt-1">WhatsApp: +591 681 86701 · Email: info@apme.bo</div>
  </div>
</div>
@endsection
