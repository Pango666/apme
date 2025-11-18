@extends('layouts.app')
@section('title','Productos · '.$community->name.' · APME')

@section('content')
<div class="max-w-[1200px] mx-auto px-5 py-10">
  <h1 class="text-2xl font-display font-extrabold text-borgo">Productos — {{ $community->name }}</h1>

  @if($productos->count())
    <div class="grid md:grid-cols-3 gap-6 mt-4">
      @foreach($productos as $p)
        <a href="{{ route('productos.show',$p) }}"
           class="rounded-xl border bg-white p-5 hover:shadow transition">
          <div class="text-xs uppercase text-slate-500">{{ $p->type }}</div>
          <div class="font-semibold">{{ $p->name }}</div>
          @if($p->price_bs)
            <div class="text-borgo font-bold mt-1">{{ number_format($p->price_bs,2) }} Bs</div>
          @endif
        </a>
      @endforeach
    </div>
    <div class="mt-6">{{ $productos->links() }}</div>
  @else
    <div class="mt-4 text-slate-600">Aún no hay productos activos.</div>
  @endif
</div>
@endsection
