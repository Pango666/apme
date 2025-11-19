{{-- resources/views/admin/newsletter/subscribers/index.blade.php --}}
@extends('layouts.admin')
@section('title','Suscriptores · Newsletter')
@section('header') <h1 class="text-xl font-bold">Suscriptores</h1> @endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-8">
  <form class="flex gap-3 mb-4">
    <input type="text" name="s" value="{{ request('s') }}" placeholder="Buscar email..." class="border rounded p-2">
    <select name="status" class="border rounded p-2">
      <option value="">Todos</option>
      @foreach(['pending','subscribed','unsubscribed','bounced'] as $st)
        <option @selected(request('status')===$st)>{{ $st }}</option>
      @endforeach
    </select>
    <button class="px-3 py-2 bg-borgo text-white rounded">Filtrar</button>
  </form>

  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50"><tr>
        <th class="p-3 text-left">Email</th><th>Nombre</th><th>Estado</th><th>Alta</th><th class="p-3 w-28">Acciones</th>
      </tr></thead>
      <tbody>
      @foreach($items as $s)
        <tr class="border-t">
          <td class="p-3">{{ $s->email }}</td>
          <td>{{ $s->name }}</td>
          <td>{{ $s->status }}</td>
          <td>{{ $s->created_at->format('d/m/Y') }}</td>
          <td class="p-3">
            <form method="post" action="{{ route('admin.newsletter.subscribers.destroy',$s) }}" onsubmit="return confirm('¿Eliminar suscriptor?')">
              @csrf @method('DELETE')
              <button class="text-red-600">Eliminar</button>
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
