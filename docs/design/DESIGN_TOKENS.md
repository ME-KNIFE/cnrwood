# CNRWOOD Design System — Developer Handoff

**Version:** 1.0  
**Stack:** Laravel Blade · Tailwind CSS · Filament  
**Languages:** TR / EN ready (Ş Ç Ü Ğ İ Ö tested)

---

## 01 — Color Palette

### Wood / Ahşap — Primary brand: backgrounds, surfaces, text

| Token | Hex | Notes |
|---|---|---|
| `wood-50` | `#FAF7F2` | Page background |
| `wood-100` | `#F5F0E8` | Cream — section backgrounds, input backgrounds |
| `wood-200` | `#E8DCC9` | Borders, card backgrounds |
| `wood-300` | `#D4BE9C` | Dividers, hover states |
| `wood-400` | `#B88D5E` | Accents, icons |
| `wood-500` | `#8B5A2B` | **Accent** — links, selection, scrollbar thumb |
| `wood-600` | `#6F471F` | Darker accent |
| `wood-700` | `#573717` | Strong accent |
| `wood-800` | `#3E2006` | **Dark Brown** — primary text, headings |
| `wood-900` | `#2A1604` | Footer background |
| `wood-950` | `#1A0D02` | Deepest brown |

### Blue / Mavi CTA — Primary CTA only

| Token | Hex | Notes |
|---|---|---|
| `blue-50` | `#EEF3F8` | |
| `blue-100` | `#D4E0EE` | |
| `blue-200` | `#A9C0DC` | |
| `blue-400` | `#4B739E` | |
| `blue-600` | `#1E3A5F` | **Primary CTA bg** (Teklif Al, Gönder) |
| `blue-700` | `#182F4D` | CTA hover state |
| `blue-800` | `#122339` | |

> **Rule:** Blue is used **only** for primary CTA buttons (Teklif Al, Gönder). Nowhere else.

### WhatsApp — WhatsApp action only

| Token | Hex | Notes |
|---|---|---|
| `green-500` | `#25D366` | WhatsApp button bg |
| `green-600` | `#1DA851` | WhatsApp hover / semantic success |
| `green-700` | `#178A43` | |

> **Rule:** WhatsApp green is reserved exclusively for WhatsApp communication actions.

### Neutral / Warm Gray — Borders, text, UI surfaces

| Token | Hex | Notes |
|---|---|---|
| `neutral-0` | `#FFFFFF` | Pure white |
| `neutral-50` | `#FAFAF9` | |
| `neutral-100` | `#F4F2EF` | |
| `neutral-200` | `#E7E3DD` | |
| `neutral-300` | `#D2CCC2` | Scrollbar track |
| `neutral-400` | `#A8A096` | |
| `neutral-500` | `#7C7468` | Secondary text |
| `neutral-600` | `#5C554B` | |
| `neutral-700` | `#443E36` | |
| `neutral-800` | `#2B2722` | Body text color |
| `neutral-900` | `#1A1714` | |

### Semantic / Status

| Token | Hex | Usage |
|---|---|---|
| `success` | `#1DA851` | Stokta, onaylandı |
| `warning` | `#B8841F` | Uyarı durumları |
| `error` | `#B3261E` | Hata, form validation |
| `info` | `#1E3A5F` | Bilgi mesajları |

---

## 02 — Typography

### Font Families

| Token | Family | Usage |
|---|---|---|
| `font-family-sans` | IBM Plex Sans | UI, headings, body — all general text |
| `font-family-mono` | IBM Plex Mono | Technical labels, SKU codes, ISPM-15 codes, dimensions (e.g. `1200×800×600mm`) |

Both support full Turkish character set: **Ş ş Ç ç Ü ü Ğ ğ İ ı Ö ö**

Body: `font-family: 'IBM Plex Sans', system-ui, -apple-system, sans-serif`  
Mono: `font-family: 'IBM Plex Mono', monospace`

### Type Scale

| Token | Weight | Size | Line Height | Letter Spacing | Usage |
|---|---|---|---|---|---|
| `text-display` | 700 | 48px (mobile: 40px) | 56px / 1.16 | -0.01em | Hero headline |
| `text-h1` | 700 | 40px (mobile: 30px) | 48px | -0.01em | Page H1 |
| `text-h2` | 600 | 32px | 40px | 0 | Section heading |
| `text-h3` | 600 | 24px | 32px | 0 | Sub-section heading |
| `text-h4` | 600 | 20px | 28px | 0 | Card title, sidebar heading |
| `text-body-lg` | 400 | 18px | 30px | 0 | Lead / intro text |
| `text-body` | 400 | 16px | 26px | 0 | Standard body |
| `text-body-sm` | 400 | 14px | 22px | 0 | Secondary text, form help, meta |
| `text-caption` | 500 | 13px | 18px | 0 | Image captions, labels, footnotes |
| `text-label` | mono 600 | 12px | 16px | +0.08em | Category tags, SKU labels (mono, UPPERCASE) |

