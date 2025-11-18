@extends('layouts.admin')
@section('title', ($page->exists?'Editar':'Nueva').' página · Admin')

@section('header')
  <h1 class="text-xl font-bold">{{ $page->exists ? 'Editar Página' : 'Nueva Página' }}</h1>
@endsection

@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
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

  <form method="post" action="{{ $page->exists?route('admin.pages.update',$page):route('admin.pages.store') }}">
    @csrf @if($page->exists) @method('PUT') @endif

    <label class="block text-sm mb-1">Título</label>
    <input id="titleInput" name="title" value="{{ old('title',$page->title) }}" class="w-full border rounded p-2" required>

    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input id="slugInput" name="slug" value="{{ old('slug',$page->slug) }}" class="w-full border rounded p-2" required>

    <div class="grid md:grid-cols-2 gap-6 mt-4">
      <div>
        <label class="block text-sm mb-1">Resumen</label>
        <textarea name="excerpt" rows="5" class="w-full border rounded p-2">{{ old('excerpt',$page->excerpt) }}</textarea>
      </div>
      <div>
        <label class="block text-sm mb-1">Vista pública</label>
        <div class="p-3 rounded border bg-slate-50 text-sm">
          @if($page->exists && $page->slug)
            <a class="text-borgo underline" target="_blank" href="{{ route('page.show',$page->slug) }}">
              {{ route('page.show',$page->slug) }}
            </a>
          @else
            <span class="text-slate-500">Se generará al guardar…</span>
          @endif
        </div>
      </div>
    </div>

    <label class="block text-sm mt-4 mb-1">Contenido (HTML permitido)</label>
    <textarea id="bodyInput" name="body" rows="14" class="w-full border rounded p-2">{{ old('body',$page->body) }}</textarea>

    {{-- Opcional: previsualización rápida --}}
    <details class="mt-3">
      <summary class="cursor-pointer text-sm text-slate-600">Previsualizar contenido</summary>
      <div id="previewBox" class="mt-3 p-4 border rounded bg-white prose max-w-none"></div>
    </details>

    <div class="mt-6 flex items-center gap-3">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="underline" href="{{ route('admin.pages.index') }}">Volver</a>
    </div>
  </form>
</div>

<script>
// Auto-slug en el cliente
const titleEl = document.getElementById('titleInput');
const slugEl  = document.getElementById('slugInput');

function slugify(str){
  return (str||'')
    .toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g,'') // quitar acentos
    .replace(/[^a-z0-9]+/g,'-')
    .replace(/^-+|-+$/g,'')
    .substring(0,180);
}
titleEl?.addEventListener('input', () => {
  if (!slugEl.value || slugEl.dataset.touched !== '1') {
    slugEl.value = slugify(titleEl.value);
  }
});
slugEl?.addEventListener('input', () => { slugEl.dataset.touched = '1'; });

// Previsualización rápida
const bodyEl = document.getElementById('bodyInput');
const preview = document.getElementById('previewBox');
function renderPreview(){
  if (!preview) return;
  preview.innerHTML = bodyEl.value || '<p class="text-slate-400">Sin contenido…</p>';
}
bodyEl?.addEventListener('input', renderPreview);
renderPreview();
</script>
@endsection
