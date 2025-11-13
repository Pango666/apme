@extends('layouts.admin')
@section('title', ($album->exists?'Editar':'Nuevo').' álbum · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($album->exists?'Editar':'Nuevo') }}</h1>
@endsection
@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  <form method="post" enctype="multipart/form-data" action="{{ $album->exists?route('admin.albums.update',$album):route('admin.albums.store') }}">
    @csrf @if($album->exists) @method('PUT') @endif

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1">Título</label>
        <input name="title" value="{{ old('title',$album->title) }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block text-sm mb-1">Slug</label>
        <input name="slug" value="{{ old('slug',$album->slug) }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block text-sm mb-1">Tipo</label>
        <select name="type" class="w-full border rounded p-2">
          @foreach(['feria','portafolio','galeria'] as $t)
          <option value="{{ $t }}" @selected(old('type',$album->type)==$t)>{{ ucfirst($t) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm mb-1">Fecha</label>
        <input type="date" name="date" value="{{ old('date',optional($album->date)->format('Y-m-d')) }}" class="w-full border rounded p-2">
      </div>
    </div>

    <label class="block text-sm mt-4 mb-1">Lugar</label>
    <input name="place" value="{{ old('place',$album->place) }}" class="w-full border rounded p-2">

    <label class="block text-sm mt-4 mb-1">Resumen</label>
    <textarea name="summary" rows="5" class="w-full border rounded p-2">{{ old('summary',$album->summary) }}</textarea>

    <label class="block text-sm mt-4 mb-1">Agregar fotos (múltiples)</label>
    <input type="file" name="photos[]" multiple>

    @if($album->exists && $album->photos->count())
      <div class="grid grid-cols-3 gap-4 mt-4">
        @foreach($album->photos as $ph)
          <div class="border rounded overflow-hidden">
            <img src="{{ $ph->path }}" class="w-full aspect-[4/3] object-cover" alt="">
            <div class="p-2 text-xs">{{ $ph->caption }}</div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.albums.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
