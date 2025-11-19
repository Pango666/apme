@extends('layouts.admin')
@section('title', ($campaign->exists?'Editar':'Nueva').' campaña')
@section('header') <h1 class="text-xl font-bold">{{ $campaign->exists?'Editar':'Nueva' }} campaña</h1> @endsection
@section('content')
<div class="max-w-3xl mx-auto px-5 py-8">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  @if(session('err'))<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">{{ session('err') }}</div>@endif
  <form method="post" action="{{ $campaign->exists ? route('admin.newsletter.campaigns.update',$campaign) : route('admin.newsletter.campaigns.store') }}">
    @csrf @if($campaign->exists) @method('PUT') @endif

    <label class="block text-sm mb-1">Nombre interno</label>
    <input name="name" value="{{ old('name',$campaign->name) }}" class="w-full border rounded p-2">

    <div class="grid md:grid-cols-2 gap-4 mt-4">
      <div>
        <label class="block text-sm mb-1">Asunto</label>
        <input name="subject" value="{{ old('subject',$campaign->subject) }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block text-sm mb-1">Preheader (opcional)</label>
        <input name="preheader" value="{{ old('preheader',$campaign->preheader) }}" class="w-full border rounded p-2">
      </div>
    </div>

    <label class="block text-sm mt-4 mb-1">Contenido HTML</label>
    <textarea name="html" rows="14" class="w-full border rounded p-2" placeholder="Puedes usar {{ '{' }}{{ 'unsubscribe_url' }}{{ '}' }} para el enlace de baja.">{{ old('html',$campaign->html) }}</textarea>

    <label class="block text-sm mt-4 mb-1">Programar (opcional)</label>
    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}" class="border rounded p-2">

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      @if($campaign->exists && $campaign->status!=='sent')
        <form class="inline" method="post" action="{{ route('admin.newsletter.campaigns.send',$campaign) }}" onsubmit="return confirm('¿Enviar ahora?')">
          @csrf <button class="ml-3 px-4 py-2 bg-green-600 text-white rounded">Enviar ahora</button>
        </form>
      @endif
      <a class="ml-3 underline" href="{{ route('admin.newsletter.campaigns.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
