@extends('layouts.public')

@php
    $catName = $category->getTranslation('name', 'tr') ?? '—';
    $catDesc = $category->getTranslation('description', 'tr');
    $title          = $catName . ' — CNRWOOD';
    $metaDescription = $catDesc ? strip_tags(\Illuminate\Support\Str::limit($catDesc, 160)) : ($catName . ' kategorisi altındaki CNRWOOD ürünleri.');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.products') }}" class="hover:underline">Ürünler</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ $catName }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ $catName }}</h1>
        @if ($catDesc)
            <p class="text-[#555555] mt-3 max-w-3xl leading-relaxed">{{ $catDesc }}</p>
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($products->isEmpty())
        <div class="text-center py-16 bg-white border border-[#E6DFD2] rounded-lg">
            <p class="text-[#555555] mb-4">Bu kategoride şu anda görüntülenecek ürün bulunmuyor.</p>
            <a href="{{ route('public.products') }}" class="text-[#1F497D] hover:underline">Tüm ürünleri göster</a>
        </div>
    @else
        <div class="mb-6 text-sm text-[#555555]">
            <strong class="text-[#3E2006]">{{ $products->total() }}</strong> ürün listeleniyor
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif

    @if ($siblings->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-[#E6DFD2]">
            <h2 class="text-xl font-bold text-[#3E2006] mb-4">Diğer Kategoriler</h2>
            <div class="flex flex-wrap gap-2">
                @foreach ($siblings as $sib)
                    <a href="{{ route('public.category', $sib->slug) }}"
                       class="inline-block px-4 py-2 text-sm bg-white border border-[#E6DFD2] rounded hover:border-[#8B5A2B] hover:text-[#3E2006] text-[#555555] transition-colors">
                        {{ $sib->getTranslation('name', 'tr') }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</section>

@endsection
