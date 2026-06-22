@extends('layouts.account')

@php
    $isEdit = $address !== null;
    $title  = ($isEdit ? 'Adresi Düzenle' : 'Yeni Adres Ekle') . ' — CNRWOOD';
@endphp

@section('account-content')

<div class="bg-white border border-[#E6DFD2] rounded-lg">
    <div class="px-6 py-4 border-b border-[#E6DFD2]">
        <a href="{{ route('account.addresses') }}" class="text-xs text-[#1F497D] hover:underline">
            ← Adreslerime Dön
        </a>
        <h1 class="font-bold text-[#3E2006] text-xl mt-1">
            {{ $isEdit ? 'Adresi Düzenle' : 'Yeni Adres Ekle' }}
        </h1>
    </div>

    @if ($errors->any())
        <div class="mx-6 mt-5 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $isEdit ? route('account.addresses.update', $address) : route('account.addresses.store') }}"
          novalidate
          class="px-6 py-6">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Adres Başlığı <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $address?->title) }}"
                       required
                       maxlength="100"
                       placeholder="Örn: Ev, İş, Depo"
                       class="w-full border {{ $errors->has('title') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="full_name" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Alıcı Adı Soyadı <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="full_name"
                       name="full_name"
                       value="{{ old('full_name', $address?->full_name ?? auth()->user()->name) }}"
                       required
                       maxlength="255"
                       class="w-full border {{ $errors->has('full_name') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('full_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-[#3E2006] mb-1">Telefon</label>
                <input type="tel"
                       id="phone"
                       name="phone"
                       value="{{ old('phone', $address?->phone ?? auth()->user()->phone) }}"
                       maxlength="50"
                       autocomplete="tel"
                       class="w-full border {{ $errors->has('phone') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address_line1" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Adres Satırı 1 <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="address_line1"
                       name="address_line1"
                       value="{{ old('address_line1', $address?->address_line1) }}"
                       required
                       maxlength="500"
                       placeholder="Mahalle, cadde, sokak, bina no, daire no"
                       class="w-full border {{ $errors->has('address_line1') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('address_line1')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address_line2" class="block text-sm font-medium text-[#3E2006] mb-1">
                    Adres Satırı 2 <span class="text-[#999]">(isteğe bağlı)</span>
                </label>
                <input type="text"
                       id="address_line2"
                       name="address_line2"
                       value="{{ old('address_line2', $address?->address_line2) }}"
                       maxlength="500"
                       class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            </div>

            <div>
                <label for="city" class="block text-sm font-medium text-[#3E2006] mb-1">
                    İl <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="city"
                       name="city"
                       value="{{ old('city', $address?->city) }}"
                       required
                       maxlength="100"
                       class="w-full border {{ $errors->has('city') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                              rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="district" class="block text-sm font-medium text-[#3E2006] mb-1">İlçe</label>
                <input type="text"
                       id="district"
                       name="district"
                       value="{{ old('district', $address?->district) }}"
                       maxlength="100"
                       class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            </div>

            <div>
                <label for="postal_code" class="block text-sm font-medium text-[#3E2006] mb-1">Posta Kodu</label>
                <input type="text"
                       id="postal_code"
                       name="postal_code"
                       value="{{ old('postal_code', $address?->postal_code) }}"
                       maxlength="20"
                       class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            </div>

            <div>
                <label for="country" class="block text-sm font-medium text-[#3E2006] mb-1">Ülke</label>
                <input type="text"
                       id="country"
                       name="country"
                       value="{{ old('country', $address?->country ?? 'Türkiye') }}"
                       maxlength="100"
                       class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                              focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            </div>

            {{-- Default flags ──────────────────────────────────────────────── --}}
            <div class="sm:col-span-2 space-y-3 pt-2 border-t border-[#E6DFD2]">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox"
                           name="is_default_shipping"
                           value="1"
                           {{ old('is_default_shipping', $address?->is_default_shipping) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-[#E6DFD2] text-[#3E2006]
                                  focus:ring-[#3E2006] focus:ring-offset-0">
                    <span class="text-sm text-[#3E2006] group-hover:text-[#6B3A1F]">
                        Bu adresi varsayılan teslimat adresi olarak kullan
                    </span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox"
                           name="is_default_billing"
                           value="1"
                           {{ old('is_default_billing', $address?->is_default_billing) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-[#E6DFD2] text-[#3E2006]
                                  focus:ring-[#3E2006] focus:ring-offset-0">
                    <span class="text-sm text-[#3E2006] group-hover:text-[#6B3A1F]">
                        Bu adresi varsayılan fatura adresi olarak kullan
                    </span>
                </label>
            </div>

        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('account.addresses') }}"
               class="px-5 py-2.5 text-sm font-medium rounded border border-[#E6DFD2]
                      text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
                İptal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold rounded bg-[#3E2006] text-white
                           hover:bg-[#6B3A1F] transition-colors">
                {{ $isEdit ? 'Güncelle' : 'Adresi Kaydet' }}
            </button>
        </div>
    </form>
</div>

@endsection
