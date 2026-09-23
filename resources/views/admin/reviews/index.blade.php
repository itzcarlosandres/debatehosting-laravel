@extends('layouts.admin')

@section('title', 'Reseñas & Análisis')

@section('content')
<!-- Encabezado de la Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Reseñas y Análisis Editoriales</h1>
        <p class="page-header-subtitle">Artículos a fondo con activación automática de Widget Promocional o Alternativas Recomendadas.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.reviews.create') }}" class="btn-primary">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Redactar Nueva Reseña</span>
        </a>
    </div>
</div>

<!-- Tarjetas de Resumen -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-label">Total Reseñas</span>
            <div class="stat-icon-box" style="background: rgba(56, 189, 248, 0.12); color: var(--sky-primary);">
                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalCount }}</div>
        <div class="stat-footer">
            <span style="font-size: 0.76rem; color: var(--text-dim);">Artículos registrados</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-label">Publicadas en Vivo</span>
            <div class="stat-icon-box" style="background: rgba(16, 185, 129, 0.12); color: var(--emerald-primary);">
                <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
            </div>
        </div>
        <div class="stat-value">{{ $publishedCount }}</div>
        <div class="stat-footer">
            <span style="font-size: 0.76rem; color: var(--emerald-primary);">Visibles para los usuarios</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-label">Borradores</span>
            <div class="stat-icon-box" style="background: rgba(245, 158, 11, 0.12); color: var(--amber-primary);">
                <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
            </div>
        </div>
        <div class="stat-value">{{ $draftCount }}</div>
        <div class="stat-footer">
            <span style="font-size: 0.76rem; color: var(--text-dim);">Pendientes de publicación</span>
        </div>
    </div>
</div>

