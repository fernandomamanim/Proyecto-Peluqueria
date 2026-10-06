<!DOCTYPE html>
<html lang="es">
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

    @isset($header)
        <header class="max-w-2xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h2 class="titulo-seccion" style="font-size: 1.8rem;">{{ $header }}</h2>
        </header>
    @endisset

    <main>
        {{ $slot }}
    </main>

    @include('partials.footer-marca')
</body>
</html>