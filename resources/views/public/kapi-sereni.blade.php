@extends('layouts.public')

@php
    $title           = 'Kapı Sereni — CNRWOOD';
    $metaDescription = 'CNRWOOD kapı sereni üretimi: panel kapı karkası için ekli ve eksiz kapı sereni çözümleri. Kaliteli ahşap, proje bazlı üretim.';
@endphp

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ── Kapı Sereni — dark industrial page ───────────────────────── */
body { background-color: #0a0a0a; color: #e5e2e1; }

.ks-container { max-width: 1080px; margin: 0 auto; padding: 0 64px; }
@media (max-width: 767px) { .ks-container { padding: 0 24px; } }

.ks-brass-label {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: 11px;
    font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase;
    color: #a67c00;
}
.ks-brass-label::before {
    content: ''; display: block; width: 28px; height: 1px;
    background: #a67c00; flex-shrink: 0;
}

.ks-hero {
    padding: 96px 0 80px;
    border-bottom: 1px solid rgba(51,51,51,0.20);
    background: linear-gradient(to bottom, rgba(26,36,26,0.30) 0%, transparent 100%);
}
.ks-hero h1 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700; line-height: 1.08;
    letter-spacing: -0.02em; text-transform: uppercase;
    color: #f4f4f4; margin: 20px 0 28px;
}
.ks-hero p {
    font-family: 'Inter', sans-serif;
    font-size: 18px; line-height: 1.75;
    color: #c3c8be; max-width: 36rem;
}

.ks-section { padding: 72px 0; border-bottom: 1px solid rgba(51,51,51,0.12); }
.ks-section:last-of-type { border-bottom: none; }

.ks-section-head {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 26px; font-weight: 700; line-height: 1.2;
    color: #f4f4f4; margin: 16px 0 20px;
}
.ks-section p {
    font-family: 'Inter', sans-serif;
    font-size: 16px; line-height: 1.75;
    color: #c3c8be; max-width: 52rem;
}

.ks-card-grid {
    display: grid; gap: 20px;
    grid-template-columns: 1fr; margin-top: 36px;
}
@media (min-width: 640px)  { .ks-card-grid { grid-template-columns: repeat(2, 1fr); } }

.ks-card {
    padding: 28px 24px;
    background-color: #131313;
    border: 1px solid rgba(51,51,51,0.28);
    border-radius: 10px;
    transition: border-color 0.25s;
}
.ks-card:hover { border-color: rgba(174,207,168,0.18); }
.ks-card h3 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 18px; font-weight: 600;
    color: #f4f4f4; margin-bottom: 10px;
}
.ks-card p {
    font-family: 'Inter', sans-serif;
    font-size: 14px; line-height: 1.65; color: #c3c8be;
}

.ks-spec-row {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 16px 0; border-bottom: 1px solid rgba(51,51,51,0.15);
}
.ks-spec-row:last-child { border-bottom: none; padding-bottom: 0; }
.ks-spec-label {
    flex-shrink: 0; min-width: 180px;
    font-family: 'JetBrains Mono', monospace; font-size: 12px;
    font-weight: 500; letter-spacing: 0.06em; color: #aecfa8;
    text-transform: uppercase; padding-top: 2px;
}
.ks-spec-val {
    font-family: 'Inter', sans-serif; font-size: 15px; color: #c3c8be;
}

