@extends('layouts.app')
@section('title',$community->name.' · APME')
@section('content')
<div class="max-w-3xl mx-auto px-5 py-10">
  <h1 class="text-2xl font-bold text-borgo">{{ $community->name }}</h1>
  <p class="text-slate-600">{{ $community->province }}</p>
  <div class="mt-4">{{ $community->description }}</div>
</div>
@endsection
