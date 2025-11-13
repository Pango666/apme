@extends('layouts.admin')
@section('title','Aliados · Admin')
@section('header')
  <h1 class="text-xl font-bold">Aliados</h1>
@endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-borgo">Aliados</h1>
    <a href="{{ route('admin.partners.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nuevo</a>
  </div>
  <div class="grid md:grid-cols-6 gap-4">
    @foreach($items as $p)
      <div class="border bg-white rounded-xl p-4 text-center">
        @if($p->logo_path)<img src="{{ $p->logo_path }}" class="h-10 mx-auto mb-2 object-contain">@endif
        <div class="text-sm font-medium">{{ $p->name }}</div>
        <div class="mt-2">
          <a class="text-borgo text-sm" href="{{ route('admin.partners.edit',$p) }}">Editar</a>
          <form class="inline" method="post" action="{{ route('admin.partners.destroy',$p) }}" onsubmit="return confirm('¿Eliminar?');">
            @csrf @method('DELETE') <button class="text-red-600 ml-3 text-sm">Eliminar</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
