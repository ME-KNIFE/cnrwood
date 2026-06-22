@extends('layouts.public')

@php
    $title           = __('projects.title') . ' — CNRWOOD';
    $metaDescription = __('projects.meta_desc');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.projects') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('projects.title') }}</h1>
        <p class="text-[#555555] mt-2">
            {{ __('projects.subtitle') }}
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if ($projects->isEmpty())
        <div class="text-center py-20 text-[#8B5A2B]">
            <p class="text-lg">{{ __('projects.empty') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($projects as $project)
                @php
                    $cover = $project->getCoverImageUrl();
                    $title = $project->getTranslation('title', app()->getLocale());
                    $desc  = $project->getTranslation('description', app()->getLocale());
                @endphp
                <article class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden flex flex-col
                                hover:shadow-md hover:border-[#8B5A2B] transition-all group">

                    <a href="{{ route('public.projects.show', $project->slug) }}"
                       class="block overflow-hidden aspect-video bg-[#F5F0E8]">
                        @if ($cover)
                            <img src="{{ $cover }}"
                                 alt="{{ $title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-14 h-14 text-[#C8B99A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    <div class="p-6 flex flex-col flex-1">
                        @if ($project->completed_at)
                            <p class="text-xs text-[#8B5A2B] mb-2">{{ $project->completed_at->format('Y') }}</p>
                        @endif

                        <h2 class="text-lg font-bold text-[#3E2006] mb-2 leading-snug">
                            <a href="{{ route('public.projects.show', $project->slug) }}"
                               class="hover:text-[#6B3A1F] transition-colors">
                                {{ $title }}
                            </a>
                        </h2>

                        @if ($desc)
                            <p class="text-sm text-[#555555] line-clamp-3 flex-1">{{ $desc }}</p>
                        @endif

                        <a href="{{ route('public.projects.show', $project->slug) }}"
                           class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#1F497D] hover:underline">
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
