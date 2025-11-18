@extends('layouts.admin')
@section('title', ($community->exists?'Editar':'Nueva').' comunidad · Admin')

@section('header')
  <h1 class="text-xl font-bold">{{ ($community->exists?'Editar':'Nueva') }}</h1>
@endsection

@section('content')
<div class="max-w-[1000px] mx-auto px-5 py-10">
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

  <form method="post" enctype="multipart/form-data"
        action="{{ $community->exists?route('admin.communities.update',$community):route('admin.communities.store') }}">
    @csrf @if($community->exists) @method('PUT') @endif

    {{-- Básicos --}}
    <div class="bg-white border rounded-xl p-5 space-y-4">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Nombre</label>
          <input name="name" value="{{ old('name',$community->name) }}" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="block text-sm mb-1">Slug</label>
          <input name="slug" value="{{ old('slug',$community->slug) }}" class="w-full border rounded p-2" required>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Provincia</label>
          <input name="province" value="{{ old('province',$community->province) }}" class="w-full border rounded p-2">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Latitud</label>
            <input name="latitude" type="number" step="any" value="{{ old('latitude',$community->latitude) }}" class="w-full border rounded p-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Longitud</label>
            <input name="longitude" type="number" step="any" value="{{ old('longitude',$community->longitude) }}" class="w-full border rounded p-2">
          </div>
        </div>
      </div>

      <label class="block text-sm mb-1">Descripción corta</label>
      <textarea name="description" rows="4" class="w-full border rounded p-2">{{ old('description',$community->description) }}</textarea>
    </div>

    {{-- Hero --}}
    <section class="bg-white border rounded-xl p-5 mt-6">
      <div class="font-semibold mb-4">Portada (Hero)</div>
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm mb-1">Título</label>
          <input class="w-full border rounded p-2" name="hero_title" value="{{ old('hero_title',$community->hero_title) }}">
          <label class="block text-sm mt-4 mb-1">Subtítulo</label>
          <input class="w-full border rounded p-2" name="hero_subtitle" value="{{ old('hero_subtitle',$community->hero_subtitle) }}">
          <label class="block text-sm mt-4 mb-1">Imagen (opcional)</label>
          <input type="file" name="hero_image" id="heroImageInput" accept="image/*">
        </div>

        <div>
          <div class="text-sm text-slate-600 mb-2">Vista previa</div>
          @php
            $heroPath = old('hero_image_path'); // por si quieres propagar
            $current  = $community->hero_image;
            $preview  = $heroPath ?: $current;
          @endphp
          <div class="aspect-[16/9] rounded-xl overflow-hidden border bg-slate-50">
            @if($preview)
              <img id="heroPreview" src="{{ $preview }}" class="w-full h-full object-cover" alt="">
            @else
              <img id="heroPreview" class="w-full h-full object-cover hidden" alt="">
              <div id="heroEmpty" class="w-full h-full grid place-items-center text-slate-400">Sin imagen</div>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- About / Blocks --}}
    <section class="bg-white border rounded-xl p-5 mt-6 space-y-4">
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm mb-1">Sobre la comunidad (HTML)</label>
          <textarea name="about_html" rows="10" class="w-full border rounded p-2">{{ old('about_html',$community->about_html) }}</textarea>
          <p class="text-xs text-slate-500 mt-1">Puedes pegar HTML simple (p, h2, img, ul/ol…).</p>
        </div>
        <div>
          <label class="block text-sm mb-1">Bloques (JSON)</label>
          <textarea name="blocks" rows="10" class="w-full border rounded p-2" placeholder='[{"type":"text","title":"Título","body":"..."},{"type":"gallery","images":["/a.jpg","/b.jpg"]}]'>{{ old('blocks', $community->blocks ? json_encode($community->blocks, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
          <p class="text-xs text-slate-500 mt-1">Dejar vacío si no se usará. Debe ser JSON válido.</p>
        </div>
      </div>
    </section>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.communities.index') }}">Volver</a>
    </div>
  </form>
</div>

<script>
document.getElementById('heroImageInput')?.addEventListener('change', (e) => {
  const file = e.target.files?.[0];
  if (!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('heroPreview');
  const empty = document.getElementById('heroEmpty');
  if (empty) empty.classList.add('hidden');
  img.src = url;
  img.classList.remove('hidden');
});
</script>
@endsection
