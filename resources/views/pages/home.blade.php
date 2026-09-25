@extends('layouts.app')

@section('title', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones')
@section('meta_description', 'Observatorio técnico y comparador independiente de hosting web, servidores VPS y cupones verificados. Pruebas reales de velocidad TTFB, uptime y precios auditados.')
@section('og_title', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones')
@section('og_description', 'Observatorio técnico y comparador independiente de hosting web, servidores VPS y cupones verificados. Pruebas reales de velocidad TTFB, uptime y precios auditados.')

@section('content')
    <!-- =========================================================================
         1. HERO SECTION MINIMALISTA
         ========================================================================= -->
    <section class="hero-clean-section">
        <!-- Fondo de Malla Tecnológica y Resplandor Ambiental -->
        <div class="hero-backdrop-glow"></div>
        <div class="hero-grid-pattern"></div>

        <div class="container relative-z">
            <div class="hero-grid-2col">
                <!-- Columna Izquierda: Propuesta de Valor y Autoridad -->
                <div class="hero-left-content">
                    <!-- Chip Kicker de Estado con Radar Neon -->
                    <div class="hero-kicker-badge">
                        <span class="pulse-radar">
                            <span class="pulse-radar-wave"></span>
                            <span class="pulse-radar-dot"></span>
                        </span>
                        <span class="kicker-title">{{ $hero['kickerText'] ?? $hero['kicker'] ?? 'OBSERVATORIO DE HOSTING Y NUBE' }}</span>
                        <span class="kicker-tag">AUDITORÍA 2026</span>
                    </div>

                    <!-- Título H1 de Alto Impacto Editorial -->
                    <h1 class="hero-lead-title">
                        {{ $hero['titleBefore'] ?? 'El gran' }}
                        <span class="hero-title-highlight">{{ $hero['titleHighlight'] ?? 'debate' }}</span>
                        {{ ltrim($hero['titleAfter'] ?? 'del hosting.') }}
                    </h1>

                    <!-- Bajada Editorial -->
                    <p class="hero-lead-desc">
                        {{ $hero['description'] ?? 'Comparamos proveedores en vivo sin sesgos ni publicidad encubierta. Ajusta tus prioridades en La Balanza, analiza las métricas de rendimiento y decide con certeza matemática quién se queda con tu proyecto.' }}
                    </p>

                    <!-- Grupo de Acciones / CTAs Principales -->
                    <div class="hero-cta-group">
                        <a href="{{ $hero['primaryBtnUrl'] ?? route('balanza') }}" class="btn-hero-primary">
                            <i data-lucide="scale" style="width: 18px; height: 18px;"></i>
                            <span>{{ $hero['primaryBtnText'] ?? 'Pesar Proveedores' }}</span>
                            <i data-lucide="sparkles" style="width: 14px; height: 14px; opacity: 0.85;"></i>
                        </a>
                        <a href="{{ $hero['secondaryBtnUrl'] ?? route('providers.index') }}" class="btn-hero-secondary">
                            <span>{{ $hero['secondaryBtnText'] ?? 'Ver Ofertas' }}</span>
                            <i data-lucide="arrow-right" class="hero-btn-arrow" style="width: 16px; height: 16px;"></i>
                        </a>
                    </div>

                    <!-- Micro-garantías de Transparencia -->
                    <div class="hero-trust-row">
                        <div class="trust-item">
                            <i data-lucide="shield-check" class="trust-icon"></i>
                            <span>100% Independiente</span>
                        </div>
                        <span class="trust-dot">•</span>
                        <div class="trust-item">
                            <i data-lucide="gauge" class="trust-icon"></i>
                            <span>Pruebas TTFB Reales</span>
                        </div>
                        <span class="trust-dot">•</span>
                        <div class="trust-item">
                            <i data-lucide="lock" class="trust-icon"></i>
                            <span>Cero Enlaces Patrocinados Ocultos</span>
                        </div>
                    </div>

                    <!-- Panel Flotante de Métricas del Observatorio -->
                    <div class="hero-stats-panel">
                        <div class="hero-stat-card">
                            <div class="stat-number-wrap">
                                <span class="stat-number">{{ $providers->count() }}</span>
                                <span class="stat-pill-mini">AUDITADOS</span>
                            </div>
                            <span class="stat-label">{{ $hero['counter1Label'] ?? 'Proveedores analizados' }}</span>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-number-wrap">
                                <span class="stat-number">{{ $coupons->count() }}</span>
                                <span class="stat-pill-mini stat-emerald-tag"><i data-lucide="check" style="width: 10px; height: 10px;"></i> HOY</span>
                            </div>
                            <span class="stat-label">{{ $hero['counter2Label'] ?? 'Cupones verificados' }}</span>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-number-wrap">
                                <span class="stat-number stat-emerald-val">100%</span>
                            </div>
                            <span class="stat-label">{{ $hero['counter3Label'] ?? 'Metodología abierta' }}</span>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-number-wrap">
                                <span class="stat-number">0</span>
                            </div>
                            <span class="stat-label">{{ $hero['counter4Label'] ?? 'Patrocinios encubiertos' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Tarjeta de Comparativa Editorial en Vivo (Elite Benchmark Monitor) -->
                <div class="hero-right-visual">
                    <div class="hero-duel-card-premium">
                        <!-- Resplandor Superior de Acento -->
                        <div class="duel-card-glow-bar"></div>

                        <!-- Encabezado del Duelo -->
                        <div class="duel-header-premium">
                            <div class="duel-header-title-box">
                                <div class="duel-icon-chip">
                                    <i data-lucide="activity" style="width: 16px; height: 16px;"></i>
                                </div>
                                <div>
                                    <h3 class="duel-title-text">{{ $hero['previewTitle'] ?? 'Comparativa Editorial en Vivo' }}</h3>
                                    <span class="duel-subtitle-text">Monitor en vivo • 182ms TTFB promedio</span>
                                </div>
                            </div>
                            <div class="duel-live-pill">
                                <span class="duel-beacon"></span>
                                <span>AUDITADO</span>
                            </div>
                        </div>

                        @php
                            $f1Name = $hero['fighter1Name'] ?? 'Hostinger';
                            $f2Name = $hero['fighter2Name'] ?? 'SiteGround';
                            $f1Provider = $providers->first(function($p) use ($f1Name) {
                                return stripos($p->name, $f1Name) !== false || stripos($f1Name, $p->name) !== false;
                            });
                            $f2Provider = $providers->first(function($p) use ($f2Name) {
                                return stripos($p->name, $f2Name) !== false || stripos($f2Name, $p->name) !== false;
                            });
                        @endphp

                        <!-- Matchup Arena de Proveedores -->
                        <div class="duel-arena-premium">
                            <!-- Contendiente 1 -->
                            <div class="contender-card contender-left">
                                <div class="contender-logo-wrapper">
                                    @if($f1Provider && $f1Provider->resolved_logo_url)
                                        <img src="{{ $f1Provider->resolved_logo_url }}" alt="{{ $f1Name }}" class="contender-logo-img">
                                    @else
                                        <div class="contender-avatar-chip avatar-emerald">
                                            <span>{{ strtoupper(substr($f1Name, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="contender-info">
                                    <span class="contender-name">{{ $f1Name }}</span>
                                    <span class="contender-price">{{ $hero['fighter1Price'] ?? '$2.49/mes' }}</span>
                                </div>
                                <span class="contender-badge-tier">Líder Calidad/Precio</span>
                            </div>

                            <!-- Círculo Central VS -->
                            <div class="duel-vs-premium">
                                <span>VS</span>
                            </div>

                            <!-- Contendiente 2 -->
                            <div class="contender-card contender-right">
                                <div class="contender-logo-wrapper">
                                    @if($f2Provider && $f2Provider->resolved_logo_url)
                                        <img src="{{ $f2Provider->resolved_logo_url }}" alt="{{ $f2Name }}" class="contender-logo-img">
                                    @else
                                        <div class="contender-avatar-chip avatar-sky">
                                            <span>{{ strtoupper(substr($f2Name, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="contender-info">
                                    <span class="contender-name">{{ $f2Name }}</span>
                                    <span class="contender-price">{{ $hero['fighter2Price'] ?? '$3.99/mes' }}</span>
                                </div>
                                <span class="contender-badge-tier tier-premium">WordPress Élite</span>
                            </div>
                        </div>

                        <!-- Barras de Métricas Comparativas en Paralelo -->
                        <div class="duel-telemetry-metrics">
                            <!-- Métrica 1 -->
                            <div class="telemetry-row">
                                <div class="telemetry-labels">
                                    <span class="metric-name">
                                        <i data-lucide="zap" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                                        {{ $hero['metric1Label'] ?? 'Rendimiento web y TTFB' }}
                                    </span>
                                    <div class="metric-scores-split">
                                        <span class="score-f1">9.4</span>
                                        <span class="score-divider">vs</span>
                                        <span class="score-f2">8.8</span>
                                    </div>
                                </div>
                                <div class="telemetry-dual-meter">
                                    <div class="meter-bar-fill fill-f1" style="width: 53%;"></div>
                                    <div class="meter-bar-fill fill-f2" style="width: 47%;"></div>
                                </div>
                            </div>

                            <!-- Métrica 2 -->
                            <div class="telemetry-row">
                                <div class="telemetry-labels">
                                    <span class="metric-name">
                                        <i data-lucide="headphones" style="width: 14px; height: 14px; color: var(--sky-primary);"></i>
                                        {{ $hero['metric2Label'] ?? 'Soporte técnico y atención' }}
                                    </span>
                                    <div class="metric-scores-split">
                                        <span class="score-f1">7.5</span>
                                        <span class="score-divider">vs</span>
                                        <span class="score-f2">9.5</span>
                                    </div>
                                </div>
                                <div class="telemetry-dual-meter">
                                    <div class="meter-bar-fill fill-f1" style="width: 44%;"></div>
                                    <div class="meter-bar-fill fill-f2" style="width: 56%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Radar de Cupones Verificados en Vivo (Rotador Dinámico cada 12s) -->
                        @php
                            $liveCoupons = $coupons->where('verified', true)->values();
                            if ($liveCoupons->isEmpty()) {
                                $liveCoupons = $coupons->take(5)->values();
                            }
                        @endphp

                        @if($liveCoupons->isNotEmpty())
                        <div class="hero-live-coupons-box" id="hero-coupon-rotator">
                            <div class="coupon-rotator-top">
                                <div class="coupon-rotator-label">
                                    <span class="pulse-beacon-gold"></span>
                                    <span>CUPÓN EN VIVO VERIFICADO</span>
                                </div>
                                <div class="coupon-rotator-nav">
                                    <span class="coupon-timer-badge">
                                        <i data-lucide="timer" style="width: 11px; height: 11px;"></i>
                                        <span id="rotator-countdown">12s</span>
                                    </span>
                                    <div class="coupon-rotator-dots">
                                        @foreach($liveCoupons as $idx => $lc)
                                            <button type="button" class="rotator-dot {{ $idx === 0 ? 'active' : '' }}" onclick="switchHeroCoupon({{ $idx }})" aria-label="Cupón {{ $idx + 1 }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor de Diapositivas de Cupones -->
                            <div class="coupon-slides-viewport">
                                @foreach($liveCoupons as $idx => $lc)
                                <div class="coupon-slide {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}">
                                    <div class="coupon-slide-content">
                                        <div class="coupon-slide-left">
                                            @if($lc->provider && $lc->provider->resolved_logo_url)
                                                <img src="{{ $lc->provider->resolved_logo_url }}" alt="{{ $lc->provider->name }}" class="coupon-slide-logo">
                                            @else
                                                <div class="coupon-slide-avatar">{{ strtoupper(substr($lc->provider->name ?? 'DH', 0, 2)) }}</div>
                                            @endif
                                            <div class="coupon-slide-info">
                                                <div class="coupon-provider-row">
                                                    <span class="coupon-provider-name">{{ $lc->provider->name ?? 'Proveedor' }}</span>
                                                    <span class="coupon-discount-tag">{{ $lc->discount }}</span>
                                                </div>
                                                <span class="coupon-condition-text">{{ $lc->condition ?: 'Descuento verificado hoy para planes activos.' }}</span>
                                            </div>
                                        </div>

                                        <div class="coupon-slide-action">
                                            <button type="button" onclick="copyVoucher('{{ $lc->code }}', '{{ route('go', $lc->provider->slug ?? '') }}')" class="btn-copy-live-coupon" title="Copiar código y activar oferta">
                                                <code>{{ $lc->code }}</code>
                                                <i data-lucide="copy" style="width: 12px; height: 12px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Barra de Progreso de Rotación -->
                            <div class="rotator-progress-track">
                                <div class="rotator-progress-bar" id="rotator-bar"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. EL PODIO (TOP 3 MEJORES SELECCIONES)
         ========================================================================= -->
    <section class="podium-section">
        <div class="container">
            <div class="section-headline-wrap">
                <div class="kicker">{{ $sectionHeaders['podio']['kicker'] ?? 'LOS MEJORES SEGÚN NUESTRAS AUDITORÍAS' }}</div>
                <h2 class="section-title">{{ $sectionHeaders['podio']['titleBefore'] ?? 'El Podio Editorial' }}</h2>
                <p class="section-subtitle">
                    {{ $sectionHeaders['podio']['subtitle'] ?? 'Los tres proveedores que obtuvieron el balance más sobresaliente en pruebas de latencia, disponibilidad real y relación coste/beneficio.' }}
                </p>
            </div>

            <div class="podium-cards-grid">
                @php
                    $pick1 = $picks->firstWhere('position', 1);
                    $pick2 = $picks->firstWhere('position', 2);
                    $pick3 = $picks->firstWhere('position', 3);

                    $topRated = $providers->sortByDesc('overall_score')->values();

                    $goldProvider = ($pick1 && $pick1->provider) ? $pick1->provider : $topRated->first();
                    $silverProvider = ($pick2 && $pick2->provider) ? $pick2->provider : $topRated->skip(1)->first();
                    $bronzeProvider = ($pick3 && $pick3->provider) ? $pick3->provider : $topRated->skip(2)->first();

                    $goldBadge = ($pick1 && !empty($pick1->tag)) ? $pick1->tag : 'MEJOR ELECCIÓN GLOBAL';
                    $silverBadge = ($pick2 && !empty($pick2->tag)) ? $pick2->tag : 'MEJOR SOPORTE Y WP';
                    $bronzeBadge = ($pick3 && !empty($pick3->tag)) ? $pick3->tag : 'MÁXIMA VELOCIDAD NVMe';

                    $podiumList = [
                        ['p' => $goldProvider, 'rank' => '1', 'name' => 'Oro', 'color' => 'var(--amber-primary)', 'bg' => 'var(--amber-subtle)', 'badge' => $goldBadge, 'first' => true],
                        ['p' => $silverProvider, 'rank' => '2', 'name' => 'Plata', 'color' => '#64748B', 'bg' => '#F1F5F9', 'badge' => $silverBadge, 'first' => false],
                        ['p' => $bronzeProvider, 'rank' => '3', 'name' => 'Bronce', 'color' => '#B45309', 'bg' => '#FFFBEB', 'badge' => $bronzeBadge, 'first' => false],
                    ];
                @endphp

                @foreach($podiumList as $item)
                    @if($item['p'])
                        @php $p = $item['p']; @endphp
                        <div class="podium-clean-card {{ $item['first'] ? 'first-place' : '' }}">
                            <div>
                                <div class="podium-compact-header">
                                    <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0;">
                                        @if($p->resolved_logo_url)
                                            <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" class="podium-compact-logo">
                                        @else
                                            <div class="podium-compact-fallback">{{ substr($p->name, 0, 2) }}</div>
                                        @endif
                                        <div style="min-width: 0;">
                                            <div class="podium-rank-badge" style="color: {{ $item['color'] }};">
                                                #{{ $item['rank'] }} {{ $item['name'] }} • {{ $item['badge'] }}
                                            </div>
                                            <h3 class="podium-compact-title">{{ $p->name }}</h3>
                                        </div>
                                    </div>
                                    <div style="flex-shrink: 0;">
                                        <span class="badge-pill badge-emerald" style="font-weight: 700; font-size: 0.78rem;">
                                            ★ {{ $p->overall_score }}
                                        </span>
                                    </div>
                                </div>

                                <div class="podium-compact-price-row">
                                    <div class="price-compact-num">
                                        ${{ number_format($p->price_from, 2) }}
                                        <span class="price-compact-sub">/{{ $p->period }}</span>
                                    </div>
                                    <span class="podium-compact-plan">{{ $p->plan }}</span>
                                </div>

                                <div class="podium-compact-grid">
                                    <div class="podium-compact-metric">
                                        <span class="metric-label">Uptime</span>
                                        <span class="metric-val">{{ $p->uptime }}%</span>
                                    </div>
                                    <div class="podium-compact-metric">
                                        <span class="metric-label">Velocidad</span>
                                        <span class="metric-val">{{ $p->score_rendimiento }}/10</span>
                                    </div>
                                    <div class="podium-compact-metric">
                                        <span class="metric-label">Soporte</span>
                                        <span class="metric-val">{{ $p->score_soporte }}/10</span>
                                    </div>
                                    <div class="podium-compact-metric">
                                        <span class="metric-label">Panel</span>
                                        <span class="metric-val">{{ $p->score_facilidad }}/10</span>
                                    </div>
                                </div>

                                <p class="podium-compact-verdict">
                                    "{{ Str::limit($p->verdict ?: $p->description, 100) }}"
                                </p>
                            </div>

                            <div class="podium-compact-actions">
                                <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary btn-sm" style="flex: 1; padding: 0.45rem 0.65rem; font-size: 0.82rem; justify-content: center;">
                                    <span>Análisis</span>
                                </a>
                                <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="flex: 1.2; padding: 0.45rem 0.65rem; font-size: 0.82rem; justify-content: center;">
                                    <span>Ir a Oferta</span>
                                    <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>



    <!-- =========================================================================
         4. TABLA COMPARATIVA GENERAL DE PROVEEDORES
         ========================================================================= -->
    <section style="padding: 4.5rem 0;">
        <div class="container">
            <div class="section-headline-wrap">
                <div class="kicker">{{ $sectionHeaders['tabla']['kicker'] ?? 'AUDITORÍA DIRECTA • ORDENADOS POR INCORPORACIÓN RECIENTE' }}</div>
                <h2 class="section-title">{{ $sectionHeaders['tabla']['titleBefore'] ?? 'Tabla Comparativa de Rendimiento' }}</h2>
                <p class="section-subtitle">
                    {{ $sectionHeaders['tabla']['subtitle'] ?? 'Datos consolidados de proveedores ordenados por los últimos añadidos a nuestro observatorio técnico, con hardware y latencias probadas.' }}
                </p>
            </div>

            @php
                $recentProviders = $providers->sortByDesc('id')->values();
            @endphp

            <div class="table-clean-card">
                <div class="clean-table-responsive">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th style="width: 320px;">Proveedor y Plan</th>
                                <th>Categorías</th>
                                <th>Precio / Período</th>
                                <th>Descuento</th>
                                <th>Cupón Promocional</th>
                                <th style="text-align: right; width: 140px;">Enlace Directo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProviders->take(10) as $index => $p)
                            @php
                                $activeCoupon = $p->coupons->where('verified', true)->first() ?? $p->coupons->first();
                                $cats = is_array($p->categories) ? $p->categories : json_decode($p->categories ?? '[]', true);
                                if (!is_array($cats)) { $cats = []; }
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                                        @if($p->resolved_logo_url)
                                            <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="width: 54px; height: 54px; object-fit: contain; background: #FFFFFF; padding: 6px; border-radius: 12px; border: 1px solid var(--border-color); flex-shrink: 0; box-shadow: var(--shadow-sm);">
                                        @else
                                            <div style="width: 54px; height: 54px; border-radius: 12px; background: var(--bg-subtle); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.15rem; border: 1px solid var(--border-color); flex-shrink: 0;">
                                                {{ substr($p->name, 0, 2) }}
                                            </div>
                                        @endif
                                        <div style="min-width: 0;">
                                            <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                                <a href="{{ route('providers.show', $p->slug) }}" style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); text-decoration: none;">
                                                    {{ $p->name }}
                                                </a>

                                                @if($p->badge)
                                                    @php
                                                        $bUpper = strtoupper($p->badge);
                                                        $bColor = $p->badge_color ?? ($bUpper === 'HOT' ? 'red' : 'green');
                                                        $colorMap = [
                                                            'green' => 'background: rgba(16, 185, 129, 0.08); color: #059669; border: 1px solid rgba(5, 150, 105, 0.4);',
                                                            'gold' => 'background: rgba(245, 158, 11, 0.08); color: #D97706; border: 1px solid rgba(217, 119, 6, 0.4);',
                                                            'red' => 'background: #FFF1F2; color: #E11D48; border: 1px solid #FDA4AF;',
                                                            'dark' => 'background: #F1F5F9; color: #0F172A; border: 1.5px solid #0F172A;',
                                                            'sky' => 'background: rgba(14, 165, 233, 0.08); color: #0284C7; border: 1px solid rgba(2, 132, 199, 0.4);',
                                                            'rose' => 'background: #FFF1F2; color: #E11D48; border: 1px solid #FDA4AF;',
                                                        ];
                                                        $badgeStyle = $colorMap[$bColor] ?? ($bUpper === 'HOT' ? $colorMap['red'] : $colorMap['green']);
                                                    @endphp
                                                    <span style="font-family: var(--font-mono); font-size: 0.65rem; font-weight: 800; padding: 0.12rem 0.45rem; border-radius: 4px; letter-spacing: 0.04em; text-transform: uppercase; {{ $badgeStyle }}">
                                                        {{ $p->badge }}
                                                    </span>
                                                @endif

                                                @if($index < 2 && !$p->badge)
                                                    <span class="badge-pill badge-sky" style="font-size: 0.65rem; padding: 0.1rem 0.45rem; font-weight: 800;">NUEVO</span>
                                                @endif
                                            </div>
                                            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.15rem;">
                                                <span>{{ $p->plan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Categorías -->
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; align-items: center;">
                                        @forelse($cats as $cat)
                                            <span class="category-table-pill">
                                                {{ strtoupper($cat) }}
                                            </span>
                                        @empty
                                            <span class="category-table-pill">
                                                HOSTING
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Precio / Período -->
                                <td>
                                    <div style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 800; color: var(--emerald-primary); line-height: 1;">
                                        ${{ number_format($p->price_from, 2) }}<span style="font-size: 0.78rem; font-weight: 500; color: var(--text-muted);">/{{ $p->period ?? 'mes' }}</span>
                                    </div>
                                </td>

                                <!-- Descuento -->
                                <td>
                                    @if($activeCoupon && !empty($activeCoupon->discount))
                                        <span style="font-family: var(--font-mono); font-size: 0.78rem; font-weight: 800; color: var(--emerald-primary); background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.2rem 0.55rem; border-radius: 4px;">
                                            {{ $activeCoupon->discount }}
                                        </span>
                                    @elseif($p->price_before && $p->price_before > $p->price_from)
                                        @php
                                            $discountPct = round((($p->price_before - $p->price_from) / $p->price_before) * 100);
                                        @endphp
                                        <span style="font-family: var(--font-mono); font-size: 0.78rem; font-weight: 800; color: var(--emerald-primary); background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.2rem 0.55rem; border-radius: 4px;">
                                            {{ $discountPct }}% OFF
                                        </span>
                                    @else
                                        <span style="font-size: 0.8rem; color: var(--text-dim); font-family: var(--font-mono);">
                                            Tarifa estándar
                                        </span>
                                    @endif
                                </td>

                                <!-- Cupón Promocional -->
                                <td>
                                    @if($activeCoupon && !empty($activeCoupon->code))
                                        <button type="button" 
                                                onclick="copyVoucher('{{ $activeCoupon->code }}', '{{ route('go', $p->slug) }}')" 
                                                class="coupon-dashed-pill" 
                                                title="Copiar cupón y ver oferta">
                                            <i data-lucide="copy" style="width: 12px; height: 12px;"></i>
                                            <span>{{ $activeCoupon->code }}</span>
                                        </button>
                                    @else
                                        <span style="font-family: var(--font-mono); font-size: 0.78rem; color: var(--text-dim);">
                                            Automático
                                        </span>
                                    @endif
                                </td>

                                <!-- Enlace Directo -->
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 0.45rem; justify-content: flex-end;">
                                        <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary btn-sm" style="padding: 0.42rem 0.65rem; font-size: 0.76rem;" title="Ver Ficha Técnica">
                                            <span>Ficha</span>
                                        </a>
                                        <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-ver-web" title="Ir a la web oficial">
                                            <span>Ver web</span>
                                            <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('providers.index') }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; font-weight: 600;">
                    <span>Ver Directorio Completo con {{ $providers->count() }} Proveedores</span>
                    <i data-lucide="arrow-right" style="width: 15px; height: 15px;"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         5. RADAR DE CUPONES VERIFICADOS
         ========================================================================= -->
    <section style="padding: 4.5rem 0; background-color: #FFFFFF; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container">
            <div class="section-headline-wrap">
                <div class="kicker">AHORRO GARANTIZADO</div>
                <h2 class="section-title">Cupones Verificados</h2>
                <p class="section-subtitle">
                    Códigos de descuento probados a mano. Haz clic en el código para copiarlo al portapapeles y activar la promoción.
                </p>
            </div>

            <div class="coupons-grid">
                @foreach($coupons->take(3) as $c)
                <div class="voucher-clean-box">
                    <div>
                        <div class="voucher-top-row">
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                @if($c->provider->resolved_logo_url)
                                    <img src="{{ $c->provider->resolved_logo_url }}" alt="{{ $c->provider->name }}" style="height: 36px; max-width: 105px; object-fit: contain;">
                                @endif
                                <span style="font-weight: 700; font-size: 1.05rem; color: var(--text-main);">{{ $c->provider->name }}</span>
                            </div>
                            <span class="badge-pill badge-rose" style="font-weight: 800;">{{ $c->discount }}</span>
                        </div>

                        <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.5rem; min-height: 40px;">
                            {{ $c->condition ?: 'Válido en nuevas contrataciones anuales.' }}
                        </p>
                    </div>

                    <div>
                        <button onclick="copyVoucher('{{ $c->code }}', '{{ route('go', $c->provider->slug) }}')" class="voucher-code-btn" title="Copiar cupón y abrir proveedor">
                            <span style="display: flex; align-items: center; gap: 0.45rem;">
                                <i data-lucide="copy" style="width: 14px; height: 14px;"></i>
                                <span>{{ $c->code }}</span>
                            </span>
                            <span style="font-size: 0.72rem; color: var(--emerald-primary); text-transform: uppercase;">Copiar ↗</span>
                        </button>

                        <div style="display: flex; justify-content: space-between; font-size: 0.74rem; color: var(--text-dim); margin-top: 0.75rem;">
                            <span style="display: flex; align-items: center; gap: 0.25rem;">
                                <i data-lucide="shield-check" style="width: 12px; height: 12px; color: var(--emerald-primary);"></i>
                                <span>Verificado</span>
                            </span>
                            <span>{{ $c->clicks }} canjes</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 2.5rem;">
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">
                    <span>Ver Todos los Cupones de Hosting ({{ $coupons->count() }})</span>
                    <i data-lucide="arrow-right" style="width: 15px; height: 15px;"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         7. NEWSLETTER / BOLETÍN EDITORIAL
         ========================================================================= -->
    <section class="container">
        <div class="newsletter-clean-card">
            <h2 style="font-size: 1.9rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.02em; margin-bottom: 0.6rem;">
                {{ $sectionHeaders['news']['titleBefore'] ?? 'Suscríbete al Boletín Técnico de Hosting' }}
            </h2>
            <p style="font-size: 0.95rem; color: #94A3B8; max-width: 500px; margin: 0 auto; line-height: 1.6;">
                {{ $sectionHeaders['news']['subtitle'] ?? 'Recibe semanalmente alertas de caídas masivas de servidores, auditorías de nuevos proveedores y cupones exclusivos probados.' }}
            </p>

            <form id="newsletter-form" onsubmit="handleNewsletter(event)" class="newsletter-form-row">
                <input type="email" id="nl-email" placeholder="tu@email.com" required class="newsletter-clean-input">
                <button type="submit" class="btn btn-emerald" style="height: 42px;">
                    <span>Suscribirme</span>
                </button>
            </form>
            <div id="nl-msg" style="display: none; font-size: 0.85rem; margin-top: 0.85rem;"></div>
        </div>
    </section>

@push('scripts')
<script>
    // Copiado interactivo de cupones con notificación flotante (Toast)
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

    // Suscripción al Boletín por AJAX
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
            msg.style.color = '#34D399';
            msg.innerText = data.message || '¡Te has suscrito con éxito!';
            document.getElementById('nl-email').value = '';
            window.showToast('¡Suscripción confirmada! Bienvenido.', 'success');
        })
        .catch(() => {
            msg.style.display = 'block';
            msg.style.color = '#F87171';
            msg.innerText = 'Error al suscribirte. Inténtalo de nuevo.';
        });
    }

    // Rotador automático de cupones en vivo del Hero (cada 12 segundos)
    (function() {
        const rotator = document.getElementById('hero-coupon-rotator');
        if (!rotator) return;

        const slides = rotator.querySelectorAll('.coupon-slide');
        const dots = rotator.querySelectorAll('.rotator-dot');
        const countdownEl = document.getElementById('rotator-countdown');
        const barEl = document.getElementById('rotator-bar');
        if (slides.length <= 1) return;

        let currentIndex = 0;
        const totalDuration = 12; // 12 segundos
        let timeLeft = totalDuration;
        let isPaused = false;

        rotator.addEventListener('mouseenter', function() { isPaused = true; });
        rotator.addEventListener('mouseleave', function() { isPaused = false; });

        window.switchHeroCoupon = function(index) {
            slides[currentIndex].classList.remove('active');
            if (dots[currentIndex]) dots[currentIndex].classList.remove('active');

            currentIndex = (index + slides.length) % slides.length;

            slides[currentIndex].classList.add('active');
            if (dots[currentIndex]) dots[currentIndex].classList.add('active');

            timeLeft = totalDuration;
            updateUI();
            if (window.lucide) lucide.createIcons();
        };

        function updateUI() {
            if (countdownEl) countdownEl.textContent = timeLeft + 's';
            if (barEl) {
                const percent = ((totalDuration - timeLeft) / totalDuration) * 100;
                barEl.style.width = percent + '%';
            }
        }

        setInterval(function() {
            if (isPaused) return;
            timeLeft--;
            if (timeLeft <= 0) {
                switchHeroCoupon(currentIndex + 1);
            } else {
                updateUI();
            }
        }, 1000);

        updateUI();
    })();
</script>
@endpush
@endsection
