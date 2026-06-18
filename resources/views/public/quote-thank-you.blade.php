@extends('layouts.public')

@php
    $title           = 'Teklif Talebiniz Alındı — CNRWOOD';
    $metaDescription = 'Teklif talebiniz başarıyla alındı. CNRWOOD ekibi en geç 1 iş günü içinde sizinle iletişime geçecektir.';
@endphp

@section('content')

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <div class="bg-white border border-[#E6DFD2] rounded-lg p-8 sm:p-12 text-center shadow-sm">

        <div class="mx-auto w-16 h-16 rounded-full bg-[#2C5F2E]/10 flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-[#2C5F2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-[#3E2006] mb-3">Teklif Talebiniz Alındı</h1>
        <p class="text-[#555555] leading-relaxed mb-6">
            Teşekkür ederiz. Talebiniz başarıyla kayıt altına alındı.
            Uzman ekibimiz <strong>en geç 1 iş günü içinde</strong> sizinle iletişime geçecek.
        </p>

        @if ($reference)
            <div class="inline-block bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg px-6 py-3 mb-8">
                <p class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-1">Referans Numaranız</p>
                <p class="text-xl font-mono font-bold text-[#3E2006]">{{ $reference }}</p>
            </div>
        @endif

        <div class="text-sm text-[#555555] mb-8 p-4 bg-[#1F497D]/5 border border-[#1F497D]/15 rounded text-left">
            <strong class="text-[#3E2006]">Önemli:</strong>
            Bu form için sizden herhangi bir ödeme alınmadı. Teklif tamamen ücretsiz ve
            bağlayıcı değildir.
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded
                      bg-[#3E2006] hover:bg-[#6B3A1F] text-white transition-colors">
                Anasayfaya Dön
            </a>
            <a href="{{ route('public.products') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded
                      bg-white border border-[#3E2006] text-[#3E2006] hover:bg-[#F5F0E8] transition-colors">
                Ürünleri İncele
            </a>
        </div>

        <p class="text-xs text-[#555555] mt-8 pt-6 border-t border-[#E6DFD2]">
            Acil durumlar için doğrudan arayabilirsiniz:
            <a href="tel:+902627512120" class="text-[#1F497D] hover:underline font-medium">+90 262 751 21 20</a>
        </p>
    </div>

</section>

@endsection
