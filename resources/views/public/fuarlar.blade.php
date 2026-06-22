@extends('layouts.public')

@php
    $title           = __('fairs.title') . ' — CNRWOOD';
    $metaDescription = __('fairs.meta_desc');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.fairs') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('fairs.title') }}</h1>
        <p class="text-[#555555] mt-2">
            {{ __('fairs.subtitle') }}
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-14">

    {{-- Upcoming fairs ──────────────────────────────────────────────────────── --}}
    @if ($upcoming->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-[#3E2006] mb-6">{{ __('fairs.upcoming') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($upcoming as $fair)
                    @php
                        $name = $fair->getTranslation('name', app()->getLocale());
                        $desc = $fair->getTranslation('description', app()->getLocale());
                    @endphp
                    <div class="bg-white border-2 border-[#1F497D] rounded-lg p-6 flex flex-col gap-3 relative overflow-hidden">
                        <span class="absolute top-3 right-3 text-xs font-bold px-2 py-0.5 bg-[#1F497D] text-white rounded-full">
                            {{ __('fairs.badge_upcoming') }}
                        </span>

                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-[#EBF2FA] rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#1F497D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#3E2006] text-lg leading-snug">{{ $name }}</h3>
                                @if ($fair->city)
                                    <p class="text-sm text-[#8B5A2B] mt-0.5">{{ $fair->city }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="text-sm text-[#555555] space-y-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#8B5A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-4 h-4 text-[#8B5A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <p class="text-sm text-[#555555] line-clamp-3">{{ $desc }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Past fairs ──────────────────────────────────────────────────────────── --}}
    @if ($past->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-[#3E2006] mb-6">{{ __('fairs.past') }}</h2>
            <div class="divide-y divide-[#E6DFD2] border border-[#E6DFD2] rounded-lg overflow-hidden bg-white">
                @foreach ($past as $fair)
                    @php
                        $name = $fair->getTranslation('name', app()->getLocale());
                    @endphp
                    <div class="flex flex-wrap items-center gap-4 px-6 py-4 hover:bg-[#F5F0E8] transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-[#3E2006]">{{ $name }}</p>
                            @if ($fair->venue || $fair->city)
                                <p class="text-xs text-[#8B5A2B] mt-0.5">
                                    {{ implode(' — ', array_filter([$fair->venue, $fair->city])) }}
                                </p>
                            @endif
                        </div>
                        <div class="text-sm text-[#555555] whitespace-nowrap">
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
        <div class="text-center py-20 text-[#8B5A2B]">
            <p class="text-lg">{{ __('fairs.empty') }}</p>
        </div>
    @endif

</section>

@endsection
