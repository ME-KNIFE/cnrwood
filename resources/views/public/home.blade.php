@extends('layouts.public')

@php
    $title           = 'CNRWOOD | Endüstriyel Ahşap Ambalaj Çözümleri';
    $metaDescription = 'Gebze merkezli CNRWOOD; ağır sanayi, hassas ekipman ve ihracat lojistiği için ISPM 15 uyumlu, yüksek mukavemetli ahşap sandık ve endüstriyel ambalaj çözümleri üretir.';
@endphp

@push('head')
{{-- Fonts: Material Symbols + Hanken Grotesk + JetBrains Mono + Inter --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&family=Hanken+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ================================================================
   CNRWOOD Industrial Integrity — Homepage v6 (polish pass)
   Dark industrial palette — Stitch approved design
   ================================================================ */

/* ── Material Symbols ─────────────────────────────────────────── */
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
    font-style: normal;
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr;
    vertical-align: middle;
}

/* ── Body override for dark homepage ─────────────────────────── */
body { background-color: #0a0a0a; color: #e5e2e1; }

/* ── Blueprint grid ──────────────────────────────────────────── */
.blueprint-grid {
    background-image:
        linear-gradient(to right,  rgba(51,51,51,0.10) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(51,51,51,0.10) 1px, transparent 1px);
    background-size: 40px 40px;
}

/* ── Font families ───────────────────────────────────────────── */
.cnr-font-display { font-family: 'Hanken Grotesk', sans-serif; }
.cnr-font-mono    { font-family: 'JetBrains Mono', monospace; }
.cnr-font-body    { font-family: 'Inter', sans-serif; }

/* ── Type scale ──────────────────────────────────────────────── */
.cnr-text-xl {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 48px; line-height: 56px;
    letter-spacing: -0.02em; font-weight: 700;
}
.cnr-text-lg {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 32px; line-height: 40px;
    letter-spacing: -0.01em; font-weight: 700;
}
.cnr-text-md {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 24px; line-height: 32px; font-weight: 600;
}
.cnr-label-sm {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px; line-height: 14px; font-weight: 500;
}
.cnr-label-md {
    font-family: 'JetBrains Mono', monospace;
    font-size: 14px; line-height: 16px; font-weight: 500;
}
.cnr-body-lg { font-family: 'Inter', sans-serif; font-size: 18px; line-height: 28px; font-weight: 400; }
.cnr-body-md { font-family: 'Inter', sans-serif; font-size: 16px; line-height: 24px; font-weight: 400; }
.cnr-body-sm { font-family: 'Inter', sans-serif; font-size: 14px; line-height: 20px; font-weight: 400; }

/* ── Colour utilities ────────────────────────────────────────── */
.cnr-bg-obsidian    { background-color: #0a0a0a; }
.cnr-bg-charcoal    { background-color: #1e1e1e; }
.cnr-bg-surface-min { background-color: #0e0e0e; }
.cnr-bg-surface     { background-color: #201f1f; }
.cnr-bg-primary-con { background-color: #1e3a1e; }
.cnr-bg-primary     { background-color: #aecfa8; }
.cnr-bg-on-primary  { background-color: #1b361b; }

.cnr-text-primary    { color: #aecfa8; }
.cnr-text-on-primary { color: #1b361b; }
.cnr-text-surface    { color: #e5e2e1; }
.cnr-text-sv         { color: #c3c8be; }
.cnr-text-tw         { color: #f4f4f4; }
.cnr-text-brass      { color: #a67c00; }

/* ── Layout ──────────────────────────────────────────────────── */
.cnr-container { max-width: 1280px; margin: 0 auto; padding: 0 64px; }

/* ── Hero ────────────────────────────────────────────────────── */
.cnr-hero {
    position: relative;
    min-height: 85vh;
    display: flex;
    align-items: center;
    overflow: hidden;
}
/* [1] opacity 0.50 → 0.55; fade center eased 0.75 → 0.70 — ~5% less dark */
.cnr-hero-bg-img {
    position: absolute; inset: 0;
    background-color: #1a2e1a;
    background-image: url('{{ asset('images/cnrwood/homepage/hero-factory.jpg') }}'),
                      linear-gradient(135deg, #1a2e1a 0%, #0a0a0a 100%);
    background-size: cover; background-position: center;
    opacity: 0.55;
    filter: grayscale(0.45);
}
.cnr-hero-fade {
    position: absolute; inset: 0;
    background: linear-gradient(to right, #0a0a0a 0%, rgba(10,10,10,0.70) 50%, rgba(10,10,10,0.15) 100%);
}
.cnr-hero-content { position: relative; z-index: 10; width: 100%; }
.cnr-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 4px 12px;
    background: rgba(174,207,168,0.10);
    border: 1px solid rgba(174,207,168,0.20);
    border-radius: 9999px;
    margin-bottom: 24px;
}
.cnr-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-top: 80px;
    border-top: 1px solid rgba(51,51,51,0.20);
    padding-top: 48px;
}

/* ── Trust grid ──────────────────────────────────────────────── */
.cnr-trust-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
.cnr-trust-card {
    padding: 32px;
    background-color: #1e1e1e;
    border: 1px solid rgba(51,51,51,0.30);
    border-radius: 12px;
}

/* ── Bento grid ──────────────────────────────────────────────── */
.cnr-bento-outer {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
.cnr-bento-card {
    position: relative; overflow: hidden; border-radius: 12px;
    border: 1px solid rgba(51,51,51,0.20);
    min-height: 280px;
}
.cnr-bento-card-main { min-height: 420px; }
.cnr-bento-img {
    position: absolute; inset: 0;
    background-size: cover; background-position: center;
    transition: transform 0.7s ease;
}
.cnr-bento-card:hover .cnr-bento-img { transform: scale(1.05); }
.cnr-bento-grad {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(10,10,10,0.42) 52%, transparent 100%);
}
.cnr-bento-body-lg { position: absolute; bottom: 0; left: 0; padding: 40px; width: 100%; }
.cnr-bento-body-sm { position: absolute; bottom: 0; left: 0; padding: 28px; }
.cnr-flagship-tag {
    display: inline-block;
    background: rgba(174,207,168,0.90);
    color: #1b361b;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px; font-weight: 500;
    padding: 4px 10px; border-radius: 2px;
    margin-bottom: 14px;
    text-transform: uppercase; letter-spacing: 0.08em;
}
.cnr-bento-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.20);
    color: #fff;
    font-family: 'JetBrains Mono', monospace; font-size: 14px;
    padding: 12px 24px; border-radius: 2px;
    text-decoration: none;
    transition: background 0.2s;
}
.cnr-bento-btn:hover { background: rgba(255,255,255,0.20); }

/* ── Link arrow ──────────────────────────────────────────────── */
.cnr-link-arrow {
    display: inline-flex; align-items: center; gap: 6px;
    color: #aecfa8;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    border-bottom: 1px solid rgba(174,207,168,0.30);
    padding-bottom: 4px;
    text-decoration: none;
    white-space: nowrap;
}
.cnr-link-arrow .material-symbols-outlined { transition: transform 0.2s; }
.cnr-link-arrow:hover .material-symbols-outlined { transform: translateX(4px); }

/* ── Specialized section ─────────────────────────────────────── */
.cnr-spec-card {
    background-color: #0a0a0a;
    border-radius: 16px; overflow: hidden;
    border: 1px solid rgba(174,207,168,0.10);
    display: flex; flex-direction: column;
}
.cnr-spec-content { padding: 48px 40px; }
/* [7] Use heavy-duty-crate as fallback: clear industrial packaging image */
.cnr-spec-img {
    min-height: 320px;
    background-color: #1a2e1a;
    background-image: url('{{ asset('images/cnrwood/ahsap-sandik/hero.jpg') }}'),
                      url('{{ asset('images/cnrwood/ahsap-sandik/factory-packaging.jpg') }}'),
                      linear-gradient(135deg, #1a2e1a 0%, #0a0a0a 100%);
    background-size: cover; background-position: center;
    position: relative; flex-shrink: 0;
}
.cnr-spec-img-fade {
    position: absolute; inset: 0;
    background: linear-gradient(to right, transparent, rgba(10,10,10,0.20), #0a0a0a);
    display: none;
}
.cnr-brass-label {
    display: flex; align-items: center; gap: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: 12px;
    font-weight: 500; letter-spacing: 0.12em; text-transform: uppercase;
    color: #a67c00; margin-bottom: 24px;
}
.cnr-brass-label::before {
    content: ''; display: block; width: 32px; height: 1px; background: #a67c00; flex-shrink: 0;
}
.cnr-spec-list { list-style: none; padding: 0; margin: 0 0 48px; }
.cnr-spec-list li {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.06);
}
.cnr-spec-list li:last-child { border-bottom: none; padding-bottom: 0; }
.cnr-spec-cta {
    display: inline-block;
    background-color: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    padding: 16px 40px; border-radius: 2px;
    text-decoration: none; transition: opacity 0.2s;
}
.cnr-spec-cta:hover { opacity: 0.90; }

/* ── Hero CTA buttons ────────────────────────────────────────── */
.cnr-hero-btn-p {
    display: inline-flex; align-items: center; gap: 12px;
    background-color: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    padding: 16px 32px; border-radius: 4px;
    border-bottom: 2px solid rgba(166,124,0,0.40);
    text-decoration: none; transition: all 0.2s;
}
.cnr-hero-btn-p:hover { opacity: 0.90; box-shadow: 0 8px 24px rgba(174,207,168,0.10); }
.cnr-hero-btn-o {
    display: inline-flex; align-items: center; gap: 12px;
    background: transparent; color: #f4f4f4;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    padding: 16px 32px; border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.22);
    text-decoration: none; transition: background 0.2s;
}
.cnr-hero-btn-o:hover { background: rgba(255,255,255,0.06); }

/* ── [8] CTA section — improved contrast ─────────────────────── */
.cnr-cta-section {
    background-color: #162a16; /* slightly darker than #1e3a1e for contrast */
    padding: 88px 0;
}
.cnr-cta-btn-main {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 16px 48px;
    background-color: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 600;
    border-radius: 8px; text-decoration: none;
    border-bottom: 1px solid rgba(166,124,0,0.25);
    transition: opacity 0.2s;
}
.cnr-cta-btn-main:hover { opacity: 0.88; }

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 767px) {
    .cnr-container { padding: 0 20px; }
    .cnr-text-xl   { font-size: 30px; line-height: 36px; }
    .cnr-text-lg   { font-size: 22px; line-height: 30px; }
    .cnr-spec-content { padding: 40px 24px; }
}

@media (min-width: 768px) {
    .cnr-stats-grid     { grid-template-columns: repeat(4, 1fr); }
    .cnr-trust-grid     { grid-template-columns: repeat(3, 1fr); }
    .cnr-bento-outer    { grid-template-columns: repeat(12, 1fr); grid-auto-rows: auto; }
    .cnr-bento-main     { grid-column: span 8; }
    .cnr-bento-side     { grid-column: span 4; }
    .cnr-bento-outer.cnr-bento-height { height: 900px; }
    .cnr-bento-card { min-height: unset; height: 100%; }
}

@media (min-width: 1024px) {
    .cnr-spec-card    { flex-direction: row; }
    .cnr-spec-content { flex: 1; padding: 80px; }
    .cnr-spec-img     { flex: 1; min-height: 400px; }
    .cnr-spec-img-fade { display: block; }
}

/* ================================================================
   CNRWOOD MOTION LAYER v1
   Industrial precision — CSS keyframes + IntersectionObserver
   Reduced-motion safe throughout
   ================================================================ */

@keyframes cnr-rise {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0);    }
}

@keyframes cnr-drift {
    from { transform: scale(1.00) translateX(0%);  }
    to   { transform: scale(1.08) translateX(-1%); }
}

.cnr-hero-in {
    opacity: 0;
    animation: cnr-rise 0.75s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}
.cnr-hi-d1 { animation-delay: 0.10s; }
.cnr-hi-d2 { animation-delay: 0.28s; }
.cnr-hi-d3 { animation-delay: 0.48s; }
.cnr-hi-d4 { animation-delay: 0.66s; }
.cnr-hi-d5 { animation-delay: 0.86s; }

.cnr-hero-bg-img {
    animation: cnr-drift 24s ease-in-out infinite alternate;
    will-change: transform;
    transform-origin: center center;
}

.cnr-sr {
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity   0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) var(--cnr-sd, 0s),
        transform 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) var(--cnr-sd, 0s);
}
.cnr-sr.is-visible {
    opacity: 1;
    transform: translateY(0);
}

.cnr-trust-card {
    transition: border-color 0.30s ease, transform 0.30s ease, box-shadow 0.30s ease;
}
.cnr-trust-card:hover {
    border-color: rgba(174,207,168,0.22);
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.28);
}

.cnr-bento-card {
    transition: border-color 0.35s ease;
}
.cnr-bento-card:hover {
    border-color: rgba(166,124,0,0.30);
}
.cnr-bento-grad {
    transition: opacity 0.35s ease;
}
.cnr-bento-card:hover .cnr-bento-grad {
    opacity: 0.72;
}
.cnr-bento-btn .material-symbols-outlined {
    transition: transform 0.25s ease;
}
.cnr-bento-btn:hover .material-symbols-outlined {
    transform: translateX(4px);
}

@media (prefers-reduced-motion: reduce) {
    .cnr-hero-in     { animation: none; opacity: 1; }
    .cnr-hero-bg-img { animation: none; }
    .cnr-sr          { opacity: 1; transform: none; transition: none; }
    .cnr-trust-card  { transition: none; }
    .cnr-trust-card:hover  { transform: none; box-shadow: none; }
    .cnr-bento-card  { transition: none; }
    .cnr-bento-img   { transition: none; }
    .cnr-bento-grad  { transition: none; }
    .cnr-bento-btn .material-symbols-outlined { transition: none; }
}
</style>
@endpush

@section('content')

{{-- ================================================================
     HERO SECTION
================================================================ --}}
<section class="cnr-hero">
    <div style="position:absolute;inset:0;z-index:0">
        <div class="cnr-hero-bg-img"></div>
        <div class="cnr-hero-fade"></div>
    </div>

    <div class="cnr-hero-content">
        <div class="cnr-container">
            <div style="max-width:48rem">

                {{-- Badge --}}
                <div class="cnr-hero-badge cnr-hero-in cnr-hi-d1">
                    <span class="material-symbols-outlined cnr-text-primary"
                          style="font-size:16px;font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24">verified</span>
                    <span class="cnr-label-sm cnr-text-primary" style="text-transform:uppercase;letter-spacing:0.12em">ISPM 15 Uyumlu Üretim</span>
                </div>

                {{-- Heading --}}
                <h1 class="cnr-text-xl cnr-text-tw cnr-hero-in cnr-hi-d2" style="text-transform:uppercase;margin-bottom:24px;line-height:1.05">
                    AHŞAP SANDIK VE ENDÜSTRİYEL AMBALAJ ÇÖZÜMLERİ
                </h1>

                {{-- [2] Updated subtitle — removed "savunma sanayi" from hero --}}
                <p class="cnr-body-lg cnr-text-sv cnr-hero-in cnr-hi-d3" style="margin-bottom:40px;max-width:42rem">
                    Hassas ekipman, ağır sanayi, makine ve ihracat lojistiği için yüksek mukavemetli, proje bazlı ahşap ambalaj ve paketleme sistemleri.
                </p>

                {{-- CTA buttons --}}
                <div class="cnr-hero-in cnr-hi-d4" style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:0">
                    <a href="{{ route('public.quote.create') }}" class="cnr-hero-btn-p">
                        <span>Teklif Al</span>
                        <span class="material-symbols-outlined" style="font-size:20px">request_quote</span>
                    </a>
                    <a href="{{ route('public.products') }}" class="cnr-hero-btn-o">
                        <span>Ürünleri İncele</span>
                        <span class="material-symbols-outlined" style="font-size:20px">inventory_2</span>
                    </a>
                </div>

                {{-- Stats row --}}
                <div class="cnr-stats-grid cnr-hero-in cnr-hi-d5">
                    <div>
                        <div class="cnr-text-md cnr-text-primary" style="margin-bottom:4px">1998</div>
                        <div class="cnr-label-sm cnr-text-sv" style="text-transform:uppercase;letter-spacing:0.15em">Kuruluş Yılı</div>
                    </div>
                    <div>
                        <div class="cnr-text-md cnr-text-primary" style="margin-bottom:4px">70+</div>
                        <div class="cnr-label-sm cnr-text-sv" style="text-transform:uppercase;letter-spacing:0.15em">Uzman Çalışan</div>
                    </div>
                    <div>
                        <div class="cnr-text-md cnr-text-primary" style="margin-bottom:4px;text-transform:uppercase">Proje Bazlı</div>
                        <div class="cnr-label-sm cnr-text-sv" style="text-transform:uppercase;letter-spacing:0.15em">Üretim Kapasitesi</div>
                    </div>
                    <div>
                        <div class="cnr-text-md cnr-text-primary" style="margin-bottom:4px">7+ Ülke</div>
                        <div class="cnr-label-sm cnr-text-sv" style="text-transform:uppercase;letter-spacing:0.15em">İhracat Ağı</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     TRUST / SERVICE CARDS
     [4] Reduced padding 96px → 72px to close dead space
================================================================ --}}
<section class="cnr-bg-surface-min blueprint-grid" style="padding:72px 0">
    <div class="cnr-container">
        <div class="cnr-trust-grid">

            {{-- [3] Card 1: title + desc updated --}}
            <div class="cnr-trust-card cnr-sr" style="--cnr-sd:0s">
                <span class="material-symbols-outlined cnr-text-primary" style="font-size:36px;display:block;margin-bottom:24px">precision_manufacturing</span>
                <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:16px">Hassas Ekipman Ambalajı</h3>
                <p class="cnr-body-md cnr-text-sv">Savunma sanayi ve hassas ekipman taşımacılığına uygun, proje bazlı ahşap ambalaj çözümleri.</p>
            </div>

            <div class="cnr-trust-card cnr-sr" style="--cnr-sd:0.12s">
                <span class="material-symbols-outlined cnr-text-primary" style="font-size:36px;display:block;margin-bottom:24px">architecture</span>
                <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:16px">Özel Proje Tasarımı</h3>
                <p class="cnr-body-md cnr-text-sv">Makinelerinizin ağırlık merkezi ve hassasiyetine göre özel olarak tasarlanan endüstriyel ambalaj çözümleri.</p>
            </div>

            <div class="cnr-trust-card cnr-sr" style="--cnr-sd:0.24s">
                <span class="material-symbols-outlined cnr-text-primary" style="font-size:36px;display:block;margin-bottom:24px">workspace_premium</span>
                <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:16px">Yüksek Kalite Standartları</h3>
                <p class="cnr-body-md cnr-text-sv">İhracat ambalajı ve ısıl işlemli ahşap çözümlerimiz, ISPM 15 süreçlerine uygun şekilde hazırlanır.</p>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     PRODUCT BENTO GRID
     [4] Reduced padding 96px → 72px to close dead space
================================================================ --}}
<section class="cnr-bg-obsidian" style="padding:72px 0 96px">
    <div class="cnr-container">

        {{-- Section header --}}
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:24px;margin-bottom:64px">
            <div style="max-width:32rem">
                <div class="cnr-label-sm cnr-text-primary" style="margin-bottom:16px;text-transform:uppercase;letter-spacing:0.2em">Endüstriyel Çözümlerimiz</div>
                <h2 class="cnr-text-xl cnr-text-tw" style="text-transform:uppercase;line-height:1.05">YÜKSEK MUKAVEMETLİ ÜRETİM PORTFÖYÜ</h2>
            </div>
            <a href="{{ route('public.products') }}" class="cnr-link-arrow">
                Tüm Ürünleri Gör
                <span class="material-symbols-outlined" style="font-size:18px">trending_flat</span>
            </a>
        </div>

        {{-- Bento grid --}}
        <div class="cnr-bento-outer cnr-bento-height">

            {{-- [5] Large card: tag → "ÖNE ÇIKAN ÇÖZÜM" --}}
            <div class="cnr-bento-card cnr-bento-main cnr-bento-card-main cnr-sr" style="--cnr-sd:0s">
                <div class="cnr-bento-img"
                     style="background-image:url('{{ asset('images/cnrwood/ahsap-sandik/hero.jpg') }}'),linear-gradient(135deg,#1a2e1a 0%,#0a0a0a 100%)">
                </div>
                <div class="cnr-bento-grad"></div>
                <div class="cnr-bento-body-lg">
                    <span class="cnr-flagship-tag">ÖNE ÇIKAN ÇÖZÜM</span>
                    <h3 class="cnr-text-lg cnr-text-tw" style="margin-bottom:12px">Ağır Yük ve Makine Sandıkları</h3>
                    <p class="cnr-text-sv cnr-body-md" style="max-width:32rem;margin-bottom:24px">Tonlarca ağırlıktaki sanayi makineleri ve hassas ekipmanlar için çelik takviyeli, yüksek mukavemetli kafes ve kapalı sandık sistemleri.</p>
                    <a href="{{ route('public.products') }}" class="cnr-bento-btn">
                        Teknik Detaylar
                        <span class="material-symbols-outlined" style="font-size:18px">info</span>
                    </a>
                </div>
            </div>

            {{-- [5] Side: OSB — updated description --}}
            <div class="cnr-bento-card cnr-bento-side cnr-sr" style="--cnr-sd:0.08s">
                <div class="cnr-bento-img"
                     style="background-image:url('{{ asset('images/cnrwood/levha-kereste/osb-kontrplak.jpg') }}'),linear-gradient(135deg,#2a1a0a 0%,#0a0a0a 100%)">
                </div>
                <div class="cnr-bento-grad"></div>
                <div class="cnr-bento-body-sm">
                    <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:8px">OSB &amp; Kontrplak Levha</h3>
                    <p class="cnr-body-sm cnr-text-sv">Endüstriyel ambalaj, mobilya ve yapı uygulamaları için kaliteli levha çözümleri.</p>
                </div>
            </div>

            {{-- [5] Side: İhracat — updated description --}}
            <div class="cnr-bento-card cnr-bento-side cnr-sr" style="--cnr-sd:0.16s">
                <div class="cnr-bento-img"
                     style="background-image:url('{{ asset('images/cnrwood/ahsap-sandik/ihracat-ambalaji.jpg') }}'),linear-gradient(135deg,#1a1a2a 0%,#0a0a0a 100%)">
                </div>
                <div class="cnr-bento-grad"></div>
                <div class="cnr-bento-body-sm">
                    <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:8px">İhracat Ambalajı</h3>
                    <p class="cnr-body-sm cnr-text-sv">Konteyner içi sabitleme, korumalı paketleme ve ihracata uygun ahşap ambalaj çözümleri.</p>
                </div>
            </div>

            {{-- [5] Side: Kapı Sereni — title stripped "& Kereste", updated desc --}}
            <div class="cnr-bento-card cnr-bento-side cnr-sr" style="--cnr-sd:0.24s">
                <div class="cnr-bento-img"
                     style="background-image:url('{{ asset('images/cnrwood/kapi-sereni/hero.jpg') }}'),linear-gradient(135deg,#1a2a1a 0%,#0a0a0a 100%)">
                </div>
                <div class="cnr-bento-grad"></div>
                <div class="cnr-bento-body-sm">
                    <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:8px">Kapı Sereni</h3>
                    <p class="cnr-body-sm cnr-text-sv">Panel kapı karkası üretimi için ekli ve eksiz kapı sereni çözümleri.</p>
                </div>
            </div>

            {{-- [5] Side: Isıl İşlemli — updated description --}}
            <div class="cnr-bento-card cnr-bento-side cnr-sr" style="--cnr-sd:0.32s">
                <div class="cnr-bento-img"
                     style="background-image:url('{{ asset('images/cnrwood/isil-islemli/hero.jpg') }}'),linear-gradient(135deg,#2a2010 0%,#0a0a0a 100%)">
                </div>
                <div class="cnr-bento-grad"></div>
                <div class="cnr-bento-body-sm">
                    <h3 class="cnr-text-md cnr-text-tw" style="margin-bottom:8px">Isıl İşlemli Ahşap</h3>
                    <p class="cnr-body-sm cnr-text-sv">ISPM 15 standartlarına uygun, ihracat süreçleri için ısıl işlem görmüş ahşap çözümleri.</p>
                </div>
            </div>

        </div>{{-- /bento --}}
    </div>
</section>

{{-- ================================================================
     SPECIALIZED PROJECT SECTION
     [7] Image changed to heavy-duty-crate (packaging), paragraph + features updated
================================================================ --}}
<section class="cnr-bg-surface blueprint-grid" style="padding:96px 0;position:relative;overflow:hidden">
    <div class="cnr-container" style="position:relative;z-index:10">
        <div class="cnr-spec-card cnr-sr">

            {{-- Content --}}
            <div class="cnr-spec-content">
                <div class="cnr-brass-label">PROJE BAZLI ÇÖZÜMLER</div>
                <h2 class="cnr-text-xl cnr-text-tw" style="margin-bottom:32px;line-height:1.05">Özel Proje Ahşap Ambalaj Çözümleri</h2>
                <p class="cnr-body-lg cnr-text-sv" style="margin-bottom:40px;max-width:28rem;line-height:1.75">
                    Standart ölçülerin dışında kalan ürünler, hassas ekipmanlar ve özel taşıma gereksinimleri için proje bazlı ahşap ambalaj çözümleri geliştiriyoruz.
                </p>

                <ul class="cnr-spec-list">
                    <li>
                        <span class="material-symbols-outlined cnr-text-primary" style="font-size:20px;margin-top:2px;flex-shrink:0">check_circle</span>
                        <div>
                            <h4 class="cnr-text-md cnr-text-tw" style="font-size:18px;margin-bottom:4px">Hassas Ekipman Sandıkları</h4>
                            <p class="cnr-body-sm cnr-text-sv">Yüksek değerli ve hassas cihazlar için korumalı, ölçüye özel ahşap sandık çözümleri.</p>
                        </div>
                    </li>
                    <li>
                        <span class="material-symbols-outlined cnr-text-primary" style="font-size:20px;margin-top:2px;flex-shrink:0">check_circle</span>
                        <div>
                            <h4 class="cnr-text-md cnr-text-tw" style="font-size:18px;margin-bottom:4px">Endüstriyel Sistem Muhafazaları</h4>
                            <p class="cnr-body-sm cnr-text-sv">Teknik ekipman ve sistem bileşenleri için taşıma ve depolama odaklı ahşap ambalaj çözümleri.</p>
                        </div>
                    </li>
                </ul>

                <a href="{{ route('public.sandik') }}" class="cnr-spec-cta">Teknik Danışmanlık Al</a>
            </div>

            {{-- Image --}}
            <div class="cnr-spec-img">
                <div class="cnr-spec-img-fade"></div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     CTA SECTION
     [8] Darker bg, high-contrast text, bright green primary button
         E-catalog button HIDDEN: catalog/cnrwood-e-katalog.pdf missing
================================================================ --}}
<section class="cnr-cta-section">
    <div class="cnr-container cnr-sr" style="text-align:center">
        <h2 class="cnr-text-xl" style="color:#d4eacf;text-transform:uppercase;margin-bottom:24px;line-height:1.05;max-width:40rem;margin-left:auto;margin-right:auto">
            PROJENİZ İÇİN EN DOĞRU AMBALAJI BİRLİKTE TASARLAYALIM
        </h2>
        <p class="cnr-body-lg" style="color:#8aaf86;margin-bottom:40px;max-width:36rem;margin-left:auto;margin-right:auto">
            Uzman ekibimiz, ürününüzün teknik gereksinimlerine göre güvenli, dayanıklı ve proje bazlı ambalaj çözümünü tasarlamaya hazır.
        </p>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px">
            <a href="{{ route('public.contact') }}" class="cnr-cta-btn-main">
                <span class="material-symbols-outlined" style="font-size:18px">call</span>
                Bize Ulaşın
            </a>
            {{-- E-Katalog button intentionally omitted: catalog/cnrwood-e-katalog.pdf not yet published --}}
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.cnr-sr').forEach(function (el) {
            el.classList.add('is-visible');
        });
        return;
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.10,
        rootMargin: '0px 0px -32px 0px'
    });

    document.querySelectorAll('.cnr-sr').forEach(function (el) {
        io.observe(el);
    });
}());
</script>
@endpush
