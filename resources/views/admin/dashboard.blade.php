@extends('layouts.admin')

@section('title', 'Dashboard — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Panel de Control</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Resumen de actividad y métricas de DebateHosting.</p>
    </div>
    <a href="{{ route('admin.providers.create') }}" class="btn-action">+ Nuevo Proveedor</a>
</div>

<!-- Tarjetas de Estadísticas -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem;">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Proveedores Activos</span>
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent); margin-top: 0.25rem;">
            {{ $activeProviders }} / {{ $totalProviders }}
        </div>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem;">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Cupones Registrados</span>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.25rem;">
            {{ $totalCoupons }}
        </div>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem;">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Total Clics Afiliados</span>
        <div style="font-size: 2rem; font-weight: 700; color: #38BDF8; margin-top: 0.25rem;">
            {{ $totalClicks }}
        </div>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem;">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Suscriptores Newsletter</span>
        <div style="font-size: 2rem; font-weight: 700; color: #F59E0B; margin-top: 0.25rem;">
            {{ $totalSubscribers }}
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Top Proveedores más visitados -->
    <div>
        <h2 style="font-size: 1.2rem; margin-bottom: 1rem;">🔥 Proveedores con más clics</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Plan</th>
                        <th>Clics</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProviders as $tp)
                    <tr>
                        <td style="font-weight: 600;">{{ $tp->name }}</td>
                        <td style="color: var(--text-muted);">{{ $tp->plan }}</td>
                        <td style="font-family: 'IBM Plex Mono', monospace; font-weight: 700; color: var(--accent);">{{ $tp->clicks }}</td>
                        <td>
                            <a href="{{ route('admin.providers.edit', $tp) }}" style="color: #38BDF8; text-decoration: none; font-size: 0.85rem;">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div>
        <h2 style="font-size: 1.2rem; margin-bottom: 1rem;">⚡ Registro reciente de clics</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentClicks as $rc)
                    <tr>
                        <td>
                            <span style="background: #162032; border: 1px solid var(--border-color); font-size: 0.75rem; padding: 0.2rem 0.4rem; border-radius: 4px;">
                                {{ $rc->type == 'affiliate_link' ? '🔗 Afiliado' : '📋 Cupón' }}
                            </span>
                        </td>
                        <td style="font-weight: 600;">
                            {{ $rc->provider ? $rc->provider->name : ($rc->coupon ? $rc->coupon->code : 'General') }}
                        </td>
                        <td style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ $rc->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-muted);">No hay clics registrados aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
