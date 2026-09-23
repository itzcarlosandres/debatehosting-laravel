<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = \App\Models\Setting::get('siteName', 'Debatehosting');
        $siteUrl = \App\Models\Setting::get('siteUrl', url('/'));
        $defaultTitle = \App\Models\Setting::get('defaultMetaTitle', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones');
        $defaultDesc = \App\Models\Setting::get('defaultMetaDescription', 'Medio editorial y comparador técnico independiente de hosting web, servidores VPS, cloud y cupones verificados sin patrocinios encubiertos.');
        $defaultKeywords = \App\Models\Setting::get('defaultKeywords', 'hosting web, mejor hosting espana, comparativa hosting, vps baratos, cupones hosting, hosting wordpress, test ttfb');
        $faviconUrl = \App\Models\Setting::get('faviconUrl', asset('favicon.png'));
        $ogImageUrl = \App\Models\Setting::get('ogImageUrl', asset('og-image.png'));
        $gscCode = \App\Models\Setting::get('googleSearchConsoleCode', '');
        $gaId = \App\Models\Setting::get('googleAnalyticsId', '');
    @endphp

    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">

    <!-- Open Graph / Redes Sociales (Facebook, WhatsApp, LinkedIn, etc.) -->
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', $defaultTitle))">
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', $defaultDesc))">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', $ogImageUrl)">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', View::yieldContent('title', $defaultTitle))">
    <meta name="twitter:description" content="@yield('og_description', View::yieldContent('meta_description', $defaultDesc))">
    <meta name="twitter:image" content="@yield('og_image', $ogImageUrl)">

    <!-- Google Search Console -->
    @if(!empty($gscCode))
    <meta name="google-site-verification" content="{{ $gscCode }}">
    @endif

    <!-- Google Analytics (GA4) -->
    @if(!empty($gaId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif

    <!-- Favicon dinámico -->
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Schema.org JSON-LD Global: Organización y Sitio Web -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "{{ url('/') }}#organization",
                "name": "{{ $siteName }}",
                "url": "{{ url('/') }}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('logo.png') }}"
                }
            },
            {
                "@type": "WebSite",
                "@id": "{{ url('/') }}#website",
                "url": "{{ url('/') }}",
                "name": "{{ $siteName }}",
                "description": "{{ $defaultDesc }}",
                "publisher": {
                    "@id": "{{ url('/') }}#organization"
                },
                "inLanguage": "es"
            }
        ]
    }
    </script>

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
    <div class="header-top-ribbon">
        <div class="container header-top-inner">
            <div class="header-top-left">
                <span class="top-ticker-pulse">
                    <span class="pulse-dot"></span>
                    RADAR ACTIVO
                </span>
                <span class="top-ticker-sep">|</span>
                <span class="top-ticker-text">
                    {{ $tickerItems->first()->text ?? '14 Proveedores de Hosting bajo auditoría de rendimiento en tiempo real' }}
                </span>
            </div>
            <div class="header-top-right">
                <span class="top-ticker-badge">100% INDEPENDIENTE</span>
                <span class="top-ticker-sep">•</span>
                <span class="top-ticker-date">{{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
            </div>
        </div>
    </div>
    @else
    <div class="header-top-ribbon">
        <div class="container header-top-inner">
            <div class="header-top-left">
                <span class="top-ticker-pulse">
                    <span class="pulse-dot"></span>
                    RADAR ACTIVO
                </span>
                <span class="top-ticker-sep">|</span>
                <span class="top-ticker-text">14 Proveedores de Hosting bajo auditoría de rendimiento en tiempo real</span>
            </div>
            <div class="header-top-right">
                <span class="top-ticker-badge">100% INDEPENDIENTE</span>
                <span class="top-ticker-sep">•</span>
                <span class="top-ticker-date">EDICIÓN {{ date('Y') }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Principal -->
    <header class="site-header">
        <div class="site-header-main">
            <div class="container header-inner">
                <a href="{{ route('home') }}" class="logo-brand" style="display: inline-flex; align-items: center; gap: 0.65rem; text-decoration: none;">
                    <div style="width: 36px; height: 36px; background-color: #0E6B41; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; box-shadow: 0 2px 8px rgba(0,0,0,0.12); flex-shrink: 0;">
                        <i data-lucide="rocket" style="width: 20px; height: 20px;"></i>
                    </div>
                    <span style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 800; letter-spacing: -0.02em; color: var(--text-ink);">
                        Debate<span style="color: #0E6B41;">hosting</span>
                    </span>
                    <span class="editorial" style="margin-left: 0.2rem;">EDITORIAL</span>
                </a>

                <nav class="nav-desktop">
                    <ul class="nav-pill-group">
                        <li>
                            <a href="{{ route('balanza') }}" class="nav-pill-link {{ request()->routeIs('balanza') ? 'active' : '' }}">
                                <span class="nav-pill-icon"><i data-lucide="scale" style="width: 15px; height: 15px;"></i></span>
                                <span class="nav-pill-text">La Balanza</span>
                                <span class="nav-micro-badge nav-badge-accent">AI</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('providers.index') }}" class="nav-pill-link {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                                <span class="nav-pill-icon"><i data-lucide="globe" style="width: 15px; height: 15px;"></i></span>
                                <span class="nav-pill-text">Proveedores</span>
                                <span class="nav-pill-count">14</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupons.index') }}" class="nav-pill-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                                <span class="nav-pill-icon"><i data-lucide="ticket" style="width: 15px; height: 15px;"></i></span>
                                <span class="nav-pill-text">Cupones</span>
                                <span class="nav-micro-badge nav-badge-hot">🔥 -85%</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ofertas') }}" class="nav-pill-link {{ request()->routeIs('ofertas') ? 'active' : '' }}">
                                <span class="nav-pill-icon"><i data-lucide="zap" style="width: 15px; height: 15px;"></i></span>
                                <span class="nav-pill-text">Ofertas</span>
                                <span class="nav-micro-badge nav-badge-live">FLASH</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auditor') }}" class="nav-pill-link {{ request()->routeIs('auditor') ? 'active' : '' }}">
                                <span class="nav-pill-icon"><i data-lucide="shield-check" style="width: 15px; height: 15px;"></i></span>
                                <span class="nav-pill-text">Auditor</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="header-cta-group">
                    <a href="{{ route('balanza') }}" class="header-cta-btn desktop-only">
                        <span>Explorar Ranking</span>
                        <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                    <button class="mobile-menu-btn" onclick="toggleMobileNav()" aria-label="Abrir menú">
                        <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Drawer Móvil -->
        <div id="mobile-nav-backdrop" class="mobile-nav-backdrop" style="display: none;" onclick="toggleMobileNav()">
            <div class="mobile-nav-drawer" onclick="event.stopPropagation()">
                <div class="mobile-nav-header">
                    <div class="mobile-nav-header-left">
                        <span class="pulse-dot"></span>
                        <span class="mobile-nav-kicker">SUMARIO EDITORIAL</span>
                    </div>
                    <button class="mobile-nav-close-btn" onclick="toggleMobileNav()" aria-label="Cerrar menú">
                        <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <div class="mobile-nav-card-list" style="padding: 1.5rem;">
                    <a href="{{ route('balanza') }}" class="mobile-nav-card" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 0; text-decoration: none; color: inherit; border-bottom: 1px solid var(--border-subtle, rgba(0,0,0,0.06));">
                        <i data-lucide="scale" style="width: 18px; height: 18px; color: #0E6B41;"></i>
                        <span style="font-weight: 600;">La Balanza Interactiva</span>
                    </a>
                    <a href="{{ route('providers.index') }}" class="mobile-nav-card" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 0; text-decoration: none; color: inherit; border-bottom: 1px solid var(--border-subtle, rgba(0,0,0,0.06));">
                        <i data-lucide="globe" style="width: 18px; height: 18px; color: #0369A1;"></i>
                        <span style="font-weight: 600;">Directorio de Proveedores</span>
                    </a>
                    <a href="{{ route('coupons.index') }}" class="mobile-nav-card" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 0; text-decoration: none; color: inherit; border-bottom: 1px solid var(--border-subtle, rgba(0,0,0,0.06));">
                        <i data-lucide="ticket" style="width: 18px; height: 18px; color: #DC2626;"></i>
                        <span style="font-weight: 600;">Cupones Verificados</span>
                    </a>
                    <a href="{{ route('ofertas') }}" class="mobile-nav-card" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 0; text-decoration: none; color: inherit;">
                        <i data-lucide="zap" style="width: 18px; height: 18px; color: #D97706;"></i>
                        <span style="font-weight: 600;">Radar de Ofertas</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="site-main">
        @yield('content')
    </main>

    <!-- Footer Editorial -->
    <footer class="site-footer">
        <div class="footer-ticker-bar">
            <div class="container footer-ticker-inner">
                <div class="footer-status-indicator">
                    <span class="footer-pulse-dot"></span>
                    <span class="footer-status-text">
                        OBSERVATORIO EN VIVO — AUDITORÍAS TTFB & MONITORIZACIÓN 24/7
                    </span>
                </div>
                <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="footer-back-to-top" title="Volver al inicio" style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <span>Volver arriba</span>
                    <i data-lucide="chevron-up" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        </div>

        <div class="container footer-main">
            <div class="footer-grid">
                <!-- Columna 1: Marca & Misión -->
                <div class="footer-col-brand">
                    <a href="{{ route('home') }}" class="footer-logo" style="display: inline-flex; align-items: center; gap: 0.65rem; text-decoration: none;">
                        <div style="background-color: #0E6B41; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); color: #fff;">
                            <i data-lucide="rocket" style="width: 20px; height: 20px;"></i>
                        </div>
                        <span style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 800; color: #FAF7EE;">
                            Debate<span style="color: #0E6B41;">hosting</span>
                        </span>
                        <span class="footer-brand-pill">EDITORIAL</span>
                    </a>

                    <p class="footer-brand-desc">
                        Publicación tecnológica independiente especializada en el análisis técnico, benchmarking TTFB y estrés de servidores de hosting en español. Sin patrocinios ocultos ni puestos comprados.
                    </p>

                    <div class="footer-trust-seals">
                        <div class="trust-seal-pill">
                            <span class="seal-check">✓</span>
                            <span>Pruebas 100% Reales</span>
                        </div>
                        <div class="trust-seal-pill">
                            <span class="seal-check">✓</span>
                            <span>Auditoría TTFB Abierta</span>
                        </div>
                        <div class="trust-seal-pill">
                            <span class="seal-check">✓</span>
                            <span>Sin Publicidad Engañosa</span>
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Observatorio -->
                <div class="footer-nav-card">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-wrap">
                            <i data-lucide="scale" style="width: 14px; height: 14px; color: var(--green-primary);"></i>
                        </div>
                        <h5 class="footer-col-title">Observatorio</h5>
                        <span class="footer-col-count">01</span>
                    </div>
                    <ul class="footer-links-list">
                        <li>
                            <a href="{{ route('balanza') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">La Balanza (Calibrador)</span>
                                <span class="footer-pill-tag">AI PRO</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auditor') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Auditor de Servidores</span>
                                <span class="footer-mini-badge pulse-badge">LIVE</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('providers.index') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Directorio de Proveedores</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupons.index') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Cupones Verificados</span>
                                <span class="footer-pill-tag tag-green">HOT</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ofertas') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Radar de Ofertas</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Infraestructura -->
                <div class="footer-nav-card">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-wrap">
                            <i data-lucide="server" style="width: 14px; height: 14px; color: var(--green-primary);"></i>
                        </div>
                        <h5 class="footer-col-title">Infraestructura</h5>
                        <span class="footer-col-count">02</span>
                    </div>
                    <ul class="footer-links-list">
                        <li>
                            <a href="{{ route('ofertas', ['categoria' => 'hosting']) }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Hosting WordPress</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ofertas', ['categoria' => 'vps']) }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Servidores VPS NVMe</span>
                                <span class="footer-pill-tag">NVMe</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ofertas', ['categoria' => 'cloud']) }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Cloud de Rendimiento</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('ofertas', ['categoria' => 'dedicado']) }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Servidores Dedicados</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupons.index') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Descuentos Activos</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 4: Transparencia -->
                <div class="footer-nav-card">
                    <div class="footer-card-header">
                        <div class="footer-card-icon-wrap">
                            <i data-lucide="shield" style="width: 14px; height: 14px; color: var(--green-primary);"></i>
                        </div>
                        <h5 class="footer-col-title">Transparencia</h5>
                        <span class="footer-col-count">03</span>
                    </div>
                    <ul class="footer-links-list">
                        <li>
                            <a href="{{ route('metodo') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Metodología Editorial</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('afiliados') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Aviso de Afiliación</span>
                                <span class="footer-pill-tag">ÉTICA</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terminos') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Términos de Servicio</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacidad') }}" class="footer-link-row">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Política de Privacidad</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.login') }}" class="footer-link-row footer-admin-link">
                                <span class="link-arrow">→</span>
                                <span class="link-text">Consola Editorial</span>
                                <i data-lucide="lock" style="width: 12px; height: 12px;"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divulgación y Transparencia -->
            <div class="footer-disclosure-box">
                <div class="disclosure-badge">
                    <i data-lucide="shield" style="width: 14px; height: 14px; color: var(--green-primary);"></i>
                    <span>TRANSPARENCIA FINANCIERA & AVISO LEGAL</span>
                </div>
                <p class="disclosure-text">
                    Debatehosting es un medio tecnológico financiado mediante enlaces de afiliación regulados. Al adquirir un plan a través de nuestros enlaces podemos percibir una comisión de referencia que costea nuestros servidores de prueba, proxies de latencia y herramientas de auditoría continua, sin que suponga sobrecoste alguno para el usuario. Nuestras valoraciones, clasificaciones de velocidad y veredictos del podio se generan de manera estrictamente independiente.
                </p>
            </div>

            <!-- Colofón Inferior -->
            <div class="footer-bottom-bar">
                <div class="colophon-left">
                    <span>© {{ date('Y') }} <strong>Debatehosting</strong>. Todos los derechos reservados.</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script Global -->
    <script>
        lucide.createIcons();

        function toggleMobileNav() {
            const el = document.getElementById('mobile-nav-backdrop');
            if (el) {
                el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
