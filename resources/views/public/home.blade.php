@extends('layouts.public')

@php
    $title           = 'CNRWOOD — Ahşap Sandık, İhracat Ambalajı ve Ahşap Yapı Çözümleri | Gebze';
    $metaDescription = "1998'den beri Gebze'de ahşap sandık, ISPM 15 ısıl işlemli ihracat ambalajı, kapı sereni, kereste & levha ve ahşap yapı projelerinde profesyonel üretim. Hızlı teklif ve kaliteli işçilik.";
@endphp

@section('content')

{{-- Organization + LocalBusiness JSON-LD (Phase 12C) ─────────────────────── --}}
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

{{-- ── HERO ─────────────────────────────────────────────────────────────── --}}
<section class="relative bg-gradient-to-br from-[#3E2006] via-[#6B3A1F] to-[#8B5A2B] text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 20% 20%, white 1px, transparent 1px), radial-gradient(circle at 80% 80%, white 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="max-w-3xl">
            <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider bg-white/15 rounded-full mb-6">
                {{ __('home.hero_badge') }}
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                {{ __('home.hero_title') }} <span class="text-[#F5F0E8]">{{ __('home.hero_strong') }}</span>
            </h1>
            <p class="text-lg lg:text-xl text-white/90 mb-8 max-w-2xl leading-relaxed">
                {{ __('home.hero_subtitle') }}
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('public.products') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded
                          bg-white text-[#3E2006] hover:bg-[#F5F0E8] transition-colors shadow-lg">
                    {{ __('home.browse_products') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded
                          bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-lg">
                    {{ __('nav.quote') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── TRUST STRIP ──────────────────────────────────────────────────────── --}}
<section class="bg-white border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold text-[#3E2006]">1998</div>
                <div class="text-sm text-[#555555] mt-1">{{ __('home.stat_year') }}</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-[#3E2006]">4</div>
                <div class="text-sm text-[#555555] mt-1">{{ __('home.stat_branches') }}</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-[#3E2006]">70+</div>
                <div class="text-sm text-[#555555] mt-1">{{ __('home.stat_employees') }}</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-[#3E2006]">7+</div>
                <div class="text-sm text-[#555555] mt-1">{{ __('home.stat_countries') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ── CATEGORIES HIGHLIGHTS ────────────────────────────────────────────── --}}
@if ($rootCategories->isNotEmpty())
<section class="py-16 bg-[#FDFCFA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#3E2006]">{{ __('home.categories_title') }}</h2>
                <p class="text-[#555555] mt-2">{{ __('home.categories_subtitle') }}</p>
            </div>
            <a href="{{ route('public.products') }}" class="hidden sm:inline text-sm font-medium text-[#1F497D] hover:underline">
                {{ __('home.view_all') }} →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($rootCategories as $cat)
                @php $catName = $cat->getTranslation('name', app()->getLocale()) ?? '—'; @endphp
                <a href="{{ route('public.category', $cat->slug) }}"
                   class="group flex flex-col items-center justify-center text-center bg-white border border-[#E6DFD2] rounded-lg p-6 hover:border-[#8B5A2B] hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-full bg-[#F5F0E8] flex items-center justify-center mb-3 group-hover:bg-[#8B5A2B]/15 transition-colors">
                        <svg class="w-6 h-6 text-[#6B3A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-[#3E2006] group-hover:text-[#6B3A1F] leading-tight">
                        {{ $catName }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── FEATURED PRODUCTS ────────────────────────────────────────────────── --}}
@if ($featuredProducts->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#3E2006]">{{ __('home.featured_title') }}</h2>
                <p class="text-[#555555] mt-2">{{ __('home.featured_subtitle') }}</p>
            </div>
            <a href="{{ route('public.products') }}" class="hidden sm:inline text-sm font-medium text-[#1F497D] hover:underline">
                {{ __('home.all_products') }} →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA BANNER ───────────────────────────────────────────────────────── --}}
<section class="py-16 bg-[#F5F0E8]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-[#3E2006] mb-4">{{ __('home.cta_title') }}</h2>
        <p class="text-[#555555] text-lg mb-8 max-w-2xl mx-auto">
            {{ __('home.cta_body') }}
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('public.quote.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded
                      bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                {{ __('home.cta_btn') }}
            </a>
            <a href="tel:+902627512120"
               class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded
                      bg-white border border-[#3E2006] text-[#3E2006] hover:bg-[#FDFCFA] transition-colors">
                +90 262 751 21 20
            </a>
        </div>
    </div>
</section>

@endsection
