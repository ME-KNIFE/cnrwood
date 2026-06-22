@extends('layouts.public')

@php
    $title           = 'Hakkımızda — CNRWOOD | 1998’den Beri Ahşap Üretimi';
    $metaDescription = '1998’den bu yana Gebze’de ahşap sandık, ihracat ambalajı, kereste & levha ve ahşap yapı projelerinde profesyonel üretim yapan CNR Ahşap’ı yakından tanıyın.';
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('breadcrumb.home'),   'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('breadcrumb.corporate'),   'item' => route('public.corporate')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => __('breadcrumb.about'), 'item' => route('public.about')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.corporate') }}" class="hover:underline">{{ __('breadcrumb.corporate') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.about') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('about.title') }}</h1>
        <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">
            {{ __('about.subtitle') }}
        </p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div>
        <h2 class="text-2xl font-bold text-[#3E2006] mb-4">{{ __('about.founding_title') }}</h2>
        <div class="prose prose-sm max-w-none text-[#555555] leading-relaxed space-y-4">
            <p>
                CNR Ahşap, 1998 yılında Gebze’de küçük bir atölye olarak faaliyetlerine
                başlamıştır. İlk günden itibaren ahşap işçiliğine duyduğumuz özen ve
                müşteri odaklı yaklaşımımız sayesinde kısa sürede güvenilir bir üretici
                kimliği kazandık.
            </p>
            <p>
                Bugün ihracat sandıkları, ISPM 15 standardına uygun ısıl işlem görmüş
                ambalaj, kereste &amp; levha ürünleri ve özel ahşap yapı projeleriyle
                Türkiye’nin önde gelen firmalarına ve uluslararası müşterilere hizmet
                veriyoruz.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
            <h3 class="text-lg font-semibold text-[#3E2006] mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#2C5F2E]"></span>
                {{ __('about.mission_title') }}
            </h3>
            <p class="text-sm text-[#555555] leading-relaxed">
                Müşterilerimizin ürün ve projelerini en güvenli, en verimli ve uluslararası
                standartlara uygun şekilde paketleyip korumak; ahşap üretiminde Türkiye’nin
                en güvenilir çözüm ortağı olmak.
            </p>
        </div>
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
            <h3 class="text-lg font-semibold text-[#3E2006] mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#1F497D]"></span>
                {{ __('about.vision_title') }}
            </h3>
            <p class="text-sm text-[#555555] leading-relaxed">
                Sürdürülebilir orman kaynaklarını ve modern üretim teknolojilerini birleştirerek
                ahşap çözümlerde Türkiye’nin lideri, dünya ölçeğinde ise tanınmış bir marka olmak.
            </p>
        </div>
    </div>

    <div>
        <h2 class="text-2xl font-bold text-[#3E2006] mb-6">{{ __('about.values_title') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ([
                [__('about.value1_title'), __('about.value1_text')],
                [__('about.value2_title'), __('about.value2_text')],
                [__('about.value3_title'), __('about.value3_text')],
                [__('about.value4_title'), __('about.value4_text')],
            ] as [$h, $p])
                <div class="bg-[#F5F0E8] rounded-lg p-5">
                    <h4 class="font-semibold text-[#3E2006] mb-2">{{ $h }}</h4>
                    <p class="text-sm text-[#6B3A1F] leading-relaxed">{{ $p }}</p>
                </div>
            @endforeach
        </div>
    </div>

</section>

@include('partials.public-cta')

@endsection
