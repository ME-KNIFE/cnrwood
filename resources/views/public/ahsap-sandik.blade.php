@extends('layouts.public')

@php
    $title           = 'Ahşap Sandık Çözümleri — CNRWOOD';
    $metaDescription = 'CNRWOOD endüstriyel ahşap sandık çözümleri: ihracat sandığı, ağır yük sandığı, ISPM 15 sertifikalı ambalaj, kafes ve vinç aparatlı sandık. Proje bazlı, ölçüye özel üretim.';
@endphp

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ── Ahşap Sandık strategic landing — dark industrial ──────────── */
body { background-color: #0a0a0a; color: #e5e2e1; }

.as-wrap    { max-width: 1180px; margin: 0 auto; padding: 0 64px; }
.as-wrap-sm { max-width: 860px;  margin: 0 auto; padding: 0 64px; }
@media (max-width: 1023px) { .as-wrap, .as-wrap-sm { padding: 0 32px; } }
@media (max-width: 639px)  { .as-wrap, .as-wrap-sm { padding: 0 20px; } }

/* Label component */
.as-label {
    display: inline-flex; align-items: center; gap: 10px;
    font-family: 'JetBrains Mono', monospace; font-size: 10px;
    font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; color: #aecfa8;
}
.as-label::before { content: ''; display: block; width: 28px; height: 1px; background: #aecfa8; flex-shrink: 0; }

/* ── Hero ──────────────────────────────────────────────────────── */
.as-hero {
    position: relative; overflow: hidden;
    padding: 100px 0 88px;
    border-bottom: 1px solid rgba(51,51,51,0.20);
}
.as-hero-bg {
    position: absolute; inset: 0; z-index: 0;
    background-image: url('{{ asset('images/cnrwood/ahsap-sandik/hero.jpg') }}');
    background-size: cover; background-position: center 30%;
}
.as-hero-bg::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(
        105deg,
        rgba(8,8,8,0.92) 0%,
        rgba(8,8,8,0.82) 50%,
        rgba(8,8,8,0.55) 100%
    );
}
.as-hero-content { position: relative; z-index: 1; }
.as-hero h1 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: clamp(2rem, 4.5vw, 3.4rem);
    font-weight: 700; line-height: 1.06;
    letter-spacing: -0.025em; text-transform: uppercase;
    color: #f4f4f4; margin: 20px 0 22px;
}
.as-hero-lead {
    font-family: 'Inter', sans-serif; font-size: 18px; line-height: 1.75;
    color: rgba(195,200,190,0.80); max-width: 34rem; margin-bottom: 38px;
}
.as-hero-ctas { display: flex; flex-wrap: wrap; gap: 12px; }
.as-btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 32px; background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600;
    letter-spacing: 0.10em; text-transform: uppercase;
    border-radius: 4px; border-bottom: 1px solid rgba(0,0,0,0.18);
    text-decoration: none; transition: opacity 0.2s;
}
.as-btn-primary:hover { opacity: 0.88; }
.as-btn-ghost {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 32px; background: transparent; color: rgba(195,200,190,0.72);
    font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500;
    letter-spacing: 0.10em; text-transform: uppercase;
    border: 1px solid rgba(255,255,255,0.16); border-radius: 4px;
    text-decoration: none; transition: background 0.2s, color 0.2s;
}
.as-btn-ghost:hover { background: rgba(255,255,255,0.06); color: #f4f4f4; }

/* ── Trust cards ────────────────────────────────────────────────── */
.as-trust { padding: 64px 0; border-bottom: 1px solid rgba(51,51,51,0.14); }
.as-trust-grid {
    display: grid; gap: 16px;
    grid-template-columns: 1fr;
    margin-top: 36px;
}
@media (min-width: 500px) { .as-trust-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 900px) { .as-trust-grid { grid-template-columns: repeat(4, 1fr); } }
.as-trust-card {
    padding: 26px 22px;
    background: #111;
    border: 1px solid rgba(51,51,51,0.30);
    border-radius: 8px;
    transition: border-color 0.22s;
}
.as-trust-card:hover { border-color: rgba(174,207,168,0.18); }
.as-trust-icon {
    width: 38px; height: 38px;
    background: rgba(174,207,168,0.07); border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    color: #aecfa8; margin-bottom: 16px;
}
.as-trust-icon svg { width: 20px; height: 20px; }
.as-trust-card h3 {
    font-family: 'Hanken Grotesk', sans-serif; font-size: 15px; font-weight: 700;
    color: #f0eeeb; margin-bottom: 8px;
}
.as-trust-card p {
    font-family: 'Inter', sans-serif; font-size: 13px; line-height: 1.60; color: rgba(195,200,190,0.65);
}

/* ── Section shared ─────────────────────────────────────────────── */
.as-section { padding: 72px 0; border-bottom: 1px solid rgba(51,51,51,0.12); }
.as-section:last-of-type { border-bottom: none; }
.as-section-head {
    font-family: 'Hanken Grotesk', sans-serif; font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700; line-height: 1.12; letter-spacing: -0.02em;
    color: #f4f4f4; margin: 16px 0 14px;
}
.as-section-lead {
    font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.75;
    color: rgba(195,200,190,0.65); max-width: 46rem;
}

/* ── Solution cards grid ─────────────────────────────────────────── */
.as-sol-grid {
    display: grid; gap: 18px;
    grid-template-columns: 1fr; margin-top: 40px;
}
@media (min-width: 600px) { .as-sol-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 960px) { .as-sol-grid { grid-template-columns: repeat(3, 1fr); } }
.as-sol-card {
    padding: 28px 24px 24px;
    background: #111;
    border: 1px solid rgba(51,51,51,0.28); border-top: 2px solid rgba(174,207,168,0.18);
    border-radius: 8px; display: flex; flex-direction: column;
    transition: border-color 0.22s, box-shadow 0.22s;
}
.as-sol-card:hover { border-color: rgba(174,207,168,0.25); box-shadow: 0 8px 28px rgba(0,0,0,0.40); }
.as-sol-num {
    font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 600;
    letter-spacing: 0.14em; color: rgba(174,207,168,0.40);
    text-transform: uppercase; margin-bottom: 14px; display: block;
}
.as-sol-card h3 {
    font-family: 'Hanken Grotesk', sans-serif; font-size: 17px; font-weight: 700;
    color: #f0eeeb; margin-bottom: 12px; line-height: 1.2;
}
.as-sol-card p {
    font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.65;
    color: rgba(195,200,190,0.65); flex: 1;
}
.as-sol-specs {
    list-style: none; padding: 0; margin: 18px 0 20px;
    border-top: 1px solid rgba(51,51,51,0.20); padding-top: 16px;
}
.as-sol-specs li {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(174,207,168,0.60);
    padding: 4px 0;
}
.as-sol-specs li::before { content: '›'; font-size: 14px; flex-shrink: 0; }
.as-sol-link {
    display: inline-flex; align-items: center; gap: 6px; margin-top: auto;
    font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 600;
    letter-spacing: 0.10em; text-transform: uppercase; color: rgba(174,207,168,0.50);
    text-decoration: none; transition: color 0.18s;
}
.as-sol-card:hover .as-sol-link { color: #aecfa8; }

/* ── Technical process ────────────────────────────────────────────── */
.as-process { padding: 72px 0; background: #0e0e0e; border-top: 1px solid rgba(51,51,51,0.14); border-bottom: 1px solid rgba(51,51,51,0.14); }
.as-process-grid {
    display: grid; gap: 0;
    grid-template-columns: 1fr;
    margin-top: 40px;
}
@media (min-width: 640px) { .as-process-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 960px) { .as-process-grid { grid-template-columns: repeat(3, 1fr); } }
.as-process-step {
    padding: 28px 24px;
    border: 1px solid rgba(51,51,51,0.16);
    border-radius: 0; margin: -1px 0 0 -1px;
    position: relative;
}
.as-process-step:first-child { border-radius: 8px 0 0 0; }
.as-step-n {
    font-family: 'JetBrains Mono', monospace; font-size: 28px; font-weight: 600;
    color: rgba(174,207,168,0.12); letter-spacing: -0.03em;
    display: block; margin-bottom: 12px; line-height: 1;
}
.as-process-step h4 {
    font-family: 'Hanken Grotesk', sans-serif; font-size: 15px; font-weight: 700;
    color: #f0eeeb; margin-bottom: 8px;
}
.as-process-step p {
    font-family: 'Inter', sans-serif; font-size: 13px; line-height: 1.60;
    color: rgba(195,200,190,0.55);
}

/* ── Image strip ─────────────────────────────────────────────────── */
.as-imgstrip {
    display: grid; gap: 4px;
    grid-template-columns: repeat(3, 1fr);
    height: 260px; margin: 0;
    overflow: hidden; border-radius: 10px;
    margin-top: 52px;
}
.as-imgstrip-item {
    overflow: hidden; position: relative;
}
.as-imgstrip-item img {
    width: 100%; height: 100%; object-fit: cover;
    filter: brightness(0.70) saturate(0.80);
    transition: filter 0.36s, transform 0.36s;
    display: block;
}
.as-imgstrip-item:hover img { filter: brightness(0.82) saturate(0.90); transform: scale(1.04); }

/* ── Bottom CTA ──────────────────────────────────────────────────── */
.as-cta {
    padding: 88px 0; background: #0a0a0a; text-align: center;
    border-top: 1px solid rgba(51,51,51,0.14);
}
.as-cta h2 {
    font-family: 'Hanken Grotesk', sans-serif; font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700; text-transform: uppercase; color: #d4eacf;
    letter-spacing: -0.02em; margin-bottom: 14px;
}
.as-cta p {
    font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.75;
    color: #8aaf86; max-width: 32rem; margin: 0 auto 36px;
}
.as-cta-note {
    margin-top: 24px;
    font-family: 'JetBrains Mono', monospace; font-size: 10px;
    letter-spacing: 0.10em; text-transform: uppercase;
    color: rgba(174,207,168,0.28);
}
</style>
@endpush

@section('content')

{{-- ── Hero ────────────────────────────────────────────────────────── --}}
<section class="as-hero">
    <div class="as-hero-bg" role="presentation" aria-hidden="true"></div>
    <div class="as-wrap as-hero-content">
        <div class="as-label">AMBALAJ &amp; SANDIK ÇÖZÜMLERİ</div>
        <h1>Ahşap Sandık<br>Çözümleri</h1>
        <p class="as-hero-lead">Endüstriyel taşıma, ihracat ve ağır yük ambalajları için özel üretim ahşap sandık çözümleri.</p>
        <div class="as-hero-ctas">
            <a href="{{ route('public.sandik') }}" class="as-btn-primary">
                Teknik Ön Değerlendirme
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('public.quote.create') }}" class="as-btn-ghost">
                Teklif Al
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ── Trust summary cards ─────────────────────────────────────────── --}}
<section class="as-trust">
    <div class="as-wrap">
        <div class="as-label">YETKİNLİKLERİMİZ</div>
        <div class="as-trust-grid">

            <div class="as-trust-card">
                <div class="as-trust-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l9-4 9 4M3 6v12l9 4m-9-4l9 4m0 0l9-4V6m-9 16V10m9-4L12 10M3 6l9 4"/></svg>
                </div>
                <h3>Ağır Yük</h3>
                <p>500 kg üzeri makine ve ekipman taşıma için özel yapısal destek ve vinç entegrasyonu.</p>
            </div>

            <div class="as-trust-card">
                <div class="as-trust-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <h3>İhracat Ambalajı</h3>
                <p>Uluslararası deniz ve kara taşımacılığına uygun, iklim dayanımlı ihracat sandıkları.</p>
            </div>

            <div class="as-trust-card">
                <div class="as-trust-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3>ISPM 15 Süreçleri</h3>
                <p>Zorunlu ülkelere gönderim için ısıl işlem sertifikası ve IPPC damgalı üretim.</p>
            </div>

            <div class="as-trust-card">
                <div class="as-trust-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
                <h3>Ölçüye Özel Üretim</h3>
                <p>Standart boyutlar veya tam ölçüye özel kesim — her yük geometrisine uyumlu sandık tasarımı.</p>
            </div>

        </div>
    </div>
