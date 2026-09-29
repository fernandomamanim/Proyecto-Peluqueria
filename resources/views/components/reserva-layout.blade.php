<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Peluquería') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="{{ url('/') }}" class="font-semibold text-lg text-gray-800">
                        {{ config('app.name', 'Peluquería') }}
                    </a>
                    <div class="space-x-4 text-sm">
                        @auth
                            @if (auth()->user()->esCliente())
                                <a href="{{ route('cliente.dashboard') }}" class="text-gray-600 hover:text-gray-900">Mis reservas</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>