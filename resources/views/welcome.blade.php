<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La Guarida Barber Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&family=Bebas+Neue&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/marca.css') }}">
    <style>
        /* Estilos específicos de esta página (hero, ticket, equipo) */
        .hero { text-align: center; padding: 5rem 1.5rem 4rem; border-bottom: 1px solid var(--linea); }
        .hero img.logo { width: 150px; height: auto; margin-bottom: 1.5rem; filter: drop-shadow(0 0 22px rgba(163,32,38,0.25)); }
        .hero h1 { font-family: 'Pirata One', cursive; font-size: clamp(2.8rem, 9vw, 5rem); margin: 0; color: var(--hueso); }
        .hero .subtitulo { font-family: 'Bebas Neue', sans-serif; letter-spacing: 0.08em; font-size: clamp(1rem, 2.5vw, 1.3rem); color: var(--rojo-brillante); margin: 0.4rem 0 1.6rem; }
        .hero p.desc { max-width: 480px; margin: 0 auto 2rem; color: var(--hueso-tenue); font-size: 0.98rem; }
        section { padding: 4.5rem 0; }
        section.alterna { background: rgba(255,255,255,0.02); border-top: 1px solid var(--linea); border-bottom: 1px solid var(--linea); }
        .ticket { max-width: 620px; margin: 2.5rem auto 0; border-top: 2px solid var(--rojo); border-bottom: 2px solid var(--rojo); padding: 0.5rem 0; }
        .fila-precio { display: flex; justify-content: space-between; gap: 1rem; padding: 0.85rem 0.5rem; border-bottom: 1px dashed var(--linea); }
        .fila-precio:last-child { border-bottom: none; }
        .fila-precio .precio { font-family: 'Bebas Neue', sans-serif; color: var(--rojo-brillante); letter-spacing: 0.03em; white-space: nowrap; }
        .nota-precios { max-width: 620px; margin: 1.5rem auto 0; font-size: 0.85rem; color: var(--hueso-tenue); }
        .nota-precios p { margin: 0.2rem 0; }
        .equipo { display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-top: 2.5rem; }
        @media (max-width: 640px) { .equipo { grid-template-columns: 1fr; } }
        .persona { text-align: center; }
        .persona img { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 3px solid var(--rojo); margin-bottom: 1rem; }
        .persona h3 { font-family: 'Bebas Neue', sans-serif; font-size: 1.5rem; letter-spacing: 0.05em; margin: 0 0 0.5rem; }
        .persona p { color: var(--hueso-tenue); font-size: 0.92rem; max-width: 300px; margin: 0 auto; }
    </style>
</head>
<body class="pagina-marca">

    @include('partials.nav-marca')

    <div class="hero">
        <img src="{{ asset('images/logo-la-guarida.png') }}" alt="La Guarida Barber Studio" class="logo">
        <h1>La Guarida</h1>
        <p class="subtitulo">Barber Studio</p>
        <p class="desc">
            Cortes con carácter, rituales de barba y una experiencia hecha para quien no se conforma
            con lo de siempre. Sin necesidad de crear una cuenta.
        </p>
        <a href="{{ route('reservas.create') }}" class="cta">Reservar mi cita</a>
    </div>

    <section>
        <div class="contenedor" style="text-align:center;">
            <h2 class="titulo-seccion">Precios y Servicios</h2>

            <div class="ticket">
                <div class="fila-precio"><span class="servicio">Corte de Cabello</span><span class="precio">60 Bs</span></div>
                <div class="fila-precio"><span class="servicio">Corte + Asesoramiento</span><span class="precio">70 Bs</span></div>
                <div class="fila-precio"><span class="servicio">Barberia Especial</span><span class="precio">140 Bs</span></div>
                <div class="fila-precio"><span class="servicio">Cejas</span><span class="precio">10 Bs</span></div>
                <div class="fila-precio"><span class="servicio">Solo lavado y peinado</span><span class="precio">10 Bs</span></div>
                <div class="fila-precio"><span class="servicio">Tintes</span><span class="precio">A cotización</span></div>
                <div class="fila-precio"><span class="servicio">Ondulaciones</span><span class="precio">A cotización</span></div>
                <div class="fila-precio"><span class="servicio">Alisado</span><span class="precio">A cotización</span></div>
            </div>

            <div class="nota-precios">
                <p>El corte incluye lavado y peinado con productos de primera calidad.</p>
                <p>El servicio de Barba incluye vaporizador, toalla caliente, espuma y máscara led.</p>
            </div>
        </div>
    </section>

    <section class="alterna">
        <div class="contenedor" style="text-align:center;">
            <h2 class="titulo-seccion">Conócenos</h2>

            <div class="equipo">
                <div class="persona">
                    <img src="{{ asset('images/carlos.png') }}" alt="Carlos">
                    <h3>Carlos</h3>
                    <p>
                        Soy dueño de 4 barberías y 1 academia de barberos: La Guarida Barber Studio
                        (2 sucursales), Mr Flow Barber Studio, La Guarida Kids y la academia formadora
                        de barberos El Club del Barbero. Me destaco en cortes con mucha textura, como
                        el mod cut o cabellos largos. Te ofrezco un servicio VIP.
                    </p>
                </div>
                <div class="persona">
                    <img src="{{ asset('images/andres.png') }}" alt="Andrés">
                    <h3>Andrés</h3>
                    <p>
                        Me apasiona el mundo de la barbería y disfruto crear estilos que reflejen la
                        personalidad de cada cliente. Me especializo en cortes como el Taper Fade,
                        Middle Part, y también en tintes. Agenda conmigo y vive una experiencia única,
                        donde cada detalle cuenta.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section style="text-align:center;">
        <div class="contenedor">
            <h2 class="titulo-seccion">¿Listo para tu próximo corte?</h2>
            <p class="desc" style="margin-bottom:2rem;">Reserva en un par de minutos, con o sin cuenta.</p>
            <a href="{{ route('reservas.create') }}" class="cta">Reservar mi cita</a>
        </div>
    </section>

    @include('partials.footer-marca')

</body>
</html>
