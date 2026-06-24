@php
    /** @var \App\Models\Product $product */
    $locale    = app()->getLocale();
    $name      = $product->getTranslation('name', $locale) ?? '\u2014';
    $catName   = $product->category?->getTranslation('name', $locale) ?? null;
    $primary   = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $imgUrl    = $primary ? \Illuminate\Support\Facades\Storage::disk('public')->url($primary->url) : null;
    $isBuyable = $product->isBuyable();
    $priceTxt  = $isBuyable ? $product->getDisplayPrice() : null;
@endphp

{{-- Phase 14C.1: tokens applied -- bg-wood-100, border-wood-200, text-wood-*, text-success, bg-info/10 etc. --}}
<a href="{{ route('public.product', $product->slug) }}"
   class="group flex flex-col bg-white border border-wood-200 rounded-lg overflow-hidden
          hover:shadow-md hover:border-wood-500 transition-all">

    <div class="aspect-[4/3] bg-wood-100 overflow-hidden">
        @if ($imgUrl)
            <img src="{{ $imgUrl }}"
                 alt="{{ $name }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-wood-500/50">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="p-4 flex flex-col flex-grow">
        @if ($catName)
            <p class="text-xs uppercase tracking-wide text-wood-500 mb-1">{{ $catName }}</p>
        @endif

        <h3 class="text-wood-800 font-semibold leading-snug line-clamp-2 mb-3">
            {{ $name }}
        </h3>

        <div class="mt-auto flex items-center justify-between gap-2">
            @if ($isBuyable)
                @if ($priceTxt)
                    <span class="text-wood-800 font-semibold">{{ $priceTxt }}</span>
                @else
                    <span class="text-xs text-neutral-500">{{ __('product.no_price') }}</span>
                @endif
                <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded bg-success/10 text-success">
                    {{ __('product.for_sale') }}
                </span>
            @else
                {{-- Quote-only: no price, no cart, no stock -- Phase 14C.1 --}}
                <span class="text-sm text-neutral-500">{{ __('product.request_quote_short') }}</span>
                <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded bg-info/10 text-info">
                    {{ __('product.quote_only_short') }}
                </span>
            @endif
        </div>
    </div>
</a>
