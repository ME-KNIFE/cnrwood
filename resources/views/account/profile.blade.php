@extends('layouts.account')

@php $title = 'Profilim — CNRWOOD'; @endphp

@section('account-content')

<div class="bg-white border border-[#E6DFD2] rounded-lg">
    <div class="px-6 py-4 border-b border-[#E6DFD2]">
        <h1 class="font-bold text-[#3E2006] text-xl">Profil Bilgileri</h1>
        <p class="text-sm text-[#555555] mt-0.5">Ad, telefon ve e-posta adresinizi güncelleyin.</p>
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

    <form method="POST" action="{{ route('account.profile.update') }}" novalidate class="px-6 py-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-medium text-[#3E2006] mb-1">
                Ad Soyad <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required
                   maxlength="255"
                   class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[#3E2006] mb-1">
                E-posta <span class="text-red-500">*</span>
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   maxlength="255"
                   autocomplete="email"
                   class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-[#3E2006] mb-1">Telefon</label>
            <input type="tel"
                   id="phone"
                   name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   maxlength="50"
                   autocomplete="tel"
                   class="w-full border {{ $errors->has('phone') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                          rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                          focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
            @error('phone')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold rounded bg-[#3E2006] text-white
                           hover:bg-[#6B3A1F] transition-colors">
                Kaydet
            </button>
        </div>
    </form>
</div>

@endsection
