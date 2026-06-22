@extends('layouts.public')

@php $title = 'Giriş Yap — CNRWOOD'; @endphp

@section('content')

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-[#3E2006]">Hesabınıza Giriş Yapın</h1>
            <p class="text-sm text-[#555555] mt-1">Sipariş takibi ve adres yönetimi için giriş yapın.</p>
        </div>

        <div class="bg-white border border-[#E6DFD2] rounded-lg p-8">

            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('account.login') }}" novalidate class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-[#3E2006] mb-1">
                        E-posta <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                  rounded px-3 py-2 text-sm text-[#3E2006] bg-white
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
                           autocomplete="current-password"
                           class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-[#E6DFD2]' }}
                                  rounded px-3 py-2 text-sm text-[#3E2006] bg-white
                                  focus:outline-none focus:ring-1 focus:ring-[#3E2006]">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           name="remember"
                           class="w-4 h-4 rounded border-[#E6DFD2] text-[#3E2006]">
                    <span class="text-sm text-[#555555]">Beni hatırla</span>
                </label>

                <button type="submit"
                        class="w-full py-2.5 text-sm font-semibold rounded bg-[#3E2006] text-white
                               hover:bg-[#6B3A1F] transition-colors">
                    Giriş Yap
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#555555]">
                Hesabınız yok mu?
                <a href="{{ route('account.register') }}" class="text-[#1F497D] hover:underline font-medium">
                    Üye Ol
                </a>
            </p>
        </div>

    </div>
</div>

@endsection
