@extends('layouts.account')

@php $title = 'Sipariş ' . $order->order_number . ' — CNRWOOD'; @endphp

@section('account-content')

<div class="space-y-6">

    {{-- Header ───────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('account.orders') }}" class="text-xs text-[#1F497D] hover:underline">
                ← Siparişlerime Dön
            </a>
            <h1 class="text-xl font-bold text-[#3E2006] mt-1">{{ $order->order_number }}</h1>
            <p class="text-sm text-[#555555]">{{ $order->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Invoice download ─────────────────────────────────────────── --}}
            <a href="{{ route('admin.orders.invoice', $order) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded
                      border border-[#E6DFD2] text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Fatura İndir
            </a>
            {{-- Reorder placeholder (future feature) ────────────────────── --}}
            <button type="button" disabled
                    title="Yakında kullanılabilir"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded
                           border border-[#E6DFD2] text-[#999] bg-[#F5F0E8] cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Tekrar Sipariş Ver
            </button>
        </div>
    </div>

    {{-- Status row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $statusMap = [
                'teslim_edildi'   => ['bg-green-100', 'text-green-700'],
                'kargoya_verildi' => ['bg-blue-100', 'text-blue-700'],
                'islemde'         => ['bg-yellow-100', 'text-yellow-700'],
                'iptal_edildi'    => ['bg-red-100', 'text-red-700'],
            ];
            [$statusBg, $statusText] = $statusMap[$order->status] ?? ['bg-gray-100', 'text-gray-600'];
        @endphp
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-4">
            <p class="text-xs text-[#555555] uppercase tracking-wider mb-1">Sipariş Durumu</p>
            <span class="inline-block text-sm px-2 py-0.5 rounded-full font-medium {{ $statusBg }} {{ $statusText }}">
                {{ $order->getStatusLabel() }}
            </span>
        </div>
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-4">
            <p class="text-xs text-[#555555] uppercase tracking-wider mb-1">Ödeme</p>
            <span class="inline-block text-sm px-2 py-0.5 rounded-full font-medium
                {{ $order->payment_status === 'odendi' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $order->payment_status === 'odendi' ? 'Ödendi' : 'Beklemede' }}
            </span>
        </div>
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-4">
            <p class="text-xs text-[#555555] uppercase tracking-wider mb-1">Ödeme Yöntemi</p>
            <p class="text-sm font-medium text-[#3E2006]">
                {{ $order->payment_method === 'havale_eft' ? 'Havale / EFT' : $order->payment_method }}
            </p>
        </div>
    </div>

    {{-- Shipment tracking ───────────────────────────────────────────────────── --}}
    @if ($order->shipments->isNotEmpty())
        <div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E6DFD2]">
                <h2 class="font-bold text-[#3E2006]">Kargo / Sevkiyat</h2>
            </div>

            <div class="divide-y divide-[#E6DFD2]">
                @foreach ($order->shipments->sortByDesc('created_at') as $shipment)
                    <div class="px-6 py-5">

                        {{-- Status badge + company ─────────────────────────── --}}
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            @php
                                $shipBadge = match ($shipment->status) {
                                    'kargoya_verildi' => ['bg-blue-100', 'text-blue-700',  'Kargoya Verildi'],
                                    'teslim_edildi'   => ['bg-green-100','text-green-700', 'Teslim Edildi'],
                                    'iade'            => ['bg-red-100',  'text-red-700',   'İade'],
                                    default           => ['bg-gray-100', 'text-gray-600',  'Hazırlanıyor'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full
                                         {{ $shipBadge[0] }} {{ $shipBadge[1] }}">
                                @if ($shipment->status === 'teslim_edildi')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif ($shipment->status === 'kargoya_verildi')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                @endif
                                {{ $shipBadge[2] }}
                            </span>
                            @if ($shipment->cargo_company)
                                <span class="text-sm font-semibold text-[#3E2006]">{{ $shipment->cargo_company }}</span>
                            @endif
                        </div>

                        {{-- Tracking number + link ─────────────────────────── --}}
                        @if ($shipment->tracking_number)
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex-1">
                                    <p class="text-xs text-[#555555] mb-0.5">Takip Numarası</p>
                                    <p class="text-sm font-mono font-semibold text-[#3E2006]">
                                        {{ $shipment->tracking_number }}
                                    </p>
                                </div>
                                @if ($shipment->tracking_url)
                                    <a href="{{ $shipment->tracking_url }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold
                                              rounded bg-[#1F497D] text-white hover:bg-[#173a64] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Kargoyu Takip Et
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Dates ──────────────────────────────────────────── --}}
                        <div class="flex flex-wrap gap-6 text-xs text-[#555555]">
                            @if ($shipment->shipped_at)
                                <div>
                                    <p class="uppercase tracking-wider mb-0.5">Gönderim</p>
                                    <p class="font-medium text-[#3E2006]">{{ $shipment->shipped_at->format('d.m.Y H:i') }}</p>
                                </div>
                            @endif
                            @if ($shipment->estimated_delivery)
                                <div>
                                    <p class="uppercase tracking-wider mb-0.5">Tahmini Teslim</p>
                                    <p class="font-medium text-[#3E2006]">{{ $shipment->estimated_delivery->format('d.m.Y') }}</p>
                                </div>
                            @endif
                            @if ($shipment->delivered_at)
                                <div>
                                    <p class="uppercase tracking-wider mb-0.5">Teslim Tarihi</p>
                                    <p class="font-medium text-green-700">{{ $shipment->delivered_at->format('d.m.Y H:i') }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($shipment->notes)
                            <p class="mt-3 text-xs text-[#555555] bg-[#F5F0E8] rounded px-3 py-2">
                                {{ $shipment->notes }}
                            </p>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 gap-6">

        {{-- Shipping address ─────────────────────────────────────────────── --}}
        @if ($order->shipping_address)
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-5">
            <h2 class="font-bold text-[#3E2006] text-sm uppercase tracking-wider mb-3">Teslimat Adresi</h2>
            @php $sa = $order->shipping_address; @endphp
            <address class="not-italic text-sm text-[#555555] space-y-0.5">
                <p class="font-medium text-[#3E2006]">{{ $sa['full_name'] ?? '—' }}</p>
                @if (!empty($sa['phone']))<p>{{ $sa['phone'] }}</p>@endif
                <p>{{ $sa['address_line1'] ?? '' }}</p>
                @if (!empty($sa['address_line2']))<p>{{ $sa['address_line2'] }}</p>@endif
                <p>{{ implode(', ', array_filter([$sa['district'] ?? null, $sa['city'] ?? null, $sa['postal_code'] ?? null])) }}</p>
                <p>{{ $sa['country'] ?? 'Türkiye' }}</p>
            </address>
        </div>
        @endif

        {{-- Billing address ──────────────────────────────────────────────── --}}
        @if ($order->billing_address)
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-5">
            <h2 class="font-bold text-[#3E2006] text-sm uppercase tracking-wider mb-3">Faturalama Adresi</h2>
            @php $ba = $order->billing_address; @endphp
            <address class="not-italic text-sm text-[#555555] space-y-0.5">
                <p class="font-medium text-[#3E2006]">{{ $ba['full_name'] ?? '—' }}</p>
                @if (!empty($ba['phone']))<p>{{ $ba['phone'] }}</p>@endif
                <p>{{ $ba['address_line1'] ?? '' }}</p>
                @if (!empty($ba['address_line2']))<p>{{ $ba['address_line2'] }}</p>@endif
                <p>{{ implode(', ', array_filter([$ba['district'] ?? null, $ba['city'] ?? null, $ba['postal_code'] ?? null])) }}</p>
                <p>{{ $ba['country'] ?? 'Türkiye' }}</p>
            </address>
        </div>
        @endif

    </div>

    {{-- Order items ──────────────────────────────────────────────────────── --}}
    <div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E6DFD2]">
            <h2 class="font-bold text-[#3E2006]">Sipariş Kalemleri</h2>
        </div>
        <div class="divide-y divide-[#E6DFD2]">
            @foreach ($order->items as $item)
                <div class="flex justify-between items-center px-6 py-4 gap-4">
                    <div class="flex-grow min-w-0">
                        <p class="font-medium text-[#3E2006] text-sm leading-snug">
                            {{ $item->product_name }}
                            @if ($item->variant_name)
                                <span class="text-[#555555] font-normal">({{ $item->variant_name }})</span>
                            @endif
                        </p>
                        @if ($item->product_sku)
                            <p class="text-xs text-[#555555]">SKU: {{ $item->product_sku }}</p>
                        @endif
                        <p class="text-xs text-[#555555] mt-0.5">
                            {{ $item->quantity }} adet × {{ number_format($item->unit_price, 2, ',', '.') }} TL
                        </p>
                    </div>
                    <p class="font-bold text-[#3E2006] whitespace-nowrap flex-shrink-0">
                        {{ number_format($item->total_price, 2, ',', '.') }} TL
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Totals ───────────────────────────────────────────────────────── --}}
        <div class="px-6 py-4 border-t border-[#E6DFD2] space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-[#555555]">Ara Toplam</span>
                <span class="font-medium text-[#3E2006]">{{ number_format($order->subtotal, 2, ',', '.') }} TL</span>
            </div>
            @if ($order->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-[#555555]">
                        İndirim
                        @if ($order->coupon_code)
                            <span class="text-xs ml-1 font-mono bg-gray-100 px-1 rounded">{{ $order->coupon_code }}</span>
                        @endif
                    </span>
                    <span class="text-[#2C5F2E] font-medium">
                        − {{ number_format($order->discount_amount, 2, ',', '.') }} TL
                    </span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="text-[#555555]">Kargo</span>
                <span class="font-medium text-[#3E2006]">
                    {{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2, ',', '.') . ' TL' : 'Ücretsiz' }}
                </span>
            </div>
            <div class="flex justify-between pt-2 border-t border-[#E6DFD2] text-base font-bold">
                <span class="text-[#3E2006]">Genel Toplam</span>
                <span class="text-[#3E2006]">{{ number_format($order->total, 2, ',', '.') }} TL</span>
            </div>
        </div>
    </div>

</div>

@endsection
