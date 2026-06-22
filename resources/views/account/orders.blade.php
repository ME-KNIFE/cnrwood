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
