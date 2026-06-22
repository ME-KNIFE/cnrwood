@extends('layouts.public')

@php $title = 'Kart Bilgileri — CNRWOOD'; @endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <a href="{{ route('cart.index') }}" class="hover:underline">Sepetim</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Kart Bilgileri</span>
        </nav>
    </div>
</section>

<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F5F0E8] mb-4">
            <svg class="w-7 h-7 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-[#3E2006]">Kart Bilgilerinizi Girin</h1>
        <p class="text-sm text-[#555555] mt-1">
            Sipariş <span class="font-semibold">{{ $order->order_number }}</span> —
            <span class="font-semibold text-[#3E2006]">{{ number_format($order->total, 2, ',', '.') }} TL</span>
        </p>
    </div>

    {{-- Security badge ─────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-center gap-2 mb-6 text-xs text-[#8B5A2B]">
        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span>Kart bilgileriniz <strong>iyzico</strong> güvencesiyle 3D Secure ile işlenir ve sunucularımızda saklanmaz.</span>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ route('payment.initiate', $order) }}"
          novalidate
          class="bg-white border border-[#E6DFD2] rounded-lg p-8 space-y-5">
        @csrf

        {{-- Card holder ─────────────────────────────────────────────────────── --}}
        <div>
            <label for="card_holder" class="block text-sm font-medium text-[#3E2006] mb-1">
                Kart Üzerindeki Ad Soyad <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="card_holder"
                   name="card_holder"
                   value="{{ old('card_holder') }}"
                   required
                   autocomplete="cc-name"
                   placeholder="AD SOYAD"
                   maxlength="100"
                   class="w-full border {{ $errors->has('card_holder') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white uppercase tracking-wider
                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            @error('card_holder')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Card number ──────────────────────────────────────────────────────── --}}
        <div>
            <label for="card_number" class="block text-sm font-medium text-[#3E2006] mb-1">
                Kart Numarası <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="card_number"
                   name="card_number"
                   required
                   autocomplete="cc-number"
                   inputmode="numeric"
                   placeholder="0000 0000 0000 0000"
                   maxlength="19"
                   class="w-full border {{ $errors->has('card_number') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white tracking-widest font-mono
                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            @error('card_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Expire + CVC ─────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label for="card_expire_month" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Ay <span class="text-red-500">*</span>
                </label>
                <select id="card_expire_month"
                        name="card_expire_month"
                        required
                        autocomplete="cc-exp-month"
                        class="w-full border {{ $errors->has('card_expire_month') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                               rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                               focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                    <option value="">AA</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                @selected(old('card_expire_month') === str_pad($m, 2, '0', STR_PAD_LEFT))>
                            {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="card_expire_year" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Yıl <span class="text-red-500">*</span>
                </label>
                <select id="card_expire_year"
                        name="card_expire_year"
                        required
                        autocomplete="cc-exp-year"
                        class="w-full border {{ $errors->has('card_expire_year') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                               rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                               focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                    <option value="">YYYY</option>
                    @for ($y = date('Y'); $y <= date('Y') + 15; $y++)
                        <option value="{{ $y }}" @selected(old('card_expire_year') === (string) $y)>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="card_cvc" class="block text-sm font-medium text-[#3E2006] mb-1">
                    CVV/CVC <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       id="card_cvc"
                       name="card_cvc"
                       required
                       autocomplete="cc-csc"
                       inputmode="numeric"
                       placeholder="•••"
                       maxlength="4"
                       class="w-full border {{ $errors->has('card_cvc') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white font-mono
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('card_cvc')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 text-sm font-semibold rounded bg-[#3E2006] text-white
                       hover:bg-[#6B3A1F] transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            {{ number_format($order->total, 2, ',', '.') }} TL Öde
        </button>

    </form>

    {{-- Iyzico branding ─────────────────────────────────────────────────────── --}}
    <p class="mt-6 text-center text-xs text-[#8B5A2B]">
        Ödeme altyapısı
        <a href="https://iyzico.com" target="_blank" rel="noopener" class="font-semibold hover:underline">iyzico</a>
        tarafından sağlanmaktadır.
    </p>

</section>

{{-- Auto-format card number with spaces ────────────────────────────────────── --}}
<script>
document.getElementById('card_number').addEventListener('input', function (e) {
    let v = e.target.value.replace(/\D/g, '').substring(0, 16);
    e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
});
</script>

@endsection
