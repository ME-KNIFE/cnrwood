@extends('layouts.public')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="lg:grid lg:grid-cols-4 lg:gap-8">

        {{-- Sidebar ──────────────────────────────────────────────────────── --}}
        <aside class="mb-8 lg:mb-0">
            <div class="bg-white border border-[#E6DFD2] rounded-lg overflow-hidden">
                <div class="px-5 py-4 bg-[#F5F0E8] border-b border-[#E6DFD2]">
                    <p class="font-bold text-[#3E2006] truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-[#555555] truncate mt-0.5">{{ auth()->user()->email }}</p>
                </div>
                <nav class="py-2">
                    @php
                        $menu = [
                            ['label' => 'Genel Bakış',   'route' => 'account.dashboard',  'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Siparişlerim',  'route' => 'account.orders',      'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['label' => 'Adreslerim',    'route' => 'account.addresses',   'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                            ['label' => 'Profilim',      'route' => 'account.profile',     'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ];
                    @endphp

                    @foreach ($menu as $item)
                        @php $active = request()->routeIs($item['route'] . '*'); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-5 py-2.5 text-sm transition-colors
                                  {{ $active
                                       ? 'bg-[#F5F0E8] text-[#3E2006] font-semibold border-l-2 border-[#3E2006]'
                                       : 'text-[#555555] hover:bg-[#F5F0E8] hover:text-[#3E2006]' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div class="border-t border-[#E6DFD2] my-2"></div>
                    <form method="POST" action="{{ route('account.logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-5 py-2.5 text-sm text-[#555555] hover:text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Çıkış Yap
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- Main content ──────────────────────────────────────────────────── --}}
        <div class="lg:col-span-3">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('account-content')
        </div>

    </div>
</div>

@endsection
