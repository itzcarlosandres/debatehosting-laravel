@extends('layouts.app')

@section('title', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones')
@section('meta_description', 'Medio editorial y comparador técnico independiente de hosting web, servidores VPS, cloud y cupones verificados sin patrocinios encubiertos.')

@section('content')
    <!-- =========================================================================
         1. HERO SECTION EDITORIAL
         ========================================================================= -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <!-- Columna Izquierda -->
                <div class="hero-text-content">
                    <div class="kicker" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                        <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                        <span>{{ $hero['kickerText'] ?? 'OBSERVATORIO DE HOSTING Y NUBE' }}</span>
                    </div>

                    <h1 class="hero-title">
                        {{ $hero['titleBefore'] ?? 'El gran' }}
                        <span class="italic-serif">{{ $hero['titleHighlight'] ?? 'debate' }}</span>
                        {{ $hero['titleAfter'] ?? 'del hosting.' }}
                    </h1>

                    <p class="hero-description">
                        {{ $hero['description'] ?? 'Comparamos proveedores en vivo sin sesgos ni publicidad encubierta. Ajusta tus prioridades en La Balanza, analiza las métricas de rendimiento y decide quién se queda con tu proyecto.' }}
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('balanza') }}" class="btn btn-primary">
                            <span>Pesar Proveedores</span>
                            <i data-lucide="scale" style="width: 16px; height: 16px;"></i>
                        </a>
                        <a href="{{ route('ofertas') }}" class="btn btn-secondary">
                            <span>Ver Ofertas</span>
                            <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                        </a>
                    </div>

                    <div class="hero-counters">
                        <div class="counter-item">
                            <div class="counter-num">{{ $providers->count() }}</div>
                            <span class="counter-label">Proveedores analizados</span>
                        </div>
                        <div class="counter-item">
                            <div class="counter-num">{{ $coupons->count() }}</div>
                            <span class="counter-label">Cupones verificados</span>
                        </div>
                        <div class="counter-item">
                            <div class="counter-num">0</div>
                            <span class="counter-label">Patrocinios pagados</span>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Tarjeta Preview Duelo en Vivo -->
                <div class="hero-preview-col">
                    <div class="hero-preview-card">
                        <div class="preview-card-header">
                            <span class="title">COMPARATIVA EDITORIAL EN VIVO</span>
                            <span class="badge-tag badge-green">EN VIVO</span>
                        </div>

                        <div class="preview-duel-row">
                            <div class="preview-fighter">
                                <div class="name">Hostinger</div>
                                <div class="price">$2.49/mes</div>
                            </div>
                            <div class="preview-vs">VS</div>
                            <div class="preview-fighter">
                                <div class="name">SiteGround</div>
                                <div class="price">$3.99/mes</div>
                            </div>
                        </div>

                        <div class="preview-bar-row">
                            <div class="preview-bar-labels">
                                <span>Rendimiento web</span>
                                <span>9.4 vs 8.8</span>
                            </div>
                            <div class="preview-progress-track">
                                <div class="preview-progress-fill" style="width: 74%"></div>
                            </div>
                        </div>

                        <div class="preview-bar-row">
                            <div class="preview-bar-labels">
                                <span>Soporte técnico</span>
                                <span>7.5 vs 9.5</span>
                            </div>
                            <div class="preview-progress-track">
                                <div class="preview-progress-fill" style="width: 85%"></div>
                            </div>
                        </div>

                        <div style="margin-top: 1.4rem; text-align: center;">
                            <a href="{{ route('balanza') }}" class="btn btn-dark btn-sm" style="width: 100%; justify-content: center;">
                                <span>Explorar en La Balanza</span>
                                <i data-lucide="arrow-right" style="width: 14px; height: 14px; margin-left: 0.4rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. EL PODIO EDITORIAL (TOP PICKS OLÍMPICO)
         ========================================================================= -->
    @if($picks->count() > 0)
    <section class="section-container" style="background: var(--bg-surface); border-top: var(--border-width) solid var(--border-ink); border-bottom: var(--border-width) solid var(--border-ink); padding: 4.5rem 0;">
        <div class="container">
            <div class="section-header-editorial" style="text-align: center; margin-bottom: 3rem;">
                <div class="kicker">LOS 3 ELEGIDOS DE LA REDACCIÓN</div>
                <h2 class="section-title">El Podio del <span class="italic-serif">Hosting</span></h2>
                <p class="section-subtitle">Las tres opciones definitivas que recomendamos con los ojos cerrados este año.</p>
            </div>

            <div class="podio-olympic-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem; align-items: stretch;">
                @foreach($picks as $idx => $pick)
                @php
                    $pos = $pick->position ?? ($idx + 1);
                    $prov = $pick->provider;
                @endphp
                @if($prov)
                <div class="podio-olympic-card pos-{{ $pos }} {{ $pos === 1 ? 'is-winner' : '' }}" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="podio-card-header">
                            <div class="podio-corner-flag flag-{{ $pos }}">
                                <span>0{{ $pos }}</span>
                            </div>

                            @if($pos === 1)
                            <div class="podio-choice-pill">
                                <span class="pill-star">★</span>
                                <span>ELECCIÓN DE LA REDACCIÓN</span>
                            </div>
                            @endif
                        </div>

                        <div class="podio-main-body">
                            <div class="podio-brand-area">
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    @if($prov->resolved_logo_url)
                                        <img src="{{ $prov->resolved_logo_url }}" alt="{{ $prov->name }}" class="podio-logo-img" style="width: 38px; height: 38px; object-fit: contain; border-radius: 6px; background-color: #FFFFFF; border: 1.5px solid var(--border-ink); padding: 2px; flex-shrink: 0;">
                                    @else
                                        <div style="width: 38px; height: 38px; background: #0E6B41; color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.9rem;">
                                            {{ substr($prov->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <span class="brand-title-editorial" style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700;">{{ $prov->name }}</span>
                                </div>

                                <div class="podio-meta-row" style="margin-top: 0.5rem; display: flex; gap: 0.5rem; align-items: center;">
                                    @if($prov->plan)
                                        <span class="podio-plan-tag" style="font-size: 0.75rem; color: var(--text-muted);">{{ $prov->plan }}</span>
                                    @endif
                                    @if($prov->price_from)
                                        <span class="podio-price-badge" style="font-family: var(--font-mono); font-weight: 700; font-size: 0.85rem; color: var(--text-ink);">
                                            ${{ number_format($prov->price_from, 2) }}/{{ $prov->period ?? 'mes' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="podio-rating-box">
                                <div class="podio-score-header">
                                    @if($pos === 1) <span class="podio-trophy-icon">🏆</span> @endif
                                    <span class="podio-score-number">{{ number_format($prov->overall_score, 1) }}</span>
                                </div>

                                <div class="podio-stars-row" style="color: #EAB308;">
                                    ★★★★★
                                </div>

                                <span class="podio-reviews-label">{{ $pos === 1 ? '3,897 opiniones' : ($pos === 2 ? '1,420 opiniones' : '3,915 opiniones') }}</span>
                            </div>
                        </div>

                        <div style="padding: 0 1.5rem 1rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--text-ink);">
                                "{{ $pick->titulo }}"
                            </h4>
                            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.45;">
                                {{ $pick->veredicto }}
                            </p>
                        </div>
                    </div>

                    <div class="podio-footer-row" style="padding: 1rem 1.5rem; border-top: 1px dashed var(--border-ink); display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <a href="{{ route('providers.show', $prov->slug) }}" class="btn btn-secondary btn-sm podio-btn" style="text-decoration: none;">
                            <span>Ficha Técnica</span>
                        </a>
                        <a href="{{ route('go', $prov->slug) }}" target="_blank" rel="noopener noreferrer" class="{{ $pos === 1 ? 'btn btn-primary btn-sm podio-btn' : 'btn btn-secondary btn-sm podio-btn' }}" style="text-decoration: none;">
                            <span>Visitar Sitio</span>
                            <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                        </a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- =========================================================================
         3. PARRILLA COMPACTA DE MEJORES PROVEEDORES
         ========================================================================= -->
    <section class="mejores-section" id="mejores" style="padding: 4.5rem 0;">
        <div class="container">
            <div class="section-header-editorial" style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <div class="kicker">DIRECTORIO AUDITADO 2026</div>
                    <h2 class="section-title">
                        Proveedores Destacados por <span class="italic-serif">Categoría</span>
                    </h2>
                    <p class="section-subtitle">Evaluamos latencia TTFB, fiabilidad y soporte técnico con pruebas reales.</p>
                </div>
                <a href="{{ route('providers.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                    <span>Ver catálogo completo ({{ $providers->count() }})</span>
                    <i data-lucide="arrow-right" style="width: 15px; height: 15px;"></i>
                </a>
            </div>

            <div class="mejores-grid compact">
                @foreach($providers->take(8) as $idx => $p)
                @php
                    $badgeThemes = ['theme-gold', 'theme-green', 'theme-red', 'theme-dark'];
                    $theme = $badgeThemes[$idx % count($badgeThemes)];
                @endphp
                <article class="mejores-compact-card">
                    <div>
                        <div class="mejores-card-top">
                            <span class="mejores-award-tag {{ $theme }}">
                                <i data-lucide="award" style="width: 12px; height: 12px;"></i>
                                <span>{{ $p->badge ?: 'RECOMENDADO' }}</span>
                            </span>

                            <div class="mejores-clean-score" title="Calificación: {{ $p->overall_score }}/10">
                                <span class="score-num">{{ number_format($p->overall_score, 1) }}</span>
                                <span class="score-max">/10</span>
                            </div>
                        </div>

                        <div class="mejores-brand-header">
                            @if($p->resolved_logo_url)
                                <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" class="mejores-clean-logo">
                            @else
                                <div class="mejores-clean-monogram">
                                    {{ substr($p->name, 0, 2) }}
                                </div>
                            @endif
                            <div class="mejores-brand-meta">
                                <h3 class="mejores-clean-name">{{ $p->name }}</h3>
                                <span class="mejores-clean-plan">{{ $p->plan }}</span>
                            </div>
                        </div>

                        <div class="mejores-clean-price-row">
                            <div class="price-amount-wrap">
                                <span class="price-lead">DESDE</span>
                                <span class="price-bold">${{ number_format($p->price_from, 2) }}</span>
                                <span class="price-lead">/{{ $p->period ?? 'mes' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mejores-clean-actions">
                        <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm mejores-clean-cta" style="text-decoration: none;">
                            <span>Contratar Oferta</span>
                            <i data-lucide="external-link" style="width: 13px; height: 13px; margin-left: 0.3rem;"></i>
                        </a>
                        <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary btn-sm mejores-clean-cta" style="text-decoration: none;">
                            <span>Ver Análisis Editorial</span>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =========================================================================
         4. CUPONES ACTIVOS DEL MES
         ========================================================================= -->
    @if($coupons->count() > 0)
    <section id="cupones" class="cupones-section" style="background: var(--bg-surface); border-top: var(--border-width) solid var(--border-ink); border-bottom: var(--border-width) solid var(--border-ink); padding: 4.5rem 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2.5rem;">
                <div>
                    <div class="kicker">DESCUENTOS VERIFICADOS</div>
                    <h2 class="section-title">
                        Cupones Activos del <span class="italic-serif">Mes</span>
                    </h2>
                    <p class="section-subtitle" style="max-width: 640px; margin-top: 0.4rem;">
                        Códigos promocionales probados manualmente por la redacción antes de su publicación.
                    </p>
                </div>

                <a href="{{ route('coupons.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>Ver todos los cupones ({{ $coupons->count() }})</span>
                    <i data-lucide="arrow-right" style="width: 15px; height: 15px;"></i>
                </a>
            </div>

            <!-- Filas de Cupones -->
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                @foreach($coupons->take(4) as $c)
                <div class="coupon-list-row" style="background-color: #FFFFFF; border: 1.5px solid var(--border-ink); box-shadow: 3px 3px 0 var(--border-ink); padding: 1.25rem 1.6rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.2rem; border-radius: var(--radius-sm);">
                    <!-- Info Marca y Descuento -->
                    <div class="coupon-brand-info" style="display: flex; align-items: center; gap: 1.1rem; flex: 1 1 280px;">
                        @if($c->provider && $c->provider->resolved_logo_url)
                            <img src="{{ $c->provider->resolved_logo_url }}" alt="{{ $c->provider->name }}" style="width: 44px; height: 44px; object-fit: contain; border-radius: 6px; border: 1px solid var(--border-ink); padding: 2px; background: #fff;">
                        @else
                            <div style="width: 44px; height: 44px; background: #0E6B41; color: #fff; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ substr($c->provider->name ?? 'DH', 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="font-family: var(--font-serif); font-weight: 700; font-size: 1.15rem; color: var(--text-ink);">
                                    {{ $c->provider->name ?? 'Proveedor' }}
                                </span>
                                <span style="background: #ECFDF5; color: #047857; font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 3px; border: 1px solid rgba(4,120,87,0.2);">
                                    {{ $c->discount_percent ? '-' . $c->discount_percent . '% OFF' : 'DESCUENTO' }}
                                </span>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.2rem 0 0;">
                                {{ $c->description ?: 'Válido en nuevos planes y renovaciones' }}
                            </p>
                        </div>
                    </div>

                    <!-- Código y Botón Copiar -->
                    <div class="coupon-card-actions" style="display: flex; align-items: center; gap: 0.75rem;">
                        <button onclick="copyCouponCode('{{ $c->code }}', '{{ $c->provider ? route('go', $c->provider->slug) : '#' }}')" class="btn btn-primary btn-sm" style="font-family: var(--font-mono); font-size: 0.88rem; padding: 0.55rem 1rem; cursor: pointer;">
                            <i data-lucide="copy" style="width: 14px; height: 14px; margin-right: 0.4rem;"></i>
                            <span>{{ $c->code }}</span>
                        </button>
                        @if($c->provider)
                        <a href="{{ route('go', $c->provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" style="text-decoration: none;">
                            <span>Aplicar Cupón ↗</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <script>
        function copyCouponCode(code, url) {
            navigator.clipboard.writeText(code).then(() => {
                alert('¡Código copiado: ' + code + '! Redirigiendo a la oferta...');
                if (url && url !== '#') {
                    window.open(url, '_blank');
                }
            });
        }
    </script>
@endsection
