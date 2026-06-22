@php
    $navLinks = [
        ['label' => 'Anasayfa',         'url' => route('home'),             'active' => request()->routeIs('home')],
        ['label' => 'Kurumsal',         'url' => route('public.corporate'), 'active' => request()->routeIs('public.corporate') || request()->routeIs('public.about')],
        ['label' => 'Hizmetler',        'url' => route('public.services'),  'active' => request()->routeIs('public.services')],
        ['label' => 'Ürünler',          'url' => route('public.products'),  'active' => request()->routeIs('public.products') || request()->routeIs('public.product') || request()->routeIs('public.category')],
        ['label' => 'Sandık Hesaplama', 'url' => route('public.sandik'),    'active' => request()->routeIs('public.sandik')],
        ['label' => 'İletişim',         'url' => route('public.contact'),   'active' => request()->routeIs('public.contact')],
    ];
@endphp

<header class="bg-white border-b border-[#E6DFD2] sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#3E2006] text-[#F5F0E8] font-bold text-sm">CN</span>
                <span class="text-[#3E2006] font-semibold text-lg tracking-tight group-hover:text-[#6B3A1F] transition-colors">CNRWOOD</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="px-3 py-2 text-sm font-medium rounded transition-colors
                              {{ $link['active']
                                    ? 'text-[#3E2006] bg-[#F5F0E8]'
                                    : 'text-[#555555] hover:text-[#3E2006] hover:bg-[#F5F0E8]/60' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden md:flex items-center gap-2">
                {{-- Cart icon with session-backed item count --}}
                @php $cartCount = session('cart_count', 0); @endphp
                <a href="{{ route('cart.index') }}"
                   aria-label="Sepetim"
                   class="relative inline-flex items-center p-2 rounded text-[#3E2006]
                          hover:bg-[#F5F0E8] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if ($cartCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center
                                     text-[10px] font-bold bg-[#2C5F2E] text-white rounded-full px-1 leading-none">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('account.dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded
                              text-[#3E2006] hover:bg-[#F5F0E8] transition-colors {{ request()->routeIs('account.*') ? 'bg-[#F5F0E8]' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Hesabım
                    </a>
                @else
                    <a href="{{ route('account.login') }}"
                       class="text-sm font-medium text-[#555555] hover:text-[#3E2006] px-3 py-2 rounded hover:bg-[#F5F0E8] transition-colors">
                        Giriş Yap
                    </a>
                @endauth

                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded
                          text-white bg-[#1F497D] hover:bg-[#173a64] transition-colors">
                    Teklif Al
                </a>
            </div>

            <details class="md:hidden relative">
                <summary class="list-none cursor-pointer p-2 rounded hover:bg-[#F5F0E8]" aria-label="Menüyü aç">
                    <svg class="w-6 h-6 text-[#3E2006]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </summary>
                <div class="absolute right-0 mt-2 w-56 bg-white border border-[#E6DFD2] rounded-md shadow-lg py-2">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['url'] }}"
                           class="block px-4 py-2 text-sm {{ $link['active'] ? 'text-[#3E2006] bg-[#F5F0E8]' : 'text-[#555555] hover:bg-[#F5F0E8]' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('cart.index') }}"
                       class="flex items-center justify-between mx-3 mt-2 px-3 py-2 text-sm text-[#3E2006] bg-[#F5F0E8] hover:bg-[#E6DFD2] rounded">
                        <span>Sepetim</span>
                        @if (session('cart_count', 0) > 0)
                            <span class="min-w-[18px] h-[18px] flex items-center justify-center
                                         text-[10px] font-bold bg-[#2C5F2E] text-white rounded-full px-1">
                                {{ session('cart_count', 0) > 99 ? '99+' : session('cart_count', 0) }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ route('account.dashboard') }}"
                           class="block px-4 py-2 text-sm {{ request()->routeIs('account.*') ? 'text-[#3E2006] bg-[#F5F0E8]' : 'text-[#555555] hover:bg-[#F5F0E8]' }}">
                            Hesabım
                        </a>
                    @else
                        <a href="{{ route('account.login') }}"
                           class="block px-4 py-2 text-sm text-[#555555] hover:bg-[#F5F0E8]">
                            Giriş Yap
                        </a>
                    @endauth
                    <a href="{{ route('public.quote.create') }}"
                       class="block mx-3 mt-2 px-3 py-2 text-sm text-center text-white bg-[#1F497D] hover:bg-[#173a64] rounded">
                        Teklif Al
                    </a>
                </div>
            </details>

        </div>
    </div>
</header>
