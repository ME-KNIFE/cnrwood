@extends('layouts.public')

@php
    $title           = 'Sandık Hesaplama Talebi — CNRWOOD | Özel Ahşap Sandık Teklifi';
    $metaDescription = 'Ürün ölçülerinizi, ağırlığınızı ve teknik gereksinimlerinizi girin; CNRWOOD uzmanları en geç 1 iş günü içinde size özel teklif hazırlasın.';
@endphp

@section('content')

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Anasayfa',         'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Sandık Hesaplama', 'item' => route('public.sandik')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ── Page Header ──────────────────────────────────────────────────────────── --}}
<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-[#8B5A2B] mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Anasayfa</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">Sandık Hesaplama</span>
        </nav>
        <h1 class="text-3xl sm:text-4xl font-bold text-[#3E2006]">Sandık Hesaplama Talebi</h1>
        <p class="text-[#555555] mt-2 max-w-3xl leading-relaxed">
            Ürün bilgilerinizi ve teknik gereksinimlerinizi girin; uzman ekibimiz
            <strong>en geç 1 iş günü içinde</strong> size özel sandık teklifi hazırlasın.
            Bu talep <strong>tamamen ücretsizdir</strong> ve hiçbir ödeme alınmaz.
        </p>
    </div>
</section>

{{-- ── Main Form Section ────────────────────────────────────────────────────── --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">Lütfen aşağıdaki hataları düzeltin:</p>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── FORM ──────────────────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('public.sandik.store') }}"
              class="lg:col-span-2 space-y-8">
            @csrf

            {{-- Honeypot — hidden from real users; bots fill it --}}
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            {{-- ── SECTION 1: İletişim Bilgileri ──────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">1</span>
                    İletişim Bilgileri
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="contact_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Ad Soyad <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="contact_name" id="contact_name" required maxlength="120"
                               value="{{ old('contact_name') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_name') border-red-400 @enderror">
                        @error('contact_name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            E-posta <span class="text-red-600">*</span>
                        </label>
                        <input type="email" name="contact_email" id="contact_email" required maxlength="160"
                               value="{{ old('contact_email') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_email') border-red-400 @enderror">
                        @error('contact_email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Telefon <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" required maxlength="20"
                               value="{{ old('contact_phone') }}"
                               placeholder="+90 5XX XXX XX XX"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('contact_phone') border-red-400 @enderror">
                        @error('contact_phone')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="preferred_contact" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Tercih Edilen İletişim
                        </label>
                        <select name="preferred_contact" id="preferred_contact"
                                class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                            <option value="email"    @selected(old('preferred_contact', 'email') === 'email')>E-posta</option>
                            <option value="phone"    @selected(old('preferred_contact') === 'phone')>Telefon</option>
                            <option value="whatsapp" @selected(old('preferred_contact') === 'whatsapp')>WhatsApp</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Firma Adı <span class="text-xs text-[#555555] font-normal">(opsiyonel)</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" maxlength="160"
                               value="{{ old('company_name') }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: Ölçüler & Ağırlık ───────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-1 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">2</span>
                    Ölçüler &amp; Ağırlık
                </h2>
                <p class="text-sm text-[#555555] mb-5 ml-9">Ambalajlanacak ürünün veya yükün dış ölçüleri ve brüt ağırlığı.</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                    <div>
                        <label for="length_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Uzunluk (cm) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="length_cm" id="length_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('length_cm') }}"
                               placeholder="ör. 120"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('length_cm') border-red-400 @enderror">
                        @error('length_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="width_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Genişlik (cm) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="width_cm" id="width_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('width_cm') }}"
                               placeholder="ör. 80"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('width_cm') border-red-400 @enderror">
                        @error('width_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="height_cm" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Yükseklik (cm) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="height_cm" id="height_cm" required min="1" max="99999" step="0.01"
                               value="{{ old('height_cm') }}"
                               placeholder="ör. 60"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('height_cm') border-red-400 @enderror">
                        @error('height_cm')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="weight_kg" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Ağırlık (kg) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="weight_kg" id="weight_kg" required min="0.01" max="99999" step="0.01"
                               value="{{ old('weight_kg') }}"
                               placeholder="ör. 250"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('weight_kg') border-red-400 @enderror">
                        @error('weight_kg')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: Sandık Tipi ───────────────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">3</span>
                    Sandık Tipi <span class="text-red-600 ml-1">*</span>
                </h2>
                @php
                    $crateOptions = [
                        'ahsap'         => ['label' => 'Ahşap Sandık',     'desc' => 'Klasik çivi/vida birleşimli ahşap sandık'],
                        'osb'           => ['label' => 'OSB Sandık',        'desc' => 'OSB levha ile üretilmiş sandık'],
                        'izgara'        => ['label' => 'Izgara Palet',      'desc' => 'Alt ızgara yapılı taşıma paleti'],
                        'vinc_aparatli' => ['label' => 'Vinç Aparatlı',    'desc' => 'Vinç kaldırma noktaları bulunan sandık'],
                        'endcap'        => ['label' => 'End Cap',           'desc' => 'Ürün uçlarını koruyan ahşap kapama'],
                        'taban_izgara'  => ['label' => 'Taban Izgara',     'desc' => 'Yalnızca taban ızgara/palet'],
                        'bilmiyorum'    => ['label' => 'Bilmiyorum / Önerin', 'desc' => 'CNRWOOD uzmanı en uygun tipi önersin'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($crateOptions as $val => $opt)
                        <label class="flex items-start gap-3 p-3 border rounded cursor-pointer transition-colors
                                      {{ old('crate_type') === $val ? 'border-[#8B5A2B] bg-[#F5F0E8]' : 'border-[#E6DFD2] bg-white hover:border-[#8B5A2B] hover:bg-[#F5F0E8]' }}">
                            <input type="radio" name="crate_type" value="{{ $val }}"
                                   class="mt-0.5 accent-[#3E2006]"
                                   @checked(old('crate_type') === $val) required>
                            <span>
                                <span class="block text-sm font-medium text-[#3E2006]">{{ $opt['label'] }}</span>
                                <span class="block text-xs text-[#555555] mt-0.5">{{ $opt['desc'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('crate_type')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── SECTION 4: Teknik Gereksinimler ────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">4</span>
                    Teknik Gereksinimler
                </h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_ispm15" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_ispm15'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">ISPM-15 Sertifikası</span>
                            <span class="block text-xs text-[#555555]">İhracat için uluslararası ahşap ambalaj standartı (fumigasyon)</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_forklift" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_forklift'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">Forklift Girişi</span>
                            <span class="block text-xs text-[#555555]">Alt kısımda forklift kolları için boşluk gerekiyor</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_crane" value="1" class="w-4 h-4 accent-[#3E2006]"
                               @checked(old('requires_crane'))>
                        <span>
                            <span class="text-sm font-medium text-[#3E2006]">Vinç / Taşıma Aparatı</span>
                            <span class="block text-xs text-[#555555]">Sandık üstünde vinç kaldırma noktaları gerekiyor</span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- ── SECTION 5: Miktar & Sevkiyat ───────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">5</span>
                    Miktar &amp; Sevkiyat
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Sandık Adedi <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" required min="1" max="999999"
                               value="{{ old('quantity', 1) }}"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('quantity') border-red-400 @enderror">
                        @error('quantity')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="shipping_type" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Sevkiyat Tipi <span class="text-red-600">*</span>
                        </label>
                        <select name="shipping_type" id="shipping_type" required
                                class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] @error('shipping_type') border-red-400 @enderror">
                            <option value="ihracat" @selected(old('shipping_type', 'ihracat') === 'ihracat')>İhracat (Yurt Dışı)</option>
                            <option value="ic"      @selected(old('shipping_type') === 'ic')>İç (Yurt İçi)</option>
                        </select>
                        @error('shipping_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="destination_country" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Varış Ülkesi <span class="text-xs text-[#555555] font-normal">(opsiyonel)</span>
                        </label>
                        <input type="text" name="destination_country" id="destination_country" maxlength="120"
                               value="{{ old('destination_country', 'Türkiye') }}"
                               placeholder="ör. Almanya"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                    <div>
                        <label for="destination_city" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            Varış Şehri <span class="text-xs text-[#555555] font-normal">(opsiyonel)</span>
                        </label>
                        <input type="text" name="destination_city" id="destination_city" maxlength="120"
                               value="{{ old('destination_city') }}"
                               placeholder="ör. Hamburg"
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="material" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                            İçerik / Malzeme <span class="text-xs text-[#555555] font-normal">(opsiyonel)</span>
                        </label>
                        <input type="text" name="material" id="material" maxlength="120"
                               value="{{ old('material') }}"
                               placeholder="ör. Makine parçası, cam, hassas elektronik..."
                               class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">
                    </div>
                </div>
            </div>

            {{-- ── SECTION 6: Notlar & Gönder ──────────────────────────────── --}}
            <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-[#3E2006] mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-[#3E2006] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">6</span>
                    Ekstra Notlar
                </h2>
                <div>
                    <label for="notes" class="block text-sm font-medium text-[#3E2006] mb-1.5">
                        Ek Bilgi / Özel İstek <span class="text-xs text-[#555555] font-normal">(opsiyonel)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="5" maxlength="4000"
                              placeholder="Özel üretim gereksinimleri, teslimat tarihi, stacking ihtiyacı veya diğer detaylar..."
                              class="w-full px-3 py-2 border border-[#E6DFD2] rounded text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-5 pt-5 border-t border-[#E6DFD2] text-xs text-[#555555] leading-relaxed">
                    Formu göndererek, paylaştığınız bilgilerin CNR Ahşap tarafından yalnızca
                    bu teklif talebinin değerlendirilmesi amacıyla
                    <strong>6698 sayılı KVKK</strong> kapsamında işlenmesini kabul etmiş olursunuz.
                    Verileriniz üçüncü kişilerle paylaşılmaz.
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <p class="text-xs text-[#555555]">
                        <span class="text-red-600">*</span> ile işaretli alanlar zorunludur.
                    </p>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-7 py-3 text-base font-semibold rounded
                                   bg-[#1F497D] hover:bg-[#173a64] text-white transition-colors shadow-md">
                        Hesaplama Talebi Gönder
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>

        </form>

        {{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
        <aside class="space-y-6">

            <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-3">Süreç Nasıl İşler?</h3>
                <ol class="space-y-2 text-[#555555]">
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        Formu doldurun ve gönderin.
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        Uzman ekibimiz talebinizi inceler.
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        En geç 1 iş günü içinde sizinle iletişime geçilir.
                    </li>
                    <li class="flex gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#3E2006] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                        Detaylı, yazılı teklif tarafınıza sunulur.
                    </li>
                </ol>
            </div>

            <div class="bg-white border border-[#E6DFD2] rounded-lg p-5 text-sm">
                <h3 class="font-semibold text-[#3E2006] mb-3">Doğrudan İletişim</h3>
                <ul class="space-y-2 text-[#555555]">
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">Telefon</span>
                        <a href="tel:+902627512120" class="text-[#3E2006] hover:underline">+90 262 751 21 20</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">E-posta</span>
                        <a href="mailto:info@cnrwood.com" class="text-[#3E2006] hover:underline">info@cnrwood.com</a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase text-[#8B5A2B]">Çalışma Saatleri</span>
                        Hafta içi 07:20 – 17:20
                    </li>
                </ul>
            </div>

            <div class="bg-[#1F497D]/5 border border-[#1F497D]/20 rounded-lg p-5 text-sm">
                <p class="text-[#1F497D] font-semibold mb-1">Bu talep ücretsizdir.</p>
                <p class="text-[#555555]">
                    Hesaplama talebi göndermek hiçbir ödeme veya yükümlülük gerektirmez.
                    Teklifi inceleyip karar vermekte tamamen özgürsünüz.
                </p>
            </div>

        </aside>
    </div>

</section>

@endsection
