<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $defaultTitle = 'CNRWOOD — Ahşap Sandık, Ambalaj, Kereste ve Ahşap Yapı Çözümleri';
        $defaultDesc  = 'Gebze merkezli CNR Ahşap; ihracat sandıkları, ISPM 15 ısıl işlemli ambalaj, kapı sereni, kereste & levha ve ahşap yapı projelerinde 1998’den beri profesyonel çözüm sunar.';
    @endphp

    <title>{{ $title ?? $defaultTitle }}</title>
    <meta name="description" content="{{ $metaDescription ?? $defaultDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? $defaultTitle }}">
    <meta property="og:description" content="{{ $metaDescription ?? $defaultDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="CNRWOOD">
    <meta property="og:locale" content="tr_TR">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFCFA] text-[#1A1A1A] antialiased flex flex-col min-h-screen">

    @include('partials.public-header')

    <main class="flex-grow">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.public-footer')

</body>
</html>
