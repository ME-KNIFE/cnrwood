@extends('layouts.public')

@php
    $locale          = app()->getLocale();
    $title           = 'Isıl İşlemli Ahşap & ISPM 15 Uyumlu Üretim — CNRWOOD';
    $metaDescription = 'CNRWOOD ısıl işlemli ahşap çözümleri: ISPM 15 standardına uygun ihracat ambalajı, uluslararası nakliye için sertifikalı ahşap sandık ve palet üretimi.';
@endphp

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ── Isıl İşlemli Ahşap — dark industrial page ────────────────── */
body { background-color: #0a0a0a; color: #e5e2e1; }

.ii-container { max-width: 1080px; margin: 0 auto; padding: 0 64px; }
@media (max-width: 767px) { .ii-container { padding: 0 24px; } }

.ii-brass-label {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: 11px;
    font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase;
    color: #a67c00;
}
.ii-brass-label::before {
    content: ''; display: block; width: 28px; height: 1px;
    background: #a67c00; flex-shrink: 0;
}

.ii-hero {
    padding: 96px 0 80px;
    border-bottom: 1px solid rgba(51,51,51,0.20);
    background: linear-gradient(to bottom, rgba(26,46,26,0.28) 0%, transparent 100%);
}
.ii-hero h1 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700; line-height: 1.08;
    letter-spacing: -0.02em; text-transform: uppercase;
    color: #f4f4f4; margin: 20px 0 28px;
}
.ii-hero p {
    font-family: 'Inter', sans-serif;
    font-size: 18px; line-height: 1.75;
    color: #c3c8be; max-width: 36rem;
}

.ii-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 6px 14px;
    background: rgba(174,207,168,0.10);
    border: 1px solid rgba(174,207,168,0.22);
    border-radius: 9999px; margin-bottom: 20px;
    font-family: 'JetBrains Mono', monospace; font-size: 12px;
    font-weight: 500; letter-spacing: 0.10em; text-transform: uppercase;
    color: #aecfa8;
}

.ii-section { padding: 80px 0; border-bottom: 1px solid rgba(51,51,51,0.12); }
.ii-section:last-of-type { border-bottom: none; }

.ii-section-head {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 26px; font-weight: 700; line-height: 1.2;
    color: #f4f4f4; margin: 16px 0 20px;
}
.ii-section p {
    font-family: 'Inter', sans-serif;
    font-size: 16px; line-height: 1.75;
    color: #c3c8be; max-width: 52rem;
}

.ii-card-grid {
    display: grid; gap: 20px;
    grid-template-columns: 1fr;
    margin-top: 40px;
}
@media (min-width: 640px)  { .ii-card-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .ii-card-grid { grid-template-columns: repeat(3, 1fr); } }

.ii-card {
    padding: 28px 24px;
    background-color: #131313;
    border: 1px solid rgba(51,51,51,0.28);
    border-radius: 10px;
    transition: border-color 0.25s;
}
.ii-card:hover { border-color: rgba(174,207,168,0.18); }
.ii-card-icon {
    font-size: 28px; margin-bottom: 16px;
    display: block; line-height: 1;
    font-family: 'Material Symbols Outlined', sans-serif;
    font-variation-settings: 'FILL' 1,'wght' 300,'GRAD' 0,'opsz' 24;
    color: #aecfa8;
}
.ii-card h3 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 17px; font-weight: 600;
    color: #f4f4f4; margin-bottom: 10px;
}
.ii-card p {
    font-family: 'Inter', sans-serif;
    font-size: 14px; line-height: 1.65;
    color: #c3c8be;
}

.ii-process-list { list-style: none; padding: 0; margin: 32px 0 0; }
.ii-process-list li {
    display: flex; gap: 20px; align-items: flex-start;
    padding: 20px 0; border-bottom: 1px solid rgba(51,51,51,0.15);
}
.ii-process-list li:last-child { border-bottom: none; padding-bottom: 0; }
.ii-step-num {
    flex-shrink: 0; width: 32px; height: 32px;
    background: rgba(174,207,168,0.10);
    border: 1px solid rgba(174,207,168,0.22);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600;
    color: #aecfa8; margin-top: 2px;
}
.ii-step-title {
    font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 600;
    color: #f4f4f4; margin-bottom: 6px;
}
.ii-step-desc {
    font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.65;
    color: #c3c8be;
}

.ii-ispm-box {
    margin-top: 40px; padding: 32px 36px;
    background: rgba(26,46,26,0.35);
    border: 1px solid rgba(174,207,168,0.18);
    border-left: 3px solid #aecfa8;
    border-radius: 8px;
}
.ii-ispm-box h3 {
    font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 600;
    color: #aecfa8; letter-spacing: 0.10em; text-transform: uppercase;
    margin-bottom: 12px;
}
.ii-ispm-box p {
    font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.75;
    color: #c3c8be; max-width: 100%;
}

