@php
    $navLinks = [
        ['label' => 'Anasayfa', 'url' => route('home'),             'active' => request()->routeIs('home')],
        ['label' => 'Ürünler', 'url' => route('public.products'),   'active' => request()->routeIs('public.products') || request()->routeIs('public.product') || request()->routeIs('public.category')],
        ['label' => 'İletişim', 'url' => route('public.contact'),   'active' => request()->routeIs('public.contact')],
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
                    <a href="{{ route('public.quote.create') }}"
                       class="block mx-3 mt-2 px-3 py-2 text-sm text-center text-white bg-[#1F497D] hover:bg-[#173a64] rounded">
                        Teklif Al
                    </a>
                </div>
            </details>

        </div>
    </div>
</header>
