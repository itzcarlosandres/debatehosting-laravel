@extends('layouts.app')

@section('title', 'Debatehosting — El Gran Observatorio de Hosting, VPS y Cupones')

@section('content')
    <!-- =========================================================================
         1. HERO SECTION EDITORIAL
         ========================================================================= -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-kicker">
                    <span class="pulse-dot"></span>
                    {{ $hero['kickerText'] ?? 'OBSERVATORIO DE HOSTING Y NUBE' }}
                </div>

                <h1 class="hero-title">
                    {{ $hero['titleBefore'] ?? 'El gran' }}
                    <span class="hero-title-highlight">{{ $hero['titleHighlight'] ?? 'debate' }}</span>
                    {{ $hero['titleAfter'] ?? 'del hosting.' }}
                </h1>

                <p class="hero-description">
                    {{ $hero['description'] ?? 'Comparamos proveedores en vivo sin sesgos ni publicidad encubierta. Ajusta tus prioridades en La Balanza, analiza las métricas de rendimiento y decide quién se queda con tu proyecto.' }}
                </p>

                <div class="hero-actions">
                    <a href="{{ route('balanza') }}" class="btn-primary-editorial">
                        ⚖️ Pesar en La Balanza
                    </a>
                    <a href="#ofertas" class="btn-secondary-editorial">
                        Ver Ofertas y Cupones →
                    </a>
                </div>

                <!-- Contadores Estadísticos -->
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">{{ $providers->count() }}</span>
                        <span class="hero-stat-label">Proveedores auditados</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">{{ $coupons->count() }}</span>
                        <span class="hero-stat-label">Cupones verificados</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">0</span>
                        <span class="hero-stat-label">Patrocinios pagados</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Editorial Duelo en Vivo -->
            <div class="hero-preview-col">
                <div class="hero-duel-card">
                    <div class="duel-card-header">
                        <span class="duel-badge">COMPARATIVA EDITORIAL EN VIVO</span>
                        <span class="pulse-dot"></span>
                    </div>
                    
                    <div class="duel-card-body">
                        <div class="duel-fighters">
                            <div class="fighter-side">
                                <div class="fighter-logo-box">
                                    <img src="{{ asset('storage/logos/hostinger.svg') }}" alt="Hostinger" style="max-height: 38px;">
                                </div>
                                <span class="fighter-name">Hostinger</span>
                                <span class="fighter-price">$2.49/mes</span>
                                <span class="fighter-score" style="color: var(--green-primary); font-weight: 700;">8.8 / 10</span>
                            </div>

                            <div class="duel-versus">VS</div>

                            <div class="fighter-side">
                                <div class="fighter-logo-box">
                                    <img src="{{ asset('storage/logos/siteground.svg') }}" alt="SiteGround" style="max-height: 38px;">
                                </div>
                                <span class="fighter-name">SiteGround</span>
                                <span class="fighter-price">$3.99/mes</span>
                                <span class="fighter-score" style="color: var(--green-primary); font-weight: 700;">9.4 / 10</span>
                            </div>
                        </div>

                        <div class="duel-card-summary">
                            <strong>El Veredicto Rápido:</strong> Si buscas máxima economía y buen rendimiento, <em>Hostinger</em> se lleva el punto. Si la prioridad es soporte instantáneo y WordPress gestionado de élite, <em>SiteGround</em> gana el duelo.
                        </div>

                        <a href="{{ route('balanza') }}" class="btn-primary-editorial" style="width: 100%; justify-content: center; font-size: 0.9rem;">
                            Probar mi caso en La Balanza
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. EL PODIO EDITORIAL (TOP PICKS)
         ========================================================================= -->
    @if($picks->count() > 0)
    <section class="section-container" style="background: var(--bg-surface); border-top: var(--border-width) solid var(--border-ink); border-bottom: var(--border-width) solid var(--border-ink); padding: 4rem 1.5rem;">
        <div class="max-w-content" style="max-width: 1200px; margin: 0 auto;">
            <div class="section-header-editorial" style="text-align: center; margin-bottom: 3rem;">
                <span class="section-kicker">LOS 3 ELEGIDOS DE LA REDACCIÓN</span>
                <h2 class="section-title">El Podio del Hosting</h2>
                <p class="section-subtitle">Las tres opciones definitivas que recomendamos con los ojos cerrados este año.</p>
            </div>

            <div class="podio-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
                @foreach($picks as $pick)
                <div class="podio-card" style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); box-shadow: var(--shadow-solid); padding: 2rem; display: flex; flexDirection: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                            <span class="badge-editorial" style="background: {{ $pick->position == 1 ? '#FEF08A' : ($pick->position == 2 ? '#E2E8F0' : '#FFEDD5') }}; color: #17140F; border: var(--border-width) solid var(--border-ink); font-weight: 700; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm);">
                                #{{ $pick->position }} {{ $pick->tag }}
                            </span>
                            <span style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--green-primary);">
                                ★ {{ $pick->provider->overall_score }}
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                            @if($pick->provider->resolved_logo_url)
                                <img src="{{ $pick->provider->resolved_logo_url }}" alt="{{ $pick->provider->name }}" style="height: 48px; max-width: 120px; object-fit: contain;">
                            @else
                                <div style="width: 48px; height: 48px; background: #0E6B41; color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 6px;">
                                    {{ substr($pick->provider->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h3 style="font-family: var(--font-serif); font-size: 1.5rem; line-height: 1.2;">
                                    <a href="{{ route('providers.show', $pick->provider->slug) }}" style="color: inherit; text-decoration: none;">
                                        {{ $pick->provider->name }}
                                    </a>
                                </h3>
                                <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $pick->provider->plan }}</span>
                            </div>
                        </div>

                        <h4 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-ink);">
                            "{{ $pick->titulo }}"
                        </h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.5rem;">
                            {{ $pick->veredicto }}
                        </p>
                    </div>

                    <div style="border-top: 1px dashed var(--border-ink); padding-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Desde</span>
                            <div style="font-family: var(--font-mono); font-size: 1.35rem; font-weight: 700;">
                                ${{ number_format($pick->provider->price_from, 2) }}<span style="font-size: 0.8rem; font-weight: 400;">/{{ $pick->provider->period }}</span>
                            </div>
                        </div>
                        <a href="{{ route('go', $pick->provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary-editorial" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
                            Ver Oferta →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- =========================================================================
         3. DIRECTORIO DE OFERTAS Y PROVEEDORES
         ========================================================================= -->
    <section id="ofertas" class="section-container" style="padding: 4rem 1.5rem; max-width: 1200px; margin: 0 auto;">
        <div class="section-header-editorial" style="margin-bottom: 2.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="section-kicker">TABLA DE AUDITORÍA INDEPENDIENTE</span>
                    <h2 class="section-title">Directorio de Proveedores</h2>
                    <p class="section-subtitle">Evaluamos rendimiento de servidor, precio y soporte sin rodeos.</p>
                </div>
                <a href="{{ route('providers.index') }}" class="btn-secondary-editorial" style="font-size: 0.9rem;">
                    Explorar todos los filtros →
                </a>
            </div>
        </div>

        <!-- Tarjetas de Proveedores -->
        <div class="providers-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($providers as $p)
            <div class="provider-dossier-card" style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); box-shadow: var(--shadow-solid); padding: 1.5rem; display: grid; grid-template-columns: 240px 1fr 200px; gap: 2rem; align-items: center;">
                <!-- Columna Marca -->
                <div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                        @if($p->resolved_logo_url)
                            <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 42px; max-width: 100px; object-fit: contain;">
                        @else
                            <div style="width: 42px; height: 42px; background: #0E6B41; color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                {{ substr($p->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <h3 style="font-family: var(--font-serif); font-size: 1.35rem; margin: 0;">
                                <a href="{{ route('providers.show', $p->slug) }}" style="color: inherit; text-decoration: none;">{{ $p->name }}</a>
                            </h3>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $p->plan }}</span>
                        </div>
                    </div>

                    @if($p->badge)
                    <span class="badge-editorial" style="background: var(--green-tint); color: var(--green-dark); border: 1px solid var(--green-primary); font-size: 0.7rem; padding: 0.2rem 0.5rem; border-radius: 3px; font-weight: 600;">
                        ★ {{ $p->badge }}
                    </span>
                    @endif
                </div>

                <!-- Columna Métricas -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; border-left: 1px dashed var(--border-ink); border-right: 1px dashed var(--border-ink); padding: 0 1.5rem;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Precio</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_precio }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Velocidad</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_rendimiento }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Soporte</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_soporte }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Uptime</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--green-primary);">{{ $p->uptime }}%</div>
                    </div>
                </div>

                <!-- Columna Precio y Acción -->
                <div style="text-align: right;">
                    @if($p->discount_percent > 0)
                    <span style="background: #FDE8E5; color: #B03A26; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 3px;">
                        -{{ $p->discount_percent }}% OFF
                    </span>
                    @endif
                    <div style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 800; color: var(--text-ink); margin: 0.25rem 0;">
                        ${{ number_format($p->price_from, 2) }}<span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/{{ $p->period }}</span>
                    </div>
                    <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary-editorial" style="width: 100%; justify-content: center; padding: 0.6rem 1rem; font-size: 0.9rem;">
                        Contratar Oferta ↗
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- =========================================================================
         4. CUPONES VERIFICADOS
         ========================================================================= -->
    @if($coupons->count() > 0)
    <section class="section-container" style="background: var(--bg-surface); border-top: var(--border-width) solid var(--border-ink); padding: 4rem 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div class="section-header-editorial" style="text-align: center; margin-bottom: 2.5rem;">
                <span class="section-kicker">CÓDIGOS PROMOCIONALES VERIFICADOS</span>
                <h2 class="section-title">Cupones Activos del Mes</h2>
                <p class="section-subtitle">Probados manualmente esta semana para garantizar que se apliquen sin sorpresas.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($coupons as $coupon)
                <div style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); padding: 1.5rem; box-shadow: var(--shadow-subtle);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="font-weight: 700; font-size: 1.1rem; font-family: var(--font-serif);">{{ $coupon->provider->name }}</span>
                        <span style="background: var(--green-soft); color: var(--green-dark); font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 3px;">
                            {{ $coupon->discount }}
                        </span>
                    </div>

                    <p style="font-size: 0.85rem; color: var(--text-muted); min-height: 40px; margin-bottom: 1rem;">
                        {{ $coupon->condition }}
                    </p>

                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="copyCoupon('{{ $coupon->code }}', '{{ route('go', $coupon->provider->slug) }}')" class="btn-primary-editorial" style="flex: 1; justify-content: center; font-size: 0.85rem; font-family: var(--font-mono);">
                            📋 {{ $coupon->code }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <script>
        function copyCoupon(code, url) {
            navigator.clipboard.writeText(code).then(() => {
                alert('¡Código copiado: ' + code + '! Redirigiendo a la oferta...');
                window.open(url, '_blank');
            });
        }
    </script>
@endsection
