<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones')</title>
    <meta name="description" content="@yield('meta_description', 'Medio editorial y comparador técnico independiente de hosting web, servidores VPS, cloud y cupones verificados sin patrocinios encubiertos.')">

    <!-- Tipografías de Imprenta Editorial -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..900;1,9..144,400..900&family=IBM+Plex+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos Editoriales DebateHosting -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body>
    <!-- Ticker Superior -->
    @if(isset($tickerItems) && $tickerItems->count() > 0)
    <div class="top-bar">
        <div class="top-bar-inner">
            <div class="top-bar-badge">
                <span class="pulse-dot"></span> EN VIVO
            </div>
            <div class="ticker-wrapper" style="overflow: hidden; white-space: nowrap;">
                <div class="ticker-text" style="display: inline-block;">
                    @foreach($tickerItems as $item)
                        <span style="margin-right: 2rem;">{{ $item->text }}</span>
                    @endforeach
                </div>
            </div>
            <div class="top-bar-date">
                {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
            </div>
        </div>
    </div>
    @endif

    <!-- Header Principal -->
    <header class="site-header">
        <div class="site-header-container">
            <div class="site-header-left">
                <a href="{{ route('home') }}" class="site-logo">
                    <span class="logo-badge">DH</span>
                    <span class="logo-text">
                        <strong>Debate</strong><span>hosting</span>
                    </span>
                </a>
            </div>

            <nav class="site-nav">
                <ul class="nav-list">
                    <li><a href="{{ route('providers.index') }}" class="{{ request()->routeIs('providers.*') ? 'active' : '' }}">Proveedores</a></li>
                    <li><a href="{{ route('coupons.index') }}" class="{{ request()->routeIs('coupons.*') ? 'active' : '' }}">Cupones</a></li>
                    <li><a href="{{ route('balanza') }}" class="{{ request()->routeIs('balanza') ? 'active' : '' }}">La Balanza</a></li>
                    <li><a href="{{ route('auditor') }}" class="{{ request()->routeIs('auditor') ? 'active' : '' }}">Auditor</a></li>
                    <li><a href="{{ route('ofertas') }}" class="{{ request()->routeIs('ofertas') ? 'active' : '' }}">Ofertas</a></li>
                </ul>
            </nav>

            <div class="site-header-right">
                <a href="{{ route('balanza') }}" class="btn-primary-editorial" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                    ⚖️ Comparar Ahora
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="site-main">
        @yield('content')
    </main>

    <!-- Footer Editorial -->
    <footer class="site-footer">
        <div class="site-footer-top">
            <div class="footer-col-brand">
                <div class="site-logo" style="margin-bottom: 1rem;">
                    <span class="logo-badge">DH</span>
                    <span class="logo-text"><strong>Debate</strong><span>hosting</span></span>
                </div>
                <p class="footer-desc">
                    El gran observatorio editorial independiente de hosting web, servidores VPS y servicios cloud. Métricas reales, auditorías sin censura y cupones verificados.
                </p>
                <div class="footer-disclosure" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem; border-top: 1px dashed var(--border-ink); padding-top: 0.75rem;">
                    <strong>Aviso de Transparencia:</strong> Debatehosting se financia mediante enlaces de afiliados regulados. Esto nunca altera nuestras puntuaciones ni la objetividad editorial.
                </div>
            </div>

            <div class="footer-col-links">
                <h4>Observatorio</h4>
                <ul>
                    <li><a href="{{ route('providers.index') }}">Directorio Completo</a></li>
                    <li><a href="{{ route('balanza') }}">La Balanza (Comparador)</a></li>
                    <li><a href="{{ route('coupons.index') }}">Cupones de Descuento</a></li>
                    <li><a href="{{ route('auditor') }}">Auditor de Dominios</a></li>
                    <li><a href="{{ route('ofertas') }}">Ofertas Especiales</a></li>
                </ul>
            </div>

            <div class="footer-col-links">
                <h4>Metodología</h4>
                <ul>
                    <li><a href="{{ route('metodo') }}">Criterios de Evaluación</a></li>
                    <li><a href="{{ route('afiliados') }}">Aviso de Afiliación</a></li>
                    <li><a href="{{ route('privacidad') }}">Política de Privacidad</a></li>
                    <li><a href="{{ route('terminos') }}">Términos de Servicio</a></li>
                    <li><a href="{{ route('admin.login') }}">Acceso Redacción</a></li>
                </ul>
            </div>

            <div class="footer-col-newsletter">
                <h4>Boletín Semanal</h4>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Recibe cada lunes auditorías de caídas de servidores y nuevos cupones verificados.
                </p>
                <form id="newsletter-form" class="newsletter-form" onsubmit="handleNewsletter(event)">
                    <input type="email" id="nl-email" placeholder="tu@email.com" required style="padding: 0.5rem; border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); width: 100%; margin-bottom: 0.5rem;">
                    <button type="submit" class="btn-primary-editorial" style="width: 100%; justify-content: center;">Suscribirme</button>
                    <div id="nl-msg" style="display: none; font-size: 0.8rem; margin-top: 0.5rem;"></div>
                </form>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div>
                © {{ date('Y') }} Debatehosting.com — Todos los derechos reservados.
            </div>
            <div>
                Hecho para desarrolladores, webmasters y dueños de proyectos.
            </div>
        </div>
    </footer>

    <!-- Script Global -->
    <script>
        lucide.createIcons();

        function handleNewsletter(e) {
            e.preventDefault();
            const email = document.getElementById('nl-email').value;
            const msg = document.getElementById('nl-msg');
            
            fetch('{{ route("api.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(r => r.json())
            .then(data => {
                msg.style.display = 'block';
                msg.style.color = '#0E6B41';
                msg.innerText = data.message || '¡Suscrito con éxito!';
                document.getElementById('nl-email').value = '';
            })
            .catch(() => {
                msg.style.display = 'block';
                msg.style.color = '#B03A26';
                msg.innerText = 'Error al suscribirse. Inténtalo de nuevo.';
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
