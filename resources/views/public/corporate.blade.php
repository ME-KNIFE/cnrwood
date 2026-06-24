@extends('layouts.public')

@php
    $locale      = app()->getLocale();
    $spTitle     = $sitePage?->getTitle($locale);
    $spExcerpt   = $sitePage?->getExcerpt($locale);
    $spMetaTitle = $sitePage?->getMetaTitle($locale);
    $spMetaDesc  = $sitePage?->getMetaDescription($locale);
    $title           = $spMetaTitle ?? (__('corporate.title') . ' — CNRWOOD');
    $metaDescription = $spMetaDesc ?? 'Gebze merkezli CNR Ahsap; ahsap sandik, ihracat ambalaji, kereste & levha ve ahsap yapi projelerinde 1998den beri profesyonel cozum sunar.';
@endphp

@section('content')

{{-- BreadcrumbList JSON-LD --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('breadcrumb.home'),      'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('breadcrumb.corporate'), 'item' => route('public.corporate')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.corporate') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ $spTitle ?? __('corporate.title') }}</h1>
        <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">
            @if ($spExcerpt)
                {{ $spExcerpt }}
            @else
                CNR Ahşap, 1998'den bu yana Gebze merkezli üretim tesisinde; ahşap sandık,
                ihracat ambalajı, ISPM 15 ısıl işlemli ürünler, kereste &amp; levha ve ahşap yapı
                projelerinde Türkiye ve dünya genelinde güvenilir bir çözüm ortağıdır.
            @endif
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('public.about') }}"
           class="block bg-white border border-[#E6DFD2] rounded-lg p-6 hover:border-[#8B5A2B] hover:shadow-md transition">
            <div class="w-10 h-10 rounded bg-[#3E2006]/10 text-[#3E2006] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-[#3E2006] mb-2">{{ __('corporate.about_title') }}</h3>
            <p class="text-sm text-[#555555] leading-relaxed">{{ __('corporate.about_desc') }}</p>
            <span class="inline-block mt-3 text-sm text-[#1F497D] font-medium">{{ __('corporate.about_link') }} &rarr;</span>
        </a>

        <a href="{{ route('public.services') }}"
           class="block bg-white border border-[#E6DFD2] rounded-lg p-6 hover:border-[#8B5A2B] hover:shadow-md transition">
            <div class="w-10 h-10 rounded bg-[#2C5F2E]/10 text-[#2C5F2E] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-[#3E2006] mb-2">{{ __('corporate.services_title') }}</h3>
            <p class="text-sm text-[#555555] leading-relaxed">{{ __('corporate.services_desc') }}</p>
            <span class="inline-block mt-3 text-sm text-[#1F497D] font-medium">{{ __('corporate.services_link') }} &rarr;</span>
        </a>

        <a href="{{ route('public.products') }}"
           class="block bg-white border border-[#E6DFD2] rounded-lg p-6 hover:border-[#8B5A2B] hover:shadow-md transition">
            <div class="w-10 h-10 rounded bg-[#8B5A2B]/15 text-[#8B5A2B] flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-[#3E2006] mb-2">{{ __('corporate.products_title') }}</h3>
            <p class="text-sm text-[#555555] leading-relaxed">{{ __('corporate.products_desc') }}</p>
            <span class="inline-block mt-3 text-sm text-[#1F497D] font-medium">{{ __('corporate.products_link') }} &rarr;</span>
        </a>

    </div>

    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
        <div class="bg-[#F5F0E8] rounded-lg p-5">
            <div class="text-3xl font-bold text-[#3E2006]">1998</div>
            <div class="text-xs text-[#6B3A1F] mt-1 uppercase tracking-wider">{{ __('corporate.stat_founding') }}</div>
        </div>
        <div class="bg-[#F5F0E8] rounded-lg p-5">
            <div class="text-3xl font-bold text-[#3E2006]">25+</div>
            <div class="text-xs text-[#6B3A1F] mt-1 uppercase tracking-wider">{{ __('corporate.stat_experience') }}</div>
        </div>
        <div class="bg-[#F5F0E8] rounded-lg p-5">
            <div class="text-3xl font-bold text-[#3E2006]">ISPM 15</div>
            <div class="text-xs text-[#6B3A1F] mt-1 uppercase tracking-wider">{{ __('corporate.stat_certified') }}</div>
        </div>
        <div class="bg-[#F5F0E8] rounded-lg p-5">
            <div class="text-3xl font-bold text-[#3E2006]">Gebze</div>
            <div class="text-xs text-[#6B3A1F] mt-1 uppercase tracking-wider">{{ __('corporate.stat_facility') }}</div>
        </div>
    </div>

</section>

@include('partials.public-cta', [
    'ctaTitle' => __('corporate.cta_title'),
    'ctaText'  => __('corporate.cta_text'),
])

@endsection
