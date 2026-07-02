@php
    use App\Models\Setting;
    $currentLocale = app()->getLocale();

    // ── Direct nav links (no dropdown) ──────────────────────────────────
    // "Teknik Ön Değerlendirme" removed from top nav; accessible via:
    //   • Ahşap Sandık page CTA
    //   • Ürünler dropdown → Ambalaj & Sandık Çözümleri group
    $directLinks = [
        [
            'label'  => 'Ana Sayfa',
            'url'    => route('home'),
            'active' => request()->routeIs('home'),
        ],
        [
            'label'  => 'Ahşap Sandık',
            'url'    => route('public.ahsap-sandik'),
            'active' => request()->routeIs('public.ahsap-sandik'),
        ],
        [
            'label'  => 'Kapı Sereni',
            'url'    => route('public.kapi-sereni'),
            'active' => request()->routeIs('public.kapi-sereni'),
        ],
        [
            'label'  => 'Isıl İşlemli Ahşap',
            'url'    => route('public.isil'),
            'active' => request()->routeIs('public.isil'),
        ],
        [
            'label'  => 'Projeler',
            'url'    => route('public.projects.index'),
            'active' => request()->routeIs('public.projects.*'),
        ],
        [
            'label'  => 'Kurumsal',
            'url'    => route('public.corporate'),
            'active' => request()->routeIs('public.corporate') || request()->routeIs('public.about') || request()->routeIs('public.services'),
        ],
        [
            'label'  => 'İletişim',
            'url'    => route('public.contact'),
            'active' => request()->routeIs('public.contact'),
        ],
    ];

    // ── Ürünler mega-dropdown groups ─────────────────────────────────────
    // ✓ = slug confirmed in ProductCategorySeeder (DB record exists)
    // ↩ = slug not in DB — safe fallback to public.products to avoid 404
    // ✎ = slug corrected (ihracat-ambalaj, not ihracat-ambalaji)
    $fallback = route('public.products'); // safe landing for missing category slugs
    $ddGroups = [
        [
            'label'    => 'E-Ticaret Ürünleri',
            'groupUrl' => route('public.products', ['grup' => 'e-ticaret']),
            'items'    => [
                ['label' => 'Pelet',                      'url' => $fallback],  // ↩ no DB slug
                ['label' => 'Kedi Kumu',                  'url' => $fallback],  // ↩
                ['label' => 'Raf',                        'url' => $fallback],  // ↩
                ['label' => 'Sehpa',                      'url' => $fallback],  // ↩
                ['label' => 'Vestiyer',                   'url' => $fallback],  // ↩
                ['label' => 'Çocuk Masa & Sandalye Seti', 'url' => $fallback],  // ↩
                ['label' => 'Tabure',                     'url' => $fallback],  // ↩
            ],
        ],
        [
            'label'    => 'Ambalaj & Sandık Çözümleri',
            'groupUrl' => route('public.products', ['grup' => 'ambalaj-sandik']),
            'items'    => [
                ['label' => 'Ahşap Sandık',                'url' => route('public.ahsap-sandik')],                           // ✓ strategic page
                ['label' => 'İhracat Ambalajı',            'url' => route('public.category', 'ihracat-ambalaj')],           // ✓ ✎ was ihracat-ambalaji
                ['label' => 'Ağır Yük & Makine Sandıkları','url' => route('public.category', 'sandik-ve-ambalaj')],        // ↩ → parent category
                ['label' => 'Kafes Sandık',                'url' => route('public.category', 'sandik-ve-ambalaj')],        // ↩ → parent category
                ['label' => 'Taban Izgara Sandık',         'url' => route('public.category', 'sandik-ve-ambalaj')],        // ↩ → parent category
                ['label' => 'Vinç Aparatlı Sandık',        'url' => route('public.category', 'sandik-ve-ambalaj')],        // ↩ → parent category
                ['label' => 'Teknik Ön Değerlendirme',     'url' => route('public.sandik'),  'accent' => true],            // ✓
            ],
        ],
        [
            'label'    => 'Levha & Kereste',
            'groupUrl' => route('public.products', ['grup' => 'levha-kereste']),
            'items'    => [
                ['label' => 'OSB & Kontrplak Levha', 'url' => $fallback],  // ↩ osb-kontrplak not in DB
                ['label' => 'Lamine Kiriş',          'url' => $fallback],  // ↩
                ['label' => 'İroko Kereste',         'url' => $fallback],  // ↩
                ['label' => 'Ladin Kereste',         'url' => $fallback],  // ↩
            ],
        ],
        [
            'label'    => 'Ahşap Yapılar',
            'groupUrl' => route('public.products', ['grup' => 'ahsap-yapilar']),
            'items'    => [
                ['label' => 'Bungalov',      'url' => $fallback],  // ↩
                ['label' => 'Pergola',       'url' => $fallback],  // ↩
                ['label' => 'Kamelya',       'url' => $fallback],  // ↩
                ['label' => 'Veranda',       'url' => $fallback],  // ↩
                ['label' => 'Deck Kaplama',  'url' => $fallback],  // ↩
            ],
        ],
    ];

    // Ürünler trigger "active" — true when on any product/category page
    $urunlerActive =
        request()->routeIs('public.products') ||
        request()->routeIs('public.product')  ||
        request()->routeIs('public.category');

    // Full flat link list for mobile overlay (direct + Ürünler sub-items)
    $allMobileLinks = $directLinks;
