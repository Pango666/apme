@extends('layouts.app')
@section('title','Involúcrate · '.$community->name.' · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-10">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Involúcrate — {{ $community->name }}</h1>
  <p class="mt-4 text-slate-700">
    Puedes apoyar a la comunidad difundiendo sus productos, participando en ferias y promoviendo el consumo responsable.
  </p>
  <div class="mt-6 grid md:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-5">
      <h3 class="font-semibold text-borgo">Compra directa</h3>
      <p class="text-sm text-slate-600 mt-1">Adquiere sus productos certificados y fomenta el comercio justo.</p>
    </div>
    <div class="rounded-xl border bg-white p-5">
      <h3 class="font-semibold text-borgo">Ferias y eventos</h3>
      <p class="text-sm text-slate-600 mt-1">Participa en actividades, charlas y degustaciones locales.</p>
    </div>
  </div>
</div>
@endsection
