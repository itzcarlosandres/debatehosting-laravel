@extends('layouts.admin')

@section('title', 'Dashboard de Rendimiento — Debate Admin')

@section('content')
<!-- Estilos dedicados para los componentes del Dashboard -->
<style>
    .dashboard-header-wrap {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .dashboard-kicker {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--emerald-primary);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.35rem;
    }

    .dashboard-title {
        font-size: clamp(1.4rem, 4vw, 1.95rem);
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    /* Grilla de 8 KPIs */
    .kpis-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.15rem;
        margin-bottom: 1.75rem;
    }

    @media (max-width: 1100px) {
        .kpis-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .kpis-grid {
            grid-template-columns: 1fr;
        }
    }

    .kpi-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-sm);
        padding: 1.15rem 1.35rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 95px;
        transition: border-color 0.15s ease, transform 0.15s ease;
    }

    .kpi-card:hover {
        border-color: var(--border-medium);
        transform: translateY(-2px);
    }

    .kpi-header {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-dim);
    }

    .kpi-header-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .kpi-value {
        font-family: var(--font-mono);
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1;
        margin-top: 0.65rem;
        color: #FFFFFF;
        letter-spacing: -0.02em;
    }

    .kpi-value.emerald {
        color: var(--emerald-primary);
    }

    /* Secciones Generales */
    .dash-section-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        margin-bottom: 1.75rem;
        overflow: hidden;
    }

    .dash-section-header {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .dash-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.98rem;
        font-weight: 700;
        color: #FFFFFF;
    }

    .dash-section-subtitle {
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: var(--text-dim);
    }

    /* Gráfico de Barras de Actividad */
    .barchart-container {
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        height: 175px;
        padding: 1.75rem 2rem 1rem 2rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .barchart-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        height: 100%;
        flex: 1;
        max-width: 65px;
    }

    .barchart-count {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--emerald-primary);
        margin-bottom: 0.5rem;
    }

    .barchart-bar {
        width: 32px;
        background-color: var(--emerald-primary);
        border-radius: 4px 4px 0 0;
        transition: height 0.4s ease, background-color 0.2s ease;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
    }

    .barchart-bar:hover {
        background-color: #34D399;
    }

    .barchart-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-dim);
        text-transform: uppercase;
        margin-top: 0.65rem;
    }

    /* Lista de Top Proveedores */
    .top-provider-item {
        padding: 0.85rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: background-color 0.15s ease;
    }

    .top-provider-item:last-child {
        border-bottom: none;
    }

    .top-provider-item:hover {
        background-color: var(--bg-hover);
    }

    .top-provider-progress-track {
        width: 100%;
        height: 7px;
        background-color: #1A1E26;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.4rem;
    }

    .top-provider-progress-fill {
        height: 100%;
        background-color: var(--emerald-primary);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    /* Badges de Telemetría */
    .telemetry-badge {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.18rem 0.52rem;
        border-radius: 4px;
        white-space: nowrap;
    }

    .telemetry-badge.coupon {
        background-color: rgba(16, 185, 129, 0.08);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }

    .telemetry-badge.redirect {
        background-color: rgba(16, 185, 129, 0.05);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.22);
    }

    /* Tabla de Telemetría */
    .telemetry-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.84rem;
        text-align: left;
    }

    .telemetry-table th {
        background-color: var(--bg-sidebar);
        padding: 0.85rem 1.5rem;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid var(--border-subtle);
    }

    .telemetry-table td {
        padding: 0.95rem 1.5rem;
        border-bottom: 1px solid var(--border-subtle);
        vertical-align: middle;
        color: var(--text-main);
    }

    .telemetry-table tr:last-child td {
        border-bottom: none;
    }

    .telemetry-table tr:hover td {
        background-color: var(--bg-hover);
    }

    @media (max-width: 640px) {
        .dashboard-header-wrap {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        .dashboard-header-wrap button {
            width: 100%;
            justify-content: center;
        }
        .barchart-container {
            padding: 1.25rem 0.5rem 0.75rem 0.5rem;
            height: 155px;
        }
        .barchart-bar {
            width: 18px;
        }
        .barchart-count {
            font-size: 0.68rem;
        }
        .barchart-label {
            font-size: 0.65rem;
        }
        .dash-section-header {
            padding: 1rem;
            flex-direction: column;
            align-items: flex-start;
        }
        .top-provider-item {
            padding: 0.75rem 1rem;
        }
        .telemetry-table th,
        .telemetry-table td {
            padding: 0.75rem 1rem;
        }
    }
</style>

<!-- Encabezado del Dashboard -->
<div class="dashboard-header-wrap">
    <div>
        <div class="dashboard-kicker">
            <span>MÉTRICAS EDITORIALES EN TIEMPO REAL</span>
            <span>—</span>
        </div>
        <h1 class="dashboard-title">Dashboard de Rendimiento</h1>
    </div>

    <div>
        <button type="button" onclick="window.location.reload()" class="btn-secondary" style="font-family: var(--font-sans); font-weight: 600; font-size: 0.82rem; padding: 0.55rem 1rem;">
            <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
            <span>Actualizar Datos</span>
        </button>
    </div>
</div>

<!-- =========================================================================
     1. GRILLA DE 8 TARJETAS KPI (METRICS)
     ========================================================================= -->
<div class="kpis-grid">
    <!-- Fila 1: Métricas de Tiempo -->
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-header-dot" style="background-color: var(--emerald-primary);"></span>
            <span>CLICS DE HOY (24H)</span>
        </div>
        <div class="kpi-value emerald">{{ number_format($todayClicks) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-header-dot" style="background-color: var(--sky-primary);"></span>
            <span>ÚLTIMOS 7 DÍAS</span>
        </div>
        <div class="kpi-value">{{ number_format($clicks7Days) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-header-dot" style="background-color: var(--sky-primary);"></span>
            <span>ÚLTIMOS 30 DÍAS</span>
        </div>
        <div class="kpi-value">{{ number_format($clicks30Days) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-header-dot" style="background-color: #A855F7;"></span>
            <span>HISTÓRICO TOTAL</span>
        </div>
        <div class="kpi-value">{{ number_format($historicalClicks) }}</div>
    </div>

    <!-- Fila 2: Canales y Conversión -->
    <div class="kpi-card">
        <div class="kpi-header">
            <i data-lucide="link-2" style="width: 13px; height: 13px; color: var(--text-dim);"></i>
            <span>SALIDAS A HOSTING (/GO/)</span>
        </div>
        <div class="kpi-value">{{ number_format($affiliateClicks) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <i data-lucide="ticket" style="width: 13px; height: 13px; color: var(--rose-primary);"></i>
            <span>COPIAS DE CUPÓN</span>
        </div>
        <div class="kpi-value">{{ number_format($couponCopies) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <i data-lucide="mail" style="width: 13px; height: 13px; color: var(--sky-primary);"></i>
            <span>SUSCRIPTORES NEWSLETTER</span>
        </div>
        <div class="kpi-value">{{ number_format($totalSubscribers) }}</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <i data-lucide="check-circle" style="width: 13px; height: 13px; color: var(--amber-primary);"></i>
            <span>CUPONES VERIFICADOS</span>
        </div>
        <div class="kpi-value">{{ number_format($verifiedCoupons) }}</div>
    </div>
</div>

<!-- =========================================================================
     2. ACTIVIDAD DIARIA DE CLICS (ÚLTIMOS 7 DÍAS)
     ========================================================================= -->
<div class="dash-section-card">
    <div class="dash-section-header">
        <div class="dash-section-title">
            <span>📊</span>
            <span>Actividad Diaria de Clics (Últimos 7 Días)</span>
        </div>
        <div class="dash-section-subtitle">
            Total 7 días: <strong style="color: var(--emerald-primary);">{{ number_format($totalDailyClicks7) }} clics</strong>
        </div>
    </div>

    <div class="barchart-container">
        @foreach($dailyActivity as $day)
            @php
                $ratio = $maxDailyClicks > 0 ? ($day['count'] / $maxDailyClicks) : 0;
                $barHeightPct = max(round($ratio * 82), $day['count'] > 0 ? 8 : 3);
            @endphp
            <div class="barchart-col">
                <span class="barchart-count">{{ $day['count'] }}</span>
                <div class="barchart-bar" style="height: {{ $barHeightPct }}%; opacity: {{ $day['count'] > 0 ? '1' : '0.2' }};"></div>
                <span class="barchart-label">{{ $day['dayLabel'] }}</span>
            </div>
        @endforeach
    </div>
</div>

<!-- =========================================================================
     3. TOP PROVEEDORES POR TRÁFICO DE AFILIADOS
     ========================================================================= -->
<div class="dash-section-card">
    <div class="dash-section-header">
        <div class="dash-section-title">
            <span>🏆</span>
            <span>Top Proveedores por Tráfico de Afiliados</span>
        </div>
        <div class="dash-section-subtitle">
            Enlaces Limpios (/go/{slug})
        </div>
    </div>

    <div>
        @forelse($topProviders as $tp)
            @php
                $pct = $totalProviderClicks > 0 ? round(($tp->clicks / $totalProviderClicks) * 100) : 0;
            @endphp
            <div class="top-provider-item">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-weight: 700; color: #FFFFFF; font-size: 0.92rem;">{{ $tp->name }}</span>
                        <a href="{{ route('go', $tp->slug) }}" target="_blank" rel="noopener noreferrer" style="color: var(--emerald-primary); font-family: var(--font-mono); font-size: 0.76rem; text-decoration: none;">
                            /go/{{ $tp->slug }}
                        </a>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.65rem; font-family: var(--font-mono); font-size: 0.78rem;">
                        <span style="color: var(--text-dim);">{{ $pct }}% del total</span>
                        <strong style="color: #FFFFFF; font-weight: 700;">{{ number_format($tp->clicks) }} clics</strong>
                    </div>
                </div>
                <div class="top-provider-progress-track">
                    <div class="top-provider-progress-fill" style="width: {{ $pct }}%;"></div>
                </div>
            </div>
        @empty
            <div style="padding: 2.5rem; text-align: center; color: var(--text-dim); font-size: 0.88rem;">
                No hay registros de tráfico aún.
            </div>
        @endforelse
    </div>
</div>

<!-- =========================================================================
     4. ÚLTIMOS CLICS Y CONVERSIONES REGISTRADAS (TELEMETRÍA EN VIVO)
     ========================================================================= -->
<div class="dash-section-card">
    <div class="dash-section-header">
        <div class="dash-section-title">
            <span>⚡</span>
            <span>Últimos Clics y Conversiones Registradas</span>
        </div>
        <div>
            <span class="live-status-pill">
                <span class="pulse-live-dot"></span>
                <span>Telemetría en Vivo</span>
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="telemetry-table">
            <thead>
                <tr>
                    <th style="width: 210px;">Fecha y Hora</th>
                    <th style="width: 200px;">Tipo de Evento</th>
                    <th>Proveedor</th>
                    <th>Cupón Involucrado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentClicks as $rc)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.76rem; color: var(--text-muted);">
                        {{ $rc->created_at->format('d/m/Y, H:i:s') }}
                    </td>
                    <td>
                        @if($rc->type === 'coupon_copy')
                            <span class="telemetry-badge coupon">CUPÓN + ENLACE</span>
                        @else
                            <span class="telemetry-badge redirect">REDIRECCIÓN /GO/</span>
                        @endif
                    </td>
                    <td>
                        @if($rc->provider)
                            <a href="{{ route('go', $rc->provider->slug) }}" target="_blank" rel="noopener noreferrer" style="color: #FFFFFF; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span>{{ $rc->provider->name }}</span>
                                <i data-lucide="arrow-up-right" style="width: 12px; height: 12px; color: var(--text-dim);"></i>
                            </a>
                        @else
                            <span style="color: var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($rc->coupon)
                            <span style="font-family: var(--font-mono); font-weight: 700; color: #FFFFFF; font-size: 0.8rem; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-subtle); padding: 0.15rem 0.5rem; border-radius: 4px;">
                                {{ $rc->coupon->code }}
                            </span>
                        @else
                            <span style="color: var(--text-dim); font-family: var(--font-mono);">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-dim); padding: 2.5rem;">
                        No hay eventos de telemetría registrados todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
