@extends('layouts.public')

@php
    $title           = 'Sandık Hesaplama — CNRWOOD | İhtiyacınıza Özel Ahşap Sandık';
    $metaDescription = 'Ürün ölçüleriniz, ağırlığınız ve teknik gereksinimlerinize göre özel sandık hesaplama hizmeti. Hızlı ve doğru teklif için CNRWOOD’a ulaşın.';
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Anasayfa',         'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Sandık Hesaplama', 'item' => route('public.sandik')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Sandık Hesaplama</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">Sandık Hesaplama</h1>
        <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">
            Ürünlerinizi en güvenli ve en uygun maliyetle taşımanız için özel ahşap sandık çözümleri sunuyoruz.
        </p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="bg-gradient-to-br from-[#F5F0E8] to-white border border-[#E6DFD2] rounded-lg p-8 sm:p-12 text-center">

        <div class="mx-auto w-16 h-16 rounded-full bg-[#3E2006]/10 flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold text-[#3E2006] mb-3">
            İnteraktif Hesaplama Aracı Yakında
        </h2>
        <p class="text-[#555555] max-w-2xl mx-auto leading-relaxed mb-8">
            Şu an detaylı sandık hesaplaması için sizinle birebir çalışıyoruz. Ürün ölçüleriniz,
            ağırlığınız, ihracat varış noktanız ve teknik gereksinimleriniz (ISPM 15, forklift,
            vinç vb.) hakkında bilgi verirseniz, en geç 1 iş günü içinde özel teklif hazırlıyoruz.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('public.quote.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold rounded
                      text-white bg-[#1F497D] hover:bg-[#173a64] transition-colors">
                Sandık Teklifi Al
            </a>
            <a href="{{ route('public.contact') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold rounded
                      border border-[#3E2006] text-[#3E2006] hover:bg-[#F5F0E8] transition-colors">
                Bizi Arayın
            </a>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ([
            ['1', 'Ürün Bilgileri', 'Ölçü, ağırlık ve içerik bilgilerinizi iletin.'],
            ['2', 'Teknik Gereksinimler', 'ISPM 15, forklift, vinç gibi özel ihtiyaçları belirtin.'],
            ['3', 'Özel Teklif', 'En geç 1 iş günü içinde size özel teklifimizi gönderelim.'],
        ] as [$n, $h, $p])
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-5">
                <div class="w-8 h-8 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center mb-3">{{ $n }}</div>
                <h4 class="font-semibold text-[#3E2006] mb-1">{{ $h }}</h4>
                <p class="text-sm text-[#555555] leading-relaxed">{{ $p }}</p>
            </div>
        @endforeach
    </div>

</section>

@include('partials.public-cta', [
    'ctaTitle' => 'Özel Sandık İhtiyacınız mı Var?',
    'ctaText'  => 'Ürün bilgilerinizi iletin; size en uygun ahşap sandık çözümünü birlikte tasarlayalım.',
])

@endsection
