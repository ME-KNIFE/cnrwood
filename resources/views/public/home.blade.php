@extends('layouts.public')

@php
    $title           = 'CNRWOOD — Ahşap Sandık, İhracat Ambalajı ve Ahşap Yapı Çözümleri | Gebze';
    $metaDescription = "1998'den beri Gebze'de ahşap sandık, ISPM 15 ısıl işlemli ihracat ambalajı, kapı sereni, kereste & levha ve ahşap yapı projelerinde profesyonel üretim. Hızlı teklif ve kaliteli işçilik.";

    // Split featured products — quote-safe, no controller change needed
    $quoteProducts = $featuredProducts->filter(fn($p) => ! $p->isBuyable())->values();
    $storeProducts = $featuredProducts->filter(fn($p) =>   $p->isBuyable())->values();
@endphp

@section('content')

{{-- Organization + LocalBusiness JSON-LD ────────────────────────────────── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'     => 'https://schema.org',
    '@type'        => ['Organization', 'LocalBusiness'],
    '@id'          => url('/') . '#organization',
    'name'         => 'CNRWOOD',
    'url'          => url('/'),
    'foundingDate' => '1998',
    'description'  => 'Ahşap sandık, ihracat ambalajı (ISPM 15), kapı sereni, kereste ve ahşap yapı çözümleri.',
    'address'      => [
        '@type'           => 'PostalAddress',
        'addressLocality' => 'Gebze',
        'addressRegion'   => 'Kocaeli',
        'addressCountry'  => 'TR',
    ],
    'contactPoint' => [
        '@type'       => 'ContactPoint',
        'contactType' => 'customer service',
        'url'         => route('public.contact'),
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- ════════════════════════════════════════════════════════════════════════ --}}
{{-- HERO                                                                     --}}
{{-- ════════════════════════════════════════════════════════════════════════ --}}
<section class="relative isolate overflow-hidden bg-wood-deep">
    {{-- CSS gradient overlay (no image dependency) --}}
    <div class="absolute inset-0 bg-gradient-to-r from-wood-deep via-wood-deep/90 to-wood-medium/60" aria-hidden="true"></div>
    {{-- Subtle wood-grain texture via radial dots --}}
    <div class="absolute inset-0 opacity-5"
         style="background-image: radial-gradient(circle, #f5f0e8 1px, transparent 1px); background-size: 32px 32px;"
         aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-20 lg:px-8 lg:py-32">
        <div class="max-w-2xl">
            {{-- Badge --}}
            <span class="inline-flex items-center gap-2 rounded-sm border border-wood-natural/50 bg-wood-deep/40 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-wood-natural">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                {{ __('home.hero_badge') }}
            </span>

            <h1 class="mt-6 font-heading text-4xl font-bold uppercase leading-[1.05] tracking-tight text-cream sm:text-5xl lg:text-6xl">
                {{ __('home.hero_title') }}<br>
                <span class="text-wood-natural">{{ __('home.hero_strong') }}</span>
            </h1>

            <p class="mt-6 max-w-xl text-base leading-relaxed text-cream/80 lg:text-lg">
                {{ __('home.hero_subtitle') }}
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-sm bg-steel px-7 py-3.5 text-base font-semibold text-white shadow-sm transition-colors hover:bg-[#173a64]">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('nav.quote') }}
                </a>
                <a href="{{ route('public.products') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-sm border-2 border-cream/30 px-7 py-3.5 text-base font-semibold text-cream transition-colors hover:bg-cream/10">
                    {{ __('home.browse_products') }}
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        {{-- Trust badge strip --}}
        <div class="mt-14 grid max-w-3xl grid-cols-2 gap-px overflow-hidden rounded-sm border border-cream/15 bg-cream/15 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ([
                ['value' => '1998',    'label' => __('home.stat_year')],
                ['value' => '4',       'label' => __('home.stat_branches')],
                ['value' => '70+',     'label' => __('home.stat_employees')],
                ['value' => '7+',      'label' => __('home.stat_countries')],
                ['value' => 'ISPM 15', 'label' => 'Sertifikalı'],
            ] as $badge)
                <div class="flex flex-col items-center justify-center bg-wood-deep/80 px-4 py-5 text-center">
                    <span class="font-heading text-2xl font-bold text-wood-natural lg:text-3xl">{{ $badge['value'] }}</span>
                    <span class="mt-0.5 text-xs font-medium uppercase tracking-wide text-cream/70">{{ $badge['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════ --}}
{{-- PRODUCT SPLITTER                                                          --}}
{{-- ════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-sm font-semibold uppercase tracking-widest text-wood-natural">{{ __('home.how_we_work') }}</span>
            <h2 class="mt-3 font-heading text-3xl font-bold uppercase tracking-tight text-wood-deep lg:text-4xl">
                {{ __('home.splitter_title') }}
            </h2>
            <p class="mt-4 text-base leading-relaxed text-[#555555]">
                {{ __('home.splitter_subtitle') }}
            </p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            {{-- Industrial / Quote-only card --}}
            <div class="group flex flex-col overflow-hidden rounded-lg border-2 border-steel/30 bg-wood-deep">
                <div class="relative h-52 w-full overflow-hidden bg-wood-medium/60 flex items-end p-6">
                    {{-- Pattern bg for industrial look --}}
                    <div class="absolute inset-0 opacity-10"
                         style="background-image: repeating-linear-gradient(45deg, #8b5a2b 0, #8b5a2b 1px, transparent 0, transparent 50%); background-size: 12px 12px;"
                         aria-hidden="true"></div>
                    <span class="relative z-10 inline-flex items-center gap-2 rounded-sm bg-steel px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Teklife Özel
                    </span>
                </div>
                <div class="flex flex-1 flex-col p-7">
                    <h3 class="font-heading text-2xl font-bold uppercase tracking-tight text-cream">
                        {{ __('home.industrial_title') }}
                    </h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-cream/75">
                        {{ __('home.industrial_desc') }}
                    </p>
                    <a href="{{ route('public.products') }}?tip=quote_only"
                       class="mt-6 inline-flex items-center justify-center gap-2 rounded-sm bg-steel px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#173a64]">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('nav.quote') }}
                    </a>
                </div>
            </div>

            {{-- Store / Buyable card --}}
            <div class="group flex flex-col overflow-hidden rounded-lg border-2 border-forest/30 bg-white">
                <div class="relative h-52 w-full overflow-hidden bg-[#ECE3D6] flex items-end p-6">
                    <div class="absolute inset-0 opacity-10"
                         style="background-image: repeating-linear-gradient(-45deg, #2c5f2e 0, #2c5f2e 1px, transparent 0, transparent 50%); background-size: 12px 12px;"
                         aria-hidden="true"></div>
                    <span class="relative z-10 inline-flex items-center gap-2 rounded-sm bg-forest px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-white">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Hemen Al
                    </span>
                </div>
                <div class="flex flex-1 flex-col p-7">
                    <h3 class="font-heading text-2xl font-bold uppercase tracking-tight text-wood-deep">
                        {{ __('home.store_title') }}
                    </h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-[#555555]">
                        {{ __('home.store_desc') }}
                    </p>
                    <a href="{{ route('public.products') }}?tip=buyable"
                       class="mt-6 inline-flex items-center justify-center gap-2 rounded-sm bg-forest px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#1e4520]">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Mağazaya Git
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- QUOTE-ONLY PRODUCTS (dark, no price ever) --}}
@if ($quoteProducts->isNotEmpty())
<section id="urunler" class="scroll-mt-20 bg-wood-deep py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex flex-col gap-4 border-b border-cream/15 pb-8 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <span class="text-sm font-semibold uppercase tracking-widest text-wood-natural">{{ __('home.industrial_label') }}</span>
                <h2 class="mt-3 font-heading text-3xl font-bold uppercase tracking-tight text-cream lg:text-4xl">
                    {{ __('home.quote_products_title') }}
                </h2>
            </div>
            <p class="max-w-md text-sm leading-relaxed text-cream/70">
                Bu ürünler projeye özel üretilir. Ölçü ve adet bilgilerinizi paylaşın, ekibimiz size özel teklif hazırlasın.
            </p>
        </div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($quoteProducts->take(6) as $product)
                @php
                    $locale  = app()->getLocale();
                    $pName   = $product->getTranslation('name', $locale) ?? '—';
                    $pDesc   = $product->getTranslation('short_description', $locale) ?? $product->getTranslation('description', $locale) ?? '';
                    $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                    $imgUrl  = $primary ? \Illuminate\Support\Facades\Storage::disk('public')->url($primary->url) : null;
                @endphp
                <article class="group flex flex-col overflow-hidden rounded-lg border border-wood-medium/50 bg-white">
                    <div class="relative h-52 w-full overflow-hidden bg-[#ECE3D6]">
                        @if ($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $pName }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-wood-natural/40">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                        <span class="absolute right-3 top-3 rounded-sm bg-steel/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-white">
                            Teklife Özel
                        </span>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="font-heading text-xl font-bold uppercase tracking-tight text-wood-deep">{{ $pName }}</h3>
                        @if ($pDesc)
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-[#555555] line-clamp-2">{{ $pDesc }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        {{-- NO price, NO cart, NO "0 TL" — quote-only rule enforced --}}
                        <div class="mt-5 flex items-center gap-3">
                            <a href="{{ route('public.quote.create', ['urun' => $product->slug]) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 rounded-sm bg-steel px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#173a64]">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ __('nav.quote') }}
                            </a>
                            <a href="{{ route('public.product', $product->slug) }}"
                               class="inline-flex items-center justify-center rounded-sm border border-[#E6DFD2] px-3 py-2.5 text-sm text-[#555555] transition-colors hover:border-wood-natural hover:text-wood-deep">
                                Detay
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('public.products') }}?tip=quote_only"
               class="inline-flex items-center gap-2 rounded-sm border border-cream/30 px-6 py-3 text-sm font-semibold text-cream transition-colors hover:bg-cream/10">
                {{ __('home.view_all') }}
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- SANDIK CALCULATOR CTA --}}
<section id="hesaplama" class="relative isolate scroll-mt-20 overflow-hidden bg-wood-medium py-16 lg:py-24">
    <div class="absolute inset-0 bg-wood-deep/70" aria-hidden="true"></div>
    <div class="absolute inset-0 opacity-10"
         style="background-image: repeating-linear-gradient(0deg,#8b5a2b 0,#8b5a2b 1px,transparent 0,transparent 60px),repeating-linear-gradient(90deg,#8b5a2b 0,#8b5a2b 1px,transparent 0,transparent 60px);"
         aria-hidden="true"></div>
    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:items-center lg:px-8">
        <div>
            <span class="inline-flex items-center gap-2 rounded-sm bg-wood-natural px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-wood-deep">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Sandık Hesaplama
            </span>
            <h2 class="mt-5 font-heading text-3xl font-bold uppercase leading-tight tracking-tight text-cream lg:text-4xl">
                Ürününüz İçin En Uygun<br>Sandık Tipini Belirleyelim
            </h2>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-cream/80">
                Ölçü, ağırlık, adet ve teknik gereksinimlerinizi paylaşın; uzman ekibimiz en kısa sürede size özel teklif hazırlasın.
            </p>
            <a href="{{ route('public.sandik') }}"
               class="mt-8 inline-flex items-center justify-center gap-2 rounded-sm bg-steel px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-[#173a64]">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                {{ __('footer.sandik_link') }}
            </a>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach ([['M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4','Ölçü','01'],['M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3','Ağırlık','02'],['M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','Adet','03'],['M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','Teknik Çizim','04']] as [$path,$label,$num])
                <div class="flex flex-col gap-3 rounded-lg border border-cream/15 bg-wood-deep/60 p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <span class="flex h-11 w-11 items-center justify-center rounded-sm bg-wood-natural text-wood-deep">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                        </span>
                        <span class="font-heading text-2xl font-bold text-cream/30">{{ $num }}</span>
                    </div>
                    <span class="font-heading text-lg font-semibold uppercase tracking-wide text-cream">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STORE PREVIEW (buyable only — price + cart allowed) --}}
