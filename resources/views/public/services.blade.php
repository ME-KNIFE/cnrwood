@extends('layouts.public')

@php
    $title           = 'Hizmetlerimiz — CNRWOOD | Ahşap Sandık, Palet ve İhracat Ambalajı';
    $metaDescription = 'CNR Ahşap’ın sunduğu hizmetler: özel ahşap sandık üretimi, ISPM 15 sertifikalı ihracat ambalajı, palet üretimi, kereste & levha tedariği ve ahşap yapı çözümleri.';
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Anasayfa',     'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Hizmetlerimiz', 'item' => route('public.services')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Hizmetlerimiz</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">Hizmetlerimiz</h1>
        <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">
            Üretim sürecinizin her aşamasında ihtiyaç duyacağınız ahşap çözümleri tek çatı altında sunuyoruz.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @php
            $services = [
                [
                    'title' => 'Ahşap Sandık Üretimi',
                    'desc'  => 'Standart ve özel ölçülerde ahşap sandık üretimi; ağır makine, otomotiv, savunma ve elektronik sektörlerine uygun çözümler.',
                    'color' => '#3E2006',
                    'link'  => ['url' => route('public.products', ['kategori' => 'ahsap-sandik']), 'label' => 'Sandıkları İncele'],
                ],
                [
                    'title' => 'ISPM 15 İhracat Ambalajı',
                    'desc'  => 'Uluslararası ihracat için ISPM 15 standardına uygun ısıl işlem görmüş (HT) ahşap ambalaj ve paletler.',
                    'color' => '#2C5F2E',
                    'link'  => ['url' => route('public.products', ['kategori' => 'ihracat-ambalaj']), 'label' => 'İhracat Ambalajı'],
                ],
                [
                    'title' => 'Palet Üretimi',
                    'desc'  => 'Euro palet, ISPM 15 palet ve özel ölçülerde palet üretimi. Lojistik ve depolama için dayanıklı çözümler.',
                    'color' => '#8B5A2B',
                    'link'  => ['url' => route('public.products', ['kategori' => 'palet']), 'label' => 'Palet Çeşitleri'],
                ],
                [
                    'title' => 'Kereste & Levha',
                    'desc'  => 'Çam, kayın ve karışık kereste; OSB ve diğer levha ürünleri için güvenilir tedarik.',
                    'color' => '#6B3A1F',
                    'link'  => ['url' => route('public.products'), 'label' => 'Ürün Kataloğu'],
                ],
                [
                    'title' => 'Ahşap Yapı Projeleri',
                    'desc'  => 'CLT ve özel ahşap yapı sistemleriyle restoran, çardak ve mimari uygulamalar için anahtar teslim üretim.',
                    'color' => '#1F497D',
                    'link'  => ['url' => route('public.quote.create'), 'label' => 'Proje Teklif Al'],
                ],
                [
                    'title' => 'Sandık Hesaplama',
                    'desc'  => 'Ürün ölçüleriniz ve teknik gereksinimleriniz için özel sandık hesaplama hizmeti.',
                    'color' => '#3E2006',
                    'link'  => ['url' => route('public.sandik'), 'label' => 'Hesaplama Aracı'],
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
    'ctaTitle' => 'Size En Uygun Hizmeti Birlikte Belirleyelim',
    'ctaText'  => 'Proje detaylarınızı paylaşın; uzman ekibimiz size özel teklif hazırlasın.',
])

@endsection
