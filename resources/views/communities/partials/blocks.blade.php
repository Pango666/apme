@php
  // Helper para normalizar URL de imagen relativa
  $urlify = function (?string $path) {
      if (!$path) return null;
      return preg_match('#^(https?://|/)#', $path) ? $path : \Illuminate\Support\Facades\Storage::url($path);
  };
@endphp

@foreach($community->blocks as $block)
  @php
    $type = $block['type'] ?? null;
    $data = $block['data'] ?? [];
  @endphp

  @switch($type)
    {{-- Texto libre (HTML) --}}
    @case('text')
      @if(!empty($data['html']))
        <div class="prose max-w-none">{!! $data['html'] !!}</div>
      @endif
      @break

    {{-- Imagen simple --}}
    @case('image')
      @php $src = $urlify($data['src'] ?? null); @endphp
      @if($src)
        <figure class="rounded-xl overflow-hidden border bg-white">
          <img src="{{ $src }}" class="w-full h-auto object-cover" alt="">
          @if(!empty($data['caption']))
            <figcaption class="p-3 text-sm text-slate-600">{{ $data['caption'] }}</figcaption>
          @endif
        </figure>
      @endif
      @break

    {{-- Galería de imágenes --}}
    @case('gallery')
      @php $items = $data['items'] ?? []; @endphp
      @if(is_array($items) && count($items))
        <div class="grid md:grid-cols-3 gap-6">
          @foreach($items as $it)
            @php $src = $urlify($it['src'] ?? null); @endphp
            @if($src)
              <div class="aspect-[4/3] rounded-xl overflow-hidden border bg-white">
                <img src="{{ $src }}" class="w-full h-full object-cover" alt="">
              </div>
            @endif
          @endforeach
        </div>
      @endif
      @break

    {{-- Métricas simples (stats) --}}
    @case('stats')
      @php $items = $data['items'] ?? []; @endphp
      @if(is_array($items) && count($items))
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          @foreach($items as $s)
            <div class="rounded-xl border bg-white p-4 text-center">
              <div class="text-2xl font-extrabold text-borgo">{{ $s['value'] ?? '—' }}</div>
              <div class="text-xs uppercase tracking-wide text-slate-500">{{ $s['label'] ?? '' }}</div>
            </div>
          @endforeach
        </div>
      @endif
      @break

    {{-- Listado de productos (por community_id) --}}
    @case('products')
      @php
        $limit = (int)($data['limit'] ?? 6);
        $cid = $data['community_id'] ?? '__SELF__';
        $communityId = $cid === '__SELF__' ? $community->id : (int)$cid;
        $prods = \App\Models\Product::query()
            ->where('is_active', 1)
            ->where('community_id', $communityId)
            ->latest('id')
            ->take($limit)
            ->get();
      @endphp
      @if($prods->count())
        <div>
          <h3 class="font-display text-xl font-extrabold text-borgo mb-3">Productos</h3>
          <div class="grid md:grid-cols-3 gap-6">
            @foreach($prods as $p)
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
        </div>
      @endif
      @break
  @endswitch
@endforeach
