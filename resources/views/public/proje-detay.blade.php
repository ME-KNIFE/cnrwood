@extends('layouts.public')

@php
    $localTitle = $project->getTranslation('title', app()->getLocale());
    $localDesc  = $project->getTranslation('description', app()->getLocale());
    $title   = ($project->getTranslation('meta_title', app()->getLocale()) ?? $localTitle) . ' — CNRWOOD';
    $metaDescription = $project->getTranslation('meta_description', app()->getLocale())
        ?? ($localDesc ? \Illuminate\Support\Str::limit($localDesc, 160) : null);
    $gallery = $project->getMedia('project_gallery');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.projects.index') }}" class="hover:underline">{{ __('breadcrumb.projects') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ $localTitle }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ $localTitle }}</h1>
        @if ($project->completed_at)
            <p class="text-sm text-[#8B5A2B] mt-2">{{ __('projects.completed') }}: {{ $project->completed_at->format('F Y') }}</p>
        @endif
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid lg:grid-cols-3 gap-10">

        {{-- Main content ──────────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Cover / first image ──────────────────────────────────────── --}}
            @if ($gallery->isNotEmpty())
                <div class="rounded-lg overflow-hidden border border-[#E6DFD2] aspect-video bg-[#F5F0E8]">
                    <img src="{{ $gallery->first()->getUrl() }}"
                         alt="{{ $localTitle }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            {{-- Description ──────────────────────────────────────────────── --}}
            @if ($localDesc)
                <div class="prose prose-stone max-w-none text-[#333333]">
                    {!! nl2br(e($localDesc)) !!}
                </div>
            @endif

            {{-- Full gallery ─────────────────────────────────────────────── --}}
            @if ($gallery->count() > 1)
                <div>
                    <h2 class="text-lg font-bold text-[#3E2006] mb-4">{{ __('projects.gallery') }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($gallery as $media)
                            <a href="{{ $media->getUrl() }}"
                               target="_blank"
                               class="block rounded-lg overflow-hidden border border-[#E6DFD2] aspect-square
                                      bg-[#F5F0E8] hover:opacity-90 transition-opacity">
                                <img src="{{ $media->getUrl('thumb') ?: $media->getUrl() }}"
                                     alt="{{ $localTitle }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar ──────────────────────────────────────────────────────── --}}
        <div class="space-y-6">

            {{-- Project info card ────────────────────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 space-y-4">
                <h3 class="font-bold text-[#3E2006]">{{ __('projects.details') }}</h3>
                @if ($project->completed_at)
                    <div>
                        <p class="text-xs text-[#8B5A2B] uppercase tracking-wider mb-0.5">{{ __('projects.completion_label') }}</p>
                        <p class="text-sm font-medium text-[#3E2006]">{{ $project->completed_at->format('F Y') }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-[#8B5A2B] uppercase tracking-wider mb-0.5">{{ __('projects.images_label') }}</p>
                    <p class="text-sm font-medium text-[#3E2006]">{{ __('projects.images_count', ['count' => $gallery->count()]) }}</p>
                </div>
            </div>

            {{-- CTA ─────────────────────────────────────────────────────── --}}
            <div class="bg-[#3E2006] text-white rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">{{ __('projects.cta_title') }}</h3>
                <p class="text-sm text-[#D4A96A] mb-4">{{ __('projects.cta_body') }}</p>
                <a href="{{ route('public.quote.create') }}"
                   class="block text-center py-2 px-4 bg-[#D4A96A] hover:bg-[#C49050] text-[#3E2006]
                          font-semibold rounded transition-colors text-sm">
                    {{ __('nav.quote') }}
                </a>
            </div>

            {{-- Other projects ───────────────────────────────────────────── --}}
            @if ($others->isNotEmpty())
                <div>
                    <h3 class="font-bold text-[#3E2006] mb-3">{{ __('projects.others') }}</h3>
                    <div class="space-y-3">
                        @foreach ($others as $other)
                            <a href="{{ route('public.projects.show', $other->slug) }}"
                               class="flex items-center gap-3 bg-white border border-[#E6DFD2] rounded-lg p-3
                                      hover:border-[#8B5A2B] transition-colors group">
                                @php $otherCover = $other->getCoverImageUrl(); @endphp
                                <div class="w-14 h-14 rounded bg-[#F5F0E8] overflow-hidden flex-shrink-0">
                                    @if ($otherCover)
                                        <img src="{{ $otherCover }}" alt=""
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-[#C8B99A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-[#3E2006] group-hover:text-[#6B3A1F] line-clamp-2">
                                    {{ $other->getTranslation('title', app()->getLocale()) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
</section>

@endsection
