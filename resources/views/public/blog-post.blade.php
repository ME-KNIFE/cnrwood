@extends('layouts.public')

@php
    $title           = ($post->getTranslation('meta_title', app()->getLocale()) ?? $post->getTranslation('title', app()->getLocale())) . ' — CNRWOOD';
    $metaDescription = $post->getTranslation('meta_description', app()->getLocale()) ?? $post->getTranslation('excerpt', app()->getLocale()) ?? '';
@endphp

@section('content')

{{-- Hero / breadcrumb ──────────────────────────────────────────────────── --}}
<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('public.blog.index') }}" class="hover:underline">{{ __('breadcrumb.blog') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ \Illuminate\Support\Str::limit($post->getTranslation('title', app()->getLocale()), 50) }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006] leading-tight">
            {{ $post->getTranslation('title', app()->getLocale()) }}
        </h1>
        <div class="flex items-center gap-4 mt-4 text-sm text-[#8B5A2B]">
            @if ($post->published_at)
                <span>{{ $post->published_at->format('d.m.Y') }}</span>
            @endif
            @if ($post->author)
                <span>·</span>
                <span>{{ $post->author->name }}</span>
            @endif
        </div>
    </div>
</section>

{{-- Featured image ─────────────────────────────────────────────────────── --}}
@if ($post->featured_image_url)
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <img src="{{ $post->featured_image_url }}"
             alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
             class="w-full rounded-lg object-cover max-h-96"
             loading="lazy">
    </div>
@endif

{{-- Body ───────────────────────────────────────────────────────────────── --}}
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="prose prose-stone max-w-none
                prose-headings:text-[#3E2006] prose-a:text-[#1F497D]
                prose-img:rounded-lg prose-pre:bg-[#F5F0E8]">
        {!! nl2br(e($post->getTranslation('body', app()->getLocale()))) !!}
    </div>
</article>

{{-- Related posts ──────────────────────────────────────────────────────── --}}
@if ($related->isNotEmpty())
    <section class="border-t border-[#E6DFD2] bg-[#F5F0E8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-xl font-bold text-[#3E2006] mb-6">{{ __('blog.related') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($related as $rel)
                    <a href="{{ route('public.blog.show', $rel->slug) }}"
                       class="bg-white border border-[#E6DFD2] rounded-lg p-5
                              hover:border-[#8B5A2B] hover:shadow-sm transition-all block">
                        <p class="text-xs text-[#8B5A2B] mb-1">{{ $rel->published_at?->format('d.m.Y') }}</p>
                        <p class="text-sm font-semibold text-[#3E2006] line-clamp-2">
                            {{ $rel->getTranslation('title', app()->getLocale()) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
