<footer class="bg-[#2a1604] text-cream">
    <div class="mx-auto max-w-7xl px-4 py-14 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

            {{-- Brand + contact --}}
            <div>
                <a href="{{ route('home') }}" class="inline-block font-heading text-2xl font-bold tracking-wide">
                    CNR<span class="text-wood-natural">WOOD</span>
                </a>
                <p class="mt-4 text-sm leading-relaxed text-cream/70">
                    {{ __('footer.tagline') }}
                </p>
                <ul class="mt-6 space-y-3 text-sm text-cream/75">
                    <li class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-wood-natural" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 7 12 7s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                        <span>Pelitli Mah. Pelitli Yolu Cad. No: 137/A, Gebze / Kocaeli</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-4 w-4 shrink-0 text-wood-natural" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:+902627512120" class="hover:text-cream transition-colors">+90 262 751 21 20</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-4 w-4 shrink-0 text-wood-natural" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:info@cnrwood.com" class="hover:text-cream transition-colors">info@cnrwood.com</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-wood-natural" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ __('footer.hours') }}</span>
                    </li>
                </ul>
            </div>

            {{-- Navigation --}}
            <div>
                <h3 class="font-heading text-sm font-bold uppercase tracking-widest text-cream">{{ __('footer.quick_links') }}</h3>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}"                    class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ route('public.corporate') }}"        class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.corporate') }}</a></li>
                    <li><a href="{{ route('public.services') }}"         class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.services') }}</a></li>
                    <li><a href="{{ route('public.products') }}"         class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('footer.all_products_link') }}</a></li>
                    <li><a href="{{ route('public.sandik') }}"           class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('footer.sandik_link') }}</a></li>
                    <li><a href="{{ route('public.projects.index') }}"   class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.projects') }}</a></li>
                    <li><a href="{{ route('public.blog.index') }}"       class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.blog') }}</a></li>
                    <li><a href="{{ route('public.contact') }}"          class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            {{-- Products --}}
            <div>
                <h3 class="font-heading text-sm font-bold uppercase tracking-widest text-cream">{{ __('nav.products') }}</h3>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('public.products') }}" class="text-cream/70 transition-colors hover:text-wood-natural">Ah&#351;ap Sand&#305;k</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-cream/70 transition-colors hover:text-wood-natural">&#304;hracat Ambalaj&#305;</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-cream/70 transition-colors hover:text-wood-natural">Kap&#305; Sereni</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-cream/70 transition-colors hover:text-wood-natural">Kereste &amp; Levha</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-cream/70 transition-colors hover:text-wood-natural">Ah&#351;ap Yap&#305;lar</a></li>
                    <li><a href="{{ route('public.products') }}?tip=buyable" class="text-cream/70 transition-colors hover:text-wood-natural">Ma&#287;aza &#220;r&#252;nleri</a></li>
                </ul>
            </div>

            {{-- Quick links --}}
            <div>
                <h3 class="font-heading text-sm font-bold uppercase tracking-widest text-cream">H&#305;zl&#305; Eri&#351;im</h3>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('public.quote.create') }}"   class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('footer.quote_link') }}</a></li>
                    <li><a href="{{ route('public.sandik') }}"         class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('footer.sandik_link') }}</a></li>
                    <li><a href="{{ route('public.products') }}?tip=buyable" class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.shop') }}</a></li>
                    <li><a href="{{ route('public.projects.index') }}" class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.projects') }}</a></li>
                    <li><a href="{{ route('public.fairs.index') }}"    class="text-cream/70 transition-colors hover:text-wood-natural">{{ __('nav.fairs') }}</a></li>
                </ul>
                {{-- ISPM badge --}}
                <div class="mt-6 flex items-center gap-2 rounded-sm border border-cream/15 bg-wood-deep/60 px-3 py-2.5">
                    <svg class="h-4 w-4 shrink-0 text-wood-natural" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-xs font-medium text-cream/80">ISPM 15 Sertifikal&#305;<br>&#304;hracat Ambalaj&#305;</span>
                </div>
            </div>

        </div>
    </div>

    <div class="border-t border-cream/10">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-6 text-xs text-cream/60 sm:flex-row lg:px-8">
            <p>&copy; {{ date('Y') }} {{ __('footer.copyright') }}</p>
            <p>{{ __('footer.since') }}</p>
        </div>
    </div>
</footer>

