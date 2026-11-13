@php
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle !== '' ? $pageTitle.' — PALERMOCRAFT' : 'PALERMOCRAFT — Ехо Безодні';
    $description = trim($__env->yieldContent('description')) ?: 'PALERMOCRAFT — український сюжетний SMP у світі темного фентезі. Сезон I «Ехо Безодні»: поселення, підземелля, Повелителі й дванадцять Очей Безодні. Minecraft Java '.config('palermo.game_version').', без модів.';
@endphp
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#08070c">
    @hasSection('noindex')
        <meta name="robots" content="noindex">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="PALERMOCRAFT">
    <meta property="og:locale" content="uk_UA">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preload" href="{{ asset('fonts/inter-cyrillic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/cormorant-garamond-cyrillic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ \App\Support\Assets::url('css/palermo.css') }}">
    <script>document.documentElement.classList.add('js');</script>
    <script src="{{ \App\Support\Assets::url('js/palermo.js') }}" defer></script>
    @stack('head')
</head>
<body class="@yield('body-class')">
    <a class="skip-link" href="#main">Перейти до вмісту</a>

    @include('partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
