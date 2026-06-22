@extends('layouts.public')

@php
    $title           = __('sandik.title') . ' — CNRWOOD';
    $metaDescription = __('sandik.subtitle');
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('breadcrumb.home'),         'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('breadcrumb.sandik'), 'item' => route('public.sandik')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ── Page Header ──────────────────────────────────────────────────────────── --}}
<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.sandik') }}</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">{{ __('sandik.title') }}</h1>
        <p class="text-[#555555] mt-2 max-w-3xl leading-relaxed">
            {{ __('sandik.subtitle') }} {{ __('sandik.free_note') }}
        </p>
    </div>
</section>

{{-- ── Main Form Section ────────────────────────────────────────────────────── --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">{{ __('sandik.errors_fix') }}</p>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── FORM ──────────────────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('public.sandik.store') }}"
              enctype="multipart/form-data"
              class="lg:col-span-2 space-y-8">
            @csrf

            {{-- Honeypot — hidden from real users; bots fill it --}}
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            {{-- ── SECTION 1: İletişim Bilgileri ──────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">1</span>
                    {{ __('sandik.section1') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="contact_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('quote.name') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="contact_name" id="contact_name" required maxlength="120"
                               value="{{ old('contact_name') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_name') border-red-400 @enderror">
                        @error('contact_name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('quote.email') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="email" name="contact_email" id="contact_email" required maxlength="160"
                               value="{{ old('contact_email') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_email') border-red-400 @enderror">
                        @error('contact_email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('quote.phone') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" required maxlength="20"
                               value="{{ old('contact_phone') }}"
                               placeholder="+90 5XX XXX XX XX"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_phone') border-red-400 @enderror">
                        @error('contact_phone')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="preferred_contact" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('quote.preferred_contact') }}
                        </label>
                        <select name="preferred_contact" id="preferred_contact"
                                class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                            <option value="email"    @selected(old('preferred_contact', 'email') === 'email')>E-posta</option>
                            <option value="phone"    @selected(old('preferred_contact') === 'phone')>Telefon</option>
                            <option value="whatsapp" @selected(old('preferred_contact') === 'whatsapp')>WhatsApp</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('quote.company') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" maxlength="160"
                               value="{{ old('company_name') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: Ölçüler & Ağırlık ───────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-1 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">2</span>
                    {{ __('sandik.section2_title') }}
                </h2>
                <p class="text-sm text-[#555555] mb-5 ml-9">{{ __('sandik.section2_desc') }}</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                    <div>
                        <label for="length_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.length') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="length_cm" id="length_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('length_cm') }}"
                               placeholder="ör. 120"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('length_cm') border-red-400 @enderror">
                        @error('length_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="width_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.width') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="width_cm" id="width_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('width_cm') }}"
                               placeholder="ör. 80"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('width_cm') border-red-400 @enderror">
                        @error('width_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="height_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.height') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="height_cm" id="height_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('height_cm') }}"
                               placeholder="ör. 60"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('height_cm') border-red-400 @enderror">
                        @error('height_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="weight_kg" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.weight') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="weight_kg" id="weight_kg" required min="0.01" max="99999" step="0.01"
                               value="{{ old('weight_kg') }}"
                               placeholder="ör. 250"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('weight_kg') border-red-400 @enderror">
                        @error('weight_kg')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: Sandık Tipi ───────────────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">3</span>
                    {{ __('sandik.section3') }} <span class="text-red-600 ml-1">*</span>
                </h2>
                @php
                    $crateOptions = [
                        'ahsap'         => ['label' => __('sandik.crate_wood'),    'desc' => __('sandik.crate_wood_desc')],
                        'osb'           => ['label' => __('sandik.crate_osb'),     'desc' => __('sandik.crate_osb_desc')],
                        'izgara'        => ['label' => __('sandik.crate_izgara'),   'desc' => __('sandik.crate_izgara_desc')],
                        'vinc_aparatli' => ['label' => __('sandik.crate_vinc'),    'desc' => __('sandik.crate_vinc_desc')],
                        'endcap'        => ['label' => __('sandik.crate_endcap'),  'desc' => __('sandik.crate_endcap_desc')],
                        'taban_izgara'  => ['label' => __('sandik.crate_taban'),   'desc' => __('sandik.crate_taban_desc')],
                        'bilmiyorum'    => ['label' => __('sandik.crate_unknown'),  'desc' => __('sandik.crate_unknown_desc')],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($crateOptions as $val => $opt)
                        <label class="flex items-start gap-3 p-3 border rounded cursor-pointer transition-colors
                                      {{ old('crate_type') === $val ? 'border-[#8B5A2B] bg-[#F5F0E8]' : 'border-[#E6DFD2] bg-white hover:border-[#8B5A2B] hover:bg-[#F5F0E8]' }}">
                            <input type="radio" name="crate_type" value="{{ $val }}"
                                   class="mt-0.5 accent-[#3E2006]"
                                   @checked(old('crate_type') === $val) required>
                            <span>
                                <span class="block text-sm font-medium text-[#3E2006]">{{ $opt['label'] }}</span>
                                <span class="block text-xs text-[#555555] mt-0.5">{{ $opt['desc'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('crate_type')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── SECTION 4: Teknik Gereksinimler ────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">4</span>
                    {{ __('sandik.section4') }}
                </h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_ispm15" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_ispm15'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">{{ __('sandik.req_ispm15') }}</span>
                            <span class="block text-xs text-[#555555]">{{ __('sandik.req_ispm15_desc') }}</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_forklift" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_forklift'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">{{ __('sandik.req_forklift') }}</span>
                            <span class="block text-xs text-[#555555]">{{ __('sandik.req_forklift_desc') }}</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_crane" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_crane'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">{{ __('sandik.req_crane') }}</span>
                            <span class="block text-xs text-[#555555]">{{ __('sandik.req_crane_desc') }}</span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- ── SECTION 5: Miktar & Sevkiyat ───────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">5</span>
                    {{ __('sandik.section5') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.quantity') }} <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" required min="1" max="999999"
                               value="{{ old('quantity', 1) }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('quantity') border-red-400 @enderror">
                        @error('quantity')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="shipping_type" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.shipping_type') }} <span class="text-red-600">*</span>
                        </label>
                        <select name="shipping_type" id="shipping_type" required
                                class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('shipping_type') border-red-400 @enderror">
                            <option value="ihracat" @selected(old('shipping_type', 'ihracat') === 'ihracat')>{{ __('sandik.export') }}</option>
                            <option value="ic"      @selected(old('shipping_type') === 'ic')>{{ __('sandik.domestic') }}</option>
                        </select>
                        @error('shipping_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="destination_country" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.destination_country') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                        </label>
                        <input type="text" name="destination_country" id="destination_country" maxlength="120"
                               value="{{ old('destination_country', 'Türkiye') }}"
                               placeholder="ör. Almanya"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                    <div>
                        <label for="destination_city" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.destination_city') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                        </label>
                        <input type="text" name="destination_city" id="destination_city" maxlength="120"
                               value="{{ old('destination_city') }}"
                               placeholder="ör. Hamburg"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="material" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            {{ __('sandik.material') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                        </label>
                        <input type="text" name="material" id="material" maxlength="120"
                               value="{{ old('material') }}"
                               placeholder="{{ __('sandik.material_placeholder') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                </div>
            </div>

            {{-- ── SECTION 6: Dosya & Notlar & Gönder ──────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">6</span>
                    {{ __('sandik.section6') }}
                </h2>

                {{-- File upload --}}
                <div class="mb-5">
                    <label for="attachment" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('sandik.attachment') }}
                        <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                    </label>
                    <input type="file" name="attachment" id="attachment"
                           accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx,.zip"
                           class="w-full text-sm text-[#555555]
                                  file:mr-3 file:py-2 file:px-4
                                  file:rounded file:border file:border-[#E6DFD2]
                                  file:text-sm file:font-medium
                                  file:bg-[#F5F0E8] file:text-[#3E2006]
                                  hover:file:bg-[#EDE7D8] cursor-pointer
                                  @error('attachment') ring-1 ring-red-400 @enderror">
                    <p class="text-xs text-[#555555] mt-1.5">
                        {{ __('sandik.attachment_hint') }}
                    </p>
                    @error('attachment')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('sandik.notes') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                    </label>
                    <textarea name="notes" id="notes" rows="5" maxlength="4000"
                              placeholder="{{ __('sandik.notes_placeholder') }}"
                              class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-5 pt-5 border-t border-[#E6DFD2] text-xs text-[#555555] leading-relaxed">
                    {{ __('sandik.kvkk') }}
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <p class="text-xs text-[#555555]">
                        <span class="text-red-600">*</span> {{ __('sandik.required_note') }}
                    </p>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3 text-base font-semibold rounded
                                   bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                        {{ __('sandik.submit') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>

        </form>

        {{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
        <aside class="space-y-6">

            <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-3">{{ __('sandik.how_it_works') }}</h3>
                <ol class="space-y-2 text-[#555555]">
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        {{ __('sandik.step1') }}
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        {{ __('sandik.step2') }}
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        {{ __('sandik.step3') }}
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                        {{ __('sandik.step4') }}
                    </li>
                </ol>
            </div>


            <div class="bg-white border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-3">{{ __('sandik.direct_contact') }}</h3>
                <ul class="space-y-2 text-[#555555]">
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">{{ __('contact.phone') }}</span>
                        <a href="tel:+902627512120" class="text-[#3E2006] hover:underline">+90 262 751 21 20</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">{{ __('contact.email') }}</span>
                        <a href="mailto:info@cnrwood.com" class="text-[#3E2006] hover:underline">info@cnrwood.com</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">{{ __('contact.hours_label') }}</span>
                        {{ __('contact.hours_value') }}
                    </li>
                </ul>
            </div>

            <div class="bg-[#1F497D]/5 border border-[#1F497D]/20 rounded-lg p-5 text-sm">
                <p class="text-[#1F497D] font-semibold mb-1">{{ __('sandik.free_badge') }}</p>
                <p class="text-[#555555]">{{ __('sandik.free_desc') }}</p>
            </div>

        </aside>
    </div>

</section>

@endsection
