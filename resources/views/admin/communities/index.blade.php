@extends('layouts.admin')
@section('title','Comunidades · Admin')

@section('header')
  <h1 class="text-xl font-bold">Comunidades</h1>
@endsection

@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
  @endif

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-borgo">Comunidades</h1>
    <a href="{{ route('admin.communities.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nueva</a>
  </div>

  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-left">
        <tr>
          <th class="p-3">Nombre</th>
          <th>Provincia</th>
          <th>Productos</th>
          <th>Coordenadas</th>
          <th class="p-3 w-48">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $c)
          <tr class="border-t">
            <td class="p-3">{{ $c->name }}</td>
            <td>{{ $c->province ?? '—' }}</td>
            <td>{{ $c->products_count ?? 0 }}</td>
            <td class="text-slate-600">
              @if(!is_null($c->latitude) && !is_null($c->longitude))
                {{ $c->latitude }}, {{ $c->longitude }}
              @else — @endif
            </td>
            <td class="p-3">
              <a class="text-borgo font-medium" href="{{ route('admin.communities.edit',$c) }}">Editar</a>
              <form class="inline" method="post" action="{{ route('admin.communities.destroy',$c) }}" onsubmit="return confirm('¿Eliminar?');">
                @csrf @method('DELETE')
                <button class="text-red-600 ml-3">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="p-3 text-slate-500" colspan="5">No hay comunidades.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