### Turkish Uppercase Heading Rule — CRITICAL

Turkish uppercase characters (Ğ, Ü, Ş, İ, Ö) with ascenders/diacritics **collide at tight line-heights**.

| | CSS | Result |
|---|---|---|
| **WRONG** | `line-height: 0.95` | Letters clip/collide |
| **CORRECT** | `line-height: 1.2` minimum + `letter-spacing: +0.02em` | Clear and legible |

Apply to **all uppercase Turkish headings** (nav labels, section titles, badges).

---

## 03 — Buttons

### Variants

| Variant | Class | Background | Text | Border | Hover |
|---|---|---|---|---|---|
| **Primary (CTA)** | `btn-primary` | `blue-600` (`#1E3A5F`) | white | none | `blue-700` (`#182F4D`) |
| **Secondary (Wood)** | `btn-secondary` | transparent | `wood-800` | `wood-500` | bg `wood-100` |
| **Ghost** | `btn-ghost` | transparent | `wood-700` | none | bg `wood-200/40` |
| **WhatsApp** | `btn-whatsapp` | `green-500` (`#25D366`) | `#0A3D1C` | none | `green-600` |

> WhatsApp button: use **only** for WhatsApp contact action. Never reuse green elsewhere.

### Sizes

| Size | Height | Usage |
|---|---|---|
| `sm` | h-36 (36px) | Compact UI, table actions |
| `md` | h-44 (44px) | Default |
| `lg` | h-52 (52px) | Hero CTA, prominent actions |

### Common Properties

- **Border radius:** `3px` (sharp — industrial aesthetic)
- **Font weight:** 600
- **Focus ring:** `3px solid blue-600/28` (blue-600 at 28% opacity)
- **Disabled:** `opacity: 0.42`

---

## 04 — Header

Two-layer header: dark wood utility strip (top) + white main navigation.

### Upper Utility Strip (desktop only)

- Background: `wood-900` (`#2A1604`)
- Content: phone (`✆`), email (`✉`), language switcher `TR | EN`
- Text: `wood-300` / `wood-400`
- Height: ~36px

### Main Navigation Bar

- Background: `#FFFFFF`
- Border-bottom: `1px solid wood-200`
- Logo: `text-h4`, `wood-800`
- Nav links: `text-body-sm`, `wood-700`, hover `wood-800`
- WhatsApp button: `btn-whatsapp`
- CTA: **"Teklif Al →"** `btn-primary` — always top-right, always visible

### Mobile (`<768px`)

- Utility strip shows phone + language only
- Main nav collapses to hamburger menu at `<lg` (1024px)
- Logo left, WhatsApp icon + "Teklif Al →" right
- Hamburger icon: `wood-800`

---

## 05 — Footer

- Background: `wood-900` (`#2A1604`)
- Text: `wood-200` / `wood-300`
- Links: hover `wood-100`

### Four Columns (desktop)

1. **Kurumsal** — logo, tagline, ISPM 15 badge (`✓ ISPM 15 SERTİFİKALI` in success green)
2. **Ürünler** — product category links
3. **Kurumsal** — company pages (Hakkımızda, Üretim Tesisi, Şubeler, Çözüm Ortakları)
4. **İletişim** — address, phone, email, WhatsApp button

### Bottom Bar

- Copyright: `© 2026 CNRWOOD Ahşap Ambalaj San. ve Tic. Ltd. Şti.`
- Links: Gizlilik, KVKK
- Color: `wood-600`

---

## 06 — Product Card (Priced)

For stocked/priced products. Shows price and cart action.

### Structure

| Element | Specs |
|---|---|
| Image area | Aspect ratio 4:3 |
| Category label | `text-label` (mono, uppercase), `wood-800` bg |
| Product title | `text-h4` / 18px / weight 600 |
| Dimensions | `text-label` / mono 12px / `neutral-500` |
| Price | 22px / weight 700 / `wood-800` |
| Stock indicator | `●` + success green dot |
| CTA button | **"Sepete Ekle →"** — uses `wood-800` bg (NOT blue) |