.ii-cta-section {
    padding: 80px 0;
    background: #0e0e0e;
    text-align: center;
}
.ii-cta-section h2 {
    font-family: 'Hanken Grotesk', sans-serif;
    font-size: 28px; font-weight: 700; text-transform: uppercase;
    color: #d4eacf; margin-bottom: 16px; letter-spacing: -0.01em;
}
.ii-cta-section p {
    font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.70;
    color: #8aaf86; margin-bottom: 36px; max-width: 34rem; margin-left: auto; margin-right: auto;
}
.ii-cta-btn {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 36px;
    background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 600;
    border-radius: 4px; text-decoration: none;
    border-bottom: 1px solid rgba(166,124,0,0.25);
    transition: opacity 0.2s;
    margin: 6px 8px;
}
.ii-cta-btn:hover { opacity: 0.88; }
.ii-cta-btn-ghost {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 36px;
    background: transparent; color: #c3c8be;
    font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 500;
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 4px; text-decoration: none;
    transition: background 0.2s;
    margin: 6px 8px;
}
.ii-cta-btn-ghost:hover { background: rgba(255,255,255,0.06); }
</style>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,1,0&display=block">
@endpush

@section('content')

{{-- ── Hero ────────────────────────────────────────────────────── --}}
<section class="ii-hero">
    <div class="ii-container">
        <span class="ii-badge">ISPM 15 Uyumlu Üretim</span>
        <div class="ii-brass-label">STRATEJİK KAPASİTE</div>
        <h1>Isıl İşlemli Ahşap &amp; ISPM 15 Uyumlu Paketleme</h1>
        <p>İhracat süreçlerinde yasal zorunluluk olan ısıl işlem uygulaması, ahşap ambalajların uluslararası gümrük ve bitki sağlığı gereksinimlerini karşılaması için kritik bir adımdır. CNRWOOD olarak ihracat ambalajı ve paletlerimizi ISPM 15 süreçlerine uygun biçimde hazırlıyoruz.</p>
    </div>
</section>

{{-- ── ISPM 15 Nedir ────────────────────────────────────────────── --}}
<section class="ii-section">
    <div class="ii-container">
        <div class="ii-brass-label">STANDART BİLGİSİ</div>
        <h2 class="ii-section-head">ISPM 15 Nedir?</h2>
        <p>ISPM 15 (International Standards for Phytosanitary Measures No. 15), uluslararası ticarette kullanılan ahşap ambalaj materyallerinin zararlı organizmalar için risk oluşturmamasını sağlamak amacıyla FAO bünyesinde geliştirilen bir uluslararası fitosanitar standarttır.</p>
        <p style="margin-top:16px">Bu standart, 180'den fazla ülkede zorunlu olup uygulanmadan gönderilen ahşap ambalajlar; gümrükte alıkonulma, iade veya imha riskiyle karşı karşıya kalabilir. İhracat yapan her firma için ihracat paketinin ISPM 15 uyumluluğu kritik bir operasyonel gereklilik haline gelmiştir.</p>

        <div class="ii-ispm-box">
            <h3>Yasal Zorunluluk</h3>
            <p>AB, ABD, Kanada, Avustralya ve diğer 180+ ülkeye yapılan ihracatta ahşap ambalaj materyallerinin (sandık, palet, kafes) ISPM 15 damgası taşıması zorunludur. Damgasız sevkiyatlar gümrükte alıkonulabilir veya iade edilebilir.</p>
        </div>
    </div>
</section>

{{-- ── İşlem Adımları ──────────────────────────────────────────── --}}
<section class="ii-section">
    <div class="ii-container">
        <div class="ii-brass-label">UYGULAMA SÜRECİ</div>
        <h2 class="ii-section-head">Isıl İşlem Nasıl Uygulanır?</h2>
        <p>Ahşap malzemenin tamamının belirli bir süre boyunca minimum 56°C iç sıcaklığa ulaşması sağlanır. Bu işlem kontrollü ısıtma odalarında gerçekleştirilir ve kayıtları tutulan belgelerle belgelendirilir.</p>

        <ul class="ii-process-list">
            <li>
                <span class="ii-step-num">01</span>
                <div>
                    <div class="ii-step-title">Ahşap Seleksiyonu</div>
                    <div class="ii-step-desc">İhracat ambalajında kullanılacak ahşap malzeme nem ve kalite kontrolünden geçirilir. Böcek veya fungal bulaşı riski taşıyan malzeme ayrıştırılır.</div>
                </div>
            </li>
            <li>
                <span class="ii-step-num">02</span>
                <div>
                    <div class="ii-step-title">Isıl İşlem Uygulaması</div>
                    <div class="ii-step-desc">Ahşap, kontrollü ısıtma odalarında ahşabın tüm kesitinde minimum 56°C'ye en az 30 dakika süreyle ulaşacak biçimde ısıl işleme tabi tutulur.</div>
                </div>
            </li>
            <li>
                <span class="ii-step-num">03</span>
                <div>
                    <div class="ii-step-title">ISPM 15 Damgası</div>
                    <div class="ii-step-desc">İşlem tamamlandıktan sonra her ahşap parça, onaylı kurum tarafından ISPM 15 logolu resmi damgasını alır. Bu damga, ülke kodu, üretici kodu ve uygulanan işlem tipini içerir.</div>
                </div>
            </li>
            <li>
                <span class="ii-step-num">04</span>
                <div>
                    <div class="ii-step-title">Üretim ve Sevkiyat</div>
                    <div class="ii-step-desc">Isıl işlemli ahşaptan üretilen ambalaj, müşterinin teknik gereksinimleri doğrultusunda imal edilir ve ihracat sürecine hazır biçimde teslim edilir.</div>
                </div>
            </li>
        </ul>
    </div>
