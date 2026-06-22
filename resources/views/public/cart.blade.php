@extends('layouts.public')

@php
    /** @var \App\Models\Cart $cart */
    $title = 'Sepetim — CNRWOOD';
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Sepetim</span>
        </nav>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-bold text-[#3E2006] mb-8">Sepetim</h1>

    @if (session('cart_success'))
        <div class="mb-6 p-4 bg-[#2C5F2E]/10 border border-[#2C5F2E]/30 rounded text-[#2C5F2E] text-sm">
            {{ session('cart_success') }}
        </div>
    @endif

    @if (session('cart_error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            {{ session('cart_error') }}
        </div>
    @endif

    @if ($cart->items->isEmpty())

        <div class="text-center py-20">
            <svg class="mx-auto w-16 h-16 text-[#8B5A2B]/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-[#555555] text-lg mb-6">Sepetiniz boş.</p>
            <a href="{{ route('public.products') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded
                      bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
                Ürünlere Göz At
            </a>
        </div>

    @else

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            {{-- Cart items ──────────────────────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-4">

                @foreach ($cart->items as $item)
                    @php
                        $product = $item->product;
                        $pname   = $product ? ($product->getTranslation('name', 'tr') ?? '—') : '—';
                        $vname   = $item->variant
                                    ? (is_array($item->variant->name)
                                        ? ($item->variant->name['tr'] ?? null)
                                        : $item->variant->name)
                                    : null;
                        $isValid = $product && $product->is_active && $product->isBuyable();
                        $img     = $product ? ($product->images->firstWhere('is_primary', true) ?? $product->images->first()) : null;
                    @endphp

                    <div class="flex gap-4 p-4 bg-white border border-[#E6DFD2] rounded-lg {{ ! $isValid ? 'opacity-60' : '' }}">

                        {{-- Thumbnail --}}
                        <div class="w-20 h-20 flex-shrink-0 bg-[#F5F0E8] border border-[#E6DFD2] rounded overflow-hidden">
                            @if ($img)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($img->url) }}"
                                     alt="{{ $pname }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#8B5A2B]/40">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow min-w-0">
                            @if ($product)
                                <a href="{{ route('public.product', $product->slug) }}"
                                   class="font-semibold text-[#3E2006] hover:underline leading-snug line-clamp-2">
                                    {{ $pname }}
                                </a>
                            @else
                                <p class="font-semibold text-[#3E2006] leading-snug">{{ $pname }}</p>
                            @endif

                            @if ($vname)
                                <p class="text-sm text-[#555555] mt-0.5">{{ $vname }}</p>
                            @endif

                            @if (! $isValid)
                                <p class="text-xs text-red-600 mt-1 font-medium">Bu ürün artık satışta değil.</p>
                            @endif

                            <p class="text-sm font-semibold text-[#2C5F2E] mt-1">
                                {{ number_format($item->unit_price, 2, ',', '.') }} TL / adet
                            </p>
                        </div>

                        {{-- Controls --}}
                        <div class="flex flex-col items-end justify-between gap-2 flex-shrink-0">

                            {{-- Quantity update --}}
                            <form method="POST" action="{{ route('cart.update', $item) }}">
                                @csrf
                                <label class="sr-only" for="qty-{{ $item->id }}">Adet</label>
                                <select id="qty-{{ $item->id }}"
                                        name="quantity"
                                        onchange="this.form.submit()"
                                        class="text-sm border border-[#E6DFD2] rounded px-2 py-1 bg-white text-[#3E2006] cursor-pointer">
                                    @for ($q = 1; $q <= 99; $q++)
                                        <option value="{{ $q }}" {{ $item->quantity === $q ? 'selected' : '' }}>{{ $q }}</option>
                                    @endfor
                                </select>
                            </form>

                            {{-- Line total --}}
                            <span class="text-sm font-bold text-[#3E2006] whitespace-nowrap">
                                {{ number_format($item->getLineTotal(), 2, ',', '.') }} TL
                            </span>

                            {{-- Remove --}}
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Ürünü sepetten kaldırmak istediğinize emin misiniz?')"
                                        class="text-xs text-[#555555] hover:text-red-600 transition-colors underline">
                                    Kaldır
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach

                {{-- Clear cart --}}
                <div class="text-right pt-2">
                    <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Sepeti tamamen temizlemek istediğinize emin misiniz?')"
                                class="text-sm text-[#555555] hover:text-red-600 transition-colors underline">
                            Sepeti Temizle
                        </button>
                    </form>
                </div>

            </div>

            {{-- Order summary ───────────────────────────────────────────────── --}}
            <div class="mt-8 lg:mt-0">
                <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-6 sticky top-24">

                    <h2 class="text-lg font-bold text-[#3E2006] mb-4">Sipariş Özeti</h2>

                    @php
                        $couponDiscount = ($cart->coupon && $cart->coupon->isValid())
                            ? (float) $cart->coupon->calculateDiscount($cart->getSubtotal())
                            : 0.0;
                        $cartTotal = $cart->getSubtotal() - $couponDiscount;
                    @endphp

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-[#555555]">Ara Toplam ({{ $cart->getItemCount() }} ürün)</span>
                            <span class="font-medium text-[#3E2006]">
                                {{ number_format($cart->getSubtotal(), 2, ',', '.') }} TL
                            </span>
                        </div>
                        @if ($couponDiscount > 0)
                        <div class="flex justify-between text-[#2C5F2E]">
                            <span>İndirim ({{ $cart->coupon->code }})</span>
                            <span class="font-medium">−{{ number_format($couponDiscount, 2, ',', '.') }} TL</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-[#555555]">Kargo</span>
                            <span class="text-[#555555] italic text-xs">Hesaplanacak</span>
                        </div>
                    </div>

                    {{-- Coupon --}}
                    @if ($cart->coupon && $cart->coupon->isValid())
                    <div class="flex items-center justify-between px-3 py-2 bg-[#2C5F2E]/10 border border-[#2C5F2E]/30 rounded text-sm mb-3">
                        <span class="text-[#2C5F2E] font-medium">
                            <strong>{{ $cart->coupon->code }}</strong> uygulandı
                        </span>
                        <form method="POST" action="{{ route('cart.coupon.remove') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-red-600 hover:underline ml-3">Kaldır</button>
                        </form>
                    </div>
                    @else
                    <form method="POST" action="{{ route('cart.coupon.apply') }}" class="mb-3">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text"
                                   name="coupon_code"
                                   placeholder="Kupon kodu"
                                   class="flex-grow text-sm border border-[#E6DFD2] rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                            <button type="submit"
                                    class="text-sm px-3 py-2 bg-[#3E2006] text-white rounded hover:bg-[#6B3A1F] transition-colors whitespace-nowrap">
                                Uygula
                            </button>
                        </div>
                    </form>
                    @endif

                    <div class="border-t border-[#E6DFD2] my-4"></div>

                    <div class="flex justify-between font-bold text-[#3E2006] text-lg mb-1">
                        <span>Toplam</span>
                        <span>{{ number_format($cartTotal, 2, ',', '.') }} TL</span>
                    </div>
                    <p class="text-xs text-[#555555] mb-6">(Kargo hariç)</p>

                    {{-- Checkout — Phase 8B --}}
                    <a href="{{ route('checkout.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-3
                              text-base font-semibold rounded bg-[#2C5F2E] hover:bg-[#214a23]
                              text-white transition-colors mb-3">
                        Siparişi Tamamla
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('public.products') }}"
                       class="w-full inline-flex items-center justify-center px-6 py-2.5
                              text-sm font-medium rounded border border-[#E6DFD2]
                              text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
                        Alışverişe Devam Et
                    </a>

                </div>
            </div>

        </div>

    @endif

</section>

@endsection
