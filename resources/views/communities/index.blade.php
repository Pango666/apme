@extends('layouts.app')
@section('title','Comunidades · APME')
@section('content')
<div class="max-w-5xl mx-auto px-5 py-10">
  <h1 class="text-2xl font-bold text-borgo">Comunidades</h1>
  <div class="grid md:grid-cols-3 gap-4 mt-4">
    @foreach($items as $c)
      <a class="border rounded p-4 hover:shadow" href="{{ route('comunidades.show',$c->slug) }}">
        <div class="font-semibold">{{ $c->name }}</div>
        <div class="text-sm text-slate-600">{{ $c->province }}</div>
      </a>
    @endforeach
  </div>
  <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection
