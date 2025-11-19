@extends('layouts.admin')
@section('title','Campañas · Admin')

@section('header')
  <div class="flex items-center justify-between">
    <h1 class="text-xl font-bold">Campañas</h1>
    <div class="text-sm text-slate-500">
      Suscriptores activos: <span class="font-semibold">{{ $subCount }}</span>
    </div>
  </div>
@endsection

@section('content')
@if(session('ok'))
  <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
@endif
@if($errors->any())
  <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm">
    <ul class="list-disc pl-5">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

{{-- Crear rápida --}}
<div class="mb-6">
  <details class="bg-slate-50 border rounded-lg">
    <summary class="px-4 py-3 cursor-pointer font-medium">Crear campaña rápida</summary>
    <div class="p-4 grid md:grid-cols-2 gap-4">
      <form class="col-span-2 grid md:grid-cols-2 gap-4" method="post" action="{{ route('admin.campaigns.store') }}">
        @csrf
        <div>
          <label class="block text-sm mb-1">Nombre</label>
          <input name="name" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="block text-sm mb-1">Asunto</label>
          <input name="subject" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="block text-sm mb-1">Preheader (opcional)</label>
          <input name="preheader" class="w-full border rounded p-2">
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm mb-1">HTML</label>
          <textarea name="html" rows="8" class="w-full border rounded p-2" required>
<!doctype html>
<html><body>
  <h2>{{ config('app.name') }}</h2>
  <p>Contenido de la campaña.</p>
</body></html>
          </textarea>
        </div>
        <div class="md:col-span-2">
          <button class="px-4 py-2 bg-borgo text-white rounded">Crear</button>
        </div>
      </form>
    </div>
  </details>
</div>

{{-- Listado --}}
<div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-left">
      <tr>
        <th class="p-2">ID</th>
        <th class="p-2">Nombre</th>
        <th class="p-2">Asunto</th>
        <th class="p-2">Estado</th>
        <th class="p-2">Prog.</th>
        <th class="p-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($campaigns as $c)
        <tr class="border-t">
          <td class="p-2">{{ $c->id }}</td>
          <td class="p-2">{{ $c->name }}</td>
          <td class="p-2">{{ $c->subject }}</td>
          <td class="p-2">
            <span class="inline-block px-2 py-0.5 rounded text-xs
              {{ $c->status==='sent' ? 'bg-green-100 text-green-700' :
                 ($c->status==='sending' ? 'bg-amber-100 text-amber-700' :
                 ($c->status==='failed' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')) }}">
              {{ $c->status }}
            </span>
          </td>
          <td class="p-2">{{ optional($c->scheduled_at)->format('Y-m-d H:i') ?: '-' }}</td>
          <td class="p-2">
            <form class="inline" method="post" action="{{ route('admin.campaigns.sync',$c) }}">
              @csrf
              <button class="px-3 py-1.5 border rounded hover:bg-slate-50">Sincronizar</button>
            </form>
            <form class="inline" method="post" action="{{ route('admin.campaigns.send',$c) }}">
              @csrf
              <button class="px-3 py-1.5 bg-borgo text-white rounded">Enviar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td class="p-3 text-slate-500" colspan="6">Sin campañas.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $campaigns->links() }}
</div>
@endsection
