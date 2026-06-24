@extends('layouts.public')

@php
    $locale          = app()->getLocale();
    $fName           = $fair->getTranslation('name', $locale);
    $fDesc           = $fair->getTranslation('description', $locale);
    $cover           = $fair->getCoverImageUrl();
    $imageAlt        = $locale === 'en'
        ? ($fair->image_alt_en ?? $fair->image_alt_tr ?? $fName)
        : ($fair->image_alt_tr ?? $fName);
    $title           = $fName . ' - CNRWOOD';
    $metaDescription = $fDesc ? \Illuminate\Support\Str::limit(strip_tags($fDesc), 160) : __('fairs.meta_desc');
@endphp

@section('content')

{{-- Hero breadcrumb --}}
<section class="bg-wood-50 border-b border-wood-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-wood-500 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.fairs.index') }}" class="hover:underline">{{ __('breadcrumb.fairs') }}</a>
            <span class="mx-1">/</span>
            <span class="text-wood-800">{{ $fName }}</span>
        </nav>

        <div class="flex flex-wrap items-center gap-2 mb-3">
            @if ($fair->isUpcoming())
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-wood-800 text-white">
                    {{ __('fairs.badge_upcoming') }}
                </span>
            @endif
            @if ($fair->is_featured)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-wood-100 text-wood-700">
                    {{ __('fairs.featured') }}
                </span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold text-wood-800">{{ $fName }}</h1>
        @if ($fair->city)
            <p class="text-sm text-wood-500 mt-2">{{ $fair->city }}</p>
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid lg:grid-cols-3 gap-10">

        {{-- Main content -------------------------------------------------- --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Cover image --}}
            @if ($cover)
                <div class="rounded-lg overflow-hidden border border-wood-200 aspect-video bg-wood-50">
                    <img src="{{ $cover }}"
                         alt="{{ $imageAlt }}"
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="rounded-lg border border-wood-200 aspect-video bg-wood-50 flex items-center justify-center">
                    <svg class="w-20 h-20 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- Description --}}
            @if ($fDesc)
                <div class="prose prose-stone max-w-none text-neutral-700">
                    {!! nl2br(e($fDesc)) !!}
                </div>
            @endif

        </div>

        {{-- Sidebar -------------------------------------------------------- --}}
        <div class="space-y-6">

            {{-- Fair info card --}}
            <div class="bg-white border border-wood-200 rounded-lg p-6 space-y-4">
                <h3 class="font-bold text-wood-800">{{ __('fairs.details') }}</h3>

                <div>
                    <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('fairs.start_date') }}</p>
                    <p class="text-sm font-medium text-wood-800">{{ $fair->start_date->format('d.m.Y') }}</p>
                </div>

                @if ($fair->end_date && $fair->end_date->ne($fair->start_date))
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('fairs.end_date') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $fair->end_date->format('d.m.Y') }}</p>
                    </div>
                @endif

                @if ($fair->venue)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('fairs.venue') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $fair->venue }}</p>
                    </div>
                @endif

                @if ($fair->city)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('fairs.city') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $fair->city }}</p>
                    </div>
                @endif
            </div>

            {{-- CTA --}}
            <div class="bg-wood-800 text-white rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">{{ __('fairs.cta_title') }}</h3>
                <p class="text-sm text-wood-300 mb-4">{{ __('fairs.cta_body') }}</p>
                <a href="{{ route('public.quote.create') }}"
                   class="btn-primary btn-sm block text-center">
                    {{ __('nav.quote') }}
                </a>
            </div>

            {{-- Other fairs --}}
            @if ($others->isNotEmpty())
                <div>
                    <h3 class="font-bold text-wood-800 mb-3">{{ __('fairs.others') }}</h3>
                    <div class="space-y-3">
                        @foreach ($others as $other)
                            @php $otherCover = $other->getCoverImageUrl(); @endphp
                            <a href="{{ route('public.fairs.show', $other->slug) }}"
                               class="flex items-center gap-3 bg-white border border-wood-200 rounded-lg p-3
                                      hover:border-wood-500 transition-colors group">
                                <div class="w-12 h-12 rounded bg-wood-50 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if ($otherCover)
                                        <img src="{{ $otherCover }}" alt=""
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-wood-800 group-hover:text-wood-600 line-clamp-2">
                                        {{ $other->getTranslation('name', $locale) }}
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-0.5">{{ $other->start_date->format('d.m.Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>

@endsection
