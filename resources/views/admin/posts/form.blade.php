@extends('layouts.admin')
@section('title', ($post->exists?'Editar':'Nueva').' noticia · Admin')

@section('header')
  <h1 class="text-xl font-bold">{{ $post->exists ? 'Editar Noticia' : 'Nueva Noticia' }}</h1>
@endsection

@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
  @endif

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
      <ul class="list-disc ml-5">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update',$post) : route('admin.posts.store') }}">
    @csrf
    @if($post->exists) @method('PUT') @endif

    <label class="block text-sm mb-1">Título</label>
    <input name="title" value="{{ old('title',$post->title) }}" class="w-full border rounded p-2">

    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input name="slug" value="{{ old('slug',$post->slug) }}" class="w-full border rounded p-2" placeholder="{{ $post->exists ? '' : 'se genera si dejas vacío' }}">

    <label class="block text-sm mt-4 mb-1">Resumen</label>
    <textarea name="excerpt" rows="3" class="w-full border rounded p-2">{{ old('excerpt',$post->excerpt) }}</textarea>

    <label class="block text-sm mt-4 mb-1">Contenido</label>
    <textarea name="body" rows="10" class="w-full border rounded p-2">{{ old('body',$post->body) }}</textarea>

    <div class="grid md:grid-cols-2 gap-6 mt-6">
      <div>
        <label class="block text-sm mb-1">Portada</label>
        <input type="file" name="cover" id="coverInput" accept="image/*">
        <div class="mt-3 aspect-video rounded border overflow-hidden bg-slate-50">
          @if($post->cover_path)
            <img id="coverPreview" src="{{ $post->cover_path }}" class="w-full h-full object-cover" alt="">
          @else
            <img id="coverPreview" class="w-full h-full object-cover hidden" alt="">
            <div id="coverEmpty" class="w-full h-full grid place-items-center text-slate-400 text-sm">Sin imagen</div>
          @endif
        </div>
        @if($post->cover_path)
          <label class="inline-flex items-center gap-2 mt-3">
            <input type="checkbox" name="remove_cover" value="1"> Quitar portada
          </label>
        @endif
      </div>

      <div>
        <label class="block text-sm mb-1">Publicación</label>
        <input type="datetime-local" name="published_at"
               value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded p-2">
        <p class="text-xs text-slate-500 mt-1">Déjalo vacío para guardar como borrador.</p>
      </div>
    </div>

    {{-- Enviar al boletín al guardar --}}
    <label class="inline-flex items-center gap-2 mt-4">
      <input type="checkbox" name="send_to_newsletter" value="1">
      Enviar al boletín al guardar
    </label>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.posts.index') }}">Volver</a>
    </div>
  </form>
</div>

<script>
  const input = document.getElementById('coverInput');
  input?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    const img = document.getElementById('coverPreview');
    const empty = document.getElementById('coverEmpty');
    if (empty) empty.classList.add('hidden');
    img.src = url;
    img.classList.remove('hidden');
  });
</script>
@endsection