@endphp

<style>
/* ===================================================================
   CNRWOOD Industrial Header v2 — Stitch + mega-dropdown
   Dark obsidian, backdrop-blur, green primary CTA
   Nav + cart + account only render together at >=1400px, where the
   1360px-capped wrap has enough room for all of them without overlap;
   below that the hamburger/mobile overlay carries the same links.
   =================================================================== */

#cnr-hdr {
    position: sticky;
    top: 0;
    z-index: 200;
    isolation: isolate;
    background: rgba(10,10,10,0.94);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(51,51,51,0.18);
    height: 80px;
    transition: background 0.30s ease, box-shadow 0.30s ease, border-color 0.30s ease;
}
#cnr-hdr.is-scrolled {
    background: rgba(10,10,10,0.985);
    box-shadow: 0 18px 45px rgba(0,0,0,0.45);
    border-bottom-color: rgba(174,207,168,0.16);
}

/* Wrapper */
.cnr-hdr-wrap {
    max-width: 1360px; margin: 0 auto; padding: 0 64px;
    height: 100%; display: flex; align-items: center;
    justify-content: space-between; position: relative;
}
@media (max-width: 767px) { .cnr-hdr-wrap { padding: 0 20px; } }

/* ── Logo ─────────────────────────────────────────────────────── */
.cnr-hdr-logo {
    flex-shrink: 0; display: inline-flex; align-items: center;
    text-decoration: none; z-index: 1; transition: opacity 0.2s;
}
.cnr-hdr-logo:hover { opacity: 0.82; }
.cnr-hdr-logo img {
    height: 40px; width: auto; display: block; object-fit: contain;
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.45));
}
@media (max-width: 767px) { .cnr-hdr-logo img { height: 34px; } }

/* ── Desktop nav — real flex participant, not absolute-centered ──
   Was position:absolute + viewport-centered, which ignored how much
   room the right-side actions actually took and visually overlapped
   them once cart/account controls were added. Now it's a normal flex
   child (flex:1) between the logo and right actions, so it can only
   ever share space with its siblings, never overlap them. ─────── */
.cnr-hdr-nav {
    display: none; align-items: center; gap: 0;
    flex: 1 1 auto; justify-content: center; min-width: 0;
}
@media (min-width: 1400px) { .cnr-hdr-nav { display: flex; } }

