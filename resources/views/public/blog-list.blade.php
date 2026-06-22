@extends('layouts.public')

@php
    $title           = 'Blog — CNRWOOD';
    $metaDescription = 'CNRWOOD blog: ahşap ambalaj, ihracat sandığı, ISPM 15 ve sektör haberleri.';
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Blog</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">Blog</h1>
        <p class="text-[#555555] mt-2">Sektör haberleri, teknik bilgiler ve CNRWOOD dünyasından güncellemeler.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if ($posts->isEmpty())
        <div class="text-center py-20 text-[#8B5A2B]">
            <p class="text-lg">Henüz yayınlanmış yazı bulunmuyor.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($posts as $post)
                <article class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden flex flex-col
                                hover:shadow-md hover:border-[#8B5A2B] transition-all group">

                    @if ($post->featured_image_url)
                        <a href="{{ route('public.blog.show', $post->slug) }}" class="block overflow-hidden aspect-video bg-[#F5F0E8]">
                            <img src="{{ $post->featured_image_url }}"
                                 alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </a>
                    @else
                        <a href="{{ route('public.blog.show', $post->slug) }}"
                           class="block aspect-video bg-[#F5F0E8] flex items-center justify-center">
                            <svg class="w-12 h-12 text-[#C8B99A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </a>
                    @endif

                    <div class="p-6 flex flex-col flex-1">
                        <p class="text-xs text-[#8B5A2B] mb-2">
                            {{ $post->published_at?->format('d.m.Y') }}
                        </p>
                        <h2 class="text-lg font-bold text-[#3E2006] mb-2 leading-snug">
                            <a href="{{ route('public.blog.show', $post->slug) }}"
                               class="hover:text-[#6B3A1F] transition-colors">
                                {{ $post->getTranslation('title', app()->getLocale()) }}
                            </a>
                        </h2>

                        @php $excerpt = $post->getTranslation('excerpt', app()->getLocale()); @endphp
                        @if ($excerpt)
                            <p class="text-sm text-[#555555] line-clamp-3 flex-1">{{ $excerpt }}</p>
                        @endif

                        <a href="{{ route('public.blog.show', $post->slug) }}"
                           class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#1F497D] hover:underline">
                            Devamını Oku
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    @endif

</section>

@endsection
