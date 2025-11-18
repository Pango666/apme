@extends('layouts.admin')
@section('title', ($product->exists?'Editar':'Nuevo').' producto · Admin')

@section('header')
  <h1 class="text-xl font-bold">{{ ($product->exists?$product->name:'Nuevo Producto') }}</h1>
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

  <form method="post"
        action="{{ $product->exists?route('admin.products.update',$product):route('admin.products.store') }}"
        enctype="multipart/form-data">
    @csrf @if($product->exists) @method('PUT') @endif

    {{-- Datos básicos --}}
    <section class="bg-white border rounded-xl p-5 mb-8">
      <div class="font-semibold mb-4">Datos básicos</div>

      <label class="block text-sm mb-1">Nombre</label>
      <input name="name" value="{{ old('name',$product->name) }}" class="w-full border rounded p-2" required>

      <label class="block text-sm mt-4 mb-1">Slug</label>
      <input name="slug" value="{{ old('slug',$product->slug) }}" class="w-full border rounded p-2" {{ $product->exists?'required':'' }}>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-sm mb-1">Tipo</label>
          <input name="type" value="{{ old('type',$product->type) }}" class="w-full border rounded p-2" placeholder="miel, polen, propóleo…">
        </div>
        <div>
          <label class="block text-sm mb-1">Precio (Bs)</label>
          <input name="price_bs" type="number" step="0.01" value="{{ old('price_bs',$product->price_bs) }}" class="w-full border rounded p-2">
        </div>
      </div>

      <label class="block text-sm mt-4 mb-1">Comunidad</label>
      <select name="community_id" class="w-full border rounded p-2">
        <option value="">— Sin comunidad —</option>
        @foreach($communities as $id=>$name)
          <option value="{{ $id }}" @selected(old('community_id',$product->community_id)==$id)>{{ $name }}</option>
        @endforeach
      </select>

      <label class="inline-flex items-center gap-2 mt-4">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->is_active))> Activo
      </label>

      <label class="block text-sm mt-4 mb-1">Descripción corta (opcional)</label>
      <textarea name="description" rows="4" class="w-full border rounded p-2">{{ old('description',$product->description) }}</textarea>
    </section>

    {{-- Landing · Hero --}}
    <section class="bg-white border rounded-xl p-5 mb-8">
      <div class="font-semibold mb-3">Landing · Hero</div>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Título</label>
          <input name="hero_title" value="{{ old('hero_title',$product->hero_title) }}" class="w-full border rounded p-2">

          <label class="block text-sm mt-4 mb-1">Subtítulo</label>
          <input name="hero_subtitle" value="{{ old('hero_subtitle',$product->hero_subtitle) }}" class="w-full border rounded p-2">

          <label class="block text-sm mt-4 mb-1">Imagen (opcional)</label>
          <input type="file" name="hero_image" id="heroInput" accept="image/*" class="block w-full">

          <div class="grid grid-cols-2 gap-3 mt-4">
            <div>
              <label class="block text-sm mb-1">Texto botón</label>
              <input name="hero_button_text" value="{{ old('hero_button_text',$product->hero_button_text) }}" class="w-full border rounded p-2" placeholder="Ej: Comprar ahora">
            </div>
            <div>
              <label class="block text-sm mb-1">URL botón</label>
              <input name="hero_button_url" value="{{ old('hero_button_url',$product->hero_button_url) }}" class="w-full border rounded p-2" placeholder="/contacto o https://...">
            </div>
          </div>

          <div class="grid md:grid-cols-3 gap-3 mt-4">
            <div>
              <label class="block text-sm mb-1">Color texto</label>
              <input name="hero_button_text_color" value="{{ old('hero_button_text_color',$product->hero_button_text_color) }}" class="w-full border rounded p-2" placeholder="#FFFFFF">
            </div>
            <div>
              <label class="block text-sm mb-1">Color fondo</label>
              <input name="hero_button_background_color" value="{{ old('hero_button_background_color',$product->hero_button_background_color) }}" class="w-full border rounded p-2" placeholder="#F59E0B">
            </div>
            <div>
              <label class="block text-sm mb-1">Color borde</label>
              <input name="hero_button_color" value="{{ old('hero_button_color',$product->hero_button_color) }}" class="w-full border rounded p-2" placeholder="#F59E0B">
            </div>
          </div>
        </div>

        <div>
          <div class="text-sm text-slate-600 mb-2">Vista previa</div>
          @php
            $heroPrev = $product->hero_image
              ? (\Illuminate\Support\Str::startsWith($product->hero_image,['http','/']) ? $product->hero_image : \Illuminate\Support\Facades\Storage::url($product->hero_image))
              : null;
          @endphp
          <div class="aspect-[16/9] rounded-xl overflow-hidden border bg-slate-50 grid place-items-center">
            @if($heroPrev)
              <img id="heroPreview" src="{{ $heroPrev }}" class="w-full h-full object-cover" alt="">
            @else
              <img id="heroPreview" class="w-full h-full object-cover hidden" alt="">
              <span id="heroEmpty" class="text-slate-400">Sin imagen</span>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- Contenido HTML --}}
    <section class="bg-white border rounded-xl p-5 mb-8">
      <div class="font-semibold mb-3">Contenido (HTML)</div>
      <textarea name="about_html" rows="10" class="w-full border rounded p-2" placeholder="<h2>Sobre el producto</h2><p>...">{!! old('about_html',$product->about_html) !!}</textarea>
      <p class="text-xs text-slate-500 mt-1">Admite HTML (títulos, párrafos, listas, imágenes con rutas públicas, etc.).</p>
    </section>

    {{-- Bloques (JSON) --}}
    <section class="bg-white border rounded-xl p-5 mb-8">
      <div class="font-semibold mb-3">Bloques (JSON)</div>
      <textarea name="blocks" rows="8" class="w-full border rounded p-2" placeholder='[{"title":"Calidad","text":"Miel cruda...","image":null}]'>{{ old('blocks', $product->blocks ? json_encode($product->blocks, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : '') }}</textarea>
      <p class="text-xs text-slate-500 mt-1">
        Estructura sugerida: arreglo de objetos con <code>title</code>, <code>text</code> e <code>image</code> (URL o path del storage).
      </p>
    </section>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.products.index') }}">Volver</a>
    </div>
  </form>
</div>

<script>
document.getElementById('heroInput')?.addEventListener('change', (e) => {
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
