@extends('layouts.admin')

@section('title', 'Cupones')

@section('content')
<!-- Encabezado de la Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Cupones de Descuento</h1>
        <p class="page-header-subtitle">Códigos de descuento verificados, porcentajes y condiciones de canje.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.coupons.create') }}" class="btn-primary">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Nuevo Cupón</span>
        </a>
    </div>
</div>

<!-- Tabla de Cupones -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i data-lucide="ticket" style="width: 16px; height: 16px; color: var(--sky-primary);"></i>
            <span>{{ $coupons->count() }} Cupones Registrados</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="minimal-table">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Código Promocional</th>
                    <th>Descuento</th>
                    <th>Condición de Uso</th>
                    <th>Usos / Clics</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $c)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            @if($c->provider->resolved_logo_url)
                                <img src="{{ $c->provider->resolved_logo_url }}" alt="{{ $c->provider->name }}" style="width: 28px; height: 28px; object-fit: contain; background: #FFFFFF; padding: 2px; border-radius: 4px; border: 1px solid var(--border-subtle);">
                            @endif
                            <span style="font-weight: 700; color: #FFFFFF;">{{ $c->provider->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-weight: 700; color: var(--sky-primary); background: var(--sky-subtle); border: 1px dashed rgba(56, 189, 248, 0.3); padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.85rem; letter-spacing: 0.04em;">
                            {{ $c->code }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: var(--emerald-primary); font-family: var(--font-mono); font-size: 0.85rem;">
                            {{ $c->discount }}
                        </span>
                    </td>
                    <td style="color: var(--text-muted); font-size: 0.8rem; max-width: 250px;">
                        {{ $c->condition ?: 'Aplicable a nuevas contrataciones.' }}
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--text-muted);">
                            {{ number_format($c->clicks) }}
                        </span>
                    </td>
                    <td>
                        @if($c->verified)
                            <span class="badge-status active">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--emerald-primary);"></span>
                                <span>Verificado</span>
                            </span>
                        @else
                            <span class="badge-status inactive">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--rose-primary);"></span>
                                <span>Expirado</span>
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                            <a href="{{ route('admin.coupons.edit', $c) }}" class="btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Editar Cupón">
                                <i data-lucide="edit-3" style="width: 12px; height: 12px;"></i>
                                <span>Editar</span>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Seguro que deseas eliminar el cupón {{ $c->code }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Eliminar Cupón">
                                    <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-dim); padding: 3rem;">
                        No hay cupones registrados actualmente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
