@php
  use App\Models\Product;
  $urlify = fn (?string $p) => Product::urlify($p);
@endphp

@foreach($product->blocks as $index => $block)
  @php
    $type = $block['type'] ?? null;
    $data = $block['data'] ?? [];
  @endphp

  {{-- Texto libre (HTML) --}}
  @if($type === 'text' && !empty($data['html']))
    <div class="prose prose-lg max-w-none">
      {!! $data['html'] !!}
    </div>
  @endif

  {{-- Imagen ancha --}}
  @if($type === 'image')
    @php $src = $urlify($data['src'] ?? null); @endphp
    @if($src)
      <figure class="rounded-2xl overflow-hidden bg-white shadow-card border border-slate-200">
        <img 
          src="{{ $src }}" 
          alt="{{ $data['caption'] ?? 'Imagen del producto' }}" 
          class="w-full h-auto object-cover"
          loading="lazy"
        >
        @if(!empty($data['caption']))
          <figcaption class="p-4 text-sm text-slate-600 border-t border-slate-100">
            {{ $data['caption'] }}
          </figcaption>
        @endif
      </figure>
    @endif
  @endif

  {{-- Galería (grid) --}}
  @if($type === 'gallery')
    @php $items = $data['items'] ?? []; @endphp
    @if(is_array($items) && count($items))
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($items as $it)
          @php $src = $urlify($it['src'] ?? null); @endphp
          @if($src)
            <div class="aspect-square rounded-xl overflow-hidden bg-white border border-slate-200 hover:shadow-md transition-all duration-300">
              <img 
                src="{{ $src }}" 
                alt="{{ $it['caption'] ?? 'Galería' }}" 
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                loading="lazy"
              >
            </div>
          @endif
        @endforeach
      </div>
    @endif
  @endif

  {{-- Specs simples (lista) --}}
  @if($type === 'specs')
    @php $specs = $data['items'] ?? []; @endphp
    @if(is_array($specs) && count($specs))
      <div class="bg-white rounded-2xl shadow-card border border-slate-200 p-6">
        <h3 class="font-semibold text-2xl text-borgo mb-6">Especificaciones técnicas</h3>
        <div class="grid md:grid-cols-2 gap-6">
          @foreach($specs as $s)
            <div class="flex justify-between items-center py-3 border-b border-slate-100 last:border-b-0">
              <span class="text-slate-600 font-medium">{{ $s['label'] ?? '—' }}</span>
              <span class="text-borgo font-semibold text-right">{{ $s['value'] ?? '' }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  @endif

  {{-- Separador entre bloques --}}
  @if(!$loop->last)
    <div class="border-t border-slate-200"></div>
  @endif
@endforeach