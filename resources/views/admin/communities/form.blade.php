@extends('layouts.admin')
@section('title', ($community->exists?'Editar':'Nueva').' comunidad · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($community->exists?'Editar':'Nueva') }}</h1>
@endsection
@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  <form method="post" action="{{ $community->exists?route('admin.communities.update',$community):route('admin.communities.store') }}">
    @csrf @if($community->exists) @method('PUT') @endif
    <label class="block text-sm mb-1">Nombre</label>
    <input name="name" value="{{ old('name',$community->name) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input name="slug" value="{{ old('slug',$community->slug) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Provincia</label>
    <input name="province" value="{{ old('province',$community->province) }}" class="w-full border rounded p-2">
    <label class="block text-sm mt-4 mb-1">Descripción</label>
    <textarea name="description" rows="6" class="w-full border rounded p-2">{{ old('description',$community->description) }}</textarea>
    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.communities.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