<!-- Tabla de Reseñas -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i data-lucide="layers" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
            <span>Listado de Reseñas Editoriales</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <form method="GET" action="{{ route('admin.reviews.index') }}" style="display: flex; gap: 0.5rem; margin: 0;">
                <select name="estado" onchange="this.form.submit()" style="background: var(--bg-input); border: 1px solid var(--border-medium); border-radius: var(--radius-sm); color: #FFFFFF; padding: 0.4rem 0.75rem; font-size: 0.8rem; outline: none;">
                    <option value="">Todos los estados</option>
                    <option value="publicadas" {{ request('estado') === 'publicadas' ? 'selected' : '' }}>Solo Publicadas</option>
                    <option value="borradores" {{ request('estado') === 'borradores' ? 'selected' : '' }}>Solo Borradores</option>
                </select>

                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar reseña o proveedor..." style="background: var(--bg-input); border: 1px solid var(--border-medium); border-radius: var(--radius-sm); color: #FFFFFF; padding: 0.4rem 0.75rem; font-size: 0.8rem; outline: none; width: 220px;">
                <button type="submit" class="btn-secondary" style="padding: 0.4rem 0.75rem;">
                    <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="minimal-table">
            <thead>
                <tr>
                    <th>Reseña / Análisis</th>
                    <th>Proveedor Analizado</th>
                    <th>Puntuación</th>
                    <th>Widget Activo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                <tr>
                    <td>
                        <div style="max-width: 320px;">
                            <div style="font-weight: 700; color: #FFFFFF; font-size: 0.92rem; margin-bottom: 0.2rem;">
                                <a href="{{ route('admin.reviews.edit', $rev) }}" style="color: inherit; text-decoration: none;">
                                    {{ $rev->title }}
                                </a>
                                @if($rev->featured)
                                    <span style="display: inline-block; font-size: 0.65rem; background: rgba(245, 158, 11, 0.2); color: var(--amber-primary); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 3px; padding: 0.1rem 0.35rem; margin-left: 0.3rem;">★ Destacada</span>
                                @endif
                            </div>
                            <a href="{{ route('reviews.show', $rev->slug) }}" target="_blank" style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--text-dim); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span>/resenas/{{ $rev->slug }}</span>
                                <i data-lucide="external-link" style="width: 10px; height: 10px;"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        @if($rev->hasActiveProvider())
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($rev->provider->resolved_logo_url)
                                    <img src="{{ $rev->provider->resolved_logo_url }}" alt="{{ $rev->provider->name }}" style="width: 24px; height: 24px; object-fit: contain; background: #FFFFFF; padding: 2px; border-radius: 4px; border: 1px solid var(--border-subtle);">
                                @endif
                                <div>
                                    <span style="font-weight: 600; color: #FFFFFF;">{{ $rev->provider->name }}</span>
                                    <span style="display: block; font-size: 0.7rem; color: var(--emerald-primary);">● En catálogo activo</span>
                                </div>
                            </div>
                        @elseif($rev->provider)
                            <div>
                                <span style="font-weight: 600; color: var(--text-muted);">{{ $rev->provider->name }}</span>
                                <span style="display: block; font-size: 0.7rem; color: var(--rose-primary);">○ Inactivo en catálogo</span>
                            </div>
                        @else
                            <div>
                                <span style="font-weight: 600; color: #FFFFFF;">{{ $rev->provider_name ?: 'Proveedor Externo' }}</span>
                                <span style="display: block; font-size: 0.7rem; color: var(--amber-primary);">▲ Sin ficha en catálogo</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-weight: 800; font-size: 0.88rem; color: var(--emerald-primary); background: var(--emerald-subtle); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.2rem 0.55rem; border-radius: 4px;">
                            ★ {{ number_format($rev->rating, 1) }}
                        </span>
                    </td>
                    <td>
                        @if($rev->hasActiveProvider())
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 700; color: #A7F3D0; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.25rem 0.55rem; border-radius: 4px;" title="Muestra deal directo, cupón y botón de afiliado de {{ $rev->provider->name }}">
                                <i data-lucide="zap" style="width: 12px; height: 12px; color: var(--emerald-primary);"></i>
                                <span>Widget Directo</span>
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 700; color: #FDE68A; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 0.25rem 0.55rem; border-radius: 4px;" title="Muestra top 3 alternativas recomendadas con enlaces de afiliado">
                                <i data-lucide="shuffle" style="width: 12px; height: 12px; color: var(--amber-primary);"></i>
                                <span>Top Alternativas</span>
                            </span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.reviews.toggle-publish', $rev) }}" method="POST" style="margin: 0;">
                            @csrf
                            @if($rev->published)
                                <button type="submit" class="badge-status active" title="Clic para despublicar (pasar a borrador)" style="background: none; border: none; cursor: pointer;">
                                    <span class="dot"></span>
                                    <span>Publicada</span>
                                </button>
                            @else
                                <button type="submit" class="badge-status inactive" title="Clic para publicar en vivo" style="background: none; border: none; cursor: pointer;">
                                    <span class="dot"></span>
                                    <span>Borrador</span>
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--text-dim);">
                            {{ $rev->published_at ? $rev->published_at->format('d/m/Y') : $rev->created_at->format('d/m/Y') }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                            <a href="{{ route('reviews.show', $rev->slug) }}" target="_blank" class="btn-action-icon" title="Ver en vivo">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                            </a>
                            <a href="{{ route('admin.reviews.edit', $rev) }}" class="btn-action-icon" title="Editar">
                                <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i>
                            </a>
                            <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar esta reseña definitivamente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-icon danger" title="Eliminar">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: var(--text-dim);">
                        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📝</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #FFFFFF; margin-bottom: 0.25rem;">No hay reseñas redactadas aún</div>
                        <p style="font-size: 0.8rem; margin-bottom: 1.25rem;">Crea tu primer análisis a fondo para posicionar en buscadores y monetizar visitas.</p>
                        <a href="{{ route('admin.reviews.create') }}" class="btn-primary" style="display: inline-flex;">
                            <span>Redactar Primera Reseña</span>
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reviews->hasPages())
    <div style="padding: 1.25rem; border-top: 1px solid var(--border-subtle);">
        {{ $reviews->links() }}
    </div>
    @endif
</div>
@endsection
