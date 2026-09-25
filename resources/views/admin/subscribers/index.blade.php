@extends('layouts.admin')

@section('title', 'Suscriptores del Boletín')

@section('content')
<!-- Encabezado de la Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Suscriptores del Boletín</h1>
        <p class="page-header-subtitle">Gestión de la audiencia de «El Debate», suscriptores a las auditorías y radar de ofertas dominical.</p>
    </div>
    <div class="page-header-actions" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('admin.subscribers.export') }}" class="btn-secondary" title="Descargar archivo CSV con todos los correos">
            <i data-lucide="download" style="width: 15px; height: 15px;"></i>
            <span>Exportar CSV</span>
        </a>
        <button type="button" onclick="toggleCreateSubscriberPanel()" class="btn-primary" id="btn-toggle-create">
            <i data-lucide="user-plus" style="width: 15px; height: 15px;"></i>
            <span>Nuevo Suscriptor</span>
        </button>
    </div>
</div>

<!-- Tarjetas de Métricas de la Audiencia -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="form-panel" style="padding: 1.25rem; margin-bottom: 0; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i data-lucide="users" style="width: 20px; height: 20px; color: var(--emerald-primary);"></i>
        </div>
        <div>
            <div style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); font-family: var(--font-mono); letter-spacing: 0.05em;">Total Audiencia</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; font-family: var(--font-mono); line-height: 1.2;">{{ number_format($totalSubscribers) }}</div>
        </div>
    </div>

    <div class="form-panel" style="padding: 1.25rem; margin-bottom: 0; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(14, 165, 233, 0.1); border: 1px solid rgba(14, 165, 233, 0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i data-lucide="calendar" style="width: 20px; height: 20px; color: var(--sky-primary);"></i>
        </div>
        <div>
            <div style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); font-family: var(--font-mono); letter-spacing: 0.05em;">Nuevos Este Mes</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; font-family: var(--font-mono); line-height: 1.2;">+{{ number_format($newThisMonth) }}</div>
        </div>
    </div>

    <div class="form-panel" style="padding: 1.25rem; margin-bottom: 0; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i data-lucide="zap" style="width: 20px; height: 20px; color: #F59E0B;"></i>
        </div>
        <div>
            <div style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); font-family: var(--font-mono); letter-spacing: 0.05em;">Últimos 7 Días</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; font-family: var(--font-mono); line-height: 1.2;">+{{ number_format($newThisWeek) }}</div>
        </div>
    </div>
</div>

<!-- Panel Plegable para Agregar Suscriptor Manual -->
<div id="create-subscriber-panel" class="form-panel" style="display: none; margin-bottom: 2rem; border-color: rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(20, 24, 33, 0.98) 100%);">
    <div class="form-panel-header" style="display: flex; align-items: center; justify-content: space-between;">
        <div class="form-panel-title">
            <i data-lucide="user-plus" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
            <span>Añadir Suscriptor al Boletín</span>
        </div>
        <button type="button" onclick="toggleCreateSubscriberPanel()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
        </button>
    </div>

    <form action="{{ route('admin.subscribers.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Correo Electrónico *</label>
                <div style="position: relative;">
                    <i data-lucide="mail" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--text-muted);"></i>
                    <input type="email" name="email" class="form-control" placeholder="ejemplo@dominio.com" style="padding-left: 2.35rem;" required>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" onclick="toggleCreateSubscriberPanel()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                    <span>Registrar</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabla y Buscador de Suscriptores -->
<div class="table-card">
    <div class="table-card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div class="table-card-title">
            <i data-lucide="mail-check" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
            <span>{{ $subscribers->total() }} Registros encontrados</span>
        </div>

        <!-- Buscador por correo -->
        <form method="GET" action="{{ route('admin.subscribers.index') }}" style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text-muted);"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por correo..." class="form-control" style="padding-left: 2.2rem; padding-top: 0.45rem; padding-bottom: 0.45rem; font-size: 0.82rem; width: 230px;">
            </div>
            <button type="submit" class="btn-secondary" style="padding: 0.45rem 0.75rem; font-size: 0.82rem;">Buscar</button>
            @if(request('q'))
                <a href="{{ route('admin.subscribers.index') }}" class="btn-secondary" style="padding: 0.45rem 0.65rem; font-size: 0.82rem;" title="Limpiar búsqueda">
                    <i data-lucide="x" style="width: 13px; height: 13px;"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="minimal-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Correo Electrónico</th>
                    <th>Estado de Entrega</th>
                    <th>Fecha de Registro</th>
                    <th>Antigüedad</th>
                    <th style="text-align: right; width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $s)
                <tr>
                    <td>
                        <span style="font-family: var(--font-mono); font-size: 0.78rem; color: var(--text-dim);">#{{ $s->id }}</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <div style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border-subtle);">
                                <i data-lucide="mail" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                            </div>
                            <div>
                                <span style="font-weight: 700; color: #FFFFFF; font-family: var(--font-mono); font-size: 0.88rem;">{{ $s->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-pill" style="background: rgba(16, 185, 129, 0.1); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.72rem; padding: 0.15rem 0.5rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
                            ACTIVO
                        </span>
                    </td>
                    <td>
                        <span style="color: var(--text-main); font-size: 0.84rem; font-family: var(--font-mono);">
                            {{ $s->created_at ? $s->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 0.78rem;">
                            {{ $s->created_at ? $s->created_at->diffForHumans() : '—' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.subscribers.destroy', $s->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Seguro que deseas dar de baja o eliminar este correo ({{ $s->email }}) de la lista?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm" style="padding: 0.35rem 0.65rem;" title="Eliminar suscriptor">
                                <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                                <span>Baja</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem 1.5rem;">
                        <div style="max-width: 320px; margin: 0 auto; color: var(--text-muted);">
                            <i data-lucide="inbox" style="width: 36px; height: 36px; color: var(--text-dim); margin-bottom: 0.75rem;"></i>
                            <p style="font-weight: 600; color: #FFFFFF; margin-bottom: 0.25rem;">No se encontraron suscriptores</p>
                            <p style="font-size: 0.82rem;">{{ request('q') ? 'Ningún correo coincide con los criterios de búsqueda.' : 'Aún no hay suscriptores registrados.' }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscribers->hasPages())
    <div style="padding: 1.25rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: center;">
        {{ $subscribers->links() }}
    </div>
    @endif
</div>

<script>
    function toggleCreateSubscriberPanel() {
        const panel = document.getElementById('create-subscriber-panel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            panel.style.display = 'none';
        }
    }
</script>
@endsection
