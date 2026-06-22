<footer class="bg-[#3E2006] text-[#F5F0E8] mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#3E2006] font-bold text-sm">CN</span>
                    <span class="font-semibold text-lg">CNRWOOD</span>
                </div>
                <p class="text-sm text-[#F5F0E8]/80 leading-relaxed max-w-md">
                    {{ __('footer.tagline') }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-[#F5F0E8]/90 mb-3">{{ __('footer.quick_links') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ route('public.corporate') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('nav.corporate') }}</a></li>
                    <li><a href="{{ route('public.about') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('nav.about') }}</a></li>
                    <li><a href="{{ route('public.services') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('nav.services') }}</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('footer.all_products_link') }}</a></li>
                    <li><a href="{{ route('public.sandik') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('footer.sandik_link') }}</a></li>
                    <li><a href="{{ route('public.quote.create') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __("nav.quote") }}</a></li>
                    <li><a href="{{ route('public.contact') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-[#F5F0E8]/90 mb-3">{{ __('nav.contact') }}</h4>
                <ul class="space-y-2 text-sm text-[#F5F0E8]/80">
                    <li>Pelitli Mah. Pelitli Yolu Cad.<br>No: 137/A, Gebze / Kocaeli</li>
                    <li><a href="tel:+902627512120" class="hover:text-white transition-colors">+90 262 751 21 20</a></li>
                    <li><a href="mailto:info@cnrwood.com" class="hover:text-white transition-colors">info@cnrwood.com</a></li>
                    <li class="text-[#F5F0E8]/60">{{ __('footer.hours') }}</li>
                </ul>
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-[#F5F0E8]/15 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs text-[#F5F0E8]/60">
            <p>&copy; {{ date('Y') }} {{ __('footer.copyright') }}</p>
            <p>{{ __('footer.since') }}</p>
        </div>
    </div>
</footer>
