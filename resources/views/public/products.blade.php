@extends('layouts.public')

@php
    $locale       = app()->getLocale();
    $selectedGrup = $selectedGrup ?? '';
    $grupNoData   = $grupNoData   ?? false;

    $grupMeta = [
        ''               => ['label' => 'Tüm Ürünler',                'sub' => 'Endüstriyel ahşap çözümleri kataloğu',        'seo' => 'CNRWOOD ürün kataloğu — Ahşap sandık, ambalaj, palet ve kereste çözümleri.'],
        'e-ticaret'      => ['label' => 'E-Ticaret Ürünleri',         'sub' => 'Online satın alınabilen ürünler',             'seo' => 'CNRWOOD online satış ürünleri — Anında sipariş verebileceğiniz ahşap ürünler.'],
        'ambalaj-sandik' => ['label' => 'Ambalaj & Sandık Çözümleri', 'sub' => 'İhracat sandıkları ve endüstriyel ambalaj',  'seo' => 'CNRWOOD ahşap sandık ve ambalaj çözümleri — İhracat, ağır yük ve özel ambalaj.'],
        'levha-kereste'  => ['label' => 'Levha & Kereste',            'sub' => 'OSB, kontrplak, lamine kiriş ve kereste',    'seo' => 'CNRWOOD levha ve kereste ürünleri — OSB, kontrplak, lamine kiriş.'],
        'ahsap-yapilar'  => ['label' => 'Ahşap Yapılar',             'sub' => 'Bungalov, pergola, kamelya, veranda',        'seo' => 'CNRWOOD ahşap yapı projeleri — Bungalov, pergola, kamelya ve veranda.'],
    ];
    $gm              = $grupMeta[$selectedGrup] ?? $grupMeta[''];
    $title           = $gm['label'] . ' — CNRWOOD';
    $metaDescription = $gm['seo'];

    $grupPills = [
        ''               => 'Tümü',
        'e-ticaret'      => 'E-Ticaret',
        'ambalaj-sandik' => 'Ambalaj & Sandık',
        'levha-kereste'  => 'Levha & Kereste',
        'ahsap-yapilar'  => 'Ahşap Yapılar',
    ];
