@extends('layouts.admin')
@section('title', ($product->exists?'Editar':'Nuevo').' producto · Admin')
@section('header')
  <h1 class="text-xl font-bold">{{ ($product->exists?$product->name:'Nuevo Producto') }}</h1>
@endsection
@section('content')
<div class="max-w-[900px] mx-auto px-5 py-10">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>@endif
  <form method="post" action="{{ $product->exists?route('admin.products.update',$product):route('admin.products.store') }}">
    @csrf @if($product->exists) @method('PUT') @endif
    <label class="block text-sm mb-1">Nombre</label>
    <input name="name" value="{{ old('name',$product->name) }}" class="w-full border rounded p-2">

    <label class="block text-sm mt-4 mb-1">Slug</label>
    <input name="slug" value="{{ old('slug',$product->slug) }}" class="w-full border rounded p-2">

    <div class="grid grid-cols-2 gap-4 mt-4">
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

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.products.index') }}">Volver</a>
    </div>
  </form>
</div>
@endsection
