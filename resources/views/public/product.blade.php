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

{{-- BreadcrumbList JSON-LD (Phase 7D) --}}
<script type="application/ld+json">
@php
    $crumbs = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Anasayfa', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Ürünler',  'item' => route('public.products')],
    ];
    if ($catName && $product->category) {
        $crumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $catName, 'item' => route('public.category', ['slug' => $product->category->slug])];
        $crumbs[] = ['@type' => 'ListItem', 'position' => 4, 'name' => $name,    'item' => route('public.product',  ['slug' => $product->slug])];
    } else {
        $crumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $name,    'item' => route('public.product',  ['slug' => $product->slug])];
    }
@endphp
{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- Product JSON-LD (Phase 12C) ──────────────────────────────────────────────
     buyable  → full Offer with price + availability (eligible for Google rich results)
     quote_only → Product schema only, no Offer (avoids "missing price" penalty)
─────────────────────────────────────────────────────────────────────────────── --}}
<script type="application/ld+json">
@php
    $imageUrls = $gallery->pluck('url')->filter()->values()->all();

    $jsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $name,
        'url'         => route('public.product', $product->slug),
        'sku'         => $product->sku,
        'brand'       => ['@type' => 'Brand', 'name' => 'CNRWOOD'],
        'description' => $shortDesc
            ? strip_tags(\Illuminate\Support\Str::limit($shortDesc, 500))
            : ($description ? strip_tags(\Illuminate\Support\Str::limit($description, 500)) : $name),
    ];

    if (! empty($imageUrls)) {
        $jsonLd['image'] = count($imageUrls) === 1 ? $imageUrls[0] : $imageUrls;
    }

    if ($catName) {
        $jsonLd['category'] = $catName;
    }

    // Only buyable products get an Offer block — quote-only have no fixed price
    if ($isBuyable && $product->price !== null) {
        $jsonLd['offers'] = [
            '@type'           => 'Offer',
            'url'             => route('public.product', $product->slug),
            'priceCurrency'   => 'TRY',
            'price'           => number_format((float) $product->price, 2, '.', ''),
            'availability'    => $product->isInStock()
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'seller'          => ['@type' => 'Organization', 'name' => 'CNRWOOD'],
        ];
    }
@endphp
{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

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

                    @if (session('cart_error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                            {{ session('cart_error') }}
                        </div>
                    @endif

                    @if ($product->isInStock())
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            @if ($product->variants->isNotEmpty())
                                <div class="mb-4">
                                    <label for="product_variant_id"
                                           class="block text-sm font-medium text-[#3E2006] mb-1">
                                        Seçenek
                                    </label>
                                    <select id="product_variant_id"
                                            name="product_variant_id"
                                            class="w-full border border-[#E6DFD2] rounded px-3 py-2 bg-white text-[#3E2006] text-sm">
                                        @foreach ($product->variants as $v)
                                            @php
                                                $vn = is_array($v->name) ? ($v->name['tr'] ?? '—') : ($v->name ?? '—');
                                            @endphp
                                            <option value="{{ $v->id }}">
                                                {{ $vn }}
                                                @if ($v->price_modifier && (float) $v->price_modifier !== 0.0)
                                                    ({{ $v->price_modifier > 0 ? '+' : '' }}{{ number_format($v->price_modifier, 2, ',', '.') }} TL)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="flex items-end gap-3 mb-4">
                                <div>
                                    <label for="quantity"
                                           class="block text-sm font-medium text-[#3E2006] mb-1">
                                        Adet
                                    </label>
                                    <input type="number" id="quantity" name="quantity"
                                           value="1" min="1" max="99"
                                           class="w-20 border border-[#E6DFD2] rounded px-3 py-2 bg-white
                                                  text-[#3E2006] text-sm text-center">
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3
                                           text-base font-semibold rounded bg-[#2C5F2E] hover:bg-[#214a23]
                                           text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Sepete Ekle
                            </button>
                        </form>
                    @else
                        <button type="button" disabled
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3
                                       text-base font-semibold rounded bg-[#555555] text-white
                                       opacity-50 cursor-not-allowed">
                            Stokta Yok
                        </button>
                    @endif

                    <p class="text-xs text-[#555555] text-center mt-2">
                        veya telefonla:
                        <a href="tel:+902627512120" class="text-[#1F497D] hover:underline">+90 262 751 21 20</a>
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

                    <a href="{{ route('public.quote.product.create', $product->slug) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded
                              bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                        Teklif Al
                    </a>
                    <p class="text-xs text-[#555555] text-center mt-2">
                        veya telefonla: <a href="tel:+902627512120" class="text-[#1F497D] hover:underline">+90 262 751 21 20</a>
                    </p>
                @endif

            </div>

            {{-- Variants are now selectable inside the add-to-cart form above --}}
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
