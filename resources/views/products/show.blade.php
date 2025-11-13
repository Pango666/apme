@extends('layouts.app')
@section('title',$product->name.' · APME')
@section('content')
<div class="max-w-3xl mx-auto px-5 py-10">
  <h1 class="text-2xl font-bold text-borgo">{{ $product->name }}</h1>
  <div class="text-slate-600">{{ $product->type }}</div>
  @if($product->price_bs)<div class="mt-2 text-borgo font-bold">{{ number_format($product->price_bs,2) }} Bs</div>@endif
</div>
@endsection
