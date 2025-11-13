@extends('layouts.admin')
@section('title','Ajustes · Admin')

@section('header')
  <h1 class="text-xl font-bold">Ajustes del sitio</h1>
@endsection
@section('content')
<div class="max-w-[1100px] mx-auto px-5 py-10">
  @if(session('ok'))
    <div class="mb-6 p-3 rounded border border-green-200 bg-green-50">{{ session('ok') }}</div>
  @endif

  <h1 class="text-2xl font-bold text-borgo mb-6">Ajustes del sitio</h1>

  <form method="post" action="{{ route('admin.settings.update', 1) }}" enctype="multipart/form-data" class="space-y-10">
    @csrf @method('PUT')

    {{-- HERO --}}
    <section class="bg-white border rounded-xl p-5">
      <div class="font-semibold mb-4">Portada (Hero)</div>
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm mb-1">Título</label>
          <input class="w-full border rounded p-2" name="hero[title]" value="{{ old('hero.title', $hero['title']) }}">

          <label class="block text-sm mt-4 mb-1">Subtítulo</label>
          <input class="w-full border rounded p-2" name="hero[subtitle]" value="{{ old('hero.subtitle', $hero['subtitle']) }}">

          <label class="block text-sm mt-4 mb-1">Imagen (opcional)</label>
          <input type="file" name="hero[image]" id="heroImageInput" accept="image/*">
        </div>

        <div>
          <div class="text-sm text-slate-600 mb-2">Vista previa</div>
          <div class="aspect-[16/9] rounded-xl overflow-hidden border bg-slate-50">
            @if(!empty($hero['image']))
              <img id="heroPreview" src="{{ $hero['image'] }}" class="w-full h-full object-cover" alt="">
            @else
              <img id="heroPreview" class="w-full h-full object-cover hidden" alt="">
              <div id="heroEmpty" class="w-full h-full grid place-items-center text-slate-400">Sin imagen</div>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- CONTACTO --}}
    <section class="bg-white border rounded-xl p-5">
      <div class="font-semibold mb-4">Contacto</div>
      <div class="grid md:grid-cols-3 gap-6">
        <div>
          <label class="block text-sm mb-1">WhatsApp</label>
          <input class="w-full border rounded p-2" name="contact[whatsapp]" value="{{ old('contact.whatsapp', $contact['whatsapp']) }}">
        </div>
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input class="w-full border rounded p-2" name="contact[email]" type="email" value="{{ old('contact.email', $contact['email']) }}">
        </div>
        <div>
          <label class="block text-sm mb-1">Dirección</label>
          <input class="w-full border rounded p-2" name="contact[address]" value="{{ old('contact.address', $contact['address']) }}">
        </div>
      </div>
    </section>

    {{-- REDES --}}
    <section class="bg-white border rounded-xl p-5">
      <div class="font-semibold mb-4">Redes sociales</div>
      <div class="grid md:grid-cols-3 gap-6">
        <div>
          <label class="block text-sm mb-1">Facebook (URL)</label>
          <input class="w-full border rounded p-2" name="social[facebook]" value="{{ old('social.facebook', $social['facebook'] ?? '') }}">
        </div>
        <div>
          <label class="block text-sm mb-1">Instagram (URL)</label>
          <input class="w-full border rounded p-2" name="social[instagram]" value="{{ old('social.instagram', $social['instagram'] ?? '') }}">
        </div>
        <div>
          <label class="block text-sm mb-1">TikTok (URL)</label>
          <input class="w-full border rounded p-2" name="social[tiktok]" value="{{ old('social.tiktok', $social['tiktok'] ?? '') }}">
        </div>
      </div>
    </section>

    <div>
      <button class="px-4 py-2 rounded bg-borgo text-white">Guardar cambios</button>
      <a class="ml-3 underline" href="{{ route('admin.dashboard') }}">Volver</a>
    </div>
  </form>
</div>

<script>
document.getElementById('heroImageInput')?.addEventListener('change', (e) => {
  const file = e.target.files?.[0];
  if (!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('heroPreview');
  const empty = document.getElementById('heroEmpty');
  if (empty) empty.classList.add('hidden');
  img.src = url;
  img.classList.remove('hidden');
});
</script>
@endsection
