@extends('layouts.public')

@php $title = 'Üye Ol — CNRWOOD'; @endphp

@section('content')

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-[#3E2006]">Hesap Oluşturun</h1>
            <p class="text-sm text-[#555555] mt-1">Sipariş takibi, adres defteri ve özel fiyatlar için üye olun.</p>
        </div>

        <div class="bg-white border border-[#E6DFD2] rounded-lg p-8">

            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.register') }}" novalidate class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-[#3E2006] mb-1">
                        Ad Soyad <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           maxlength="255"
                           autocomplete="name"
                           class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                  rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#3E2006] mb-1">
                        E-posta <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           maxlength="255"
                           autocomplete="email"
                           class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                  rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-[#3E2006] mb-1">Telefon</label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           maxlength="50"
                           autocomplete="tel"
                           class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#3E2006] mb-1">
                        Şifre <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                  rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                    <p class="mt-1 text-xs text-[#555555]">En az 8 karakter</p>
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#3E2006] mb-1">
                        Şifre Tekrar <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           class="w-full border border-[#E6DFD2] rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                </div>

                <button type="submit"
                        class="w-full py-2.5 text-sm font-semibold rounded bg-[#3E2006] text-white
                               hover:bg-[#6B3A1F] transition-colors">
                    Hesap Oluştur
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#555555]">
                Zaten üye misiniz?
                <a href="{{ route('account.login') }}" class="text-[#1F497D] hover:underline font-medium">
                    Giriş Yap
                </a>
            </p>
        </div>

    </div>
</div>

@endsection
