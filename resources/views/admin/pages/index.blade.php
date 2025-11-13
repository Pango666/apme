@extends('layouts.admin')
@section('title','Páginas · Admin')
@section('header')
  <h1 class="text-xl font-bold">Páginas</h1>
@endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-borgo">Páginas</h1>
    <a href="{{ route('admin.pages.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nueva</a>
  </div>
  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-left">
        <tr><th class="p-3">Título</th><th>Slug</th><th class="p-3 w-40">Acciones</th></tr>
      </thead>
      <tbody>
        @foreach($items as $p)
        <tr class="border-t">
          <td class="p-3">{{ $p->title }}</td>
          <td>{{ $p->slug }}</td>
          <td class="p-3">
            <a class="text-borgo font-medium" href="{{ route('admin.pages.edit',$p) }}">Editar</a>
            <form class="inline" method="post" action="{{ route('admin.pages.destroy',$p) }}" onsubmit="return confirm('¿Eliminar?');">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-3">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
