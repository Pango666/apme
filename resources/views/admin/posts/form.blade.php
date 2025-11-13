@extends('layouts.admin')
@section('title', ($post->exists?'Editar':'Nueva').' noticia · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($post->exists?'Editar Noticia':'Nueva Noticia') }}</h1>
@endsection
@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  <form method="post" enctype="multipart/form-data" action="{{ $post->exists?route('admin.posts.update',$post):route('admin.posts.store') }}">
    @csrf @if($post->exists) @method('PUT') @endif
    <label class="block text-sm mb-1">Título</label>
    <input name="title" value="{{ old('title',$post->title) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input name="slug" value="{{ old('slug',$post->slug) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Resumen</label>
    <textarea name="excerpt" rows="3" class="w-full border rounded p-2">{{ old('excerpt',$post->excerpt) }}</textarea>
    <label class="block text-sm mt-4 mb-1">Contenido</label>
    <textarea name="body" rows="10" class="w-full border rounded p-2">{{ old('body',$post->body) }}</textarea>

    <div class="grid md:grid-cols-2 gap-4 mt-4">
      <div>
        <label class="block text-sm mb-1">Portada</label>
        <input type="file" name="cover">
      </div>
      <div>
        <label class="block text-sm mb-1">Publicación</label>
        <input type="datetime-local" name="published_at"
               value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded p-2">
      </div>
    </div>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.posts.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
