@php
    $navLinks = [
        ['label' => __('nav.home'),      'url' => route('home'),                   'active' => request()->routeIs('home')],
        ['label' => __('nav.corporate'), 'url' => route('public.corporate'),       'active' => request()->routeIs('public.corporate') || request()->routeIs('public.about')],
        ['label' => __('nav.services'),  'url' => route('public.services'),        'active' => request()->routeIs('public.services')],
        ['label' => __('nav.products'),  'url' => route('public.products'),        'active' => request()->routeIs('public.products') || request()->routeIs('public.product') || request()->routeIs('public.category')],
        ['label' => __('nav.sandik'),    'url' => route('public.sandik'),          'active' => request()->routeIs('public.sandik')],
        ['label' => __('nav.projects'),  'url' => route('public.projects.index'),  'active' => request()->routeIs('public.projects.*')],
        ['label' => __('nav.blog'),      'url' => route('public.blog.index'),      'active' => request()->routeIs('public.blog.*')],
        ['label' => __('nav.contact'),   'url' => route('public.contact'),         'active' => request()->routeIs('public.contact')],
    ];
    $currentLocale = app()->getLocale();
    $cartCount     = session('cart_count', 0);
@endphp

<header class="sticky top-0 z-50 w-full">

    <div class="hidden lg:block bg-wood-deep text-cream/80">
        <div class="mx-auto flex h-10 max-w-7xl items-center justify-between px-4 text-xs lg:px-8">
            <div class="flex items-center gap-6">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-wood-natural shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 7 12 7s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                    Gebze OSB, Kocaeli
                </span>
                <a href="tel:+902627512120" class="inline-flex items-center gap-1.5 transition-colors hover:text-cream">
                    <svg class="h-3.5 w-3.5 text-wood-natural shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    +90 262 751 21 20
                </a>
                <a href="mailto:info@cnrwood.com" class="inline-flex items-center gap-1.5 transition-colors hover:text-cream">
                    <svg class="h-3.5 w-3.5 text-wood-natural shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    info@cnrwood.com
                </a>
            </div>
            <div class="flex items-center gap-5">
                <span class="inline-flex items-center gap-1.5 font-medium text-cream/90">
                    <svg class="h-3.5 w-3.5 text-wood-natural shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    ISPM 15 Sertifikalı İhracat Ambalajı
                </span>
                <div class="flex items-center overflow-hidden rounded-sm border border-cream/25 text-[11px] font-semibold">
                    <a href="{{ route('locale.switch', 'tr') }}"
                       class="px-2 py-1 transition-colors {{ $currentLocale === 'tr' ? 'bg-wood-natural text-wood-deep' : 'text-cream/70 hover:text-cream' }}">
                        TR
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="px-2 py-1 transition-colors {{ $currentLocale === 'en' ? 'bg-wood-natural text-wood-deep' : 'text-cream/70 hover:text-cream' }}">
                        EN
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-b border-[#E6DFD2] bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/85">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 lg:h-[4.5rem] lg:px-8">

            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <span class="flex h-11 w-11 items-center justify-center rounded-sm bg-wood-deep font-heading text-2xl font-bold text-wood-natural">
                    C
                </span>
                <span class="flex flex-col leading-none">
                    <span class="font-heading text-[1.6rem] font-bold tracking-wide text-wood-deep">
                        CNR<span class="text-wood-natural">WOOD</span>
                    </span>
                    <span class="hidden sm:block mt-0.5 text-[9px] font-semibold uppercase tracking-[0.18em] text-[#8B5A2B]">
                        Ahşap Ambalaj &amp; Üretim · Est. 1998
                    </span>
                </span>
            </a>

            <nav class="hidden items-center gap-0.5 xl:flex">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="rounded-sm px-3 py-2 text-sm font-semibold transition-colors
                              {{ $link['active']
                                  ? 'bg-[#F5F0E8] text-wood-deep'
                                  : 'text-[#555555] hover:bg-[#F5F0E8] hover:text-wood-deep' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 lg:gap-3">

                <a href="{{ route('cart.index') }}"
                   aria-label="{{ __('nav.cart') }}"
                   class="relative flex h-10 w-10 items-center justify-center rounded-sm text-[#3E2006] transition-colors hover:bg-[#F5F0E8]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if ($cartCount > 0)
                        <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-forest text-[10px] font-bold text-white">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('account.dashboard') }}"
                       class="hidden sm:flex h-10 w-10 items-center justify-center rounded-sm text-[#3E2006] transition-colors hover:bg-[#F5F0E8] {{ request()->routeIs('account.*') ? 'bg-[#F5F0E8]' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('account.login') }}"
                       class="hidden sm:flex h-10 w-10 items-center justify-center rounded-sm text-[#555555] transition-colors hover:bg-[#F5F0E8] hover:text-wood-deep">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @endauth

                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-sm bg-steel px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#173a64]">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="hidden sm:inline">{{ __('product.request_quote_short') }}</span>
                </a>

                <button id="mobile-menu-btn"
                        type="button"
                        aria-label="Menüyü aç / kapat"
                        class="flex h-10 w-10 items-center justify-center rounded-sm text-wood-deep transition-colors hover:bg-[#F5F0E8] xl:hidden">
                    <svg id="icon-menu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="icon-close" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu"
         class="hidden border-t border-[#E6DFD2] bg-white xl:hidden">
        <nav class="mx-auto max-w-7xl divide-y divide-[#F0EAE0] px-4 py-2 lg:px-8">
            @foreach ($navLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="flex items-center py-3 text-sm font-semibold transition-colors
                          {{ $link['active'] ? 'text-wood-deep' : 'text-[#555555] hover:text-wood-deep' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="py-4">
                <a href="{{ route('public.quote.create') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-sm bg-steel px-5 py-3 text-sm font-semibold text-white hover:bg-[#173a64]">
                    {{ __('product.request_quote_short') }}
                </a>
            </div>
        </nav>
    </div>
</header>

<script>
(function () {
    var btn  = document.getElementById('mobile-menu-btn');
    var menu = document.getElementById('mobile-menu');
    var iconMenu  = document.getElementById('icon-menu');
    var iconClose = document.getElementById('icon-close');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var open = menu.classList.toggle('hidden');
        iconMenu.classList.toggle('hidden', !open);
        iconClose.classList.toggle('hidden', open);
    });
})();
</script>
