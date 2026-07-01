@php
    use App\Models\Setting;
    $emailFooter = Setting::get('email_primary', 'info@cnrwood.com');
@endphp
<style>
/* ── CNRWOOD Industrial Footer ─────────────────────────────── */
#cnr-footer {
    background-color: #1c1b1b;
    border-top: 1px solid rgba(51,51,51,0.20);
    padding-top: 80px;
    padding-bottom: 40px;
    font-family: 'Inter', 'Instrument Sans', system-ui, sans-serif;
}
#cnr-footer .cnr-f-wrap {
    max-width: 1280px; margin: 0 auto; padding: 0 64px;
}
#cnr-footer .cnr-f-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
    margin-bottom: 80px;
}
#cnr-footer .cnr-f-brand-name {
    font-family: 'Oswald', 'Hanken Grotesk', sans-serif;
    font-size: 24px; font-weight: 700; line-height: 32px;
    color: #aecfa8; margin-bottom: 24px;
    display: block; text-decoration: none;
}
#cnr-footer .cnr-f-tagline {
    font-size: 14px; line-height: 20px;
    color: #c3c8be; margin-bottom: 24px; max-width: 22rem;
}
#cnr-footer .cnr-f-icons { display: flex; gap: 12px; }
#cnr-footer .cnr-f-icon {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 9999px;
    background: rgba(51,51,51,0.30);
    transition: background 0.2s, color 0.2s;
    text-decoration: none; color: #c3c8be;
}
#cnr-footer .cnr-f-icon:hover { background-color: #aecfa8; color: #1b361b; }
#cnr-footer .cnr-f-icon svg { width: 18px; height: 18px; }
#cnr-footer .cnr-f-col-head {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 13px; font-weight: 600;
    color: #f4f4f4;
    text-transform: uppercase; letter-spacing: 0.10em;
    margin-bottom: 24px; display: block;
}
#cnr-footer .cnr-f-list { list-style: none; padding: 0; margin: 0; }
#cnr-footer .cnr-f-list li { margin-bottom: 12px; }
#cnr-footer .cnr-f-list a {
    font-size: 14px; color: #c3c8be;
    text-decoration: none; transition: color 0.2s;
}
#cnr-footer .cnr-f-list a:hover { color: #aecfa8; }
#cnr-footer .cnr-f-contact-item {
    display: flex; align-items: flex-start; gap: 12px;
    margin-bottom: 16px;
}
#cnr-footer .cnr-f-contact-icon {
    flex-shrink: 0; width: 20px; height: 20px;
    color: #aecfa8; margin-top: 1px;
}
#cnr-footer .cnr-f-contact-item span,
#cnr-footer .cnr-f-contact-item a {
    font-size: 14px; color: #c3c8be; text-decoration: none; transition: color 0.2s;
}
#cnr-footer .cnr-f-contact-item a:hover { color: #aecfa8; }
#cnr-footer .cnr-f-bottom {
    max-width: 1280px; margin: 0 auto; padding: 32px 64px 0;
    border-top: 1px solid rgba(51,51,51,0.10);
    display: flex; flex-direction: column; align-items: center;
    justify-content: space-between; gap: 16px;
}
#cnr-footer .cnr-f-copy {
    font-size: 12px; color: #c3c8be;
}
#cnr-footer .cnr-f-legal {
    display: flex; gap: 24px; font-size: 12px;
}
#cnr-footer .cnr-f-legal a { color: #c3c8be; text-decoration: none; transition: color 0.2s; }
#cnr-footer .cnr-f-legal a:hover { color: #aecfa8; }
@media (min-width: 768px) {
    #cnr-footer .cnr-f-grid { grid-template-columns: repeat(4, 1fr); gap: 32px; }
    #cnr-footer .cnr-f-bottom { flex-direction: row; }
}
@media (max-width: 767px) {
    #cnr-footer .cnr-f-wrap { padding: 0 20px; }
    #cnr-footer .cnr-f-bottom { padding: 32px 20px 0; }
}
</style>

<footer id="cnr-footer" role="contentinfo">
    <div class="cnr-f-wrap">
        <div class="cnr-f-grid">

            {{-- Brand column --}}
            <div>
                <a href="{{ route('home') }}" class="cnr-f-brand-name">CNRWOOD</a>
                <p class="cnr-f-tagline">
                    1998'den bu yana endüstriyel ahşap çözümlerinde güvenilir lojistik ve ambalaj ihtiyaçlarına yönelik üretim çözümleri sunuyoruz.
                </p>
                <div class="cnr-f-icons">
                    <a href="{{ route('public.contact') }}" class="cnr-f-icon" aria-label="İletişim">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    </a>
                    <a href="mailto:{{ $emailFooter }}" class="cnr-f-icon" aria-label="E-posta">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </a>
                    <a href="{{ route('public.contact') }}" class="cnr-f-icon" aria-label="Konum">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Hızlı Linkler --}}
            <div>
                <span class="cnr-f-col-head">Hızlı Linkler</span>
                <ul class="cnr-f-list">
                    <li><a href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li><a href="{{ route('public.about') }}">Hakkımızda</a></li>
                    <li><a href="{{ route('public.products') }}">Ürün Kataloğu</a></li>
                    <li><a href="{{ route('public.sandik') }}">Teknik Ön Değerlendirme</a></li>
                    <li><a href="{{ route('public.projects.index') }}">Projeler</a></li>
                    <li><a href="{{ route('public.contact') }}">Bize Ulaşın</a></li>
                </ul>
            </div>

            {{-- Ürün Grupları --}}
            <div>
                <span class="cnr-f-col-head">Ürün Grupları</span>
                <ul class="cnr-f-list">
                    <li><a href="{{ route('public.category', 'ahsap-sandik') }}">Ahşap Sandık</a></li>
                    <li><a href="{{ route('public.category', 'ihracat-ambalaj') }}">İhracat Ambalajı</a></li>
                    <li><a href="{{ route('public.category', 'agir-yuk-sandiklari') }}">Ağır Yük ve Makine Sandıkları</a></li>
                    <li><a href="{{ route('public.category', 'palet-kereste') }}">Palet &amp; Kereste</a></li>
                    <li><a href="{{ route('public.kapi-sereni') }}">Kapı Sereni</a></li>
                </ul>
            </div>

            {{-- İletişim --}}
            <div>
                <span class="cnr-f-col-head">İletişim</span>
                <div class="cnr-f-contact-item">
                    <svg class="cnr-f-contact-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Gebze, Kocaeli, Türkiye</span>
                </div>
                <div class="cnr-f-contact-item">
                    <svg class="cnr-f-contact-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81a19.79 19.79 0 01-3.07-8.63A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92v2z"/></svg>
                    <a href="{{ route('public.contact') }}">İletişim Hattı İçin Tıklayın</a>
                </div>
                <div class="cnr-f-contact-item">
                    <svg class="cnr-f-contact-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>{{ $emailFooter }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="cnr-f-bottom">
        <div class="cnr-f-copy">
            © {{ date('Y') }} CNRWOOD. Tüm Hakları Saklıdır. ISPM 15 süreçlerine uygun üretim çözümleri sunulur.
        </div>
        <div class="cnr-f-legal">
            <a href="{{ route('home') }}">Gizlilik Politikası</a>
            <a href="{{ route('home') }}">KVKK</a>
        </div>
    </div>
</footer>
