<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'La Guarida') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&family=Bebas+Neue&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/marca.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tema-oscuro font-sans antialiased">
    @include('partials.nav-marca')

    <div class="min-h-screen flex flex-col items-center pt-10 pb-10">
        <a href="{{ route('reservas.create') }}" class="marca-titulo" style="font-size: 2.2rem; color: var(--hueso); text-decoration: none; margin-bottom: 1.5rem;">
            La Guarida
        </a>

        <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    @include('partials.footer-marca')
</body>
</html>