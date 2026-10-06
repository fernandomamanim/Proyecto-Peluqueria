<nav class="navbar-marca">
    <div class="contenedor-marca">
        <a href="http://127.0.0.1:8000/" class="nombre marca-titulo">La Guarida</a>
        <div class="enlaces">
            @auth
                <a href="{{ auth()->user()->esAdministrador() ? route('admin.dashboard') : (auth()->user()->esStaff() ? route('staff.dashboard') : route('cliente.dashboard')) }}">Mi cuenta</a>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
            <a href="{{ route('reservas.create') }}" class="boton">Reservar</a>
        </div>
    </div>
</nav>