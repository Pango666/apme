@extends('layouts.app')
@section('title','Productos · APME')
@section('content')
<div class="max-w-5xl mx-auto px-5 py-10">
  <h1 class="text-2xl font-bold text-borgo">Productos</h1>
  <div class="grid md:grid-cols-3 gap-4 mt-4">
    @foreach($items as $p)
      <a class="border rounded p-4 hover:shadow" href="{{ route('productos.show',$p->slug) }}">
        <div class="text-xs text-slate-500 uppercase">{{ $p->type }}</div>
        <div class="font-semibold">{{ $p->name }}</div>
        @if($p->price_bs)<div class="text-borgo font-bold">{{ number_format($p->price_bs,2) }} Bs</div>@endif
      </a>
    @endforeach
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection
