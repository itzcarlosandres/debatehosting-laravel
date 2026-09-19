@extends('layouts.admin')

@section('title', 'Nuevo Proveedor — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Crear Nuevo Proveedor</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Añade un nuevo proveedor con sus notas técnicas y enlace de afiliado.</p>
    </div>
    <a href="{{ route('admin.providers.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Volver</a>
</div>

@if($errors->any())
    <div style="background: #7F1D1D; color: #FECACA; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
        <ul style="margin-left: 1.5rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.providers.store') }}" method="POST" enctype="multipart/form-data" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; max-width: 900px;">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Nombre de la Marca *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="ej: Hostinger">
        </div>

        <div class="form-group">
            <label class="form-label">Slug URL (opcional)</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="ej: hostinger (se genera automático)">
        </div>
    </div>

    <!-- Subida de Logo -->
    <div class="form-group" style="background: #0F172A; border: 1px dashed var(--border-color); padding: 1.25rem; border-radius: 6px;">
        <label class="form-label" style="color: #fff; font-weight: 700;">Logotipo Oficial (PNG, SVG, JPG o WebP)</label>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem;">
            Se guardará de forma permanente y segura en el disco local de almacenamiento.
        </p>
        <input type="file" name="logo" accept="image/*" class="form-control" style="background: var(--bg-card);">
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Plan Recomendado / Auditado *</label>
            <input type="text" name="plan" class="form-control" value="{{ old('plan', 'Plan Premium') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Precio Promocional ($) *</label>
            <input type="number" step="0.01" name="price_from" class="form-control" value="{{ old('price_from', '2.99') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Precio Regular Antes ($)</label>
            <input type="number" step="0.01" name="price_before" class="form-control" value="{{ old('price_before', '9.99') }}" required>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
        <div class="form-group">
            <label class="form-label">Nota Precio (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_precio" class="form-control" value="{{ old('score_precio', '9.0') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Nota Velocidad (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_rendimiento" class="form-control" value="{{ old('score_rendimiento', '8.5') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Nota Soporte (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_soporte" class="form-control" value="{{ old('score_soporte', '8.0') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Uptime (%)</label>
            <input type="number" step="0.01" min="90" max="100" name="uptime" class="form-control" value="{{ old('uptime', '99.98') }}" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Enlace de Afiliado (URL destino)</label>
        <input type="url" name="affiliate_url" class="form-control" value="{{ old('affiliate_url') }}" placeholder="https://proveedor.com/?ref=debatehosting">
    </div>

    <div class="form-group">
        <label class="form-label">Veredicto / Análisis Editorial</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Puntos Fuertes (Pros)</label>
            <textarea name="pros" rows="3" class="form-control" placeholder="• Punto fuerte 1&#10;• Punto fuerte 2">{{ old('pros') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Puntos Débiles (Contras)</label>
            <textarea name="cons" rows="3" class="form-control" placeholder="• Punto a considerar 1">{{ old('cons') }}</textarea>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
        <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
        <label for="active" class="form-label" style="margin-bottom: 0; cursor: pointer;">Publicar y mostrar activamente en el sitio</label>
    </div>

    <input type="hidden" name="period" value="mes">
    <input type="hidden" name="score_facilidad" value="8.5">

    <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="{{ route('admin.providers.index') }}" class="btn-action" style="background: #334155;">Cancelar</a>
        <button type="submit" class="btn-action">Guardar Proveedor</button>
    </div>
</form>
@endsection
