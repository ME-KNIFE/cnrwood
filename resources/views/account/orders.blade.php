@extends('layouts.account')

@php $title = 'Siparişlerim — CNRWOOD'; @endphp

@section('account-content')

<div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E6DFD2]">
        <h1 class="font-bold text-[#3E2006] text-xl">Siparişlerim</h1>
    </div>

    @if ($orders->isEmpty())
        <div class="px-6 py-12 text-center">
            <p class="text-[#555555] text-sm">Henüz sipariş vermediniz.</p>
            <a href="{{ route('public.products') }}"
               class="mt-4 inline-block px-5 py-2 text-sm font-medium rounded bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
                Ürünleri Keşfet
            </a>
        </div>
    @else
        <div class="divide-y divide-[#E6DFD2]">
            @foreach ($orders as $order)
                <div class="px-6 py-5 hover:bg-[#F5F0E8] transition-colors">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="font-bold text-[#3E2006]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#555555]">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                                · {{ $order->items->count() }} ürün
                            </p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ match($order->status) {
                                        'teslim_edildi'    => 'bg-green-100 text-green-700',
                                        'kargoya_verildi'  => 'bg-blue-100 text-blue-700',
                                        'islemde'          => 'bg-yellow-100 text-yellow-700',
                                        'iptal_edildi'     => 'bg-red-100 text-red-700',
                                        default            => 'bg-gray-100 text-gray-600',
                                    } }}">
                                    {{ $order->getStatusLabel() }}
                                </span>
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ $order->payment_status === 'odendi'
                                        ? 'bg-green-50 text-green-700'
                                        : 'bg-amber-50 text-amber-700' }}">
                                    {{ $order->payment_status === 'odendi' ? 'Ödendi' : 'Ödeme Bekleniyor' }}
                                </span>
                                @php $latestShipment = $order->shipments->sortByDesc('created_at')->first(); @endphp
                                @if ($latestShipment)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-medium
                                        {{ match ($latestShipment->status) {
                                            'kargoya_verildi' => 'bg-blue-50 text-blue-600',
                                            'teslim_edildi'   => 'bg-green-50 text-green-700',
                                            'iade'            => 'bg-red-50 text-red-600',
                                            default           => 'bg-gray-50 text-gray-500',
                                        } }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-2 2v10a1 1 0 002 2h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                        {{ match ($latestShipment->status) {
                                            'kargoya_verildi' => 'Kargoya Verildi',
                                            'teslim_edildi'   => 'Teslim Edildi',
                                            'iade'            => 'İade',
                                            default           => 'Hazırlanıyor',
                                        } }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="font-bold text-[#3E2006] text-lg">
                                {{ number_format($order->total, 2, ',', '.') }} TL
                            </p>
                            <a href="{{ route('account.orders.detail', $order) }}"
                               class="px-4 py-2 text-sm font-medium rounded border border-[#E6DFD2]
                                      text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
                                Detay
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-[#E6DFD2]">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
