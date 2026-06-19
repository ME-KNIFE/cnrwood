<footer class="bg-[#3E2006] text-[#F5F0E8] mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded bg-[#F5F0E8] text-[#3E2006] font-bold text-sm">CN</span>
                    <span class="font-semibold text-lg">CNRWOOD</span>
                </div>
                <p class="text-sm text-[#F5F0E8]/80 leading-relaxed max-w-md">
                    1998’den bu yana ahşap sandık, ihracat ambalajı, ISPM 15 ısıl işlemli ürünler,
                    kereste &amp; levha ve ahşap yapı projelerinde profesyonel üretim.
                </p>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-[#F5F0E8]/90 mb-3">Hızlı Erişim</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">Anasayfa</a></li>
                    <li><a href="{{ route('public.products') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">Tüm Ürünler</a></li>
                    <li><a href="{{ route('public.products', ['tip' => 'quote_only']) }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">Teklif Verdiğimiz Ürünler</a></li>
                    <li><a href="{{ route('public.products', ['tip' => 'buyable']) }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">Mağaza Ürünleri</a></li>
                    <li><a href="{{ route('public.contact') }}" class="text-[#F5F0E8]/80 hover:text-white transition-colors">İletişim</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-[#F5F0E8]/90 mb-3">İletişim</h4>
                <ul class="space-y-2 text-sm text-[#F5F0E8]/80">
                    <li>Pelitli Mah. Pelitli Yolu Cad.<br>No: 137/A, Gebze / Kocaeli</li>
                    <li><a href="tel:+902627512120" class="hover:text-white transition-colors">+90 262 751 21 20</a></li>
                    <li><a href="mailto:info@cnrwood.com" class="hover:text-white transition-colors">info@cnrwood.com</a></li>
                    <li class="text-[#F5F0E8]/60">07:20 – 17:20 (Hafta içi)</li>
                </ul>
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-[#F5F0E8]/15 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs text-[#F5F0E8]/60">
            <p>&copy; {{ date('Y') }} CNR Ahşap Sanayi ve Ticaret. Tüm hakları saklıdır.</p>
            <p>Gebze, Türkiye · 1998’den beri</p>
        </div>
    </div>
</footer>
