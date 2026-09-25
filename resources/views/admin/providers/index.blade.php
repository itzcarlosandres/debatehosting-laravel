@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')
<!-- Encabezado de la Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Directorio de Proveedores</h1>
        <p class="page-header-subtitle">Gestión técnica, puntuaciones de La Balanza y enlaces de afiliación.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.providers.create') }}" class="btn-primary">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Nuevo Proveedor</span>
        </a>
    </div>
</div>

<!-- Tabla de Proveedores -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i data-lucide="server" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
            <span>{{ $providers->count() }} Proveedores Registrados</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <input type="text" id="filter-providers" placeholder="Filtrar por nombre..." oninput="filterTable()" style="background: var(--bg-input); border: 1px solid var(--border-medium); border-radius: var(--radius-sm); color: #FFFFFF; padding: 0.4rem 0.75rem; font-size: 0.8rem; outline: none;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="minimal-table" id="providers-table">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Plan Base</th>
                    <th>Precio Entrada</th>
                    <th>Score Global</th>
                    <th>Clics</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($providers as $p)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            @if($p->resolved_logo_url)
                                <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="width: 32px; height: 32px; object-fit: contain; background: #FFFFFF; padding: 2px; border-radius: 4px; border: 1px solid var(--border-subtle); flex-shrink: 0;">
                            @else
                                <div style="width: 32px; height: 32px; border-radius: 4px; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); flex-shrink: 0;">
                                    {{ substr($p->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 700; color: #FFFFFF;">
                                    <a href="{{ route('admin.providers.edit', $p) }}" style="color: inherit; text-decoration: none;">{{ $p->name }}</a>
                                </div>
                                <a href="{{ route('providers.show', $p->slug) }}" target="_blank" style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--text-dim); text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem;">
                                    <span>/proveedores/{{ $p->slug }}</span>
                                    <i data-lucide="external-link" style="width: 10px; height: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td style="color: var(--text-muted); font-size: 0.82rem;">{{ $p->plan }}</td>
                    <td>
                        <div style="font-family: var(--font-mono); font-weight: 700; color: #FFFFFF;">
                            ${{ number_format($p->price_from, 2) }}
                            <span style="font-size: 0.72rem; color: var(--text-dim); font-weight: 400;">/{{ $p->period }}</span>
                        </div>
                        @if($p->discount_percent > 0)
                            <span style="font-size: 0.7rem; color: var(--rose-primary); font-family: var(--font-mono); font-weight: 600;">
                                -{{ $p->discount_percent }}% OFF
                            </span>
                        @endif
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-weight: 800; font-size: 0.85rem; color: var(--emerald-primary); background: var(--emerald-subtle); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.2rem 0.55rem; border-radius: 4px;">
                            ★ {{ $p->overall_score }}
                        </span>
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-size: 0.82rem; font-weight: 600; color: var(--text-muted);">
                            {{ number_format($p->clicks) }}
                        </span>
                    </td>
                    <td>
                        @if($p->active)
                            <span class="badge-status active">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--emerald-primary);"></span>
                                <span>Activo</span>
                            </span>
                        @else
                            <span class="badge-status inactive">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--rose-primary);"></span>
                                <span>Pausado</span>
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                            <a href="{{ route('admin.providers.edit', $p) }}" class="btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Editar Proveedor">
                                <i data-lucide="edit-3" style="width: 12px; height: 12px;"></i>
                                <span>Editar</span>
                            </a>
                            <form action="{{ route('admin.providers.destroy', $p) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Seguro que deseas eliminar definitivamente a {{ $p->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Eliminar Proveedor">
                                    <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-dim); padding: 3rem;">
                        No se encontraron proveedores registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('admin-scripts')
<script>
    function filterTable() {
        const query = document.getElementById('filter-providers').value.toLowerCase();
        const rows = document.querySelectorAll('#providers-table tbody tr');
        rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            r.style.display = text.includes(query) ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