### Card Chrome

- Border radius: `6px`
- Shadow: `shadow-sm` default → `shadow-md` on hover
- Border: `1px solid wood-200`
- Background: white

> **Note:** On priced product cards, "Sepete Ekle" uses `wood-800`, not blue. Blue CTA is only for quote actions.

---

## 07 — Quote-only Product Card

For custom/made-to-order products. **Price, cart, and stock count are NEVER shown.**

### What to show instead

| Do | Don't |
|---|---|
| `"Teklife bağlı"` badge (wood tone) | ₺ price |
| "Fiyat için teklif alın" text | "Sepete Ekle" button |
| **"Teklif Al →"** `btn-primary` (blue CTA) | Stock count |

### Blade guard

```blade
@if($product->quote_only)
    {{-- Show: Teklife bağlı badge + Teklif Al CTA --}}
@else
    {{-- Show: price + Sepete Ekle --}}
@endif
```

### The one exception to the blue-CTA rule

On product cards, "Teklif Al" (blue) appears on quote-only cards. This is the only place where a non-top-level card shows a blue CTA.

---

## 08 — Hero Sections

### Variant A — Split (Text + Image)

- Left: headline (`text-display`, `wood-800`), body text, CTA pair
- Right: product/production image
- Background: `wood-100` or white
- Headline `line-height: 1.16`

### Variant B — Full-bleed with overlay

- Background: dark image with `wood-900` overlay
- Text: white / `wood-100`
- Same CTA pair structure

### Hero CTA Pattern

Primary: **"Teklif Al →"** `btn-primary` (lg size)  
Secondary: "Ürünleri Gör" or "Kataloğu İndir" `btn-secondary`

---

## 09 — Forms

### Input Fields

- Height: `h-48` (48px / `py-2 px-3`)
- Border: `1px solid wood-200` (`#E8DCC9`)
- Focus border: `wood-500` (`#8B5A2B`)
- Focus ring: `1px ring wood-500`
- Border radius: `4px`
- Font size: 14px (`text-sm`)

### Labels

- Color: `wood-800` (`#3E2006`)
- Font size: 14px / weight 500
- Required asterisk: `text-red-600`
- Optional note: `text-xs neutral-500` font-normal

### States

| State | Visual |
|---|---|
| Default | `border-wood-200` |
| Focus | `border-wood-500` + ring |
| Error | `border-error` (`#B3261E`) + red helper text |
| Success | `border-success` checkmark |

### Teklif Formu structure

Primary conversion point. Fields: Ad Soyad\*, Firma, E-posta\*, Ürün Kategorisi (select), message (textarea), KVKK checkbox.

Submit: **"Teklif Al →"** `btn-primary` full-width on mobile.

---

## 10 — Admin / Filament

### Filament Config

| Property | Value |
|---|---|
| `primary` color | `blue-600` (`#1E3A5F`) |
| `gray` palette | Warm slate (not cool gray) |
| Brand accent | `wood-500` (`#8B5A2B`) |

### Filament Dark Mode Palette (Admin Preview Card)

- Card background: `#1E222B` (slate-900-ish)
- Card border: `#262B36`
- Header bg: `#181B22`
- Text primary: `#F4F2EF`
- Text secondary: `#A0A8B8`
- Brand accent in dark: `wood-400` / `wood-500`

### Quote Request Status Badges

| Status | Label | Color |
|---|---|---|
| Pending | `● Bekliyor` | `warning` / amber |
| In Review | `● İncelemede` | `info` / blue |
| Approved | `● Onaylandı` | `success` / green |
| Cancelled | `● İptal` | `error` / red |

### Filament Navigation

- `$navigationIcon`: string or `\BackedEnum|null`
- `$navigationGroup`: `\UnitEnum|string|null`
- `$view`: non-static `protected string`

---

## 11 — Mobile & Responsive

### Breakpoints (Tailwind)

| Prefix | Min-width | Context |
|---|---|---|
| *(none)* | 0px | Mobile-first base |
| `sm` | 640px | Large phone |
| `md` | 768px | Tablet — nav expands |
| `lg` | 1024px | Desktop — full header |
| `xl` | 1280px | Wide — max-container 1200px |

### Container Widths

| Breakpoint | Max-width | Padding |
|---|---|---|
| sm | 640px | `px-4` |
| md | 768px | `px-6` |
| lg | 1024px | `px-8` |
| xl | 1200px | `px-16` |

