@extends('layouts.public')

@php
    /** @var \App\Models\Product|null $product */
    $isProduct = $product !== null;

    if ($isProduct) {
        $prodName    = $product->getTranslation('name', app()->getLocale()) ?? '—';
        $catName     = $product->category?->getTranslation('name', app()->getLocale());
        $primary     = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $imgUrl      = $primary ? \Illuminate\Support\Facades\Storage::disk('public')->url($primary->url) : null;
        $title       = __('quote.product_h1', ['name' => $prodName]) . ' — CNRWOOD';
        $metaDescription = $prodName . ' için CNRWOOD\'dan ücretsiz ve bağlayıcı olmayan teklif alın.';
        $formAction  = route('public.quote.product.store', ['slug' => $product->slug]);
    } else {
        $title       = __('nav.quote') . ' — CNRWOOD';
        $metaDescription = 'Ahşap sandık, ambalaj, kapı sereni, kereste & levha ve ahşap yapı projeleriniz için CNRWOOD\'dan ücretsiz teklif alın.';
        $formAction  = route('public.quote.store');
    }
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            @if ($isProduct)
                <span class="mx-1">/</span>
                <a href="{{ route('public.products') }}" class="hover:underline">Ürünler</a>
                <span class="mx-1">/</span>
                <a href="{{ route('public.product', $product->slug) }}" class="hover:underline">{{ $prodName }}</a>
                <span class="mx-1">/</span>
                <span class="text-[#3E2006]">{{ __('breadcrumb.quote') }}</span>
            @else
                <span class="mx-1">/</span>
                <span class="text-[#3E2006]">Teklif Al</span>
            @endif
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">
            @if ($isProduct)
                {{ __('quote.product_h1', ['name' => $prodName]) }}
            @else
                {{ __('quote.general_h1') }}
            @endif
        </h1>
        <p class="text-[#555555] mt-2">
            {{ __('quote.subtitle') }}
        </p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">{{ __('quote.errors_fix') }}</p>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── FORM ──────────────────────────────────────────────────────── --}}
        <form method="POST" action="{{ $formAction }}"
              class="lg:col-span-2 bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8 space-y-5">
            @csrf

            {{-- Honeypot (hidden from real users; bots fill it) --}}
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.name') }} <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="contact_name" id="contact_name" required maxlength="120"
                           value="{{ old('contact_name') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.email') }} <span class="text-red-600">*</span>
                    </label>
                    <input type="email" name="contact_email" id="contact_email" required maxlength="160"
                           value="{{ old('contact_email') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.phone') }} <span class="text-red-600">*</span>
                    </label>
                    <input type="tel" name="contact_phone" id="contact_phone" required maxlength="20"
                           value="{{ old('contact_phone') }}"
                           placeholder="+90 5XX XXX XX XX"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
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

                <div>
                    <label for="company_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.company') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                    </label>
                    <input type="text" name="company_name" id="company_name" maxlength="160"
                           value="{{ old('company_name') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>

                <div>
                    <label for="tax_number" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.tax_number') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                    </label>
                    <input type="text" name="tax_number" id="tax_number" maxlength="20"
                           value="{{ old('tax_number') }}"
                           class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>
            </div>

            @if ($isProduct)
                <div>
                    <label for="quantity" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        {{ __('quote.quantity') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                    </label>
                    <input type="number" name="quantity" id="quantity" min="1" max="999999"
                           value="{{ old('quantity', 1) }}"
                           class="w-full sm:w-48 px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                </div>
            @endif

            <div>
                <label for="message" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                    {{ __('quote.message_label') }} <span class="text-xs text-[#555555] font-normal">{{ __('quote.optional') }}</span>
                </label>
                <textarea name="message" id="message" rows="6" maxlength="4000"
                          placeholder="{{ __('quote.message_placeholder') }}"
                          class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">{{ old('message') }}</textarea>
            </div>

            <div class="pt-2 border-t border-[#E6DFD2] text-xs text-[#555555] leading-relaxed">
                {{ __('quote.kvkk') }}
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
                <p class="text-xs text-[#555555]">
                    <span class="text-red-600">*</span> {{ __('quote.required_note') }}
                </p>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold rounded
                               bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                    {{ __('nav.quote') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        {{-- ── SIDEBAR ──────────────────────────────────────────────────── --}}
        <aside class="space-y-6">
            @if ($isProduct)
                <div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
                    @if ($imgUrl)
                        <div class="aspect-[4/3] bg-[#F5F0E8] overflow-hidden">
                            <img src="{{ $imgUrl }}" alt="{{ $prodName }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-4">
                        @if ($catName)
                            <p class="text-xs uppercase tracking-wide text-[#8B5A2B] mb-1">{{ $catName }}</p>
                        @endif
                        <h2 class="text-[#3E2006] font-semibold mb-2">{{ $prodName }}</h2>
                        @if ($product->sku)
                            <p class="text-xs text-[#555555] font-mono">SKU: {{ $product->sku }}</p>
                        @endif
                        <a href="{{ route('public.product', $product->slug) }}"
                           class="inline-block mt-3 text-sm text-[#1F497D] hover:underline">
                            {{ __('quote.back_to_product') }} →
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-3">{{ __('quote.direct_contact') }}</h3>
                <ul class="space-y-2 text-[#555555]">
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">Telefon</span>
                        <a href="tel:+902627512120" class="text-[#3E2006] hover:underline">+90 262 751 21 20</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">E-posta</span>
                        <a href="mailto:info@cnrwood.com" class="text-[#3E2006] hover:underline">info@cnrwood.com</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">Çalışma Saatleri</span>
                        Hafta içi 07:20 – 17:20
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-2">{{ __('quote.how_it_works') }}</h3>
                <ol class="list-decimal list-inside text-[#555555] space-y-1.5">
                    <li>{{ __('quote.step1') }}</li>
                    <li>{{ __('quote.step2') }}</li>
                    <li>{{ __('quote.step3') }}</li>
                    <li>{{ __('quote.step4') }}</li>
                </ol>
            </div>
        </aside>
    </div>

</section>

@endsection
