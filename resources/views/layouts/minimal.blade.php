{{-- Для сторінок помилок і обслуговування: без бази, сесій і зовнішніх запитів --}}
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — PALERMOCRAFT</title>
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#08070c">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ \App\Support\Assets::url('css/palermo.css') }}">
    @stack('head')
</head>
<body>
    <header class="minimal-header">
        <a href="{{ url('/') }}" class="brand" aria-label="PALERMOCRAFT — на головну">
            @include('partials.mark')
            <span class="brand__name">PALERMOCRAFT</span>
        </a>
    </header>
    <main id="main" class="error-page">
        <div class="error-page__inner">
            @yield('content')
        </div>
    </main>
</body>
</html>