.ks-cta-section {
    padding: 80px 0; background: #0e0e0e; text-align: center;
}
.ks-cta-section h2 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 28px; font-weight: 700; text-transform: uppercase;
    color: #d4eacf; margin-bottom: 16px; letter-spacing: -0.01em;
}
.ks-cta-section p {
    font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.70;
    color: #8aaf86; margin-bottom: 36px;
    max-width: 34rem; margin-left: auto; margin-right: auto;
}
.ks-cta-btn {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 36px;
    background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 600;
    border-radius: 4px; text-decoration: none;
    border-bottom: 1px solid rgba(166,124,0,0.25);
    transition: opacity 0.2s; margin: 6px 8px;
}
.ks-cta-btn:hover { opacity: 0.88; }
.ks-cta-btn-ghost {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 36px;
    background: transparent; color: #c3c8be;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    border: 1px solid rgba(255,255,255,0.18); border-radius: 4px;
    text-decoration: none; transition: background 0.2s; margin: 6px 8px;
}
.ks-cta-btn-ghost:hover { background: rgba(255,255,255,0.06); }
</style>
@endpush

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────── --}}
<section class="ks-hero">
    <div class="ks-container">
        <div class="ks-brass-label">ÜRÜN GRUBU</div>
        <h1>Kapı Sereni</h1>
        <p>Panel kapı karkası üretimi için ekli ve eksiz kapı sereni çözümleri. Mobilya ve kapı sektörüne yönelik yüksek kaliteli, boyutsal hassasiyette ahşap profil üretimi.</p>
    </div>
</section>

{{-- ── Ürün Tipleri ──────────────────────────────────────────── --}}
<section class="ks-section">
    <div class="ks-container">
        <div class="ks-brass-label">ÜRÜN TİPLERİ</div>
        <h2 class="ks-section-head">Ekli ve Eksiz Kapı Sereni</h2>
        <p>CNRWOOD kapı sereni üretimi, mobilya ve kapı imalatçılarının ihtiyaçlarına göre özelleştirilebilir boyutlarda gerçekleştirilir.</p>

        <div class="ks-card-grid">
            <div class="ks-card">
                <h3>Eksiz Kapı Sereni</h3>
                <p>Tek parça ahşaptan elde edilen, birleştirme yeri bulunmayan yüksek mukavemetli kapı sereni profilleri. Panel kapı karkaslarda tercih edilen kaliteli seçenek.</p>
            </div>
            <div class="ks-card">
                <h3>Ekli Kapı Sereni</h3>
                <p>Finger-joint (parmak ekleme) yöntemiyle birleştirilen, boyutsal istikrarlı ve ekonomik kapı sereni çözümleri. Boya ve lake kapı üretiminde yaygın kullanım.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── Teknik Özellikler ─────────────────────────────────────── --}}
<section class="ks-section">
    <div class="ks-container">
        <div class="ks-brass-label">TEKNİK ÖZELLİKLER</div>
        <h2 class="ks-section-head">Üretim Detayları</h2>
        <div style="margin-top:28px">
            <div class="ks-spec-row">
                <span class="ks-spec-label">Malzeme</span>
                <span class="ks-spec-val">Ladin, Çam, İğne yapraklı türler</span>
            </div>
            <div class="ks-spec-row">
                <span class="ks-spec-label">Üretim Tipi</span>
                <span class="ks-spec-val">Ekli (finger-joint) / Eksiz (masif)</span>
            </div>
            <div class="ks-spec-row">
                <span class="ks-spec-label">Kullanım Alanı</span>
                <span class="ks-spec-val">Panel kapı karkası, doğrama profili</span>
            </div>
            <div class="ks-spec-row">
                <span class="ks-spec-label">Boyutlar</span>
                <span class="ks-spec-val">Proje bazlı, özel kesim mevcut</span>
            </div>
            <div class="ks-spec-row">
                <span class="ks-spec-label">Yüzey</span>
                <span class="ks-spec-val">Ham, zımparalı veya boyaya hazır</span>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────── --}}
<section class="ks-cta-section">
    <div class="ks-container">
        <div class="ks-brass-label" style="justify-content:center;margin-bottom:20px">CNRWOOD İLE ÇALIŞIN</div>
        <h2>Kapı Sereni İçin<br>Teklif Alın</h2>
        <p>Boyut, malzeme ve miktar gereksinimlerinizi paylaşın; teknik ekibimiz size özel fiyat ve üretim planı hazırlasın.</p>
        <div>
            <a href="{{ route('public.quote.create') }}" class="ks-cta-btn">
                Teklif Al
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('public.contact') }}" class="ks-cta-btn-ghost">
                Bize Ulaşın
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection
