@extends('layouts.public')

@php
    /** @var \App\Models\Cart $cart */
    $title = 'Siparişi Tamamla — CNRWOOD';
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <a href="{{ route('cart.index') }}" class="hover:underline">Sepetim</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Siparişi Tamamla</span>
        </nav>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-bold text-[#3E2006] mb-8">Siparişi Tamamla</h1>

    @if (session('checkout_error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            {{ session('checkout_error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            <p class="font-medium mb-1">Lütfen aşağıdaki hataları düzeltin:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" novalidate>
        @csrf

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            {{-- Form fields ──────────────────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Customer info ─────────────────────────────────────────── --}}
                <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
                    <h2 class="text-lg font-bold text-[#3E2006] mb-5">İletişim Bilgileri</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="sm:col-span-2">
                            <label for="customer_name"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Ad Soyad <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="customer_name"
                                   name="customer_name"
                                   value="{{ old('customer_name', auth()->user()?->name) }}"
                                   required
                                   maxlength="255"
                                   class="w-full border {{ $errors->has('customer_name') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('customer_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                E-posta <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="customer_email"
                                   name="customer_email"
                                   value="{{ old('customer_email', auth()->user()?->email) }}"
                                   required
                                   maxlength="255"
                                   autocomplete="email"
                                   class="w-full border {{ $errors->has('customer_email') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('customer_email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Telefon
                            </label>
                            <input type="tel"
                                   id="customer_phone"
                                   name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   maxlength="50"
                                   autocomplete="tel"
                                   class="w-full border {{ $errors->has('customer_phone') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('customer_phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Shipping address ──────────────────────────────────────── --}}
                <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
                    <h2 class="text-lg font-bold text-[#3E2006] mb-5">Teslimat Adresi</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label for="full_name"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Alıcı Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="full_name"
                                   name="full_name"
                                   value="{{ old('full_name', auth()->user()?->name) }}"
                                   required
                                   maxlength="255"
                                   class="w-full border {{ $errors->has('full_name') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('full_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Telefon (Teslimat)
                            </label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   maxlength="50"
                                   class="w-full border {{ $errors->has('phone') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address_line1"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Adres <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="address_line1"
                                   name="address_line1"
                                   value="{{ old('address_line1') }}"
                                   required
                                   maxlength="500"
                                   placeholder="Mahalle, cadde, sokak, bina no, daire no"
                                   class="w-full border {{ $errors->has('address_line1') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('address_line1')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address_line2"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Adres (devam)
                            </label>
                            <input type="text"
                                   id="address_line2"
                                   name="address_line2"
                                   value="{{ old('address_line2') }}"
                                   maxlength="500"
                                   class="w-full border {{ $errors->has('address_line2') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                        </div>

                        <div>
                            <label for="city"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                İl <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="city"
                                   name="city"
                                   value="{{ old('city') }}"
                                   required
                                   maxlength="100"
                                   class="w-full border {{ $errors->has('city') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            @error('city')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                İlçe
                            </label>
                            <input type="text"
                                   id="district"
                                   name="district"
                                   value="{{ old('district') }}"
                                   maxlength="100"
                                   class="w-full border {{ $errors->has('district') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                        </div>

                        <div>
                            <label for="postal_code"
                                   class="block text-sm font-medium text-[#3E2006] mb-1">
                                Posta Kodu
                            </label>
                            <input type="text"
                                   id="postal_code"
                                   name="postal_code"
                                   value="{{ old('postal_code') }}"
                                   maxlength="20"
                                   class="w-full border {{ $errors->has('postal_code') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#3E2006] mb-1">Ülke</label>
                            <input type="text"
                                   value="Türkiye"
                                   disabled
                                   class="w-full border border-[#E6DFD2] rounded px-3 py-2
                                          text-sm text-[#555555] bg-[#F5F0E8] cursor-not-allowed">
                        </div>

                    </div>
                </div>

                {{-- Payment info (EFT only — no gateway) ─────────────────── --}}
                <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
                    <h2 class="text-lg font-bold text-[#3E2006] mb-3">Ödeme Yöntemi</h2>

                    <div class="flex items-start gap-3 p-4 bg-[#F5F0E8] border border-[#E6DFD2] rounded">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-[#2C5F2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#3E2006]">Havale / EFT</p>
                            <p class="text-xs text-[#555555] mt-0.5">
                                Siparişiniz oluşturulduktan sonra banka hesap bilgilerimiz tarafınıza iletilecektir.
                                Ödemeniz onaylandığında siparişiniz işleme alınacaktır.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Order summary ───────────────────────────────────────────── --}}
            <div class="mt-8 lg:mt-0">
                <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-6 sticky top-24">

                    <h2 class="text-lg font-bold text-[#3E2006] mb-4">Sipariş Özeti</h2>

                    <div class="space-y-3 mb-4">
                        @foreach ($cart->items as $item)
                            @php
                                $pname = $item->product
                                    ? ($item->product->getTranslation('name', 'tr') ?? '—')
                                    : '—';
                                $vname = $item->variant
                                    ? (is_array($item->variant->name)
                                        ? ($item->variant->name['tr'] ?? null)
                                        : $item->variant->name)
                                    : null;
                            @endphp
                            <div class="flex justify-between items-start gap-2 text-sm">
                                <div class="flex-grow min-w-0">
                                    <p class="font-medium text-[#3E2006] leading-snug line-clamp-2">
                                        {{ $pname }}
                                        @if ($vname) <span class="text-[#555555]">({{ $vname }})</span> @endif
                                    </p>
                                    <p class="text-xs text-[#555555]">{{ $item->quantity }} adet</p>
                                </div>
                                <span class="font-medium text-[#3E2006] whitespace-nowrap flex-shrink-0">
                                    {{ number_format($item->getLineTotal(), 2, ',', '.') }} TL
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-[#E6DFD2] pt-4 space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-[#555555]">Ara Toplam</span>
                            <span class="font-medium text-[#3E2006]">
                                {{ number_format($cart->getSubtotal(), 2, ',', '.') }} TL
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#555555]">Kargo</span>
                            <span class="text-[#555555] italic text-xs">Hesaplanacak</span>
                        </div>
                    </div>

                    <div class="border-t border-[#E6DFD2] pt-4 flex justify-between font-bold text-[#3E2006] text-lg mb-1">
                        <span>Toplam</span>
                        <span>{{ number_format($cart->getSubtotal(), 2, ',', '.') }} TL</span>
                    </div>
                    <p class="text-xs text-[#555555] mb-6">(Kargo hariç)</p>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3
                                   text-base font-semibold rounded bg-[#2C5F2E] hover:bg-[#214a23]
                                   text-white transition-colors mb-3">
                        Siparişi Onayla
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>

                    <a href="{{ route('cart.index') }}"
                       class="w-full inline-flex items-center justify-center px-6 py-2.5
                              text-sm font-medium rounded border border-[#E6DFD2]
                              text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
                        Sepete Dön
                    </a>

                    <p class="text-xs text-[#555555] text-center mt-4">
                        Siparişinizi onaylayarak
                        <a href="{{ route('public.contact') }}" class="text-[#1F497D] hover:underline">gizlilik politikamızı</a>
                        kabul etmiş olursunuz.
                    </p>

                </div>
            </div>

        </div>

    </form>

</section>

@endsection