@if ($storeProducts->isNotEmpty())
<section id="magaza" class="scroll-mt-20 bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <span class="text-sm font-semibold uppercase tracking-widest text-forest">{{ __('home.store_label') }}</span>
                <h2 class="mt-3 font-heading text-3xl font-bold uppercase tracking-tight text-wood-deep lg:text-4xl">
                    {{ __('home.store_preview_title') }}
                </h2>
                <p class="mt-4 text-base leading-relaxed text-[#555555]">
                    Stoktan teslim, hemen satın alınabilen ürünler. Fiyatlar KDV dahildir.
                </p>
            </div>
            <a href="{{ route('public.products') }}?tip=buyable"
               class="inline-flex items-center gap-2 self-start rounded-sm border-2 border-forest px-5 py-2.5 text-sm font-semibold text-forest transition-colors hover:bg-forest hover:text-white md:self-auto">
                {{ __('home.all_products') }}
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($storeProducts->take(3) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@elseif ($rootCategories->isNotEmpty())
<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="font-heading text-3xl font-bold uppercase text-wood-deep">{{ __('home.categories_title') }}</h2>
                <p class="text-[#555555] mt-2">{{ __('home.categories_subtitle') }}</p>
            </div>
            <a href="{{ route('public.products') }}" class="hidden sm:inline text-sm font-medium text-steel hover:underline">
                {{ __('home.view_all') }} →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($rootCategories as $cat)
                @php $catName = $cat->getTranslation('name', app()->getLocale()) ?? '—'; @endphp
                <a href="{{ route('public.category', $cat->slug) }}"
                   class="group flex flex-col items-center text-center bg-white border border-[#E6DFD2] rounded-lg p-6 hover:border-wood-natural hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-full bg-[#F5F0E8] flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-wood-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-wood-deep leading-tight">{{ $catName }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- PROJECTS TEASER --}}