### Adaptation Rules

| Element | Mobile | Desktop |
|---|---|---|
| Product grid | `grid-cols-1` | `sm:grid-cols-2` → `lg:grid-cols-3` → `xl:grid-cols-4` |
| `text-display` | 40px | 60px |
| `text-h1` | 30px | 40px |
| Header nav | Hamburger | Full nav |
| Global padding | `px-4` | → `px-8` → `px-16` |
| CTA button | Fixed bottom bar | Inline |

### Touch targets

Minimum **44 × 44px** for all interactive elements.

### Mobile CTA pattern

"Teklif Al" moves to a **fixed bottom bar** on mobile (`position: fixed; bottom: 0`). Width: 100%. Height: minimum 52px.

---

## 12 — Do & Don't

### Color Usage

| ✓ Do | ✕ Don't |
|---|---|
| Use blue **only** for primary CTA (Teklif Al) | Use blue on secondary actions, decorative elements |
| WhatsApp green for WhatsApp button only | Reuse green for other actions |
| Wood tones for backgrounds, borders, non-CTA text | Mix cool grays with warm wood palette |

### Typography

| ✓ Do | ✕ Don't |
|---|---|
| `line-height: 1.2` minimum on Turkish uppercase | Tight `line-height: 0.95` on Ğ, Ü, Ş titles |
| `letter-spacing: +0.02em` on uppercase headings | Clip diacritics with cramped leading |
| IBM Plex Mono for dimensions/SKUs | Use mono for body text |

### Visual Style

| ✓ Do | ✕ Don't |
|---|---|
| Sharp corners (`border-radius: 3–6px`) | Excessive rounding (pill buttons on main CTAs) |
| Real production/product photography | Emoji, clipart, cartoonish illustrations |
| Wood tones as primary palette | Bright/saturated colors unrelated to brand |
| Shadow-sm default → shadow-md on hover | Heavy shadows on static cards |

### Quote-only Products

| ✓ Do | ✕ Don't |
|---|---|
| Show "Teklife bağlı" badge | Show ₺ price (even `₺0`) |
| Show "Teklif Al" blue CTA | Show "Sepete Ekle" button |
| Hide price block entirely with `@if($product->quote_only)` | Show price as 0, null, or "—" |

---

## CSS Variable Reference

Inject via `@push('head')` → `@stack('head')`. Mobile overrides: `@media (min-width: 1024px)`.

```css
:root {
  /* Wood */
  --wood-50:  #FAF7F2;
  --wood-100: #F5F0E8;
  --wood-200: #E8DCC9;
  --wood-300: #D4BE9C;
  --wood-400: #B88D5E;
  --wood-500: #8B5A2B;
  --wood-600: #6F471F;
  --wood-700: #573717;
  --wood-800: #3E2006;
  --wood-900: #2A1604;
  --wood-950: #1A0D02;

  /* Blue CTA */
  --blue-50:  #EEF3F8;
  --blue-100: #D4E0EE;
  --blue-200: #A9C0DC;
  --blue-400: #4B739E;
  --blue-600: #1E3A5F;
  --blue-700: #182F4D;
  --blue-800: #122339;

  /* WhatsApp */
  --green-500: #25D366;
  --green-600: #1DA851;
  --green-700: #178A43;

  /* Neutral (warm-tinted) */
  --neutral-0:   #FFFFFF;
  --neutral-50:  #FAFAF9;
  --neutral-100: #F4F2EF;
  --neutral-200: #E7E3DD;
  --neutral-300: #D2CCC2;
  --neutral-400: #A8A096;
  --neutral-500: #7C7468;
  --neutral-600: #5C554B;
  --neutral-700: #443E36;
  --neutral-800: #2B2722;
  --neutral-900: #1A1714;

  /* Semantic */
  --success: #1DA851;
  --warning: #B8841F;
  --error:   #B3261E;
  --info:    #1E3A5F;

  /* Radius */
  --radius-sm:   3px;
  --radius-md:   6px;
  --radius-lg:   12px;
  --radius-pill: 9999px;

  /* Shadows */
  --shadow-sm: 0 1px 3px rgba(42,22,4,.08), 0 1px 2px rgba(42,22,4,.06);
  --shadow-md: 0 4px 8px rgba(42,22,4,.10), 0 2px 4px rgba(42,22,4,.06);
  --shadow-lg: 0 12px 24px rgba(42,22,4,.12), 0 4px 8px rgba(42,22,4,.06);
}
```
