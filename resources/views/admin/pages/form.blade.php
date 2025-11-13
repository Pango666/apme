@extends('layouts.admin')
@section('title', ($page->exists?'Editar':'Nueva').' página · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($page->exists?'Editar':'Nueva') }}</h1>
@endsection
@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  <form method="post" action="{{ $page->exists?route('admin.pages.update',$page):route('admin.pages.store') }}">
    @csrf @if($page->exists) @method('PUT') @endif
    <label class="block text-sm mb-1">Título</label>
    <input name="title" value="{{ old('title',$page->title) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input name="slug" value="{{ old('slug',$page->slug) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Resumen</label>
    <textarea name="excerpt" rows="3" class="w-full border rounded p-2">{{ old('excerpt',$page->excerpt) }}</textarea>
    <label class="block text-sm mt-4 mb-1">Contenido</label>
    <textarea name="body" rows="10" class="w-full border rounded p-2">{{ old('body',$page->body) }}</textarea>
    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.pages.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
