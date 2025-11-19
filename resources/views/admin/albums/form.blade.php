@extends('layouts.admin')
@section('title', ($album->exists?'Editar':'Nuevo').' álbum · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($album->exists?'Editar':'Nuevo') }}</h1>
@endsection

@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif

  @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="post" enctype="multipart/form-data" action="{{ $album->exists?route('admin.albums.update',$album):route('admin.albums.store') }}">
    @csrf @if($album->exists) @method('PUT') @endif

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1">Título</label>
        <input name="title" value="{{ old('title',$album->title) }}" class="w-full border rounded p-2" required>
      </div>
      <div>
        <label class="block text-sm mb-1">Slug</label>
        <input name="slug" value="{{ old('slug',$album->slug) }}" class="w-full border rounded p-2" required>
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

    <div class="mt-6 bg-white border rounded-xl p-4">
      <label class="block text-sm mb-2">Agregar fotos (múltiples)</label>
      <input type="file" name="photos[]" multiple accept="image/*">
      <p class="text-xs text-slate-500 mt-1">Puedes seleccionar varias imágenes a la vez (máx. 8MB c/u).</p>
    </div>

    @if($album->exists && $album->photos->count())
      <div class="mt-6 bg-white border rounded-xl p-4">
        <div class="font-semibold mb-3">Fotos del álbum</div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left">
              <tr>
                <th class="p-2">Preview</th>
                <th class="p-2">Leyenda</th>
                <th class="p-2 w-24">Orden</th>
                <th class="p-2 w-20">Eliminar</th>
              </tr>
            </thead>
            <tbody>
              @foreach($album->photos as $ph)
                <tr class="border-t align-top">
                  <td class="p-2">
                    <img src="{{ $ph->path }}" class="w-24 h-16 object-cover rounded border">
                  </td>
                  <td class="p-2">
                    <input type="text" name="photos_update[{{ $ph->id }}][caption]" value="{{ old('photos_update.'.$ph->id.'.caption', $ph->caption) }}" class="w-full border rounded p-2" placeholder="Leyenda (opcional)">
                  </td>
                  <td class="p-2">
                    <input type="number" name="photos_update[{{ $ph->id }}][order]" value="{{ old('photos_update.'.$ph->id.'.order', $ph->order) }}" class="w-20 border rounded p-2">
                  </td>
                  <td class="p-2">
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" name="photos_update[{{ $ph->id }}][delete]" value="1">
                      <span class="text-red-600">Quitar</span>
                    </label>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <p class="text-xs text-slate-500 mt-2">* Cambia el orden a números (1,2,3…). Marca “Quitar” para eliminar.</p>
      </div>
    @endif

    {{-- Enviar al boletín al guardar --}}
    <label class="inline-flex items-center gap-2 mt-4">
      <input type="checkbox" name="send_to_newsletter" value="1">
      Enviar al boletín al guardar
    </label>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.albums.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
