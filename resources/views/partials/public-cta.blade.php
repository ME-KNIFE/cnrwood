{{-- Reusable bottom CTA band: Teklif Al (Steel Blue) + İletişim (Brown outline) --}}
<section class="bg-gradient-to-br from-[#3E2006] to-[#6B3A1F] text-[#F5F0E8] mt-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3">{{ $ctaTitle ?? __('cta.default_title') }}</h2>
        <p class="text-[#F5F0E8]/85 max-w-2xl mx-auto leading-relaxed mb-8">
            {{ $ctaText ?? __('cta.default_body') }}
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('public.quote.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold rounded
                      text-white bg-[#1F497D] hover:bg-[#173a64] transition-colors">
                {{ __('nav.quote') }}
            </a>
            <a href="{{ route('public.contact') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold rounded
                      border border-[#F5F0E8]/40 text-[#F5F0E8] hover:bg-[#F5F0E8]/10 transition-colors">
                {{ __('cta.contact_btn') }}
            </a>
        </div>
    </div>
</section>
