@extends('layouts.public')

@php
    $title           = __('products.title') . ' — CNRWOOD';
    $metaDescription = __('products.meta_desc');
    $locale          = app()->getLocale();
@endphp

@section('content')

<section class="bg-wood-100 border-b border-wood-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-wood-500 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-wood-800">{{ __('breadcrumb.products') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-wood-800">{{ __('products.all_title') }}</h1>
        <p class="text-neutral-500 mt-2">{{ __('products.all_subtitle') }}</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('public.products') }}"
          class="bg-white border border-wood-200 rounded-lg p-4 mb-8 grid grid-cols-1 md:grid-cols-4 gap-3">

        <div class="md:col-span-2">
            <label for="q" class="sr-only">{{ __('products.search_placeholder') }}</label>
            <input type="search" name="q" id="q" value="{{ $searchTerm }}" maxlength="100"
                   placeholder="{{ __('products.search_placeholder') }}"
                   class="w-full px-3 py-2 border border-wood-200 rounded text-sm focus:outline-none focus:border-wood-500 focus:ring-1 focus:ring-wood-500">
        </div>

        <div>
            <label for="kategori" class="sr-only">{{ __('products.filter_all') }}</label>
            <select name="kategori" id="kategori"
                    class="w-full px-3 py-2 border border-wood-200 rounded text-sm focus:outline-none focus:border-wood-500 focus:ring-1 focus:ring-wood-500">
                <option value="">{{ __('products.filter_all') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected($selectedSlug === $cat->slug)>
                        {{ $cat->getTranslation('name', $locale) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <select name="tip"
                    class="flex-1 px-3 py-2 border border-wood-200 rounded text-sm focus:outline-none focus:border-wood-500 focus:ring-1 focus:ring-wood-500">
                <option value="">{{ __('products.filter_all_types') }}</option>
                <option value="buyable" @selected($selectedType === 'buyable')>{{ __('products.filter_buyable') }}</option>
                <option value="quote_only" @selected($selectedType === 'quote_only')>{{ __('products.filter_quote') }}</option>
            </select>
            <button type="submit"
                    class="btn-secondary btn-sm">
                {{ __('products.filter_btn') }}
            </button>
        </div>
    </form>

    {{-- ACTIVE FILTERS SUMMARY --}}
    @if ($searchTerm !== '' || $selectedSlug !== '' || $selectedType !== null)
        <div class="mb-6 text-sm text-neutral-500">
            <strong class="text-wood-800">{{ $products->total() }}</strong> {{ __('products.results_found', ['count' => '']) }}
            @if ($searchTerm !== '') · {{ __('products.search_label') }} <em>"{{ $searchTerm }}"</em> @endif
            ·
            <a href="{{ route('public.products') }}" class="text-info hover:underline">{{ __('products.clear_filters') }}</a>
        </div>
    @endif

    {{-- PRODUCT GRID --}}
    @if ($products->isEmpty())
        <div class="text-center py-16 bg-white border border-wood-200 rounded-lg">
            <p class="text-neutral-500 mb-4">{{ __('products.empty') }}</p>
            <a href="{{ route('public.products') }}" class="text-info hover:underline">{{ __('products.explore') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif

</section>

@endsection
