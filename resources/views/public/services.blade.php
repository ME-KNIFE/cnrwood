@extends('layouts.public')

@php
    $title           = __('services.title') . ' — CNRWOOD';
    $metaDescription = 'CNR Ahşap’ın sunduğu hizmetler: özel ahşap sandık üretimi, ISPM 15 sertifikalı ihracat ambalajı, palet üretimi, kereste & levha tedariği ve ahşap yapı çözümleri.';
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('breadcrumb.home'),     'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('breadcrumb.services'), 'item' => route('public.services')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.services') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('services.title') }}</h1>
        <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">
            {{ __('services.subtitle') }}
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @php
            $services = [
                [
                    'title' => __('services.item1_title'),
                    'desc'  => __('services.item1_desc'),
                    'color' => '#3E2006',
                    'link'  => ['url' => route('public.products', ['kategori' => 'ahsap-sandik']), 'label' => __('services.item1_link')],
                ],
                [
                    'title' => __('services.item2_title'),
                    'desc'  => __('services.item2_desc'),
                    'color' => '#2C5F2E',
                    'link'  => ['url' => route('public.products', ['kategori' => 'ihracat-ambalaj']), 'label' => __('services.item2_link')],
                ],
                [
                    'title' => __('services.item3_title'),
                    'desc'  => __('services.item3_desc'),
                    'color' => '#8B5A2B',
                    'link'  => ['url' => route('public.products', ['kategori' => 'palet']), 'label' => __('services.item3_link')],
                ],
                [
                    'title' => __('services.item4_title'),
                    'desc'  => __('services.item4_desc'),
                    'color' => '#6B3A1F',
                    'link'  => ['url' => route('public.products'), 'label' => __('services.item4_link')],
                ],
                [
                    'title' => __('services.item5_title'),
                    'desc'  => __('services.item5_desc'),
                    'color' => '#1F497D',
                    'link'  => ['url' => route('public.quote.create'), 'label' => __('services.item5_link')],
                ],
                [
                    'title' => __('services.item6_title'),
                    'desc'  => __('services.item6_desc'),
                    'color' => '#3E2006',
                    'link'  => ['url' => route('public.sandik'), 'label' => __('services.item6_link')],
                ],
            ];
        @endphp

        @foreach ($services as $s)
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 flex flex-col">
                <div class="w-10 h-10 rounded mb-4 flex items-center justify-center" style="background-color: {{ $s['color'] }}15; color: {{ $s['color'] }};">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#3E2006] mb-2">{{ $s['title'] }}</h3>
                <p class="text-sm text-[#555555] leading-relaxed flex-grow">{{ $s['desc'] }}</p>
                <a href="{{ $s['link']['url'] }}" class="mt-4 inline-block text-sm font-medium text-[#1F497D] hover:underline">
                    {{ $s['link']['label'] }} &rarr;
                </a>
            </div>
        @endforeach

    </div>

</section>

@include('partials.public-cta', [
    'ctaTitle' => __('services.cta_title'),
    'ctaText'  => __('services.cta_text'),
])

@endsection
