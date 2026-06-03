<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PalermoCraft - Minecraft Сервер')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'palermo-green': '#39ff14',
                        'palermo-dark': '#121212',
                        'palermo-card': '#222222',
                    },
                    fontFamily: {
                        sans: ['Comfortaa', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #121212;
            color: white;
        }

        .border-neon {
            border-color: #39ff14;
        }
    </style>
    @stack('stylesheets')
</head>
<body class="flex flex-col w-full min-h-screen bg-palermo-dark text-white">

@include('partials.header')

@yield('content')

@include('partials.footer')

@stack('javascripts')
</body>
</html>