@endphp

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* === CNRWOOD Products Page — dark industrial redesign ======================== */
body { background-color: #0a0a0a !important; color: #e5e2e1; }

.cnrp-wrap { max-width: 1280px; margin: 0 auto; padding: 0 64px; }
@media (max-width: 1023px) { .cnrp-wrap { padding: 0 32px; } }
@media (max-width: 639px)  { .cnrp-wrap { padding: 0 20px; } }

.cnrp-label {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'JetBrains Mono', monospace; font-size: 10px;
    font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: #aecfa8;
}
.cnrp-label::before { content: ''; display: block; width: 24px; height: 1px; background: #aecfa8; flex-shrink: 0; }

/* Hero */
.cnrp-hero { padding: 52px 0 40px; border-bottom: 1px solid rgba(51,51,51,0.22); background: linear-gradient(to bottom, rgba(26,36,26,0.22) 0%, transparent 100%); }
.cnrp-hero h1 { font-family: 'Hanken Grotesk', sans-serif; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 700; line-height: 1.08; letter-spacing: -0.02em; text-transform: uppercase; color: #f4f4f4; margin: 12px 0 8px; }
.cnrp-hero-sub { font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.65; color: rgba(195,200,190,0.65); max-width: 34rem; }
.cnrp-hero-meta { font-family: 'JetBrains Mono', monospace; font-size: 11px; color: rgba(195,200,190,0.35); margin-top: 14px; letter-spacing: 0.06em; }

/* Group pills */
.cnrp-pills-bar { background-color: #0d0d0d; border-bottom: 1px solid rgba(51,51,51,0.20); overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.cnrp-pills-bar::-webkit-scrollbar { display: none; }
.cnrp-pills-inner { max-width: 1280px; margin: 0 auto; padding: 0 64px; display: flex; align-items: center; gap: 0; min-width: max-content; }
@media (max-width: 1023px) { .cnrp-pills-inner { padding: 0 32px; } }
@media (max-width: 639px)  { .cnrp-pills-inner { padding: 0 16px; } }
.cnrp-pill { display: inline-flex; align-items: center; padding: 14px 18px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; letter-spacing: 0.10em; text-transform: uppercase; color: rgba(195,200,190,0.48); text-decoration: none; border-bottom: 2px solid transparent; transition: color 0.18s, border-color 0.18s; white-space: nowrap; }
.cnrp-pill:hover { color: #c8dfc4; border-bottom-color: rgba(174,207,168,0.28); }
.cnrp-pill.active { color: #aecfa8; border-bottom-color: #aecfa8; }

/* Main */
.cnrp-main { padding: 36px 0 80px; }

/* Filter row */
.cnrp-filter-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-bottom: 28px; }
.cnrp-filter-row input[type="search"],
.cnrp-filter-row select { background: #131313; border: 1px solid rgba(51,51,51,0.55); color: #c3c8be; font-family: 'Inter', sans-serif; font-size: 13px; border-radius: 4px; padding: 8px 12px; outline: none; transition: border-color 0.18s; -webkit-appearance: none; appearance: none; }
.cnrp-filter-row input[type="search"]::placeholder { color: rgba(195,200,190,0.30); }
.cnrp-filter-row input[type="search"]:focus,
.cnrp-filter-row select:focus { border-color: rgba(174,207,168,0.45); }
.cnrp-filter-row select option { background-color: #1a1a1a; color: #c3c8be; }
.cnrp-filter-search { flex: 1; min-width: 180px; }
.cnrp-filter-cat    { min-width: 160px; }
.cnrp-filter-btn { padding: 8px 22px; cursor: pointer; background: transparent; border: 1px solid rgba(174,207,168,0.28); color: #aecfa8; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; transition: background 0.18s, border-color 0.18s; white-space: nowrap; }
.cnrp-filter-btn:hover { background: rgba(174,207,168,0.07); border-color: rgba(174,207,168,0.50); }
.cnrp-filter-clear { font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.10em; text-transform: uppercase; color: rgba(195,200,190,0.38); text-decoration: none; padding: 8px 10px; border-radius: 4px; white-space: nowrap; transition: color 0.18s; }
.cnrp-filter-clear:hover { color: rgba(195,200,190,0.75); }

/* Results bar */
.cnrp-results-bar { font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.08em; color: rgba(195,200,190,0.40); margin-bottom: 22px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.cnrp-results-bar strong { color: #aecfa8; font-weight: 600; }
.cnrp-results-bar a { color: rgba(195,200,190,0.40); text-decoration: underline; text-decoration-color: rgba(195,200,190,0.18); transition: color 0.18s; }
.cnrp-results-bar a:hover { color: rgba(195,200,190,0.75); }

/* Product grid */
.cnrp-grid { display: grid; gap: 20px; grid-template-columns: 1fr; }
@media (min-width: 500px)  { .cnrp-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 860px)  { .cnrp-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 1180px) { .cnrp-grid { grid-template-columns: repeat(4, 1fr); } }

/* Product card */
.cnrp-card { display: flex; flex-direction: column; background-color: #111111; border: 1px solid rgba(51,51,51,0.32); border-radius: 8px; overflow: hidden; text-decoration: none; transition: border-color 0.22s, box-shadow 0.22s, transform 0.22s; }
.cnrp-card:hover { border-color: rgba(174,207,168,0.22); box-shadow: 0 8px 32px rgba(0,0,0,0.50); transform: translateY(-2px); }
.cnrp-card-img { aspect-ratio: 4/3; overflow: hidden; background-color: #0d0d0d; }
.cnrp-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.36s ease; display: block; }
.cnrp-card:hover .cnrp-card-img img { transform: scale(1.04); }
.cnrp-card-img img.fallback { filter: brightness(0.72) saturate(0.80); }
.cnrp-card-img-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: rgba(174,207,168,0.10); }
.cnrp-card-img-ph svg { width: 44px; height: 44px; }
.cnrp-card-body { padding: 18px 16px 16px; flex: 1; display: flex; flex-direction: column; }
.cnrp-card-cat { font-family: 'JetBrains Mono', monospace; font-size: 9px; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(174,207,168,0.55); margin-bottom: 8px; display: block; }
.cnrp-card-name { font-family: 'Hanken Grotesk', sans-serif; font-size: 15px; font-weight: 600; line-height: 1.30; color: #f0eeeb; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.cnrp-card-foot { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.cnrp-card-price { font-family: 'JetBrains Mono', monospace; font-size: 14px; font-weight: 600; color: #f4f4f4; letter-spacing: 0.02em; }
.cnrp-card-teklif { font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; color: rgba(174,207,168,0.55); transition: color 0.18s; }
.cnrp-card:hover .cnrp-card-teklif { color: #aecfa8; }
.cnrp-card-noprice { font-family: 'Inter', sans-serif; font-size: 11px; color: rgba(195,200,190,0.30); }
.cnrp-card-foot-left { display: flex; flex-direction: column; gap: 3px; }
.cnrp-card-incele { font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 600; letter-spacing: 0.10em; text-transform: uppercase; color: rgba(174,207,168,0.45); transition: color 0.18s; }
.cnrp-card:hover .cnrp-card-incele { color: #aecfa8; }
.cnrp-badge { flex-shrink: 0; display: inline-flex; align-items: center; padding: 3px 8px; border-radius: 3px; font-family: 'JetBrains Mono', monospace; font-size: 9px; font-weight: 600; letter-spacing: 0.10em; text-transform: uppercase; white-space: nowrap; }
.cnrp-badge-sale  { background: rgba(46,160,67,0.11); color: #3fb950; }
.cnrp-badge-quote { background: rgba(174,207,168,0.07); color: rgba(174,207,168,0.50); }

/* Empty states */
.cnrp-empty { text-align: center; padding: 80px 24px; background: #0e0e0e; border: 1px solid rgba(51,51,51,0.22); border-radius: 12px; }
.cnrp-empty-icon { width: 52px; height: 52px; margin: 0 auto 22px; background: rgba(174,207,168,0.06); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: rgba(174,207,168,0.28); }
.cnrp-empty-icon svg { width: 22px; height: 22px; }
.cnrp-empty-lbl { font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(174,207,168,0.38); margin-bottom: 12px; display: block; }
.cnrp-empty h2 { font-family: 'Hanken Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #e8e6e3; margin-bottom: 12px; }
.cnrp-empty p { font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.65; color: rgba(195,200,190,0.50); max-width: 28rem; margin: 0 auto 32px; }
.cnrp-empty-actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
.cnrp-empty-btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 28px; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; text-decoration: none; transition: opacity 0.2s; }
.cnrp-empty-btn-primary { background: #aecfa8; color: #1b361b; }
.cnrp-empty-btn-ghost   { background: transparent; color: rgba(195,200,190,0.60); border: 1px solid rgba(255,255,255,0.13); }
.cnrp-empty-btn:hover { opacity: 0.85; }

/* Pagination dark override */
.cnrp-pager { margin-top: 48px; }
.cnrp-pager * { box-shadow: none !important; }
.cnrp-pager p, .cnrp-pager p.text-sm { font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important; letter-spacing: 0.06em !important; color: rgba(195,200,190,0.35) !important; }
.cnrp-pager .font-medium { color: rgba(195,200,190,0.35) !important; }
.cnrp-pager a,
.cnrp-pager span[aria-current] > span,
.cnrp-pager span[aria-disabled] > span {
    display: inline-flex !important; align-items: center !important; justify-content: center !important;
    min-width: 36px !important; height: 36px !important; padding: 0 10px !important;
    font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important;
    background-color: #1a1a1a !important; border: 1px solid rgba(51,51,51,0.40) !important;
    color: rgba(195,200,190,0.52) !important; border-radius: 4px !important;
    text-decoration: none !important; transition: background 0.18s, color 0.18s !important;
    margin: 0 2px !important;
}
.cnrp-pager a:hover { background-color: #222222 !important; color: #c3c8be !important; }
.cnrp-pager span[aria-current="page"] > span { background-color: rgba(174,207,168,0.12) !important; color: #aecfa8 !important; border-color: rgba(174,207,168,0.25) !important; }
.cnrp-pager span[aria-disabled] > span { opacity: 0.25 !important; }
.cnrp-pager nav { flex-direction: column !important; align-items: center !important; gap: 16px; }
.cnrp-pager .relative.z-0.inline-flex { box-shadow: none !important; display: flex !important; gap: 4px !important; }

/* Bottom CTA */
.cnrp-cta { margin-top: 80px; padding: 72px 0; background: #0e0e0e; border-top: 1px solid rgba(51,51,51,0.18); text-align: center; }
.cnrp-cta h2 { font-family: 'Hanken Grotesk', sans-serif; font-size: 26px; font-weight: 700; text-transform: uppercase; color: #d4eacf; margin-bottom: 12px; letter-spacing: -0.01em; }
.cnrp-cta p { font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.70; color: #8aaf86; margin-bottom: 32px; max-width: 30rem; margin-left: auto; margin-right: auto; }
.cnrp-cta-btn { display: inline-flex; align-items: center; gap: 10px; padding: 13px 32px; margin: 6px 8px; background: #aecfa8; color: #1b361b; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600; letter-spacing: 0.10em; text-transform: uppercase; border-radius: 4px; text-decoration: none; transition: opacity 0.2s; }
.cnrp-cta-btn:hover { opacity: 0.88; }
.cnrp-cta-btn-ghost { display: inline-flex; align-items: center; gap: 10px; padding: 13px 32px; margin: 6px 8px; background: transparent; color: rgba(195,200,190,0.60); border: 1px solid rgba(255,255,255,0.14); border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; letter-spacing: 0.10em; text-transform: uppercase; text-decoration: none; transition: background 0.2s; }
.cnrp-cta-btn-ghost:hover { background: rgba(255,255,255,0.05); }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="cnrp-hero">
    <div class="cnrp-wrap">
        <div class="cnrp-label">ÜRÜN KATALOĞU</div>
        <h1>{{ $gm['label'] }}</h1>
        <p class="cnrp-hero-sub">{{ $gm['sub'] }}</p>
        @if(!$grupNoData && $products->total() > 0)
            <p class="cnrp-hero-meta">{{ $products->total() }} ürün</p>
        @endif
    </div>
</section>

{{-- Group filter pills --}}
<div class="cnrp-pills-bar" role="navigation" aria-label="Ürün Grupları">
    <div class="cnrp-pills-inner">
        @foreach($grupPills as $slug => $pillLabel)
            <a href="{{ $slug === '' ? route('public.products') : route('public.products', ['grup' => $slug]) }}"
               class="cnrp-pill {{ $selectedGrup === $slug ? 'active' : '' }}">{{ $pillLabel }}</a>
        @endforeach
    </div>
</div>

{{-- Main --}}
<section class="cnrp-main">
    <div class="cnrp-wrap">

        {{-- Search + category filter --}}
        <form method="GET" action="{{ route('public.products') }}" class="cnrp-filter-row">
            @if($selectedGrup !== '')
                <input type="hidden" name="grup" value="{{ $selectedGrup }}">
            @endif
            <input type="search" name="q" class="cnrp-filter-search"
                   value="{{ $searchTerm }}" maxlength="100" placeholder="Ürün adı veya SKU ara…">
            <select name="kategori" class="cnrp-filter-cat">
                <option value="">Tüm Kategoriler</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected($selectedSlug === $cat->slug)>
                        {{ $cat->getTranslation('name', $locale) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="cnrp-filter-btn">Ara</button>
            @if($searchTerm !== '' || $selectedSlug !== '')
                <a href="{{ $selectedGrup !== ''
                        ? route('public.products', ['grup' => $selectedGrup])
                        : route('public.products') }}" class="cnrp-filter-clear">Temizle ×</a>
            @endif
        </form>

        {{-- Results count --}}
        @if(!$grupNoData && $products->total() > 0 && ($searchTerm !== '' || $selectedSlug !== ''))
            <div class="cnrp-results-bar">
                <span><strong>{{ $products->total() }}</strong> sonuç</span>
                @if($searchTerm !== '') <span>"{{ $searchTerm }}"</span> @endif
                <a href="{{ $selectedGrup !== ''
                        ? route('public.products', ['grup' => $selectedGrup])
                        : route('public.products') }}">Filtreleri temizle</a>
            </div>
        @endif

        {{-- STATE 1: grup has no DB support yet --}}
        @if($grupNoData)
            <div class="cnrp-empty">
                <div class="cnrp-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <span class="cnrp-empty-lbl">Hazırlanıyor</span>
                <h2>{{ $gm['label'] }}<br>Kataloğu Yakında</h2>
                <p>Bu ürün grubu katalog çalışmalarımız kapsamında yakında yayına girecektir. Şu an için tüm ürün listemizi inceleyebilir veya proje bazlı teklif talep edebilirsiniz.</p>
                <div class="cnrp-empty-actions">
                    <a href="{{ route('public.products') }}" class="cnrp-empty-btn cnrp-empty-btn-primary">
                        Tüm Ürünlere Bak
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('public.quote.create') }}" class="cnrp-empty-btn cnrp-empty-btn-ghost">
                        Teklif Al
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        {{-- STATE 2: search/filter returned no results --}}
        @elseif($products->isEmpty())
            <div class="cnrp-empty">
                <div class="cnrp-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <span class="cnrp-empty-lbl">Sonuç bulunamadı</span>
                <h2>Ürün Bulunamadı</h2>
                <p>Arama kriterlerinize uygun ürün bulunamadı. Farklı anahtar kelimeler deneyin veya tüm ürün listemize göz atın.</p>
                <div class="cnrp-empty-actions">
                    <a href="{{ $selectedGrup !== ''
                            ? route('public.products', ['grup' => $selectedGrup])
                            : route('public.products') }}" class="cnrp-empty-btn cnrp-empty-btn-primary">
                        Filtreleri Temizle
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        {{-- STATE 3: products found — show grid --}}
        @else
            <div class="cnrp-grid">
                @foreach($products as $product)
                    @php
                        $pName    = $product->getTranslation('name', $locale) ?? '—';
                        $pCatName = $product->category?->getTranslation('name', $locale) ?? null;
                        $pCatSlug = $product->category?->slug ?? '';
                        $pActive  = $product->images->where('is_active', true);
                        $pPrimary = $pActive->firstWhere('is_primary', true) ?? $pActive->first();
                        $pImgUrl  = $pPrimary
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($pPrimary->url)
                            : null;
                        // Category-based fallback image (shown when product has no uploaded image)
                        $pFallbackMap = [
                            'ahsap-sandik'      => 'ihracat-ambalaji.jpg',
                            'sandik-ve-ambalaj' => 'ihracat-ambalaji.jpg',
                            'ihracat-ambalaj'   => 'ihracat-ambalaji.jpg',
                            'ozel-ambalaj'      => 'project-packaging.jpg',
                            'osb-sandik'        => 'heavy-duty-crate.jpg',
                            'palet'             => 'hero-factory.jpg',
                            'euro-palet'        => 'hero-factory.jpg',
                            'ispm15-palet'      => 'hero-factory.jpg',
                            'ozel-palet'        => 'hero-factory.jpg',
                            'kagit-ve-karton'   => 'project-packaging.jpg',
                            'oluklu-mukavva'    => 'project-packaging.jpg',
                            'masif-karton'      => 'project-packaging.jpg',
                            'diger-ambalaj'     => 'project-packaging.jpg',
                        ];
                        $pFallback = asset('images/cnrwood/' . ($pFallbackMap[$pCatSlug] ?? 'hero-factory.jpg'));
                        // Business rule: NEVER show price for quote_only products
                        $pPrice   = $product->isBuyable() ? $product->getDisplayPrice() : null;
                    @endphp
                    <a href="{{ route('public.product', $product->slug) }}" class="cnrp-card">
                        <div class="cnrp-card-img">
                            @if($pImgUrl)
                                <img src="{{ $pImgUrl }}" alt="{{ $pName }}" loading="lazy">
                            @else
                                <img src="{{ $pFallback }}" alt="{{ $pName }}" loading="lazy" class="fallback">
                            @endif
                        </div>
                        <div class="cnrp-card-body">
                            @if($pCatName)
                                <span class="cnrp-card-cat">{{ $pCatName }}</span>
                            @endif
                            <h3 class="cnrp-card-name">{{ $pName }}</h3>
                            <div class="cnrp-card-foot">
                                @if($product->isBuyable())
                                    {{-- Buyable: price + "Ürünü İncele" CTA; never show 0 TL --}}
                                    <div class="cnrp-card-foot-left">
                                        @if($pPrice)
                                            <span class="cnrp-card-price">{{ $pPrice }}</span>
                                        @else
                                            <span class="cnrp-card-noprice">Fiyat için iletişim</span>
                                        @endif
                                        <span class="cnrp-card-incele">Ürünü İncele →</span>
                                    </div>
                                    <span class="cnrp-badge cnrp-badge-sale">Online Satış</span>
                                @else
                                    {{-- Quote-only: no price, no cart, no 0 TL --}}
                                    <span class="cnrp-card-teklif">Teklif Al →</span>
                                    <span class="cnrp-badge cnrp-badge-quote">Proje Bazlı</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="cnrp-pager">{{ $products->links() }}</div>
            @endif
        @endif

    </div>
</section>

{{-- Bottom CTA --}}
<section class="cnrp-cta">
    <div class="cnrp-wrap" style="max-width:640px">
        <div class="cnrp-label" style="justify-content:center;margin-bottom:18px">CNRWOOD İLE ÇALIŞIN</div>
        <h2>Teknik Ön Değerlendirme<br>İçin Başvurun</h2>
        <p>Sandık boyutları, taşıma kapasitesi ve ISPM 15 gereksinimleri için uzman ekibimizden ücretsiz teknik değerlendirme alın.</p>
        <div>
            <a href="{{ route('public.sandik') }}" class="cnrp-cta-btn">
                Teknik Değerlendirme
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('public.quote.create') }}" class="cnrp-cta-btn-ghost">
                Teklif Al
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection
