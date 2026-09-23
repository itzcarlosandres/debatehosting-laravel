@extends('layouts.admin')

@section('title', 'Badges y Distintivos')

@section('content')
<!-- Encabezado de Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Badges & Distintivos de Proveedores</h1>
        <p class="page-header-subtitle">Administra los distintivos destacados (HOT, MEJOR PRECIO, RECOMENDADO, etc.) visibles en las tarjetas de proveedores.</p>
    </div>
    <div class="page-header-actions">
        <button type="button" onclick="toggleCreateBadgePanel()" class="btn-primary" id="btn-toggle-create">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Nuevo Distintivo</span>
        </button>
    </div>
</div>

<!-- Panel Plegable para Crear Nuevo Badge -->
<div id="create-badge-panel" class="form-panel" style="display: none; margin-bottom: 2rem; border-color: rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.04) 0%, rgba(20, 24, 33, 0.98) 100%);">
    <div class="form-panel-header" style="display: flex; align-items: center; justify-content: space-between;">
        <div class="form-panel-title">
            <i data-lucide="award" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
            <span>Crear Nuevo Distintivo</span>
        </div>
        <button type="button" onclick="toggleCreateBadgePanel()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
        </button>
    </div>

    <form action="{{ route('admin.badges.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 1fr; gap: 1.25rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Texto del Distintivo (Label) *</label>
                <input type="text" name="label" class="form-control" placeholder="ej: TOP RENDIMIENTO, HOT" required>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Slug Único (Opcional)</label>
                <input type="text" name="slug" class="form-control" placeholder="ej: top-rendimiento">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Color del Distintivo *</label>
                <select name="color" class="form-control" required>
                    <option value="green">Verde Esmeralda (green)</option>
                    <option value="gold">Dorado Ámbar (gold)</option>
                    <option value="red">Rojo Vivo (red)</option>
                    <option value="sky">Azul Cielo (sky)</option>
                    <option value="rose">Rosa / Fucsia (rose)</option>
                    <option value="dark">Gris Neutro (dark)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Orden</label>
                <input type="number" name="order" class="form-control" value="{{ ($badges->max('order') ?? 0) + 1 }}" min="0">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
            <button type="button" onclick="toggleCreateBadgePanel()" class="btn-secondary">Cancelar</button>
            <button type="submit" class="btn-primary">
                <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                <span>Guardar Distintivo</span>
            </button>
        </div>
    </form>
</div>

<!-- Tabla de Distintivos -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i data-lucide="award" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
            <span>{{ $badges->count() }} Distintivos Configurados</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="minimal-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Orden</th>
                    <th style="width: 220px;">Vista Previa</th>
                    <th>Texto / Label</th>
                    <th style="width: 130px;">Color</th>
                    <th style="width: 150px;">Proveedores</th>
                    <th style="width: 160px; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($badges as $badge)
                <tr>
                    <td style="font-family: var(--font-mono); font-weight: 700; color: var(--text-dim);">
                        #{{ $badge->order }}
                    </td>
                    <td>
                        @php
                            $badgeStyles = [
                                'green' => 'background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3);',
                                'gold' => 'background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3);',
                                'red' => 'background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3);',
                                'dark' => 'background: rgba(255, 255, 255, 0.08); color: #E2E8F0; border: 1px solid rgba(255, 255, 255, 0.2);',
                                'sky' => 'background: rgba(14, 165, 233, 0.15); color: #0EA5E9; border: 1px solid rgba(14, 165, 233, 0.3);',
                                'rose' => 'background: rgba(244, 63, 94, 0.15); color: #F43F5E; border: 1px solid rgba(244, 63, 94, 0.3);',
                            ];
                            $style = $badgeStyles[$badge->color] ?? $badgeStyles['green'];
                        @endphp
                        <span style="display: inline-block; font-family: var(--font-mono); font-size: 0.76rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em; {{ $style }}">
                            {{ $badge->label }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #FFFFFF; font-size: 0.95rem;">
                            {{ $badge->label }}
                        </div>
                        <div style="font-family: var(--font-mono); font-size: 0.74rem; color: var(--text-dim); margin-top: 0.15rem;">
                            {{ $badge->slug }}
                        </div>
                    </td>
                    <td>
                        <span style="font-family: var(--font-mono); font-size: 0.76rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); background: var(--bg-input); padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid var(--border-subtle);">
                            {{ $badge->color }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ ($providersCounts[$badge->label] ?? 0) > 0 ? 'active' : '' }}" style="font-family: var(--font-mono); font-size: 0.75rem;">
                            {{ $providersCounts[$badge->label] ?? 0 }} asignados
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.45rem;">
                            <button type="button" onclick="openEditBadgeModal({{ json_encode($badge) }})" class="btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Editar">
                                <i data-lucide="edit-3" style="width: 12px; height: 12px;"></i>
                                <span>Editar</span>
                            </button>

                            <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar el badge {{ $badge->label }}?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Eliminar">
                                    <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                        No hay badges registrados aún. Pulsa en "Nuevo Distintivo" para crear el primero.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Editar Badge -->
<div id="modal-edit-badge" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="form-panel" style="width: 100%; max-width: 500px; margin-bottom: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.6); border-color: var(--border-medium);">
        <div class="form-panel-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div class="form-panel-title">
                <i data-lucide="pencil" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <span>Editar Badge / Distintivo</span>
            </div>
            <button type="button" onclick="closeEditBadgeModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

        <form id="form-edit-badge" action="" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Texto del Distintivo *</label>
                <input type="text" id="edit-badge-label" name="label" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Slug Único</label>
                <input type="text" id="edit-badge-slug" name="slug" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Color</label>
                <select id="edit-badge-color" name="color" class="form-control" required>
                    <option value="green">Verde Esmeralda (green)</option>
                    <option value="gold">Dorado Ámbar (gold)</option>
                    <option value="red">Rojo Vivo (red)</option>
                    <option value="sky">Azul Cielo (sky)</option>
                    <option value="rose">Rosa / Fucsia (rose)</option>
                    <option value="dark">Gris Neutro (dark)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Orden</label>
                <input type="number" id="edit-badge-order" name="order" class="form-control" min="0">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                <button type="button" onclick="closeEditBadgeModal()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
    function toggleCreateBadgePanel() {
        const panel = document.getElementById('create-badge-panel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            panel.style.display = 'none';
        }
    }

    function openEditBadgeModal(badge) {
        document.getElementById('form-edit-badge').action = '/admin/badges/' + badge.id;
        document.getElementById('edit-badge-label').value = badge.label;
        document.getElementById('edit-badge-slug').value = badge.slug;
        document.getElementById('edit-badge-color').value = badge.color;
        document.getElementById('edit-badge-order').value = badge.order;

        const modal = document.getElementById('modal-edit-badge');
        modal.style.display = 'flex';
        if (window.lucide) lucide.createIcons();
    }

    function closeEditBadgeModal() {
        document.getElementById('modal-edit-badge').style.display = 'none';
    }
</script>
@endpush
@endsection