<section id="projeler" class="scroll-mt-20 bg-[#ECE3D6] py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <span class="text-sm font-semibold uppercase tracking-widest text-wood-natural">{{ __('home.projects_label') }}</span>
                <h2 class="mt-3 font-heading text-3xl font-bold uppercase tracking-tight text-wood-deep lg:text-4xl">
                    {{ __('home.projects_title') }}
                </h2>
            </div>
            <a href="{{ route('public.projects.index') }}"
               class="inline-flex items-center gap-2 self-start rounded-sm bg-wood-deep px-5 py-2.5 text-sm font-semibold text-cream transition-colors hover:bg-wood-medium md:self-auto">
                Tüm Projeleri Gör
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ([['Konteyner İhracat Ambalajı','İhracat Lojistiği','Ağır makine sevkiyatı için özel ölçülü ahşap sandık ve sabitleme çözümleri.'],['CNC İşlenmiş Üretim','Hassas Üretim','Seri üretimde milimetrik hassasiyetle kesilen kereste ve profil bileşenleri.'],["Üretim Tesisi &amp; Sahası",'Kurumsal Altyapı',"Gebze'deki tesisimizde stoklu kereste ve sürekli üretim kapasitesi."]] as [$ptitle,$pcat,$pdesc])
                <article class="group relative isolate flex h-72 flex-col justify-end overflow-hidden rounded-lg bg-wood-deep">
                    <div class="absolute inset-0 opacity-15"
                         style="background-image: repeating-linear-gradient(45deg,#8b5a2b 0,#8b5a2b 1px,transparent 0,transparent 50%);background-size:8px 8px;"
                         aria-hidden="true"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-wood-deep via-wood-deep/50 to-transparent" aria-hidden="true"></div>
                    <div class="relative p-6">
                        <span class="text-xs font-semibold uppercase tracking-widest text-wood-natural">{{ $pcat }}</span>
                        <h3 class="mt-1 font-heading text-xl font-bold uppercase tracking-tight text-cream">{!! $ptitle !!}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-cream/75">{{ $pdesc }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ABOUT / FACTORY STRENGTH --}}
