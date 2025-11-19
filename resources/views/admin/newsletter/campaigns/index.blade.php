@extends('layouts.admin')
@section('title','Campañas · Newsletter')
@section('header') <h1 class="text-xl font-bold">Campañas</h1> @endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-8">
  <div class="mb-4 flex justify-between">
    <h2 class="text-borgo font-semibold">Listado</h2>
    <a href="{{ route('admin.newsletter.campaigns.create') }}" class="px-3 py-2 bg-borgo text-white rounded">Nueva campaña</a>
  </div>
  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50"><tr>
        <th class="p-3 text-left">Nombre</th><th>Estado</th><th>Programada</th><th>Enviados</th><th>Errores</th><th class="p-3 w-44">Acciones</th>
      </tr></thead>
      <tbody>
      @foreach($items as $c)
        <tr class="border-t">
          <td class="p-3">{{ $c->name }}</td>
          <td>{{ $c->status }}</td>
          <td>{{ $c->scheduled_at?->format('d/m/Y H:i') ?: '-' }}</td>
          <td>{{ $c->sent_count }}</td>
          <td>{{ $c->error_count }}</td>
          <td class="p-3">
            <a class="text-borgo" href="{{ route('admin.newsletter.campaigns.edit',$c) }}">Editar</a>
            @if($c->status!=='sent')
              <form class="inline" method="post" action="{{ route('admin.newsletter.campaigns.send',$c) }}" onsubmit="return confirm('¿Enviar ahora a todos los suscritos?')">
                @csrf <button class="ml-3 text-green-700">Enviar</button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
