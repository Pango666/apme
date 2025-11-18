@extends('layouts.admin')
@section('title','Páginas · Admin')

@section('header')
  <h1 class="text-xl font-bold">Páginas</h1>
@endsection

@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
  @endif

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-borgo">Páginas</h1>
    <a href="{{ route('admin.pages.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nueva</a>
  </div>

  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-left">
        <tr>
          <th class="p-3">Título</th>
          <th>Slug</th>
          <th class="p-3 w-48">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $p)
          <tr class="border-t">
            <td class="p-3">{{ $p->title }}</td>
            <td class="text-slate-600">{{ $p->slug }}</td>
            <td class="p-3">
              <a class="text-borgo font-medium" href="{{ route('admin.pages.edit',$p) }}">Editar</a>
              @if($p->slug)
                <a class="ml-3 text-slate-600 underline" target="_blank" href="{{ route('page.show',$p->slug) }}">Ver</a>
              @endif
              <form class="inline" method="post" action="{{ route('admin.pages.destroy',$p) }}" onsubmit="return confirm('¿Eliminar?');">
                @csrf @method('DELETE')
                <button class="text-red-600 ml-3">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="p-3 text-slate-500" colspan="3">No hay páginas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
