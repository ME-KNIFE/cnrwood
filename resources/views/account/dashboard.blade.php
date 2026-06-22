@extends('layouts.account')

@php $title = 'Hesabım — CNRWOOD'; @endphp

@section('account-content')

<div class="space-y-6">

    {{-- Welcome card ─────────────────────────────────────────────────────── --}}
    <div class="bg-white border border-[#E6DFD2] rounded-lg p-6">
        <h1 class="text-2xl font-bold text-[#3E2006]">Merhaba, {{ $user->name }}!</h1>
        <p class="text-[#555555] text-sm mt-1">Hesap panelinize hoş geldiniz.</p>
    </div>

    {{-- Stats ────────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-5 text-center">
            <p class="text-3xl font-bold text-[#3E2006]">{{ $totalOrders }}</p>
            <p class="text-xs text-[#555555] mt-1 uppercase tracking-wider">Toplam Sipariş</p>
        </div>
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-5 text-center">
            <p class="text-3xl font-bold text-[#3E2006]">
                {{ $user->addresses()->count() }}
            </p>
            <p class="text-xs text-[#555555] mt-1 uppercase tracking-wider">Kayıtlı Adres</p>
        </div>
        <div class="col-span-2 sm:col-span-1 bg-white border border-[#E6DFD2] rounded-lg p-5 text-center">
            <p class="text-sm font-semibold text-[#3E2006] truncate">{{ $user->email }}</p>
            <p class="text-xs text-[#555555] mt-1 uppercase tracking-wider">E-posta</p>
        </div>
    </div>

    {{-- Quick links ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('account.orders') }}"
           class="flex items-center gap-4 bg-white border border-[#E6DFD2] rounded-lg p-5
                  hover:border-[#3E2006] hover:bg-[#F5F0E8] transition-colors group">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#F5F0E8] group-hover:bg-white flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-[#3E2006]">Siparişlerim</p>
                <p class="text-xs text-[#555555]">Tüm siparişlerinizi görüntüleyin</p>
            </div>
        </a>
        <a href="{{ route('account.addresses') }}"
           class="flex items-center gap-4 bg-white border border-[#E6DFD2] rounded-lg p-5
                  hover:border-[#3E2006] hover:bg-[#F5F0E8] transition-colors group">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#F5F0E8] group-hover:bg-white flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-[#3E2006]">Adreslerim</p>
                <p class="text-xs text-[#555555]">Teslimat adreslerinizi yönetin</p>
            </div>
        </a>
        <a href="{{ route('account.profile') }}"
           class="flex items-center gap-4 bg-white border border-[#E6DFD2] rounded-lg p-5
                  hover:border-[#3E2006] hover:bg-[#F5F0E8] transition-colors group">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#F5F0E8] group-hover:bg-white flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-[#3E2006]">Profilim</p>
                <p class="text-xs text-[#555555]">Bilgilerinizi güncelleyin</p>
            </div>
        </a>
    </div>

    {{-- Latest orders ────────────────────────────────────────────────────── --}}
    @if ($latestOrders->isNotEmpty())
    <div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#E6DFD2]">
            <h2 class="font-bold text-[#3E2006]">Son Siparişler</h2>
            <a href="{{ route('account.orders') }}" class="text-xs text-[#1F497D] hover:underline">
                Tümünü Gör →
            </a>
        </div>
        <div class="divide-y divide-[#E6DFD2]">
            @foreach ($latestOrders as $order)
                <div class="flex items-center justify-between px-6 py-4 text-sm hover:bg-[#F5F0E8] transition-colors">
                    <div>
                        <p class="font-medium text-[#3E2006]">{{ $order->order_number }}</p>
                        <p class="text-xs text-[#555555] mt-0.5">{{ $order->created_at->format('d.m.Y') }}
                            · {{ $order->items->count() }} ürün</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="font-bold text-[#3E2006]">{{ number_format($order->total, 2, ',', '.') }} TL</p>
                            <span class="inline-block text-[10px] px-2 py-0.5 rounded-full font-medium
                                {{ match($order->status) {
                                    'teslim_edildi'    => 'bg-green-100 text-green-700',
                                    'kargoya_verildi'  => 'bg-blue-100 text-blue-700',
                                    'islemde'          => 'bg-yellow-100 text-yellow-700',
                                    'iptal_edildi'     => 'bg-red-100 text-red-700',
                                    default            => 'bg-gray-100 text-gray-600',
                                } }}">
                                {{ $order->getStatusLabel() }}
                            </span>
                        </div>
                        <a href="{{ route('account.orders.detail', $order) }}"
                           class="text-[#1F497D] hover:underline text-xs whitespace-nowrap">
                            Detay →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white border border-[#E6DFD2] rounded-lg p-10 text-center">
        <p class="text-[#555555] text-sm">Henüz sipariş vermediniz.</p>
        <a href="{{ route('public.products') }}"
           class="mt-4 inline-block px-5 py-2 text-sm font-medium rounded bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
            Ürünleri Keşfet
        </a>
    </div>
    @endif

</div>

@endsection
