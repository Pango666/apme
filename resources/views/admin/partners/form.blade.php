@extends('layouts.admin')
@section('title', ($partner->exists?'Editar':'Nuevo').' aliado · Admin')

@section('header')
  <h1 class="text-xl font-bold">{{ $partner->exists ? 'Editar aliado' : 'Nuevo aliado' }}</h1>
@endsection

@section('content')
<div class="max-w-[720px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('ok') }}</div>
  @endif

  @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="post" enctype="multipart/form-data"
        action="{{ $partner->exists ? route('admin.partners.update',$partner) : route('admin.partners.store') }}">
    @csrf
    @if($partner->exists) @method('PUT') @endif

    <label class="block text-sm mb-1">Nombre</label>
    <input name="name" value="{{ old('name',$partner->name) }}"
           class="w-full border rounded p-2" required>

    <label class="block text-sm mt-4 mb-1">URL (opcional)</label>
    <input name="url" value="{{ old('url',$partner->url) }}"
           class="w-full border rounded p-2" placeholder="https://... o #">

    <div class="grid md:grid-cols-2 gap-6 mt-4 items-start">
      <div>
        <label class="block text-sm mb-1">Logo (JPG/PNG/WebP)</label>
        <input type="file" name="logo" id="logoInput" accept="image/*" class="block w-full">
        <p class="text-xs text-slate-500 mt-1">Máx. 5MB</p>

        @if($partner->logo_path)
          <label class="inline-flex items-center gap-2 mt-3">
            <input type="checkbox" name="remove_logo" value="1"> Quitar logo
          </label>
        @endif
      </div>
      <div>
        <div class="text-sm text-slate-600 mb-2">Vista previa</div>
        <div class="aspect-[4/3] rounded-xl overflow-hidden border bg-slate-50 grid place-items-center">
          @if(old('logo_path', $partner->logo_path))
            <img id="logoPreview" src="{{ old('logo_path', $partner->logo_path) }}"
                 class="w-full h-full object-contain" alt="">
          @else
            <img id="logoPreview" class="w-full h-full object-contain hidden" alt="">
            <span id="logoEmpty" class="text-slate-400">Sin logo</span>
          @endif
        </div>
      </div>
    </div>

    <div class="mt-6">
      <button class="px-4 py-2 bg-borgo text-white rounded">Guardar</button>
      <a class="ml-3 underline" href="{{ route('admin.partners.index') }}">Volver</a>
    </div>
  </form>
</div>

<script>
document.getElementById('logoInput')?.addEventListener('change', (e) => {
  const file = e.target.files?.[0];
  if (!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('logoPreview');
  const empty = document.getElementById('logoEmpty');
  if (empty) empty.classList.add('hidden');
  img.src = url;
  img.classList.remove('hidden');
});
</script>
@endsection
