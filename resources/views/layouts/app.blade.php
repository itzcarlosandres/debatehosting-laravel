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
        "{{ '@context' }}": "https://schema.org",
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

    <!-- Tipografías: Plus Jakarta Sans & IBM Plex Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Minimalistas DebateHosting -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">

    <!-- Iconos Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    {!! \App\Models\Setting::get('customHeadCode', '') !!}

    @stack('styles')
</head>
<body>
    <!-- Header General -->
    <header class="site-header">
        @if(\App\Models\Setting::get('showTopBar', true))
        <!-- Barra Superior / Ribbon de Telemetría -->
        <div class="header-top-ribbon">
            <div class="container header-top-inner">
                <div class="top-ticker-left">
                    <span class="top-pulse-tag">
                        <span class="pulse-dot-anim"></span>
                        {{ \App\Models\Setting::get('topBarBadge', 'RADAR ACTIVO') }}
                    </span>
                    <span style="opacity: 0.3;">|</span>
                    <span style="color: #CBD5E1;">
                        {{ \App\Models\Setting::get('topBarText', \App\Models\Provider::where('active', true)->count() . ' Proveedores de Hosting bajo auditoría continua. Pruebas de velocidad en tiempo real.') }}
                    </span>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="color: #94A3B8;">{{ \App\Models\Setting::get('topBarRightBadge', '100% INDEPENDIENTE') }}</span>
                    <span style="opacity: 0.3;">•</span>
                    <span style="color: #94A3B8;">{{ \App\Models\Setting::get('topBarRightText', 'EDICIÓN ' . date('Y')) }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Barra Principal de Navegación -->
        <div class="container header-main-bar">
            <!-- Logotipo -->
            @php
                $logoType = \App\Models\Setting::get('logoType', 'icon_text');
                $logoIcon = \App\Models\Setting::get('logoIcon', 'server');
                $logoPrefix = \App\Models\Setting::get('logoTextPrefix', 'Debate');
                $logoHighlight = \App\Models\Setting::get('logoTextHighlight', 'hosting');
                $logoColor = \App\Models\Setting::get('logoColor', '#0E6B41');
                $logoUrl = \App\Models\Setting::get('logoUrl', '/logo.png');
            @endphp

            <a href="{{ route('home') }}" class="brand-logo">
                @if($logoType === 'image' && $logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $logoPrefix }}{{ $logoHighlight }}" style="height: 32px; object-fit: contain;">
                @elseif($logoType === 'text_only')
                    <div class="brand-logo-text" style="font-size: 1.35rem; font-weight: 800; color: #FFFFFF;">
                        {{ $logoPrefix }}<span style="color: {{ $logoColor }};">{{ $logoHighlight }}</span>
                    </div>
                    <span class="brand-logo-badge">EDITORIAL</span>
                @else
                    <div class="brand-logo-icon" style="background: {{ $logoColor }}; border-color: rgba(255,255,255,0.15);">
                        <i data-lucide="{{ $logoIcon }}" style="width: 18px; height: 18px;"></i>
                    </div>
                    <div class="brand-logo-text">
                        {{ $logoPrefix }}<span>{{ $logoHighlight }}</span>
                    </div>
                    <span class="brand-logo-badge">EDITORIAL</span>
                @endif
            </a>

            <!-- Menú Desktop -->
            <nav class="nav-desktop">
                <ul class="nav-desktop-list">
                    <li>
                        <a href="{{ route('providers.index') }}" class="nav-desktop-link {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                            <i data-lucide="server" style="width: 15px; height: 15px;"></i>
                            <span>Proveedores</span>
                            <span class="nav-counter-badge">{{ \App\Models\Provider::count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reviews.index') }}" class="nav-desktop-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                            <i data-lucide="file-text" style="width: 15px; height: 15px;"></i>
                            <span>Reseñas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('coupons.index') }}" class="nav-desktop-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                            <i data-lucide="ticket" style="width: 15px; height: 15px;"></i>
                            <span>Cupones</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ofertas') }}" class="nav-desktop-link {{ request()->routeIs('ofertas') ? 'active' : '' }}">
                            <i data-lucide="zap" style="width: 15px; height: 15px;"></i>
                            <span>Ofertas</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Acciones y Móvil -->
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <!-- Botón Hamburguesa Móvil -->
                <button class="mobile-menu-btn" onclick="toggleMobileNav()" aria-label="Abrir menú de navegación">
                    <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
        </div>

        <!-- Drawer Menú Móvil -->
        <div id="mobile-drawer" class="mobile-nav-drawer">
            <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="home" style="width: 18px; height: 18px;"></i> Inicio</span>
                <span>→</span>
            </a>
            <a href="{{ route('providers.index') }}" class="mobile-nav-item {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="server" style="width: 18px; height: 18px;"></i> Proveedores ({{ \App\Models\Provider::count() }})</span>
                <span>→</span>
            </a>
            <a href="{{ route('reviews.index') }}" class="mobile-nav-item {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="file-text" style="width: 18px; height: 18px;"></i> Reseñas & Análisis</span>
                <span>→</span>
            </a>
            <a href="{{ route('coupons.index') }}" class="mobile-nav-item {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="ticket" style="width: 18px; height: 18px;"></i> Cupones Verificados</span>
                <span>→</span>
            </a>
            <a href="{{ route('ofertas') }}" class="mobile-nav-item {{ request()->routeIs('ofertas') ? 'active' : '' }}">
                <span style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="zap" style="width: 18px; height: 18px;"></i> Radar de Ofertas</span>
                <span>→</span>
            </a>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Corporativo y Observatorio Técnico -->
    <footer class="site-clean-footer">
        <div class="container">
            <div class="footer-grid-4col">
                <!-- Columna 1: Marca & Misión Editorial -->
                <div class="footer-col-brand">
                    <a href="{{ route('home') }}" class="footer-brand-link">
                        <div class="brand-logo-icon">
                            <i data-lucide="rocket" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div class="brand-logo-text" style="color: #FFFFFF;">
                            Debate<span style="color: var(--emerald-primary);">hosting</span>
                        </div>
                        <span class="footer-brand-pill">OBSERVATORIO</span>
                    </a>
                    <p class="footer-brand-desc">
                        Observatorio técnico independiente especializado en benchmarking de latencia TTFB, estabilidad real y análisis de hosting web en español. Sin sesgos comerciales ni puestos comprados.
                    </p>
                    <div class="footer-trust-chips">
                        <div class="footer-trust-chip">
                            <i data-lucide="shield-check" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                            <span>Auditorías 100% Reales</span>
                        </div>
                        <div class="footer-trust-chip">
                            <i data-lucide="activity" style="width: 14px; height: 14px; color: var(--sky-primary);"></i>
                            <span>TTFB Continuo 24/7</span>
                        </div>
                        <div class="footer-trust-chip">
                            <i data-lucide="lock" style="width: 14px; height: 14px; color: #F59E0B;"></i>
                            <span>Cero Puestos Vendidos</span>
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Observatorio & Benchmark -->
                <div>
                    <h4 class="footer-col-title">
                        <i data-lucide="cpu" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                        <span>Observatorio</span>
                    </h4>
                    <ul class="footer-links-group">
                        <li>
                            <a href="{{ route('balanza') }}" class="footer-nav-link">
                                <span>La Balanza de Prioridades</span>
                                <span class="footer-link-badge badge-emerald">Herramienta</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auditor') }}" class="footer-nav-link">
                                <span>Auditor TTFB en Vivo</span>
                                <span class="footer-link-badge badge-sky"><span class="pulse-beacon-sky"></span> Live</span>
                            </a>
                        </li>
                        <li><a href="{{ route('providers.index') }}" class="footer-nav-link">Directorio de Proveedores</a></li>
                        <li><a href="{{ route('reviews.index') }}" class="footer-nav-link">Reseñas y Análisis a Fondo</a></li>
                        <li><a href="{{ route('coupons.index') }}" class="footer-nav-link">Cupones Verificados</a></li>
                        <li><a href="{{ route('ofertas') }}" class="footer-nav-link">Radar de Ofertas Flash</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Categorías Especializadas -->
                <div>
                    <h4 class="footer-col-title">
                        <i data-lucide="layers" style="width: 14px; height: 14px; color: var(--sky-primary);"></i>
                        <span>Categorías</span>
                    </h4>
                    <ul class="footer-links-group">
                        <li><a href="{{ route('ofertas') }}?categoria=wordpress" class="footer-nav-link">Hosting WordPress</a></li>
                        <li><a href="{{ route('ofertas') }}?categoria=vps" class="footer-nav-link">Servidores VPS NVMe</a></li>
                        <li><a href="{{ route('ofertas') }}?categoria=cloud" class="footer-nav-link">Cloud Hosting</a></li>
                        <li><a href="{{ route('ofertas') }}?categoria=dominios" class="footer-nav-link">Registro de Dominios</a></li>
                        <li><a href="{{ route('coupons.index') }}" class="footer-nav-link">Códigos Promocionales</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Transparencia & Legal -->
                <div>
                    <h4 class="footer-col-title">
                        <i data-lucide="shield" style="width: 14px; height: 14px; color: #94A3B8;"></i>
                        <span>Transparencia</span>
                    </h4>
                    <ul class="footer-links-group">
                        <li><a href="{{ route('metodo') }}" class="footer-nav-link">Metodología Editorial</a></li>
                        <li><a href="{{ route('afiliados') }}" class="footer-nav-link">Aviso de Afiliación</a></li>
                        <li><a href="{{ route('terminos') }}" class="footer-nav-link">Términos de Servicio</a></li>
                        <li><a href="{{ route('privacidad') }}" class="footer-nav-link">Política de Privacidad</a></li>
                        <li>
                            <a href="{{ route('admin.login') }}" class="footer-nav-link footer-nav-admin">
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <i data-lucide="lock" style="width: 12px; height: 12px;"></i> Consola Editorial
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Barra Inferior -->
            <div class="footer-bottom-row">
                <div class="footer-bottom-left">
                    © {{ date('Y') }} <strong>DebateHosting</strong>. Observatorio técnico de libre acceso para desarrolladores y webmasters.
                </div>
                <div class="footer-bottom-right">
                    <div class="footer-monitor-pill">
                        <span class="pulse-beacon-emerald"></span>
                        <span>Sistemas de Auditoría Operativos • 182ms TTFB Promedio</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Contenedor Flotante de Toasts -->
    <div id="toast-container" class="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        // Toggle Menú Móvil
        function toggleMobileNav() {
            const drawer = document.getElementById('mobile-drawer');
            drawer.classList.toggle('open');
        }

        // Sistema de Notificaciones Flotantes Modernas (Toasts)
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast-clean';

            const iconName = type === 'success' ? 'check-circle-2' : 'info';
            const iconColor = type === 'success' ? 'var(--emerald-primary)' : 'var(--sky-primary)';

            toast.innerHTML = `
                <i data-lucide="${iconName}" style="width: 16px; height: 16px; color: ${iconColor}; flex-shrink: 0;"></i>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            if (window.lucide) lucide.createIcons();

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3200);
        };
    </script>
    @stack('scripts')
</body>
</html>
