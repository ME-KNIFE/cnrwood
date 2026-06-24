@extends('layouts.public')

@php
    $locale    = app()->getLocale();
    $pTitle    = $project->getTranslation('title', $locale);
    $content   = $project->getContent($locale);
    $imageAlt  = $locale === 'en'
        ? ($project->image_alt_en ?? $project->image_alt_tr ?? $pTitle)
        : ($project->image_alt_tr ?? $pTitle);
    $gallery   = $project->getMedia('project_gallery');
    $cover     = $project->getCoverImageUrl();
    $seoTitle  = ($project->getTranslation('meta_title', $locale) ?? $pTitle) . ' - CNRWOOD';
    $metaDescription = $project->getTranslation('meta_description', $locale)
        ?? ($content ? \Illuminate\Support\Str::limit(strip_tags($content), 160) : null);
@endphp

@section('content')

{{-- Hero breadcrumb --}}
<section class="bg-wood-50 border-b border-wood-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-wood-500 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.projects.index') }}" class="hover:underline">{{ __('breadcrumb.projects') }}</a>
            <span class="mx-1">/</span>
            <span class="text-wood-800">{{ $pTitle }}</span>
        </nav>

        <div class="flex flex-wrap items-center gap-3 mb-3">
            @if ($project->is_featured)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-wood-200 text-wood-700">
                    {{ __('projects.featured') }}
                </span>
            @endif
            @if ($project->category)
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-neutral-200 text-neutral-700">
                    {{ $project->category }}
                </span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold text-wood-800">{{ $pTitle }}</h1>
        @if ($project->completed_at)
            <p class="text-sm text-wood-500 mt-2">
                {{ __('projects.completed') }}: {{ $project->completed_at->translatedFormat('F Y') }}
            </p>
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
            @elseif ($gallery->isNotEmpty())
                <div class="rounded-lg overflow-hidden border border-wood-200 aspect-video bg-wood-50">
                    <img src="{{ $gallery->first()->getUrl() }}"
                         alt="{{ $pTitle }}"
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="rounded-lg border border-wood-200 aspect-video bg-wood-50 flex items-center justify-center">
                    <svg class="w-20 h-20 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif

            {{-- Content --}}
            @if ($content)
                <div class="prose prose-stone max-w-none text-neutral-700">
                    {!! nl2br(e($content)) !!}
                </div>
            @endif

            {{-- Spatie gallery (additional images) --}}
            @if ($gallery->count() > 1 || ($gallery->isNotEmpty() && $cover))
                <div>
                    <h2 class="text-lg font-bold text-wood-800 mb-4">{{ __('projects.gallery') }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($gallery as $media)
                            <a href="{{ $media->getUrl() }}"
                               target="_blank"
                               class="block rounded-lg overflow-hidden border border-wood-200 aspect-square
                                      bg-wood-50 hover:opacity-90 transition-opacity">
                                <img src="{{ $media->getUrl('thumb') ?: $media->getUrl() }}"
                                     alt="{{ $pTitle }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar -------------------------------------------------------- --}}
        <div class="space-y-6">

            {{-- Project info card --}}
            <div class="bg-white border border-wood-200 rounded-lg p-6 space-y-4">
                <h3 class="font-bold text-wood-800">{{ __('projects.details') }}</h3>

                @if ($project->client_name)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('projects.client_label') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $project->client_name }}</p>
                    </div>
                @endif

                @if ($project->location)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('projects.location_label') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $project->location }}</p>
                    </div>
                @endif

                @if ($project->category)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('projects.category_label') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $project->category }}</p>
                    </div>
                @endif

                @if ($project->completed_at)
                    <div>
                        <p class="text-xs text-wood-500 uppercase tracking-wider mb-0.5">{{ __('projects.completion_label') }}</p>
                        <p class="text-sm font-medium text-wood-800">{{ $project->completed_at->translatedFormat('F Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- CTA --}}
            <div class="bg-wood-800 text-white rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">{{ __('projects.cta_title') }}</h3>
                <p class="text-sm text-wood-300 mb-4">{{ __('projects.cta_body') }}</p>
                <a href="{{ route('public.quote.create') }}"
                   class="btn-primary btn-sm block text-center">
                    {{ __('nav.quote') }}
                </a>
            </div>

            {{-- Other projects --}}
            @if ($others->isNotEmpty())
                <div>
                    <h3 class="font-bold text-wood-800 mb-3">{{ __('projects.others') }}</h3>
                    <div class="space-y-3">
                        @foreach ($others as $other)
                            @php $otherCover = $other->getCoverImageUrl(); @endphp
                            <a href="{{ route('public.projects.show', $other->slug) }}"
                               class="flex items-center gap-3 bg-white border border-wood-200 rounded-lg p-3
                                      hover:border-wood-500 transition-colors group">
                                <div class="w-14 h-14 rounded bg-wood-50 overflow-hidden flex-shrink-0">
                                    @if ($otherCover)
                                        <img src="{{ $otherCover }}" alt=""
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-wood-800 group-hover:text-wood-600 line-clamp-2">
                                    {{ $other->getTranslation('title', $locale) }}
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
