@extends('layouts.admin')
@section('title','Noticias · Admin')

@section('header')
  <h1 class="text-xl font-bold">Noticias</h1>
@endsection

@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
  @endif

  <div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold text-borgo">Noticias</h2>
    <a href="{{ route('admin.posts.create') }}" class="px-3 py-2 rounded bg-borgo text-white">Nueva</a>
  </div>

  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-left">
        <tr>
          <th class="p-3">Título</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th class="p-3 w-40">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $p)
          <tr class="border-t">
            <td class="p-3">{{ $p->title }}</td>
            <td>{{ optional($p->published_at)->format('d/m/Y') ?: '—' }}</td>
            <td>
              @if($p->published_at)
                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-700 border border-green-200">Publicada</span>
              @else
                <span class="px-2 py-0.5 text-xs rounded bg-slate-100 text-slate-700 border border-slate-200">Borrador</span>
              @endif
            </td>
            <td class="p-3">
              <a class="text-borgo font-medium" href="{{ route('admin.posts.edit',$p) }}">Editar</a>
              <form class="inline" method="post" action="{{ route('admin.posts.destroy',$p) }}" onsubmit="return confirm('¿Eliminar?');">
                @csrf @method('DELETE')
                <button class="text-red-600 ml-3">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-4 text-slate-500">No hay noticias.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
