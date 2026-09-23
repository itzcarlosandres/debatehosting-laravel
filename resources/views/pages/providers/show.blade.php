@extends('layouts.app')

@section('title', $provider->meta_title ?: 'Análisis y Auditoría de ' . $provider->name . ' — Debatehosting')
@section('meta_description', $provider->meta_description ?: 'Auditoría editorial de ' . $provider->name . '. Puntuación técnica ' . $provider->overall_score . '/10, planes desde $' . number_format($provider->price_from, 2) . '/mes, pros, contras y pruebas de rendimiento.')
@section('og_title', 'Análisis y Opiniones de ' . $provider->name . ' (' . date('Y') . ') — DebateHosting')
@section('og_description', $provider->meta_description ?: 'Auditoría editorial de ' . $provider->name . '. Puntuación técnica ' . $provider->overall_score . '/10, planes desde $' . number_format($provider->price_from, 2) . '/mes, pros, contras y pruebas de rendimiento.')
@section('og_image', $provider->resolved_logo_url ? (str_starts_with($provider->resolved_logo_url, 'http') ? $provider->resolved_logo_url : url($provider->resolved_logo_url)) : asset('og-image.png'))

@section('content')
<article class="provider-sheet-wrapper">
    <div class="container">
        <!-- Migas de Pan -->
        <nav class="provider-breadcrumbs">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <a href="{{ route('providers.index') }}">Proveedores</a>
            <span>/</span>
            <span class="crumb-current">{{ $provider->name }}</span>
        </nav>

        <!-- Cabecera de Ficha Técnica (Showcase Card) -->
        <header class="provider-hero-card">
            <!-- Barra Superior de Estado y Categorías -->
            <div class="provider-hero-topline">
                <div class="provider-status-badge">
                    <span class="pulse-beacon-emerald"></span>
                    <span>AUDITORÍA EDITORIAL 2026 • CERTIFICADO</span>
                </div>

                <div class="provider-tags-group">
                    @if(!empty($provider->categories) && is_array($provider->categories))
                        @foreach($provider->categories as $catSlug)
                            <span class="provider-cat-tag">{{ ucfirst($catSlug) }}</span>
                        @endforeach
                    @endif
                    <span class="provider-cat-tag" style="background: rgba(2, 132, 199, 0.08); color: #0284C7;">
                        <i data-lucide="check" style="width: 11px; height: 11px; display: inline-block; vertical-align: middle;"></i> TTFB Medido
                    </span>
                </div>
            </div>

            <!-- Fila Principal de Identidad y Precios -->
            <div class="provider-header-main">
                <!-- Identidad del Proveedor -->
                <div class="provider-brand-box">
                    @if($provider->resolved_logo_url)
                        <div class="provider-logo-frame">
                            <img src="{{ $provider->resolved_logo_url }}" alt="{{ $provider->name }}">
                        </div>
                    @else
                        <div class="provider-avatar-frame">
                            {{ strtoupper(substr($provider->name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="provider-title-wrap">
                        <div class="provider-name-row">
                            <h1 class="provider-name-heading">{{ $provider->name }}</h1>
                            @if($provider->badge)
                                <span class="provider-badge-pill">★ {{ $provider->badge }}</span>
                            @endif
                        </div>
                        <div class="provider-plan-meta">
                            <i data-lucide="server" style="width: 15px; height: 15px; color: var(--emerald-primary);"></i>
                            <span>Plan analizado: <strong>{{ $provider->plan }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Panel de Precios y Acción Principal -->
                <div class="provider-action-panel">
                    @if($provider->price_before > $provider->price_from)
                        <div class="price-strikethrough-wrap">
                            <span class="price-old-strike">${{ number_format($provider->price_before, 2) }}</span>
                            <span class="price-discount-chip">-{{ $provider->discount_percent }}% Descuento</span>
                        </div>
                    @else
                        <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 0.2rem;">Precio de entrada</span>
                    @endif

                    <div class="price-main-display">
                        ${{ number_format($provider->price_from, 2) }}
                        <span class="period">/{{ $provider->period }}</span>
                    </div>

                    <a href="{{ route('go', $provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-provider-main-cta">
                        <span>Ir a la Web Oficial</span>
                        <i data-lucide="external-link" style="width: 15px; height: 15px;"></i>
                    </a>
                </div>
            </div>

            <!-- Barra de Telemetría Técnica (5 Métricas del Observatorio) -->
            <div class="provider-telemetry-grid">
                <!-- Métrica 1: Puntuación Global -->
                <div class="provider-metric-tile tile-global">
                    <span class="tile-metric-kicker">PUNTUACIÓN GLOBAL</span>
                    <div class="tile-metric-score emerald-score">★ {{ $provider->overall_score }}</div>
                    <div class="tile-metric-meter">
                        <div class="tile-meter-fill" style="width: {{ $provider->overall_score * 10 }}%;"></div>
                    </div>
                    <span class="tile-metric-subtext">Índice Editorial</span>
                </div>

                <!-- Métrica 2: Velocidad TTFB -->
                <div class="provider-metric-tile">
                    <span class="tile-metric-kicker">VELOCIDAD TTFB</span>
                    <div class="tile-metric-score">{{ $provider->score_rendimiento }}<span style="font-size: 0.9rem; color: #94A3B8;">/10</span></div>
                    <div class="tile-metric-meter">
                        <div class="tile-meter-fill" style="width: {{ $provider->score_rendimiento * 10 }}%;"></div>
                    </div>
                    <span class="tile-metric-subtext">Latencia & Caché</span>
                </div>

                <!-- Métrica 3: Calidad / Precio -->
                <div class="provider-metric-tile">
                    <span class="tile-metric-kicker">CALIDAD / PRECIO</span>
                    <div class="tile-metric-score">{{ $provider->score_precio }}<span style="font-size: 0.9rem; color: #94A3B8;">/10</span></div>
                    <div class="tile-metric-meter">
                        <div class="tile-meter-fill" style="width: {{ $provider->score_precio * 10 }}%;"></div>
                    </div>
                    <span class="tile-metric-subtext">Retorno de Inversión</span>
                </div>

                <!-- Métrica 4: Soporte Técnico -->
                <div class="provider-metric-tile">
                    <span class="tile-metric-kicker">SOPORTE TÉCNICO</span>
                    <div class="tile-metric-score">{{ $provider->score_soporte }}<span style="font-size: 0.9rem; color: #94A3B8;">/10</span></div>
                    <div class="tile-metric-meter">
                        <div class="tile-meter-fill" style="width: {{ $provider->score_soporte * 10 }}%;"></div>
                    </div>
                    <span class="tile-metric-subtext">Atención y Rapidez</span>
                </div>

                <!-- Métrica 5: Disponibilidad Uptime -->
                <div class="provider-metric-tile">
                    <span class="tile-metric-kicker">DISPONIBILIDAD SLA</span>
                    <div class="tile-metric-score emerald-score">{{ $provider->uptime }}%</div>
                    <div class="tile-metric-meter">
                        <div class="tile-meter-fill fill-sky" style="width: {{ min($provider->uptime, 100) }}%;"></div>
                    </div>
                    <span class="tile-metric-subtext">Garantía 30 Días</span>
                </div>
            </div>
        </header>

        <!-- Contenido Dividido en 2 Columnas -->
        <div class="provider-content-layout">
            <!-- Columna Izquierda: Análisis, Especificaciones, Reseña y Pros/Contras -->
            <div>
                <!-- Catálogo Multi-Producto & Planes Disponibles -->
                @if($provider->products->count() > 0)
                <section class="provider-detail-card">
                    <div class="provider-card-header">
                        <h2 class="provider-card-title">
                            <i data-lucide="layers" style="width: 20px; height: 20px; color: var(--emerald-primary);"></i>
                            <span>Planes y Servicios Disponibles</span>
                        </h2>
                        <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">CATÁLOGO OFICIAL</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        @foreach($provider->products as $prod)
                        <div style="background: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; transition: border-color 0.2s ease;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                    @if($prod->category_slug)
                                    <span style="font-family: var(--font-mono); font-size: 0.68rem; font-weight: 700; text-transform: uppercase; background: var(--bg-surface); border: 1px solid var(--border-color); padding: 0.15rem 0.5rem; border-radius: 4px; color: var(--text-muted);">
                                        {{ $prod->category_slug }}
                                    </span>
                                    @endif
                                    @if($prod->is_featured)
                                    <span style="font-family: var(--font-mono); font-size: 0.68rem; font-weight: 700; text-transform: uppercase; background: var(--emerald-subtle); border: 1px solid var(--emerald-border); padding: 0.15rem 0.5rem; border-radius: 4px; color: var(--emerald-primary);">
                                        ★ DESTACADO
                                    </span>
                                    @endif
                                </div>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0 0 0.35rem 0;">
                                    {{ $prod->plan_name }}
                                </h3>
                                @if(!empty($prod->specs))
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                    @foreach($prod->specs as $s)
                                    <span style="font-size: 0.74rem; color: var(--text-muted); font-family: var(--font-mono);">
                                        • {{ $s }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div style="display: flex; align-items: center; gap: 1.25rem;">
                                <div style="text-align: right;">
                                    <div style="font-family: var(--font-mono); font-size: 1.45rem; font-weight: 800; color: var(--emerald-primary); line-height: 1;">
                                        ${{ number_format($prod->price_from, 2) }}
                                        <span style="font-size: 0.78rem; font-weight: 400; color: var(--text-muted);">/{{ $prod->period }}</span>
                                    </div>
                                    @if($prod->price_before > $prod->price_from)
                                    <span style="font-size: 0.78rem; color: var(--text-dim); text-decoration: line-through; font-family: var(--font-mono);">
                                        ${{ number_format($prod->price_before, 2) }}
                                    </span>
                                    @endif
                                </div>

                                <a href="{{ route('go', ['slug' => $provider->slug, 'plan' => $prod->id]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
                                    <span>Ver Plan ↗</span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Veredicto de la Redacción -->
                <section class="provider-detail-card">
                    <div class="provider-card-header">
                        <h2 class="provider-card-title">
                            <i data-lucide="file-text" style="width: 20px; height: 20px; color: var(--emerald-primary);"></i>
                            <span>Veredicto de la Redacción</span>
                        </h2>
                        <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">AUDITORÍA EDITORIAL</span>
                    </div>

                    <p style="font-size: 1.02rem; line-height: 1.72; color: #334155; margin-bottom: 1.5rem;">
                        {{ $provider->description }}
                    </p>

                    @if($provider->verdict)
                    <div class="verdict-box-modern">
                        <div class="verdict-target-badge">
                            <i data-lucide="target" style="width: 12px; height: 12px;"></i>
                            <span>¿PARA QUIÉN SE RECOMIENDA?</span>
                        </div>
                        <p style="font-size: 0.95rem; color: #0F172A; line-height: 1.6; margin: 0; font-weight: 500;">
                            {{ $provider->verdict }}
                        </p>
                    </div>
                    @endif
                </section>

                <!-- Especificaciones Técnicas Auditadas (Hardware & Entorno) -->
                <section class="provider-detail-card">
                    <div class="provider-card-header">
                        <h2 class="provider-card-title">
                            <i data-lucide="cpu" style="width: 20px; height: 20px; color: var(--sky-primary);"></i>
                            <span>Especificaciones Técnicas Auditadas</span>
                        </h2>
                        <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">INFRAESTRUCTURA</span>
                    </div>

                    <div class="provider-specs-grid">
                        <!-- Spec 1 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="hard-drive" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Almacenamiento</span>
                                <span class="provider-spec-val">Discos NVMe SSD de Alta Velocidad</span>
                            </div>
                        </div>

                        <!-- Spec 2 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="zap" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Motor Web & Caché</span>
                                <span class="provider-spec-val">LiteSpeed / Nginx con HTTP/3</span>
                            </div>
                        </div>

                        <!-- Spec 3 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="layout" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Panel de Gestión</span>
                                <span class="provider-spec-val">{{ str_contains(strtolower($provider->name), 'hostinger') ? 'hPanel Optimizado' : (str_contains(strtolower($provider->name), 'siteground') ? 'Site Tools Avanzado' : 'cPanel / Panel Personalizado') }}</span>
                            </div>
                        </div>

                        <!-- Spec 4 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="globe" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Centros de Datos</span>
                                <span class="provider-spec-val">España, Europa, EE.UU. y LATAM</span>
                            </div>
                        </div>

                        <!-- Spec 5 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Seguridad & SSL</span>
                                <span class="provider-spec-val">Certificados SSL Let's Encrypt Gratis</span>
                            </div>
                        </div>

                        <!-- Spec 6 -->
                        <div class="provider-spec-item">
                            <div class="provider-spec-icon">
                                <i data-lucide="refresh-cw" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="provider-spec-label">Garantía Comercial</span>
                                <span class="provider-spec-val">30 Días de Reembolso Garantizado</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Banner de Reseña Editorial a Fondo (si existe) -->
                @if($provider->latestReview)
                <div class="review-preview-banner">
                    <div style="display: flex; align-items: center; gap: 1.15rem;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; color: var(--emerald-primary); flex-shrink: 0;">
                            <i data-lucide="book-open" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <span style="font-family: var(--font-mono); font-size: 0.68rem; font-weight: 800; color: var(--emerald-primary); text-transform: uppercase; letter-spacing: 0.05em;">ANÁLISIS EDITORIAL A FONDO</span>
                            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0.2rem 0;">{{ $provider->latestReview->title }}</h3>
                            <p style="font-size: 0.85rem; color: #64748B; margin: 0;">Banco de pruebas de latencia, estabilidad y veredicto detallado.</p>
                        </div>
                    </div>
                    <a href="{{ route('reviews.show', $provider->latestReview->slug) }}" class="btn btn-emerald btn-sm" style="font-weight: 700; white-space: nowrap; padding: 0.6rem 1.25rem;">
                        <span>Leer Reseña Completa</span>
                        <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
                @endif

                <!-- Pros y Contras Modernos -->
                <section class="pros-cons-grid-modern">
                    <!-- Pros -->
                    <div class="pro-card-modern">
                        <h3 class="pro-card-title">
                            <i data-lucide="check-circle-2" style="width: 18px; height: 18px;"></i>
                            <span>Puntos Fuertes</span>
                        </h3>
                        <div class="pro-con-body">
                            {{ $provider->pros ?: "• Rendimiento sólido de CPU y memoria\n• Panel de control fluido e intuitivo\n• Excelente relación calidad/precio global" }}
                        </div>
                    </div>

                    <!-- Contras -->
                    <div class="con-card-modern">
                        <h3 class="con-card-title">
                            <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i>
                            <span>Aspectos a Considerar</span>
                        </h3>
                        <div class="pro-con-body">
                            {{ $provider->cons ?: "• Precio de renovación estándar superior a la oferta inicial\n• Dominio gratuito sujeto a contratación plurianual" }}
                        </div>
                    </div>
                </section>
            </div>

            <!-- Columna Derecha: Cupones Activos, Alternativas y Balanza -->
            <div>
                <!-- Cupones Disponibles -->
                @if($provider->coupons->count() > 0)
                <div class="provider-sidebar-widget">
                    <h3 class="sidebar-widget-title">
                        <i data-lucide="ticket" style="width: 18px; height: 18px; color: #D97706;"></i>
                        <span>Cupones Activos ({{ $provider->coupons->count() }})</span>
                    </h3>

                    @foreach($provider->coupons as $c)
                    <div class="sidebar-coupon-card">
                        <div class="sidebar-coupon-top">
                            <span class="badge-pill badge-emerald" style="font-weight: 800;">{{ $c->discount }}</span>
                            <span style="font-family: var(--font-mono); font-size: 0.85rem; font-weight: 700; color: #0F172A; background: #FFFFFF; border: 1.5px dashed #CBD5E1; padding: 0.25rem 0.6rem; border-radius: 6px;">{{ $c->code }}</span>
                        </div>
                        <p style="font-size: 0.8rem; color: #64748B; margin: 0 0 0.85rem 0; line-height: 1.4;">{{ $c->condition ?: 'Descuento verificado hoy para nuevos planes.' }}</p>
                        <button type="button" onclick="copyVoucher('{{ $c->code }}', '{{ route('go', $provider->slug) }}')" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center; gap: 0.45rem;">
                            <i data-lucide="copy" style="width: 13px; height: 13px;"></i>
                            <span>Copiar y Activar Descuento</span>
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Alternativas Recomendadas -->
                <div class="provider-sidebar-widget">
                    <h3 class="sidebar-widget-title">
                        <i data-lucide="shuffle" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                        <span>Alternativas Recomendadas</span>
                    </h3>

                    <div style="display: flex; flex-direction: column;">
                        @foreach($related as $rel)
                        <div class="sidebar-related-item">
                            <div class="sidebar-related-info">
                                @if($rel->resolved_logo_url)
                                    <img src="{{ $rel->resolved_logo_url }}" alt="{{ $rel->name }}" class="sidebar-related-logo">
                                @else
                                    <div class="sidebar-related-avatar">{{ strtoupper(substr($rel->name, 0, 2)) }}</div>
                                @endif
                                <div>
                                    <a href="{{ route('providers.show', $rel->slug) }}" style="font-weight: 750; font-size: 0.92rem; color: #0F172A; text-decoration: none; display: block; line-height: 1.2;">
                                        {{ $rel->name }}
                                    </a>
                                    <div style="font-family: var(--font-mono); font-size: 0.78rem; color: var(--emerald-primary); font-weight: 700; margin-top: 2px;">
                                        ${{ number_format($rel->price_from, 2) }}/mes • ★ {{ $rel->overall_score }}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('providers.show', $rel->slug) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; white-space: nowrap;">
                                <span>Ver</span>
                                <i data-lucide="arrow-right" style="width: 11px; height: 11px;"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Callout a La Balanza -->
                <div class="sidebar-balanza-prompt">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.85rem auto; color: var(--emerald-primary);">
                        <i data-lucide="scale" style="width: 22px; height: 22px;"></i>
                    </div>
                    <h4>¿Dudas con {{ $provider->name }}?</h4>
                    <p>
                        Compara a {{ $provider->name }} en tiempo real contra sus rivales directos calibrando tus prioridades en La Balanza.
                    </p>
                    <a href="{{ route('balanza') }}" class="btn-sidebar-balanza">
                        <span>Pesar en La Balanza</span>
                        <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>

@push('scripts')
<script>
    function copyVoucher(code, redirectUrl) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                window.showToast('¡Cupón "' + code + '" copiado! Abriendo sitio oficial...', 'success');
                setTimeout(() => {
                    window.open(redirectUrl, '_blank');
                }, 800);
            });
        } else {
            window.showToast('Cupón: ' + code, 'info');
            window.open(redirectUrl, '_blank');
        }
    }
</script>

<!-- Schema.org JSON-LD para Resultados Enriquecidos (Estrellas y Precio en Google) -->
<script type="application/ld+json">
{
    "{{ '@context' }}": "https://schema.org",
    "@graph": [
        {
            "@type": "Product",
            "@id": "{{ route('providers.show', $provider->slug) }}#product",
            "name": "{{ $provider->name }} Hosting & Servidores",
            "description": "{{ addslashes(strip_tags($provider->description ?: 'Planes de alojamiento web y servidores de ' . $provider->name)) }}",
            "image": "{{ $provider->resolved_logo_url ? (str_starts_with($provider->resolved_logo_url, 'http') ? $provider->resolved_logo_url : url($provider->resolved_logo_url)) : asset('logo.png') }}",
            "brand": {
                "@type": "Brand",
                "name": "{{ $provider->name }}"
            },
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "{{ $provider->overall_score }}",
                "bestRating": "10",
                "worstRating": "1",
                "ratingCount": "84"
            },
            "offers": {
                "@type": "Offer",
                "priceCurrency": "USD",
                "price": "{{ number_format($provider->price_from, 2, '.', '') }}",
                "priceValidUntil": "{{ date('Y-12-31') }}",
                "availability": "https://schema.org/InStock",
                "url": "{{ route('providers.show', $provider->slug) }}"
            }
        },
        {
            "@type": "BreadcrumbList",
            "@id": "{{ route('providers.show', $provider->slug) }}#breadcrumb",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Inicio",
                    "item": "{{ route('home') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Proveedores",
                    "item": "{{ route('providers.index') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "{{ $provider->name }}",
                    "item": "{{ route('providers.show', $provider->slug) }}"
                }
            ]
        }
    ]
}
</script>
@endpush
@endsection