<section id="kurumsal" class="scroll-mt-20 bg-cream py-16 lg:py-24">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 lg:grid-cols-2 lg:items-center lg:px-8">
        <div>
            <span class="text-sm font-semibold uppercase tracking-widest text-wood-natural">{{ __('home.about_label') }}</span>
            <h2 class="mt-3 font-heading text-3xl font-bold uppercase tracking-tight text-wood-deep lg:text-4xl">
                {{ __('home.about_title') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-[#555555]">
                1998 yılında Gebze, Kocaeli'de kurulan CNRWOOD, çeyrek asrı aşan deneyimiyle ahşap ambalaj ve üretim sektörünün güvenilir çözüm ortağıdır. 70'in üzerinde deneyimli çalışanımız ve 4 şubemizle endüstriyel ölçekte üretim yapıyor, ürünlerimizi 7'den fazla ülkeye ihraç ediyoruz.
            </p>
            <p class="mt-4 text-base leading-relaxed text-[#555555]">
                ISPM 15 standartlarına uygun ısıl işlemli ambalajdan özel ahşap yapılara kadar geniş bir üretim yelpazesinde kalite ve teslimat güvenilirliğini ön planda tutuyoruz.
            </p>
            <div class="mt-8 grid grid-cols-2 gap-4">
                @foreach ([['M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4','1998',__('home.stat_year')],['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','70+',__('home.stat_employees')],['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z','4',__('home.stat_branches')],['M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z','7+',__('home.stat_countries')]] as [$spath,$sval,$slabel])
                    <div class="flex items-center gap-4 rounded-lg border border-[#E6DFD2] bg-white p-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-sm bg-wood-deep text-cream">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $spath }}"/></svg>
                        </span>
                        <div>
                            <span class="font-heading text-2xl font-bold text-wood-deep">{{ $sval }}</span>
                            <span class="block text-xs font-medium uppercase tracking-wide text-[#555555]">{{ $slabel }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('public.corporate') }}"
               class="mt-8 inline-flex items-center gap-2 rounded-sm bg-wood-deep px-6 py-3 text-sm font-semibold text-cream transition-colors hover:bg-wood-medium">
                {{ __('nav.corporate') }}
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="relative">
            <div class="aspect-[4/3] overflow-hidden rounded-lg border border-[#E6DFD2] bg-[#ECE3D6] flex items-center justify-center">
                <div class="text-center p-12">
                    <span class="font-heading text-8xl font-bold text-wood-natural/20">CNR</span>
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        @foreach (['Sandık','Ambalaj','Kereste','Seren','Yapı','İhracat'] as $item)
                            <span class="rounded bg-wood-deep/10 px-2 py-1.5 text-xs font-semibold uppercase tracking-wide text-wood-deep">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-2 hidden rounded-lg bg-wood-deep px-7 py-5 text-cream shadow-xl sm:block lg:-right-6">
                <span class="font-heading text-4xl font-bold text-wood-natural">25+</span>
                <span class="mt-1 block text-sm font-medium uppercase tracking-wide text-cream/80">Yıllık Tecrübe</span>
            </div>
        </div>
    </div>
</section>

{{-- CONTACT CTA --}}
<section id="iletisim" class="scroll-mt-20 bg-wood-deep py-16 lg:py-24">
    <div class="mx-auto max-w-4xl px-4 text-center lg:px-8">
        <h2 class="font-heading text-3xl font-bold uppercase tracking-tight text-cream lg:text-5xl">
            {{ __('home.contact_title') }}
        </h2>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-cream/80 lg:text-lg">
            {{ __('home.contact_subtitle') }}
        </p>
        <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('public.quote.create') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-sm bg-steel px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-[#173a64] sm:w-auto">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('nav.quote') }}
            </a>
            <a href="https://wa.me/905325555555" target="_blank" rel="noopener noreferrer"
               class="inline-flex w-full items-center justify-center gap-2 rounded-sm bg-whatsapp px-7 py-3.5 text-base font-semibold text-white transition-colors hover:brightness-110 sm:w-auto">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                WhatsApp ile İletişime Geç
            </a>
        </div>
    </div>
</section>

@endsection
