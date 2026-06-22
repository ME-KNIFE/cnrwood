@extends('layouts.public')

@php
    /** @var \App\Models\Order|null $order */
    $title = __('order.title') . ' — CNRWOOD';
@endphp

@section('content')

<section class="bg-[#F5F0E8] border-b border-[#E6DFD2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="text-sm text-[#8B5A2B]">
            <a href="{{ route('home') }}" class="hover:underline">{{ __('breadcrumb.home') }}</a>
            <span class="mx-1">/</span>
            <span class="text-[#3E2006]">{{ __('breadcrumb.order_confirm') }}</span>
        </nav>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#2C5F2E]/10 mb-6">
        <svg class="w-10 h-10 text-[#2C5F2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-[#3E2006] mb-3">{{ __('order.title') }}</h1>

    @if ($order)

        <p class="text-[#555555] mb-8 leading-relaxed">
            Teşekkürler, <strong class="text-[#3E2006]">{{ $order->customer_name }}</strong>.
            {{ __('order.success_body') }}
        </p>

        <div class="bg-white border border-[#E6DFD2] rounded-lg p-6 text-left mb-8">

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">{{ __('order.number') }}</span>
                <span class="font-mono font-bold text-[#3E2006]">{{ $order->order_number }}</span>
            </div>

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">{{ __('order.status') }}</span>
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#555555] bg-[#F5F0E8] px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    {{ __('order.pending') }}
                </span>
            </div>

            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#E6DFD2]">
                <span class="text-sm text-[#555555]">{{ __('order.total_amount') }}</span>
                <span class="font-bold text-[#3E2006]">
                    {{ number_format($order->total, 2, ',', '.') }} TL
                </span>
            </div>

            <div class="flex justify-between items-start">
                <span class="text-sm text-[#555555]">{{ __('order.confirm_email') }}</span>
                <span class="text-sm text-[#3E2006]">{{ $order->customer_email }}</span>
            </div>

        </div>

        {{-- EFT payment instructions --}}
        <div class="bg-[#F5F0E8] border border-[#E6DFD2] rounded-lg p-6 text-left mb-8">
            <h2 class="text-base font-bold text-[#3E2006] mb-3">{{ __('order.eft_title') }}</h2>
            <p class="text-sm text-[#555555] mb-4">
                {{ __('order.eft_body') }}
            </p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#555555] font-medium">{{ __('order.bank_label') }}</span>
                    <span class="text-[#3E2006]">{{ __('order.bank_contact') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#555555] font-medium">{{ __('order.description_label') }}</span>
                    <span class="font-mono text-[#3E2006]">{{ $order->order_number }}</span>
                </div>
            </div>
            <p class="text-xs text-[#555555] mt-4">
                {{ __('order.eft_note') }} <strong>{{ $order->order_number }}</strong>
            </p>
        </div>

    @else

        <p class="text-[#555555] mb-8 leading-relaxed">
            {{ __('order.no_order_text') }}
        </p>

    @endif

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('public.products') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded
                  bg-[#3E2006] text-white hover:bg-[#6B3A1F] transition-colors">
            {{ __('order.continue_shopping') }}
        </a>
        <a href="{{ route('public.contact') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded
                  border border-[#E6DFD2] text-[#3E2006] bg-white hover:bg-[#F5F0E8] transition-colors">
            {{ __('order.contact_us') }}
        </a>
    </div>

</section>

@endsection
