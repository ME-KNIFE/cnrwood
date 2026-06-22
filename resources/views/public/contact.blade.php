@extends('layouts.public')

@php
    $title           = __('nav.contact') . ' — CNRWOOD';
    $metaDescription = __('contact.subtitle');
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.contact') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('breadcrumb.contact') }}</h1>
        <p class="text-[#555555] mt-2 max-w-2xl">
            {{ __('contact.subtitle') }}
            <br><strong>{{ __('contact.response_time') }}</strong>
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if (session('contact_success'))
        <div class="mb-6 p-5 bg-[#2C5F2E]/10 border border-[#2C5F2E]/30 rounded-lg flex items-start gap-3">
            <svg class="w-6 h-6 text-[#2C5F2E] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-[#2C5F2E] font-semibold">{{ __('contact.success_title') }}</p>
                <p class="text-[#2C5F2E]/80 text-sm mt-1">{{ __('contact.success_body') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">{{ __('contact.errors_fix') }}</p>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── CONTACT FORM ────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('public.contact.store') }}"
              class="lg:col-span-2 bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8 space-y-5">
            @csrf

            <h2 class="text-xl font-bold text-[#3E2006] pb-3 border-b border-[#E6DFD2]">{{ __('contact.send_message') }}</h2>

            {{-- Honeypot --}}
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('contact.name') }} <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" id="name" required maxlength="120"
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('contact.email') }} <span class="text-red-600">*</span>
                    </label>
                    <input type="email" name="email" id="email" required maxlength="160"
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('contact.phone') }} <span class="text-xs text-[#555555] font-normal">{{ __('contact.optional') }}</span>
                    </label>
                    <input type="tel" name="phone" id="phone" maxlength="20"
                           value="{{ old('phone') }}"
                           placeholder="+90 5XX XXX XX XX"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('contact.subject') }} <span class="text-xs text-[#555555] font-normal">{{ __('contact.optional') }}</span>
                    </label>
                    <input type="text" name="subject" id="subject" maxlength="255"
                           value="{{ old('subject') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                    {{ __('contact.message_label') }} <span class="text-red-600">*</span>
                </label>
                <textarea name="message" id="message" rows="7" required maxlength="4000"
                          class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">{{ old('message') }}</textarea>
            </div>

            <div class="pt-2 border-t border-[#E6DFD2] text-xs text-[#555555] leading-relaxed">
                {{ __('contact.kvkk') }}
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
                <p class="text-xs text-[#555555]">
                    <span class="text-red-600">*</span> {{ __('contact.required_note') }}
                </p>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded
                               bg-[#3E2006] hover:bg-[#6B3A1F] text-white transition-colors shadow-md">
                    {{ __('contact.send_message') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </form>

        {{-- ── INFO SIDEBAR ────────────────────────────────────────────── --}}
        <aside class="space-y-6">

            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
                <h3 class="font-bold text-[#3E2006] mb-4 pb-3 border-b border-[#E6DFD2]">{{ __('contact.info_card_title') }}</h3>

                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#6B3A1F] flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-0.5">{{ __('contact.address_label') }}</p>
                            <p class="text-[#3E2006]">
                                Pelitli Mah. Pelitli Yolu Cad.<br>
                                No: 137/A, Gebze / Kocaeli
                            </p>
                            <a href="https://www.google.com/maps/search/?api=1&query=CNR+Ahsap+Pelitli+Gebze+Kocaeli"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-block mt-1 text-xs text-[#1F497D] hover:underline">
                                {{ __('contact.map_link') }} →
                            </a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#6B3A1F] flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-0.5">Telefon</p>
                            <a href="tel:+902627512120" class="text-[#3E2006] hover:underline font-medium">+90 262 751 21 20</a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#6B3A1F] flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-0.5">E-posta</p>
                            <a href="mailto:info@cnrwood.com" class="text-[#3E2006] hover:underline font-medium">info@cnrwood.com</a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#6B3A1F] flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-0.5">{{ __('contact.hours_label') }}</p>
                            <p class="text-[#3E2006]">{{ __('contact.hours_value') }}</p>
                            <p class="text-xs text-[#555555]">{{ __('contact.weekend') }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- CTA — Teklif Al --}}
            <div class="bg-gradient-to-br from-[#1F497D] to-[#173a64] text-white rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">{{ __('contact.quote_cta_title') }}</h3>
                <p class="text-sm text-white/90 mb-4 leading-relaxed">{{ __('contact.quote_cta_body') }}</p>
                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded
                          bg-white text-[#1F497D] hover:bg-[#F5F0E8] transition-colors">
                    {{ __('nav.quote') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </aside>
    </div>

</section>

@endsection
