@extends('layouts.public')

@php
    $locale          = app()->getLocale();
    $title           = __('fairs.title') . ' - CNRWOOD';
    $metaDescription = __('fairs.meta_desc');
@endphp

@section('content')

{{-- Hero breadcrumb --}}
<section class="bg-wood-50 border-b border-wood-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-wood-500 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-wood-800">{{ __('breadcrumb.fairs') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-wood-800">{{ __('fairs.title') }}</h1>
        <p class="text-neutral-600 mt-2">{{ __('fairs.subtitle') }}</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-14">

    {{-- Upcoming fairs ---------------------------------------------------- --}}
    @if ($upcoming->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-wood-800 mb-6">{{ __('fairs.upcoming') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($upcoming as $fair)
                    @php
                        $name     = $fair->getTranslation('name', $locale);
                        $desc     = $fair->getTranslation('description', $locale);
                        $cover    = $fair->getCoverImageUrl();
                        $imageAlt = $locale === 'en'
                            ? ($fair->image_alt_en ?? $fair->image_alt_tr ?? $name)
                            : ($fair->image_alt_tr ?? $name);
                    @endphp
                    <article class="bg-white border-2 border-wood-500 rounded-lg overflow-hidden flex flex-col
                                    hover:shadow-md hover:border-wood-800 transition-all group relative">

                        {{-- Cover image or icon placeholder --}}
                        @if ($cover)
                            <a href="{{ route('public.fairs.show', $fair->slug) }}"
                               class="block overflow-hidden aspect-video bg-wood-50">
                                <img src="{{ $cover }}"
                                     alt="{{ $imageAlt }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            </a>
                        @else
                            <div class="aspect-video bg-wood-50 flex items-center justify-center">
                                <svg class="w-12 h-12 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Upcoming badge --}}
                        <span class="absolute top-3 right-3 text-xs font-bold px-2 py-0.5 rounded-full
                                     bg-wood-800 text-white">
                            {{ __('fairs.badge_upcoming') }}
                        </span>

                        {{-- Card body --}}
                        <div class="p-6 flex flex-col gap-3 flex-1">
                            <div>
                                @if ($fair->is_featured)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-wood-100 text-wood-700 mr-1">
                                        {{ __('fairs.featured') }}
                                    </span>
                                @endif
                                <h3 class="font-bold text-wood-800 text-lg leading-snug mt-1">
                                    <a href="{{ route('public.fairs.show', $fair->slug) }}"
                                       class="hover:text-wood-600 transition-colors">
                                        {{ $name }}
                                    </a>
                                </h3>
                                @if ($fair->city)
                                    <p class="text-sm text-wood-500 mt-0.5">{{ $fair->city }}</p>
                                @endif
                            </div>

                            <div class="text-sm text-neutral-600 space-y-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-wood-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>
                                        {{ $fair->start_date->format('d.m.Y') }}
                                        @if ($fair->end_date && $fair->end_date->ne($fair->start_date))
                                            — {{ $fair->end_date->format('d.m.Y') }}
                                        @endif
                                    </span>
                                </div>
                                @if ($fair->venue)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-wood-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $fair->venue }}</span>
                                    </div>
                                @endif
                            </div>

                            @if ($desc)
                                <p class="text-sm text-neutral-600 line-clamp-3 flex-1">{{ $desc }}</p>
                            @endif

                            <a href="{{ route('public.fairs.show', $fair->slug) }}"
                               class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-wood-800 hover:text-wood-600 transition-colors">
                                {{ __('fairs.view') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Past fairs -------------------------------------------------------- --}}
    @if ($past->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-wood-800 mb-6">{{ __('fairs.past') }}</h2>
            <div class="divide-y divide-wood-200 border border-wood-200 rounded-lg overflow-hidden bg-white">
                @foreach ($past as $fair)
                    @php $name = $fair->getTranslation('name', $locale); @endphp
                    <div class="flex flex-wrap items-center gap-4 px-6 py-4 hover:bg-wood-50 transition-colors">
                        {{-- Optional small thumbnail --}}
                        @if ($fair->getCoverImageUrl())
                            <div class="w-12 h-12 rounded-md overflow-hidden flex-shrink-0 bg-wood-100">
                                <img src="{{ $fair->getCoverImageUrl() }}" alt="{{ $name }}"
                                     class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-wood-800">
                                <a href="{{ route('public.fairs.show', $fair->slug) }}"
                                   class="hover:text-wood-600 transition-colors">
                                    {{ $name }}
                                </a>
                            </p>
                            @if ($fair->is_featured)
                                <span class="text-xs font-medium px-1.5 py-0.5 rounded bg-wood-100 text-wood-600">
                                    {{ __('fairs.featured') }}
                                </span>
                            @endif
                            @if ($fair->venue || $fair->city)
                                <p class="text-xs text-wood-500 mt-0.5">
                                    {{ implode(' — ', array_filter([$fair->venue, $fair->city])) }}
                                </p>
                            @endif
                        </div>
                        <div class="text-sm text-neutral-500 whitespace-nowrap">
                            {{ $fair->start_date->format('d.m.Y') }}
                            @if ($fair->end_date && $fair->end_date->ne($fair->start_date))
                                — {{ $fair->end_date->format('d.m.Y') }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($upcoming->isEmpty() && $past->isEmpty())
        <div class="text-center py-20 text-wood-500">
            <p class="text-lg">{{ __('fairs.empty') }}</p>
        </div>
    @endif

</section>

@endsection