</section>

{{-- ── Product solution blocks ─────────────────────────────────────── --}}
<section class="as-section">
    <div class="as-wrap">
        <div class="as-label">SANDIK TİPLERİ</div>
        <h2 class="as-section-head">Sandık Çözüm Gamımız</h2>
        <p class="as-section-lead">Endüstriyel ihtiyaçlara göre altı farklı sandık tipi. Yük ağırlığı, taşıma yöntemi ve hedef ülkeye göre doğru tip için teknik değerlendirme alın.</p>

        <div class="as-sol-grid">

            {{-- 1 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">01 / Standart</span>
                <h3>Standart Ahşap Sandık</h3>
                <p>Genel amaçlı ürün ve makine taşıma için standart EURO normlarında veya özel ölçülerde çam/ladin kaplamalı sandık üretimi.</p>
                <ul class="as-sol-specs">
                    <li>Çam veya ladin kaplamalı yapı</li>
                    <li>EURO norm veya özel ölçü</li>
                    <li>Orta yük kapasitesi</li>
                    <li>Hızlı üretim süresi</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 2 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">02 / İhracat</span>
                <h3>İhracat Sandığı</h3>
                <p>Uluslararası deniz ve kara taşımacılığı için iklim dayanımlı, nem geçirmez kaplama seçenekli ihracat ambalajı.</p>
                <ul class="as-sol-specs">
                    <li>ISPM 15 ısıl işlem seçeneği</li>
                    <li>Nem bariyeri kaplama</li>
                    <li>IPPC damgalı ahşap</li>
                    <li>Deniz ve hava taşımacılığına uygun</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 3 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">03 / Ağır Yük</span>
                <h3>Ağır Yük &amp; Makine Sandığı</h3>
                <p>500 kg üzeri makine, ekipman ve prototip taşıma için çelik takviyeli, yapısal destek elemanlı güçlü sandık çözümleri.</p>
                <ul class="as-sol-specs">
                    <li>500 kg+ yük kapasitesi</li>
                    <li>İç destek ve sabitleme çerçevesi</li>
                    <li>Forklift girintisi ile uyumlu taban</li>
                    <li>Titreşim yalıtım seçeneği</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 4 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">04 / Kafes</span>
                <h3>Kafes Sandık</h3>
                <p>İçeriğin görünür olması gereken veya hava sirkülasyonu ihtiyaç duyulan ürünler için çıtalı açık kafes yapılı hafif sandık.</p>
                <ul class="as-sol-specs">
                    <li>Açık çıtalı veya metal kafesli</li>
                    <li>Hava sirkülasyonuna uygun</li>
                    <li>Hafif ve orta ağırlık yük</li>
                    <li>Gümrük kontrolü için görünür iç</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 5 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">05 / Izgara</span>
                <h3>Taban Izgara Sandık</h3>
                <p>Forklift ve palet taşıma ekipmanlarıyla tam uyumlu, güçlendirilmiş taban ızgara yapılı endüstriyel sandık.</p>
                <ul class="as-sol-specs">
                    <li>Forklift girintisi entegreli taban</li>
                    <li>Çift taraflı giriş seçeneği</li>
                    <li>Depo ve lojistik kullanımı</li>
                    <li>İstiflenebilir tasarım</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 6 --}}
            <div class="as-sol-card">
                <span class="as-sol-num">06 / Vinç</span>
                <h3>Vinç Aparatlı Sandık</h3>
                <p>Üst taşıma için vinç kancası entegrasyonu, ağır sanayi ve fabrika içi sevkiyat operasyonlarına yönelik özel sandık.</p>
                <ul class="as-sol-specs">
                    <li>Sabit vinç kancası noktaları</li>
                    <li>Üst ve yan kaldırma tasarımı</li>
                    <li>Ağır sanayi uygulamaları</li>
                    <li>Yük dengeleme hesabı dahil</li>
                </ul>
                <a href="{{ route('public.quote.create') }}" class="as-sol-link">
                    Teklif Al
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ── Image strip ────────────────────────────────────────────────── --}}
<div class="as-wrap" aria-hidden="true">
    <div class="as-imgstrip">
        <div class="as-imgstrip-item">
            <img src="{{ asset('images/cnrwood/ahsap-sandik/ihracat-ambalaji.jpg') }}" alt="" loading="lazy">
        </div>
        <div class="as-imgstrip-item">
            <img src="{{ asset('images/cnrwood/ahsap-sandik/hero.jpg') }}" alt="" loading="lazy">
        </div>
        <div class="as-imgstrip-item">
            <img src="{{ asset('images/cnrwood/ahsap-sandik/factory-packaging.jpg') }}" alt="" loading="lazy">
        </div>
    </div>