/* Base nav link */
.cnr-hn {
    font-family: 'Inter', 'Instrument Sans', system-ui, sans-serif;
    font-size: 12px; font-weight: 500;
    color: rgba(195,200,190,0.70);
    padding: 5px 7px; white-space: nowrap;
    position: relative; text-decoration: none;
    transition: color 0.22s;
}
.cnr-hn::after {
    content: ''; position: absolute;
    bottom: -1px; left: 7px; right: 7px; height: 2px;
    background: #aecfa8; transform: scaleX(0);
    transform-origin: left; transition: transform 0.26s ease;
}
.cnr-hn:hover { color: #f4f4f4; }
.cnr-hn.on    { color: #aecfa8; font-weight: 700; }
.cnr-hn.on::after { transform: scaleX(1); }

/* ── Ürünler dropdown trigger ─────────────────────────────────── */
.cnr-dd-wrap {
    display: inline-flex; align-items: center; position: relative;
}
.cnr-dd-trigger {
    display: inline-flex; align-items: center; gap: 4px;
    cursor: pointer;
    font-family: 'Inter', 'Instrument Sans', system-ui, sans-serif;
    font-size: 12px; font-weight: 500;
    color: rgba(195,200,190,0.70);
    padding: 5px 7px; white-space: nowrap;
    position: relative; background: none; border: none;
    transition: color 0.22s;
}
.cnr-dd-trigger::after {
    content: ''; position: absolute;
    bottom: -1px; left: 7px; right: 7px; height: 2px;
    background: #aecfa8; transform: scaleX(0);
    transform-origin: left; transition: transform 0.26s ease;
}
.cnr-dd-trigger:hover    { color: #f4f4f4; }
.cnr-dd-trigger.on       { color: #aecfa8; font-weight: 700; }
.cnr-dd-trigger.on::after { transform: scaleX(1); }
.cnr-dd-trigger svg {
    opacity: 0.50; transition: transform 0.22s ease, opacity 0.22s;
    flex-shrink: 0;
}
.cnr-dd-trigger:hover svg,
.cnr-dd-trigger.on svg    { opacity: 0.90; }
.cnr-dd-trigger.dd-open svg { transform: rotate(180deg); }

/* ── Mega dropdown panel (position: fixed relative to viewport) ── */
#cnr-dd-products {
    position: fixed;
    top: 80px;                    /* header height */
    left: 50%;
    transform: translateX(-50%) translateY(-6px);
    width: min(900px, calc(100vw - 80px));
    background: rgba(11,11,11,0.97);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(51,51,51,0.28);
    border-top: 2px solid rgba(174,207,168,0.20);
    border-radius: 0 0 10px 10px;
    padding: 32px 36px 36px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px 24px;
    opacity: 0;
    pointer-events: none;
    z-index: 500;
    transition: opacity 0.20s ease, transform 0.20s ease;
    box-shadow: 0 32px 64px rgba(0,0,0,0.55);
}
#cnr-dd-products.is-open {
    opacity: 1;
    pointer-events: all;
    transform: translateX(-50%) translateY(0);
}

/* Dropdown group */
.cnr-dd-group-head {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 10px; font-weight: 600; letter-spacing: 0.14em;
    text-transform: uppercase; color: #aecfa8;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(174,207,168,0.14);
    margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;
    white-space: nowrap; text-decoration: none;
    transition: color 0.18s; cursor: pointer;
}
.cnr-dd-group-head:hover { color: #c8dfc4; }
.cnr-dd-group-head::after {
    content: '›'; font-size: 14px; font-weight: 300; letter-spacing: 0;
    opacity: 0; transform: translateX(-4px);
    transition: opacity 0.18s, transform 0.18s;
    flex-shrink: 0;
}
.cnr-dd-group-head:hover::after { opacity: 0.55; transform: translateX(0); }
.cnr-dd-list { list-style: none; padding: 0; margin: 0; }
.cnr-dd-list li { margin-bottom: 2px; }
.cnr-dd-list a {
    display: block; padding: 5px 0;
    font-family: 'Inter', sans-serif; font-size: 13px;
    color: rgba(195,200,190,0.82); text-decoration: none;
    transition: color 0.18s, padding-left 0.18s;
    white-space: nowrap;
}
.cnr-dd-list a:hover { color: #f4f4f4; padding-left: 5px; }
.cnr-dd-list a.dd-accent {
    color: #aecfa8; font-weight: 500;
}
.cnr-dd-list a.dd-accent:hover { color: #c8e0c4; padding-left: 5px; }

/* ── Right actions ────────────────────────────────────────────── */
.cnr-hdr-right {
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0; z-index: 1;
}

/* Cart link (icon + count badge) — desktop only; mobile gets the same link
   inside the full-screen overlay (#cnr-ov) so it's never lost, just moved. */
.cnr-hdr-cart {
    position: relative; display: none; align-items: center;
    padding: 6px; color: rgba(195,200,190,0.75);
    text-decoration: none; transition: color 0.2s;
}
@media (min-width: 1400px) { .cnr-hdr-cart { display: inline-flex; } }
.cnr-hdr-cart:hover { color: #f4f4f4; }
.cnr-hdr-cart-badge {
    position: absolute; top: 0; right: 0;
    min-width: 15px; height: 15px; padding: 0 3px;
    display: flex; align-items: center; justify-content: center;
    background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 9px; font-weight: 700;
    border-radius: 999px; line-height: 1;
}

/* Account dropdown trigger — single compact control for both guest
   (Giriş Yap / Kayıt Ol) and authenticated (Hesabım / Siparişlerim /
   Çıkış Yap) states, instead of two separate text links for guests. */
.cnr-hdr-account-wrap {
    display: none; position: relative; align-items: center; gap: 8px;
}
@media (min-width: 1400px) { .cnr-hdr-account-wrap { display: inline-flex; } }
#cnr-dd-account {
    position: absolute; top: calc(100% + 14px); right: 0;
    min-width: 180px; background: rgba(11,11,11,0.97);
    backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(51,51,51,0.28);
    border-top: 2px solid rgba(174,207,168,0.20);
    border-radius: 0 0 10px 10px;
    padding: 8px; opacity: 0; pointer-events: none; z-index: 500;
    transform: translateY(-6px);
    transition: opacity 0.20s ease, transform 0.20s ease;
    box-shadow: 0 32px 64px rgba(0,0,0,0.55);
}
#cnr-dd-account.is-open { opacity: 1; pointer-events: all; transform: translateY(0); }
#cnr-dd-account a,
#cnr-dd-account .cnr-dd-account-link {
    display: block; width: 100%; text-align: left; box-sizing: border-box;
    padding: 8px 10px; font-family: 'Inter', sans-serif; font-size: 13px;
    color: rgba(195,200,190,0.82); text-decoration: none;
    background: none; border: none; cursor: pointer; border-radius: 4px;
    transition: color 0.18s, background 0.18s;
}
#cnr-dd-account a:hover,
#cnr-dd-account .cnr-dd-account-link:hover { color: #f4f4f4; background: rgba(255,255,255,0.06); }

/* Language switcher */
.cnr-lang-sw {
    display: none; align-items: center; gap: 0;
    border-right: 1px solid rgba(255,255,255,0.08);
    padding-right: 10px; margin-right: 2px;
}
@media (min-width: 768px) { .cnr-lang-sw { display: flex; } }
.cnr-lang-sw a {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 10.5px; font-weight: 700; letter-spacing: 0.07em;
    color: rgba(255,255,255,0.28); padding: 4px 6px;
    text-decoration: none; transition: color 0.2s;
}
.cnr-lang-sw a.on,
.cnr-lang-sw a:hover { color: rgba(255,255,255,0.85); }
.cnr-lang-sep { color: rgba(255,255,255,0.15); font-size: 10px; line-height: 1; padding: 0 1px; }

/* CTA button */
.cnr-hdr-cta {
    display: none; align-items: center; gap: 8px;
    padding: 10px 22px; background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 13px; font-weight: 600; letter-spacing: 0.04em;
    border-radius: 4px; border-bottom: 1px solid rgba(166,124,0,0.28);
    text-decoration: none; flex-shrink: 0;
    transition: opacity 0.2s, transform 0.15s;
}
@media (min-width: 600px) { .cnr-hdr-cta { display: inline-flex; } }
.cnr-hdr-cta:hover  { opacity: 0.88; }
.cnr-hdr-cta:active { transform: scale(0.97); }

/* Hamburger */
.cnr-burger {
    display: flex; flex-direction: column; gap: 5px;
    padding: 8px 6px; background: none; border: none;
    cursor: pointer; margin-left: 4px; flex-shrink: 0;
}
@media (min-width: 1400px) { .cnr-burger { display: none; } }
.cnr-burger span {
    display: block; width: 22px; height: 1.5px;
    background: rgba(255,255,255,0.60);
    transition: transform 0.30s ease, opacity 0.30s ease;
}
.cnr-burger.open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); background: #fff; }
.cnr-burger.open span:nth-child(2) { opacity: 0; }
.cnr-burger.open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); background: #fff; }

/* ── Mobile overlay ──────────────────────────────────────────── */
#cnr-ov {
    position: fixed; inset: 0; z-index: 300;
    background: #0a0a0a; display: flex; flex-direction: column;
    opacity: 0; pointer-events: none; transition: opacity 0.28s ease;
}
#cnr-ov.open { opacity: 1; pointer-events: all; }
.cnr-ov-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 24px; height: 80px;
    border-bottom: 1px solid rgba(51,51,51,0.25); flex-shrink: 0;
}
.cnr-ov-close {
    width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.06); border: none; cursor: pointer;
    color: rgba(255,255,255,0.65); border-radius: 4px;
    transition: background 0.2s, color 0.2s;
}
.cnr-ov-close:hover { background: rgba(255,255,255,0.12); color: #fff; }

.cnr-ov-nav {
    flex: 1; padding: 24px 24px 16px;
    overflow-y: auto; display: flex; flex-direction: column;
}
.cnr-ov-link {
    font-family: 'Oswald', 'Hanken Grotesk', sans-serif;
    font-size: 1.9rem; font-weight: 600; line-height: 1;
    text-transform: uppercase; color: rgba(255,255,255,0.25);
    padding: 14px 0; border-bottom: 1px solid rgba(51,51,51,0.20);
    display: block; text-decoration: none;
    transition: color 0.2s, letter-spacing 0.2s;
}
.cnr-ov-link:last-child { border-bottom: none; }
.cnr-ov-link:hover { color: rgba(255,255,255,0.85); letter-spacing: 0.02em; }
.cnr-ov-link.on { color: #aecfa8; }

/* Mobile Ürünler accordion */
.cnr-ov-acc-trigger {
    display: flex; justify-content: space-between; align-items: center;
    font-family: 'Oswald', 'Hanken Grotesk', sans-serif;
    font-size: 1.9rem; font-weight: 600; line-height: 1;
    text-transform: uppercase; color: rgba(255,255,255,0.25);
    padding: 14px 0; border-bottom: 1px solid rgba(51,51,51,0.20);
    cursor: pointer; background: none; border-top: none;
    border-left: none; border-right: none; width: 100%; text-align: left;
    transition: color 0.2s;
}
.cnr-ov-acc-trigger:hover,
.cnr-ov-acc-trigger.on { color: #aecfa8; }
.cnr-ov-acc-trigger svg { flex-shrink: 0; opacity: 0.45; transition: transform 0.25s ease; }
.cnr-ov-acc-trigger.open svg { transform: rotate(180deg); }

.cnr-ov-acc-body {
    max-height: 0; overflow: hidden;
    transition: max-height 0.30s ease;
    border-bottom: 1px solid rgba(51,51,51,0.20);
}
.cnr-ov-acc-body.open { max-height: 600px; }
.cnr-ov-acc-inner { padding: 12px 0 20px 12px; }

/* Mobile per-group row: [link title] + [chevron toggle] */
.cnr-ov-grp-row {
    display: flex; align-items: center; justify-content: space-between;
    gap: 8px; margin: 16px 0 0;
}
.cnr-ov-grp-row:first-child { margin-top: 4px; }
.cnr-ov-grp-link {
    font-family: 'JetBrains Mono', monospace; font-size: 9px; font-weight: 600;
    letter-spacing: 0.16em; text-transform: uppercase; color: #aecfa8;
    text-decoration: none; flex: 1;
    transition: color 0.18s;
}
.cnr-ov-grp-link:hover { color: #c8dfc4; }
.cnr-ov-grp-toggle {
    flex-shrink: 0; background: none; border: none; cursor: pointer;
    color: rgba(174,207,168,0.45); padding: 4px 2px; line-height: 0;
    transition: transform 0.24s ease, color 0.18s;
}
.cnr-ov-grp-toggle:hover { color: rgba(174,207,168,0.80); }
.cnr-ov-grp-toggle.open { transform: rotate(180deg); }
.cnr-ov-grp-items {
    max-height: 0; overflow: hidden;
    transition: max-height 0.28s ease;
    padding-left: 4px;
}
.cnr-ov-grp-items.open { max-height: 320px; }
.cnr-ov-acc-item {
    display: block; font-family: 'Inter', sans-serif;
    font-size: 14px; color: rgba(195,200,190,0.65);
    padding: 5px 0; text-decoration: none; transition: color 0.18s;
}
.cnr-ov-acc-item:hover { color: #f4f4f4; }
.cnr-ov-acc-item.accent { color: #aecfa8; font-weight: 500; }

.cnr-ov-foot {
    padding: 20px 24px 36px; border-top: 1px solid rgba(51,51,51,0.22);
    flex-shrink: 0; display: flex; flex-direction: column; gap: 16px;
}
.cnr-ov-cta {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 18px; background: #aecfa8; color: #1b361b;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 14px; font-weight: 600; letter-spacing: 0.06em;
    text-decoration: none; border-radius: 4px;
    border-bottom: 1px solid rgba(166,124,0,0.28); transition: opacity 0.2s;
}
.cnr-ov-cta:hover { opacity: 0.88; }
.cnr-ov-util { display: flex; align-items: center; flex-wrap: wrap; gap: 14px; row-gap: 10px; }
.cnr-ov-util form { margin: 0; }
.cnr-ov-util-link.cnr-ov-util-btn {
    background: none; border: none; cursor: pointer; padding: 0;
    font: inherit; letter-spacing: inherit;
}
.cnr-ov-util-link {
    display: inline-flex; align-items: center; gap: 7px;
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600;
    letter-spacing: 0.04em; color: rgba(255,255,255,0.35);
    text-decoration: none; transition: color 0.2s;
}
.cnr-ov-util-link:hover { color: rgba(255,255,255,0.75); }
.cnr-ov-lang {
    margin-left: auto; display: flex; align-items: center; gap: 0;
    border-left: 1px solid rgba(255,255,255,0.10); padding-left: 14px;
}
.cnr-ov-lang a {
    font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 700;
    letter-spacing: 0.07em; padding: 4px 7px; text-decoration: none; transition: color 0.2s;
}
.cnr-ov-lang a.on,
.cnr-ov-lang a:hover { color: rgba(255,255,255,0.90); }
.cnr-ov-lang a:not(.on) { color: rgba(255,255,255,0.28); }
</style>

<header id="cnr-hdr" role="banner">
    <div class="cnr-hdr-wrap">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="cnr-hdr-logo" aria-label="CNRWOOD Ana Sayfa">
            <img src="{{ asset('images/cnrwood/brand/logo-horizontal-dark-header.png') }}" alt="CNRWOOD" width="160" height="40">
        </a>

        {{-- Desktop Nav: Ana Sayfa | Ürünler▾ | Ahşap Sandık | Kapı Sereni | Isıl İşlemli Ahşap | Projeler | Kurumsal | İletişim --}}
        <nav class="cnr-hdr-nav" aria-label="Ana Menü">

            {{-- Ana Sayfa (first) --}}
            <a href="{{ route('home') }}" class="cnr-hn {{ request()->routeIs('home') ? 'on' : '' }}">Ana Sayfa</a>

            {{-- Ürünler with dropdown trigger (second) --}}
            <div class="cnr-dd-wrap">
                <button
                    class="cnr-dd-trigger {{ $urunlerActive ? 'on' : '' }}"
                    id="cnr-dd-trigger"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="cnr-dd-products"
                    type="button"
                >
                    Ürünler
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
            </div>

            {{-- Remaining direct links (skip Ana Sayfa — rendered above) --}}
            @foreach($directLinks as $link)
                @if($link['label'] !== 'Ana Sayfa')
                    <a href="{{ $link['url'] }}" class="cnr-hn {{ $link['active'] ? 'on' : '' }}">{{ $link['label'] }}</a>
                @endif
            @endforeach

        </nav>

        {{-- Right: cart + account + lang switcher + CTA + hamburger --}}
        <div class="cnr-hdr-right">

            {{-- Cart (desktop) — always visible, badge shows session cart count --}}
            <a href="{{ route('cart.index') }}" class="cnr-hdr-cart" aria-label="Sepetim">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6L5 3H2"/><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/>
                </svg>
                @if ((int) session('cart_count', 0) > 0)
                    <span class="cnr-hdr-cart-badge">{{ (int) session('cart_count') > 99 ? '99+' : (int) session('cart_count') }}</span>
                @endif
            </a>

            {{-- Account (desktop): one compact dropdown trigger for both
                 guest (Giriş Yap / Kayıt Ol) and authenticated (Hesabım /
                 Siparişlerim / Çıkış Yap) states — keeps the header slim. --}}
            <div class="cnr-hdr-account-wrap">
                <button
                    class="cnr-dd-trigger"
                    id="cnr-acct-trigger"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="cnr-dd-account"
                >
                    @auth Hesabım @else Hesap @endauth
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div id="cnr-dd-account" role="menu" aria-label="Hesap Menüsü">
                    @auth
                        <a href="{{ route('account.dashboard') }}" role="menuitem">Hesabım</a>
                        <a href="{{ route('account.orders') }}" role="menuitem">Siparişlerim</a>
                        <form method="POST" action="{{ route('account.logout') }}">
                            @csrf
                            <button type="submit" class="cnr-dd-account-link" role="menuitem">Çıkış Yap</button>
                        </form>
                    @else
                        <a href="{{ route('account.login') }}" role="menuitem">Giriş Yap</a>
                        <a href="{{ route('account.register') }}" role="menuitem">Kayıt Ol</a>
                    @endauth
                </div>
            </div>

            <div class="cnr-lang-sw" aria-label="Dil seçimi">
                <a href="{{ route('locale.switch', 'tr') }}" class="{{ $currentLocale === 'tr' ? 'on' : '' }}">TR</a>
                <span class="cnr-lang-sep">/</span>
                <a href="{{ route('locale.switch', 'en') }}" class="{{ $currentLocale === 'en' ? 'on' : '' }}">EN</a>
            </div>
            <a href="{{ route('public.quote.create') }}" class="cnr-hdr-cta">
                Teklif Al
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <button class="cnr-burger" id="cnr-burger" aria-label="Menüyü aç / kapat" type="button">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- ── Mega dropdown (fixed: outside stacking context) ──────────── --}}
<div id="cnr-dd-products" role="region" aria-label="Ürün Kategorileri">
    @foreach($ddGroups as $group)
        <div>
            <a href="{{ $group['groupUrl'] }}" class="cnr-dd-group-head">{{ $group['label'] }}</a>
            <ul class="cnr-dd-list">
                @foreach($group['items'] as $item)
                    <li>
                        <a href="{{ $item['url'] }}"
                           class="{{ ($item['accent'] ?? false) ? 'dd-accent' : '' }}">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

{{-- ── Mobile full-screen overlay ──────────────────────────────── --}}
<div id="cnr-ov" role="dialog" aria-modal="true" aria-label="Gezinti Menüsü">

    <div class="cnr-ov-head">
        <a href="{{ route('home') }}" aria-label="CNRWOOD Ana Sayfa" style="display:inline-flex;align-items:center">
            <img src="{{ asset('images/cnrwood/brand/logo-horizontal-dark-header.png') }}" alt="CNRWOOD"
                 style="height:34px;width:auto;object-fit:contain;filter:drop-shadow(0 1px 2px rgba(0,0,0,0.45))">
        </a>
        <button class="cnr-ov-close" id="cnr-ov-close" aria-label="Kapat" type="button">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="cnr-ov-nav" aria-label="Mobil Menü">

        {{-- Ana Sayfa --}}
        <a href="{{ route('home') }}" class="cnr-ov-link {{ request()->routeIs('home') ? 'on' : '' }}">Ana Sayfa</a>

        {{-- Ürünler accordion --}}
        <button
            class="cnr-ov-acc-trigger {{ $urunlerActive ? 'on' : '' }}"
            id="cnr-ov-acc-btn"
            type="button"
            aria-expanded="false"
            aria-controls="cnr-ov-acc-body"
        >
            Ürünler
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>

        <div id="cnr-ov-acc-body" class="cnr-ov-acc-body {{ $urunlerActive ? 'open' : '' }}">
            <div class="cnr-ov-acc-inner">
                @foreach($ddGroups as $gi => $group)
                    <div class="cnr-ov-grp-row">
                        <a href="{{ $group['groupUrl'] }}" class="cnr-ov-grp-link">{{ $group['label'] }}</a>
                        <button class="cnr-ov-grp-toggle" data-target="cnr-grp-{{ $gi }}" aria-expanded="false" type="button" aria-label="{{ $group['label'] }} alt kategoriler">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div id="cnr-grp-{{ $gi }}" class="cnr-ov-grp-items">
                        @foreach($group['items'] as $item)
                            <a href="{{ $item['url'] }}"
                               class="cnr-ov-acc-item {{ ($item['accent'] ?? false) ? 'accent' : '' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Direct links (skip Ana Sayfa — already above) --}}
        @foreach($directLinks as $link)
            @if($link['label'] !== 'Ana Sayfa')
                <a href="{{ $link['url'] }}" class="cnr-ov-link {{ $link['active'] ? 'on' : '' }}">{{ $link['label'] }}</a>
            @endif
        @endforeach

    </nav>

    <div class="cnr-ov-foot">
        <a href="{{ route('public.quote.create') }}" class="cnr-ov-cta">
            Teklif Al
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <div class="cnr-ov-util">
            {{-- Cart — always visible, same /sepet link as desktop --}}
            <a href="{{ route('cart.index') }}" class="cnr-ov-util-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6L5 3H2"/><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/>
                </svg>
                Sepetim{{ (int) session('cart_count', 0) > 0 ? ' (' . (int) session('cart_count') . ')' : '' }}
            </a>

            @auth
                <a href="{{ route('account.dashboard') }}" class="cnr-ov-util-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Hesabım
                </a>
                <a href="{{ route('account.orders') }}" class="cnr-ov-util-link">Siparişlerim</a>
                <form method="POST" action="{{ route('account.logout') }}">
                    @csrf
                    <button type="submit" class="cnr-ov-util-link cnr-ov-util-btn">Çıkış Yap</button>
                </form>
            @else
                <a href="{{ route('account.login') }}" class="cnr-ov-util-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Giriş Yap
                </a>
                <a href="{{ route('account.register') }}" class="cnr-ov-util-link">Kayıt Ol</a>
            @endauth
            <div class="cnr-ov-lang">
                <a href="{{ route('locale.switch', 'tr') }}" class="{{ $currentLocale === 'tr' ? 'on' : '' }}">TR</a>
                <span style="color:rgba(255,255,255,0.15);font-size:10px">/</span>
                <a href="{{ route('locale.switch', 'en') }}" class="{{ $currentLocale === 'en' ? 'on' : '' }}">EN</a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var hdr      = document.getElementById('cnr-hdr');
    var burger   = document.getElementById('cnr-burger');
    var ov       = document.getElementById('cnr-ov');
    var ovClose  = document.getElementById('cnr-ov-close');
    var ddTrigger = document.getElementById('cnr-dd-trigger');
    var ddPanel   = document.getElementById('cnr-dd-products');
    var ovAccBtn  = document.getElementById('cnr-ov-acc-btn');
    var ovAccBody = document.getElementById('cnr-ov-acc-body');

    /* ── Scroll: darken header ── */
    function onScroll() { hdr.classList.toggle('is-scrolled', window.scrollY > 24); }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ── Ürünler mega-dropdown (desktop) ── */
    var ddHideTimer;

    function showDd() {
        clearTimeout(ddHideTimer);
        ddPanel.classList.add('is-open');
        ddTrigger.classList.add('dd-open');
        ddTrigger.setAttribute('aria-expanded', 'true');
    }
    function hideDd() {
        ddHideTimer = setTimeout(function () {
            ddPanel.classList.remove('is-open');
            ddTrigger.classList.remove('dd-open');
            ddTrigger.setAttribute('aria-expanded', 'false');
        }, 140);
    }

    if (ddTrigger && ddPanel) {
        ddTrigger.addEventListener('mouseenter', showDd);
        ddTrigger.addEventListener('mouseleave', hideDd);
        ddTrigger.addEventListener('click', function () {
            ddPanel.classList.contains('is-open') ? hideDd() : showDd();
        });
        ddPanel.addEventListener('mouseenter', showDd);
        ddPanel.addEventListener('mouseleave', hideDd);

        /* Keyboard: Escape closes dropdown */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && ddPanel.classList.contains('is-open')) {
                hideDd();
                ddTrigger.focus();
            }
        });

        /* Click outside closes dropdown */
        document.addEventListener('click', function (e) {
            if (!ddTrigger.contains(e.target) && !ddPanel.contains(e.target)) {
                hideDd();
            }
        });
    }

    /* ── Account dropdown (desktop, guest + authenticated) ── */
    var acctTrigger = document.getElementById('cnr-acct-trigger');
    var acctPanel   = document.getElementById('cnr-dd-account');
    var acctHideTimer;

    function showAcct() {
        clearTimeout(acctHideTimer);
        acctPanel.classList.add('is-open');
        acctTrigger.classList.add('dd-open');
        acctTrigger.setAttribute('aria-expanded', 'true');
    }
    function hideAcct() {
        acctHideTimer = setTimeout(function () {
            acctPanel.classList.remove('is-open');
            acctTrigger.classList.remove('dd-open');
            acctTrigger.setAttribute('aria-expanded', 'false');
        }, 140);
    }

    if (acctTrigger && acctPanel) {
        acctTrigger.addEventListener('mouseenter', showAcct);
        acctTrigger.addEventListener('mouseleave', hideAcct);
        acctTrigger.addEventListener('click', function () {
            acctPanel.classList.contains('is-open') ? hideAcct() : showAcct();
        });
        acctPanel.addEventListener('mouseenter', showAcct);
        acctPanel.addEventListener('mouseleave', hideAcct);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && acctPanel.classList.contains('is-open')) {
                hideAcct();
                acctTrigger.focus();
            }
        });

        document.addEventListener('click', function (e) {
            if (!acctTrigger.contains(e.target) && !acctPanel.contains(e.target)) {
                hideAcct();
            }
        });
    }

    /* ── Mobile overlay ── */
    function openOv() {
        ov.classList.add('open');
        document.body.style.overflow = 'hidden';
        burger.classList.add('open');
        ov.removeAttribute('aria-hidden');
        /* Close desktop dropdown if open */
        if (ddPanel) { ddPanel.classList.remove('is-open'); }
    }
    function closeOv() {
        ov.classList.remove('open');
        document.body.style.overflow = '';
        burger.classList.remove('open');
        ov.setAttribute('aria-hidden', 'true');
    }

    ov.setAttribute('aria-hidden', 'true');
    if (burger)  burger.addEventListener('click', function () { ov.classList.contains('open') ? closeOv() : openOv(); });
    if (ovClose) ovClose.addEventListener('click', closeOv);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeOv(); });

    /* ── Mobile Ürünler accordion (outer: shows/hides all groups) ── */
    if (ovAccBtn && ovAccBody) {
        ovAccBtn.addEventListener('click', function () {
            var isOpen = ovAccBody.classList.contains('open');
            ovAccBody.classList.toggle('open', !isOpen);
            ovAccBtn.classList.toggle('open', !isOpen);
            ovAccBtn.setAttribute('aria-expanded', String(!isOpen));
        });
    }

    /* ── Mobile per-group sub-accordion toggles ── */
    document.querySelectorAll('.cnr-ov-grp-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var target   = document.getElementById(targetId);
            if (!target) return;
            var isOpen = target.classList.contains('open');
            target.classList.toggle('open', !isOpen);
            btn.classList.toggle('open', !isOpen);
            btn.setAttribute('aria-expanded', String(!isOpen));
        });
    });

}());
</script>
