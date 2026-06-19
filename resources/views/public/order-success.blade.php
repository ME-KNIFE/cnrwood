@extends('layouts.public')

@php
    /** @var \App\Models\Order|null $order */
    $title = 'Siparişiniz Alındı — CNRWOOD';
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Sipariş Onayı</span>
        </nav>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#2C5F2E]/10 mb-6">
        <svg class="w-10 h-10 text-[#2C5F2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-[#3E2006] mb-3">Siparişiniz Alındı!</h1>

    @if ($order)

        <p class="text-[#555555] mb-8 leading-relaxed">
            Teşekkürler, <strong class="text-[#3E2006]">{{ $order->customer_name }}</strong>.
            Siparişiniz başarıyla oluşturuldu.
        </p>

        <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 text-left mb-8">

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">Sipariş Numarası</span>
                <span class="font-mono font-bold text-[#3E2006]">{{ $order->order_number }}</span>
            </div>

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">Durum</span>
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#555555] bg-[#F5F0E8] px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    Beklemede
                </span>
            </div>

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">Toplam Tutar</span>
                <span class="font-bold text-[#3E2006]">
                    {{ number_format($order->total, 2, ',', '.') }} TL
                </span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-sm text-[#555555]">Onay E-postası</span>
                <span class="text-sm text-[#3E2006]">{{ $order->customer_email }}</span>
            </div>

        </div>

        {{-- EFT payment instructions --}}
        <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-6 text-left mb-8">
            <h2 class="text-base font-bold text-[#3E2006] mb-3">Havale / EFT Bilgileri</h2>
            <p class="text-sm text-[#555555] mb-4">
                Siparişinizi işleme alabilmemiz için lütfen aşağıdaki hesaba havale/EFT yapınız.
                Ödemeniz onaylandıktan sonra siparişiniz hazırlanmaya başlayacaktır.
            </p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#555555] font-medium">Banka</span>
                    <span class="text-[#3E2006]">— İletişim için lütfen bizi arayın —</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#555555] font-medium">Açıklama</span>
                    <span class="font-mono text-[#3E2006]">{{ $order->order_number }}</span>
                </div>
            </div>
            <p class="text-xs text-[#555555] mt-4">
                Havale açıklamasına sipariş numaranızı <strong>{{ $order->order_number }}</strong>
                yazmayı unutmayınız.
            </p>
        </div>

    @else

        <p class="text-[#555555] mb-8 leading-relaxed">
            Teşekkürler! Siparişiniz başarıyla alınmıştır.<br>
            Sipariş detaylarınız e-posta adresinize gönderilecektir.
        </p>

    @endif

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('public.products') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded
                  bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
            Alışverişe Devam Et
        </a>
        <a href="{{ route('public.contact') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded
                  border border-[#E6DFD2] text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
            Bize Ulaşın
        </a>
    </div>

</section>

@endsection
