@extends('layouts.public')

@php
    /** @var \App\Models\Product $product */
    $name        = $product->getTranslation('name', 'tr') ?? '—';
    $description = $product->getTranslation('description', 'tr');
    $shortDesc   = $product->getTranslation('short_description', 'tr');
    $catName     = $product->category?->getTranslation('name', 'tr');
    $isBuyable   = $product->isBuyable();
    $priceTxt    = $isBuyable ? $product->getDisplayPrice() : null;

    $title           = $name . ' — CNRWOOD';
    $metaDescription = $shortDesc
        ? strip_tags(\Illuminate\Support\Str::limit($shortDesc, 160))
        : ($description ? strip_tags(\Illuminate\Support\Str::limit($description, 160)) : $name);

    $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $gallery = $product->images->sortBy('sort_order');

    $mailtoSubject = rawurlencode('Teklif Talebi - ' . $name . ' (' . $product->sku . ')');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.products') }}" class="hover:underline">Ürünler</a>
            @if ($catName && $product->category)
                <span class="mx-1">/</span>
                <a href="{{ route('public.category', $product->category->slug) }}" class="hover:underline">{{ $catName }}</a>
            @endif
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ $name }}</span>
        </nav>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">

        {{-- GALLERY --}}
        <div>
            <div class="aspect-[4/3] bg-white border border-[#E6DFD2] rounded-lg overflow-hidden flex items-center justify-center">
                @if ($primary)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($primary->url) }}"
                         alt="{{ $name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="text-[#8B5A2B]/50">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            @if ($gallery->count() > 1)
                <div class="grid grid-cols-5 gap-2 mt-3">
                    @foreach ($gallery as $img)
                        <div class="aspect-square bg-white border border-[#E6DFD2] rounded overflow-hidden">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($img->url) }}"
                                 alt="{{ is_array($img->alt_text) ? ($img->alt_text['tr'] ?? $name) : $name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INFO --}}
        <div>
            @if ($catName && $product->category)
                <a href="{{ route('public.category', $product->category->slug) }}"
                   class="text-xs uppercase tracking-wider text-[#8B5A2B] hover:text-[#3E2006]">
                    {{ $catName }}
                </a>
            @endif

            <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006] mt-2 leading-tight">{{ $name }}</h1>

            @if ($product->sku)
                <p class="text-sm text-[#555555] mt-2">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
            @endif

            @if ($shortDesc)
                <p class="text-[#555555] mt-4 leading-relaxed">{{ $shortDesc }}</p>
            @endif

            {{-- ════════════════════════════════════════════════════════════
                 PRICE / CTA BLOCK — STRICTLY BRANCHED BY product_type.
                 quote_only MUST NEVER show price, stock, or cart.
                 ════════════════════════════════════════════════════════════ --}}
            <div class="mt-8 p-6 bg-[#F5F0E8] rounded-lg border border-[#E6DFD2]">

                @if ($isBuyable)
                    {{-- ── BUYABLE PRODUCT ── --}}
                    @if ($priceTxt)
                        <div class="flex items-baseline gap-3 mb-4">
                            <span class="text-3xl font-bold text-[#2C5F2E]">{{ $priceTxt }}</span>
                            @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="text-lg text-[#555555] line-through">
                                    {{ number_format($product->compare_at_price, 2, ',', '.') }} TL
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="mb-4">
                        @if ($product->isInStock())
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#2C5F2E]">
                                <span class="w-2 h-2 rounded-full bg-[#2C5F2E]"></span>
                                Stokta
                                @if ($product->isLowStock())
                                    <span class="text-[#555555] font-normal">(son {{ $product->stock_quantity }} adet)</span>
                                @endif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#555555]">
                                <span class="w-2 h-2 rounded-full bg-[#555555]"></span>
                                Tükendi
                            </span>
                        @endif
                    </div>

                    <button type="button" disabled
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded
                                   bg-[#2C5F2E] text-white opacity-50 cursor-not-allowed">
                        Sipariş Ver
                        <span class="text-xs font-normal">(Yakında)</span>
                    </button>
                    <p class="text-xs text-[#555555] text-center mt-2">
                        Online sipariş yakında aktif olacak. Şimdi sipariş için
                        <a href="tel:+902627512120" class="text-[#1F497D] hover:underline">+90 262 751 21 20</a>.
                    </p>

                @else
                    {{-- ── QUOTE-ONLY PRODUCT — NO PRICE, NO STOCK, NO CART ── --}}
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1F497D] bg-[#1F497D]/10 px-3 py-1 rounded-full">
                            Sadece Teklif Üzerine
                        </span>
                    </div>

                    <p class="text-[#555555] mb-5 leading-relaxed">
                        Bu ürün ölçü, malzeme ve adet detaylarınıza göre özel olarak üretilmektedir.
                        Detaylı teklif için bizimle iletişime geçin.
                    </p>

                    <a href="mailto:info@cnrwood.com?subject={{ $mailtoSubject }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded
                              bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                        Teklif Al
                    </a>
                    <p class="text-xs text-[#555555] text-center mt-2">
                        veya telefonla: <a href="tel:+902627512120" class="text-[#1F497D] hover:underline">+90 262 751 21 20</a>
                    </p>
                @endif

            </div>

            {{-- VARIANTS (read-only display) --}}
            @if ($isBuyable && $product->variants->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#3E2006] mb-3">Seçenekler</h3>
                    <ul class="space-y-2">
                        @foreach ($product->variants as $variant)
                            @php $vname = is_array($variant->name) ? ($variant->name['tr'] ?? '—') : ($variant->name ?? '—'); @endphp
                            <li class="flex items-center justify-between p-3 bg-white border border-[#E6DFD2] rounded text-sm">
                                <span class="text-[#3E2006] font-medium">{{ $vname }}</span>
                                @if ($variant->price_modifier && (float) $variant->price_modifier !== 0.0)
                                    <span class="text-[#555555]">
                                        {{ $variant->price_modifier > 0 ? '+' : '' }}{{ number_format($variant->price_modifier, 2, ',', '.') }} TL
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- DESCRIPTION --}}
    @if ($description)
        <div class="mt-16 pt-10 border-t border-[#E6DFD2]">
            <h2 class="text-2xl font-bold text-[#3E2006] mb-4">Ürün Açıklaması</h2>
            <div class="prose prose-sm max-w-none text-[#333333] leading-relaxed whitespace-pre-line">
                {{ $description }}
            </div>
        </div>
    @endif

    {{-- RELATED --}}
    @if ($related->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-[#E6DFD2]">
            <h2 class="text-2xl font-bold text-[#3E2006] mb-6">Benzer Ürünler</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related as $rel)
                    @include('partials.product-card', ['product' => $rel])
                @endforeach
            </div>
        </div>
    @endif

</section>

@endsection
