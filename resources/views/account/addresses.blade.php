@extends('layouts.account')

@php $title = 'Adreslerim — CNRWOOD'; @endphp

@section('account-content')

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="font-bold text-[#3E2006] text-xl">Adreslerim</h1>
        <a href="{{ route('account.addresses.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded
                  bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Adres
        </a>
    </div>

    @if ($addresses->isEmpty())
        <div class="bg-white border border-[#E6DFD2] rounded-lg p-10 text-center">
            <p class="text-[#555555] text-sm mb-4">Kayıtlı adresiniz bulunmuyor.</p>
            <a href="{{ route('account.addresses.create') }}"
               class="inline-block px-5 py-2 text-sm font-medium rounded bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
                İlk Adresimi Ekle
            </a>
        </div>
    @else
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach ($addresses as $address)
                <div class="bg-white border {{ ($address->is_default_shipping || $address->is_default_billing) ? 'border-[#3E2006]' : 'border-[#E6DFD2]' }} rounded-lg p-5 relative">

                    {{-- Badges ─────────────────────────────────────────────── --}}
                    @if ($address->is_default_shipping || $address->is_default_billing)
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @if ($address->is_default_shipping)
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#3E2006] text-white uppercase tracking-wider">
                                    Varsayılan Teslimat
                                </span>
                            @endif
                            @if ($address->is_default_billing)
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#1F497D] text-white uppercase tracking-wider">
                                    Varsayılan Fatura
                                </span>
                            @endif
                        </div>
                    @endif

                    <p class="font-bold text-[#3E2006] text-sm">{{ $address->title }}</p>
                    <address class="not-italic text-sm text-[#555555] mt-1 space-y-0.5">
                        <p class="font-medium">{{ $address->full_name }}</p>
                        @if ($address->phone)<p>{{ $address->phone }}</p>@endif
                        <p>{{ $address->address_line1 }}</p>
                        @if ($address->address_line2)<p>{{ $address->address_line2 }}</p>@endif
                        <p>{{ implode(', ', array_filter([$address->district, $address->city, $address->postal_code])) }}</p>
                        <p>{{ $address->country }}</p>
                    </address>

                    {{-- Actions ─────────────────────────────────────────────── --}}
                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <a href="{{ route('account.addresses.edit', $address) }}"
                           class="px-3 py-1.5 rounded border border-[#E6DFD2] text-[#3E2006] hover:bg-[#F5F0E8] transition-colors">
                            Düzenle
                        </a>

                        @if (!$address->is_default_shipping)
                            <form method="POST" action="{{ route('account.addresses.default-shipping', $address) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 rounded border border-[#E6DFD2] text-[#3E2006] hover:bg-[#F5F0E8] transition-colors">
                                    Teslimat Varsayılanı Yap
                                </button>
                            </form>
                        @endif

                        @if (!$address->is_default_billing)
                            <form method="POST" action="{{ route('account.addresses.default-billing', $address) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 rounded border border-[#E6DFD2] text-[#3E2006] hover:bg-[#F5F0E8] transition-colors">
                                    Fatura Varsayılanı Yap
                                </button>
                            </form>
                        @endif

                        @if (!$address->isDeletionBlocked())
                            <form method="POST" action="{{ route('account.addresses.destroy', $address) }}"
                                  onsubmit="return confirm('Bu adresi silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 rounded border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                    Sil
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection
