@extends('layouts.admin')

@section('title', 'Categorías de Proveedores')

@section('content')
<!-- Encabezado de Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Categorías de Proveedores</h1>
        <p class="page-header-subtitle">Administra las taxonomías utilizadas para filtrar proveedores y planes en la web pública.</p>
    </div>
    <div class="page-header-actions">
        <button type="button" onclick="toggleCreateCategoryPanel()" class="btn-primary" id="btn-toggle-create">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Nueva Categoría</span>
        </button>
    </div>
</div>

<!-- Panel Plegable para Crear Nueva Categoría -->
<div id="create-category-panel" class="form-panel" style="display: none; margin-bottom: 2rem; border-color: rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.04) 0%, rgba(20, 24, 33, 0.98) 100%);">
    <div class="form-panel-header" style="display: flex; align-items: center; justify-content: space-between;">
        <div class="form-panel-title">
            <i data-lucide="layers" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
            <span>Crear Nueva Categoría</span>
        </div>
        <button type="button" onclick="toggleCreateCategoryPanel()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
        </button>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 1fr; gap: 1.25rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Nombre de la Categoría *</label>
                <input type="text" name="name" class="form-control" placeholder="ej: Servidores Dedicados" required>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Slug URL (Opcional)</label>
                <input type="text" name="slug" class="form-control" placeholder="ej: servidores-dedicados">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Icono Lucide</label>
                <input type="text" name="icon" class="form-control" value="server" placeholder="ej: server, cpu, globe">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Orden</label>
                <input type="number" name="order" class="form-control" value="{{ ($categories->max('order') ?? 0) + 1 }}" min="0">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
            <button type="button" onclick="toggleCreateCategoryPanel()" class="btn-secondary">Cancelar</button>
            <button type="submit" class="btn-primary">
                <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                <span>Guardar Categoría</span>
            </button>
        </div>
    </form>
</div>

<!-- Tabla de Categorías -->
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <i data-lucide="layers" style="width: 16px; height: 16px; color: var(--emerald-primary);"></i>
            <span>{{ $categories->count() }} Categorías Configuradas</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="minimal-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Orden</th>
                    <th>Nombre</th>
                    <th style="width: 200px;">Slug</th>
                    <th style="width: 150px;">Icono Lucide</th>
                    <th style="width: 150px;">Proveedores</th>
                    <th style="width: 160px; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td style="font-family: var(--font-mono); font-weight: 700; color: var(--text-dim);">
                        #{{ $cat->order }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 34px; height: 34px; border-radius: 6px; background: rgba(16, 185, 129, 0.1); color: var(--emerald-primary); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(16, 185, 129, 0.2); flex-shrink: 0;">
                                <i data-lucide="{{ $cat->icon ?: 'server' }}" style="width: 16px; height: 16px;"></i>
                            </div>
                            <span style="font-weight: 700; color: #FFFFFF; font-size: 0.95rem;">{{ $cat->name }}</span>
                        </div>
                    </td>
                    <td>
                        <code style="font-family: var(--font-mono); font-size: 0.8rem; background: var(--bg-input); padding: 0.25rem 0.55rem; border-radius: 4px; border: 1px solid var(--border-subtle); color: var(--sky-primary);">
                            {{ $cat->slug }}
                        </code>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.4rem; font-family: var(--font-mono); font-size: 0.8rem; color: var(--text-muted);">
                            <i data-lucide="{{ $cat->icon ?: 'server' }}" style="width: 13px; height: 13px; color: var(--text-dim);"></i>
                            <span>{{ $cat->icon ?: 'server' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-status {{ ($providersCounts[$cat->slug] ?? 0) > 0 ? 'active' : '' }}" style="font-family: var(--font-mono); font-size: 0.75rem;">
                            {{ $providersCounts[$cat->slug] ?? 0 }} asociados
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.45rem;">
                            <button type="button" onclick="openEditCategoryModal({{ json_encode($cat) }})" class="btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.78rem;" title="Editar">
                                <i data-lucide="edit-3" style="width: 12px; height: 12px;"></i>
                                <span>Editar</span>
                            </button>

                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar la categoría {{ $cat->name }}?');" style="display: inline;">
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
                        No hay categorías registradas aún. Pulsa en "Nueva Categoría" para crear la primera.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Editar Categoría -->
<div id="modal-edit-cat" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="form-panel" style="width: 100%; max-width: 500px; margin-bottom: 0; box-shadow: 0 10px 40px rgba(0,0,0,0.6); border-color: var(--border-medium);">
        <div class="form-panel-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div class="form-panel-title">
                <i data-lucide="pencil" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <span>Editar Categoría</span>
            </div>
            <button type="button" onclick="closeEditCategoryModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

        <form id="form-edit-cat" action="" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nombre de la Categoría *</label>
                <input type="text" id="edit-cat-name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Slug URL</label>
                <input type="text" id="edit-cat-slug" name="slug" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Icono Lucide</label>
                <input type="text" id="edit-cat-icon" name="icon" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Orden</label>
                <input type="number" id="edit-cat-order" name="order" class="form-control" min="0">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                <button type="button" onclick="closeEditCategoryModal()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
    function toggleCreateCategoryPanel() {
        const panel = document.getElementById('create-category-panel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            panel.style.display = 'none';
        }
    }

    function openEditCategoryModal(cat) {
        document.getElementById('form-edit-cat').action = '/admin/categories/' + cat.id;
        document.getElementById('edit-cat-name').value = cat.name;
        document.getElementById('edit-cat-slug').value = cat.slug;
        document.getElementById('edit-cat-icon').value = cat.icon || 'server';
        document.getElementById('edit-cat-order').value = cat.order;

        const modal = document.getElementById('modal-edit-cat');
        modal.style.display = 'flex';
        if (window.lucide) lucide.createIcons();
    }

    function closeEditCategoryModal() {
        document.getElementById('modal-edit-cat').style.display = 'none';
    }
</script>
@endpush
@endsection
