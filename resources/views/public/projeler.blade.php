@extends('layouts.public')

@php
    $locale = app()->getLocale();
    $title           = __('projects.title') . ' - CNRWOOD';
    $metaDescription = __('projects.meta_desc');
@endphp

@section('content')

{{-- Hero breadcrumb --}}
<section class="bg-wood-50 border-b border-wood-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-wood-500 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-wood-800">{{ __('breadcrumb.projects') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-wood-800">{{ __('projects.title') }}</h1>
        <p class="text-neutral-600 mt-2">{{ __('projects.subtitle') }}</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if ($projects->isEmpty())
        <div class="text-center py-20 text-wood-500">
            <p class="text-lg">{{ __('projects.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($projects as $project)
                @php
                    $cover    = $project->getCoverImageUrl();
                    $pTitle   = $project->getTranslation('title', $locale);
                    $excerpt  = $project->getExcerpt($locale);
                    $imageAlt = $locale === 'en'
                        ? ($project->image_alt_en ?? $project->image_alt_tr ?? $pTitle)
                        : ($project->image_alt_tr ?? $pTitle);
                @endphp
                <article class="bg-white border border-wood-200 rounded-lg overflow-hidden flex flex-col
                                hover:shadow-md hover:border-wood-500 transition-all group">

                    {{-- Thumbnail --}}
                    <a href="{{ route('public.projects.show', $project->slug) }}"
                       class="block overflow-hidden aspect-video bg-wood-50">
                        @if ($cover)
                            <img src="{{ $cover }}"
                                 alt="{{ $imageAlt }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-14 h-14 text-wood-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Card body --}}
                    <div class="p-6 flex flex-col flex-1">

                        {{-- Badges --}}
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if ($project->is_featured)
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-wood-100 text-wood-700">
                                    {{ __('projects.featured') }}
                                </span>
                            @endif
                            @if ($project->category)
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-600">
                                    {{ $project->category }}
                                </span>
                            @endif
                        </div>

                        @if ($project->completed_at)
                            <p class="text-xs text-wood-500 mb-1">{{ $project->completed_at->format('Y') }}</p>
                        @endif

                        <h2 class="text-lg font-bold text-wood-800 mb-2 leading-snug">
                            <a href="{{ route('public.projects.show', $project->slug) }}"
                               class="hover:text-wood-600 transition-colors">
                                {{ $pTitle }}
                            </a>
                        </h2>

                        @if ($project->client_name)
                            <p class="text-xs text-neutral-500 mb-2">{{ $project->client_name }}</p>
                        @endif

                        @if ($excerpt)
                            <p class="text-sm text-neutral-600 line-clamp-3 flex-1">{{ $excerpt }}</p>
                        @endif

                        <a href="{{ route('public.projects.show', $project->slug) }}"
                           class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-wood-800 hover:text-wood-600 transition-colors">
                            {{ __('projects.view') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

</section>

@endsection