</div>

{{-- ── Technical process ───────────────────────────────────────────── --}}
<section class="as-process">
    <div class="as-wrap">
        <div class="as-label">TEKNİK DEĞERLENDİRME</div>
        <h2 class="as-section-head" style="margin-top:16px;margin-bottom:12px">Doğru Sandık Tipi İçin<br>6 Temel Parametre</h2>
        <p class="as-section-lead">Teknik ön değerlendirme sürecinde aşağıdaki bilgileri paylaşmanız, sandık tipi önerisinin doğruluğunu artırır. Değerlendirme ücretsizdir; anlık fiyat hesabı değildir.</p>

        <div class="as-process-grid">

            <div class="as-process-step">
                <span class="as-step-n">01</span>
                <h4>Ölçü</h4>
                <p>Sandık iç boyutu (en × boy × yükseklik) veya ambalajlanacak ürünün dış ölçüleri.</p>
            </div>

            <div class="as-process-step">
                <span class="as-step-n">02</span>
                <h4>Yük Ağırlığı</h4>
                <p>Brüt yük ağırlığı, sandık yapısının tasarımını ve malzeme kalınlığını doğrudan belirler.</p>
            </div>

            <div class="as-process-step">
                <span class="as-step-n">03</span>
                <h4>Taşıma Yöntemi</h4>
                <p>Kara, deniz veya hava taşımacılığı; titreşim ve nem dayanımı gereksinimlerini etkiler.</p>
            </div>

            <div class="as-process-step">
                <span class="as-step-n">04</span>
                <h4>Sevkiyat Ülkesi</h4>
                <p>Hedef ülkenin ISPM 15 zorunluluğu varsa ısıl işlem ve IPPC damgası gereklidir.</p>
            </div>

            <div class="as-process-step">
                <span class="as-step-n">05</span>
                <h4>ISPM 15 İhtiyacı</h4>
                <p>Fümigasyon veya ısıl işlem tercihini, sertifika türünü ve üretim takvimini belirler.</p>
            </div>

            <div class="as-process-step">
                <span class="as-step-n">06</span>
                <h4>Sandık Tipi Önerisi</h4>
                <p>Tüm parametreler değerlendirilerek en uygun sandık tipi, malzeme ve yapısal çözüm önerilir.</p>
            </div>

        </div>
    </div>
</section>

{{-- ── Bottom CTA ─────────────────────────────────────────────────── --}}
<section class="as-cta">
    <div class="as-wrap-sm">
        <div class="as-label" style="justify-content:center;margin-bottom:20px">CNRWOOD İLE ÇALIŞIN</div>
        <h2>Teknik Ön Değerlendirme<br>İçin Başvurun</h2>
        <p>Sandık boyutları, taşıma kapasitesi ve ISPM 15 gereksinimleri için uzman ekibimizden ücretsiz teknik değerlendirme alın.</p>
        <div>
            <a href="{{ route('public.sandik') }}" class="as-btn-primary">
                Teknik Ön Değerlendirme
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('public.quote.create') }}" class="as-btn-ghost" style="margin-top:12px">
                Teklif Al
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <p class="as-cta-note">Teknik Ön Değerlendirme anlık fiyat hesabı değildir — uzman değerlendirmesi ile ücretsiz ön analiz sunulur.</p>
    </div>
</section>

@endsection