</section>

{{-- ── Avantajlar ──────────────────────────────────────────────── --}}
<section class="ii-section">
    <div class="ii-container">
        <div class="ii-brass-label">NEDEN ÖNEMLİ</div>
        <h2 class="ii-section-head">Isıl İşlemin Sağladığı Avantajlar</h2>

        <div class="ii-card-grid">
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">public</span>
                <h3>Gümrük Uyumluluğu</h3>
                <p>180'den fazla ülkede zorunlu ISPM 15 gereksinimini karşılayan ihracat ambalajı ile gümrük süreçlerinde gecikme ve iade riskini ortadan kaldırır.</p>
            </div>
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">bug_report</span>
                <h3>Zararlı Organizmalar</h3>
                <p>Yüksek sıcaklık işlemi böcek larvaları, fungus ve diğer zararlı organizmaları etkisiz kılarak biyolojik bulaşma riskini minimuma indirir.</p>
            </div>
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">verified</span>
                <h3>Belgelenmiş Süreç</h3>
                <p>Her sevkiyat için izlenebilir kayıtlar tutulur. Gümrük denetimlerinde sunulabilecek belgelendirme ile şeffaflık ve güven sağlanır.</p>
            </div>
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">eco</span>
                <h3>Kimyasal Kullanılmaz</h3>
                <p>Isıl işlem, metil bromit gibi zararlı kimyasal fumigasyon alternatiflerinin yerini alan çevre dostu bir yöntemdir.</p>
            </div>
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">inventory_2</span>
                <h3>Tüm Ambalaj Tipleri</h3>
                <p>Ahşap sandık, palet, kafes sandık, taban ızgara gibi tüm ambalaj türleri ısıl işlemle uyumlu biçimde üretilebilir.</p>
            </div>
            <div class="ii-card">
                <span class="ii-card-icon" style="font-family:'Material Symbols Outlined'">schedule</span>
                <h3>Sevkiyat Güvencesi</h3>
                <p>ISPM 15 uyumlu ambalaj ile alıcı ülke gümrüğünde ihracat partinizin beklenmedik durumlarla karşılaşma olasılığını en aza indirirsiniz.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── Kimler İçin ─────────────────────────────────────────────── --}}
<section class="ii-section">
    <div class="ii-container">
        <div class="ii-brass-label">KİMLER FAYDALANIR</div>
        <h2 class="ii-section-head">ISPM 15 Uyumlu Ambalaj Kimlere Gereklidir?</h2>
        <p>Ahşap ambalaj materyali kullanan ve aşağıdaki ülkelere ihracat yapan tüm firmaların ISPM 15 gereksinimlerine uyması zorunludur:</p>

        <div class="ii-card-grid" style="margin-top:32px">
            <div class="ii-card">
                <h3>Makine &amp; Teçhizat İhracatçıları</h3>
                <p>Sanayi makineleri, üretim ekipmanları ve ağır yük sevkiyatları için ahşap sandık ve kafes kullanan firmalar.</p>
            </div>
            <div class="ii-card">
                <h3>Elektronik &amp; Hassas Ekipman</h3>
                <p>Tıbbi cihaz, ölçüm ekipmanı ve hassas sistem bileşenlerini yurt dışına gönderen ihracatçılar.</p>
            </div>
            <div class="ii-card">
                <h3>Genel Ticaret İhracatçıları</h3>
                <p>Konteynerlerde palet veya ahşap malzeme ile paketlenmiş ürün ihraç eden tüm sektörlerden firmalar.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ─────────────────────────────────────────────────────── --}}
<section class="ii-cta-section">
    <div class="ii-container">
        <div class="ii-brass-label" style="justify-content:center;margin-bottom:20px">CNRWOOD İLE ÇALIŞIN</div>
        <h2>ISPM 15 Uyumlu Ambalaj<br>İçin Teklif Alın</h2>
        <p>İhracat ambalajı, ahşap sandık veya palet ihtiyacınız için teknik ekibimiz projenizi değerlendirsin.</p>
        <div>
            <a href="{{ route('public.quote.create') }}" class="ii-cta-btn">
                Teklif Al
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('public.sandik') }}" class="ii-cta-btn-ghost">
                Teknik Ön Değerlendirme
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection
