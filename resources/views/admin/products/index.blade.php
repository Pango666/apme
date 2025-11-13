@extends('layouts.admin')
@section('title','Productos - Admin')

@section('header')
  <h1 class="text-xl font-bold">Productos</h1>
@endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold text-borgo">Productos</h1>
    <a href="{{ route('admin.products.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nuevo</a>
  </div>
  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-left">
        <tr><th class="p-3">Nombre</th><th>Tipo</th><th>Comunidad</th><th>Precio</th><th>Activo</th><th class="p-3 w-40">Acciones</th></tr>
      </thead>
      <tbody>
        @foreach($items as $p)
        <tr class="border-t">
          <td class="p-3">{{ $p->name }}</td>
          <td>{{ strtoupper($p->type) }}</td>
          <td>{{ $p->community->name ?? '-' }}</td>
          <td>{{ $p->price_bs ? number_format($p->price_bs,2).' Bs' : '-' }}</td>
          <td>{{ $p->is_active ? 'Sí':'No' }}</td>
          <td class="p-3">
            <a class="text-borgo font-medium" href="{{ route('admin.products.edit',$p) }}">Editar</a>
            <form class="inline" method="post" action="{{ route('admin.products.destroy',$p) }}" onsubmit="return confirm('¿Eliminar?');">
              @csrf @method('DELETE') <button class="text-red-600 ml-3">Eliminar</button>
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
