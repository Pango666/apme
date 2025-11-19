@extends('layouts.admin')
@section('title','Suscriptores · Admin')

@section('header')
  <h1 class="text-xl font-bold">Suscriptores del boletín</h1>
@endsection

@section('content')
@if(session('ok'))
  <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
@endif

<div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-left">
      <tr>
        <th class="p-2">Email</th>
        <th class="p-2">Estado</th>
        <th class="p-2">Confirmado</th>
        <th class="p-2">Creado</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subs as $s)
        <tr class="border-t">
          <td class="p-2">{{ $s->email }}</td>
          <td class="p-2">
            <span class="inline-block px-2 py-0.5 rounded text-xs
              {{ $s->status==='subscribed' ? 'bg-green-100 text-green-700' :
                 ($s->status==='pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }}">
              {{ $s->status }}
            </span>
          </td>
          <td class="p-2">{{ optional($s->confirmed_at)->format('Y-m-d H:i') ?: '-' }}</td>
          <td class="p-2">{{ $s->created_at->format('Y-m-d H:i') }}</td>
        </tr>
      @empty
        <tr><td class="p-3 text-slate-500" colspan="4">Sin registros.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $subs->links() }}
</div>
@endsection
