@extends('layouts.admin')

@section('title', 'Editar ' . $provider->name . ' — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Editar Proveedor: {{ $provider->name }}</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Modifica los datos, notas o sube un nuevo logo oficial.</p>
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

<form action="{{ route('admin.providers.update', $provider) }}" method="POST" enctype="multipart/form-data" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; max-width: 900px;">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Nombre de la Marca *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $provider->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Slug URL</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $provider->slug) }}" required>
        </div>
    </div>

    <!-- Subida / Edición de Logo -->
    <div class="form-group" style="background: #0F172A; border: 1px dashed var(--border-color); padding: 1.25rem; border-radius: 6px;">
        <label class="form-label" style="color: #fff; font-weight: 700;">Logotipo Actual y Cambio</label>
        
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1rem;">
            @if($provider->resolved_logo_url)
                <div style="background: #1E293B; padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border-color);">
                    <img src="{{ $provider->resolved_logo_url }}" alt="{{ $provider->name }}" style="height: 48px; max-width: 120px; object-fit: contain;">
                </div>
                <span style="font-size: 0.85rem; color: var(--accent);">✔ Logo actual cargado</span>
            @else
                <span style="font-size: 0.85rem; color: var(--text-muted);">Sin logo asignado actualmente</span>
            @endif
        </div>

        <label class="form-label">Seleccionar nuevo archivo de logo para reemplazar:</label>
        <input type="file" name="logo" accept="image/*" class="form-control" style="background: var(--bg-card);">
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Plan Recomendado / Auditado *</label>
            <input type="text" name="plan" class="form-control" value="{{ old('plan', $provider->plan) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Precio Promocional ($) *</label>
            <input type="number" step="0.01" name="price_from" class="form-control" value="{{ old('price_from', $provider->price_from) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Precio Regular Antes ($)</label>
            <input type="number" step="0.01" name="price_before" class="form-control" value="{{ old('price_before', $provider->price_before) }}" required>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
        <div class="form-group">
            <label class="form-label">Nota Precio (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_precio" class="form-control" value="{{ old('score_precio', $provider->score_precio) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Nota Velocidad (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_rendimiento" class="form-control" value="{{ old('score_rendimiento', $provider->score_rendimiento) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Nota Soporte (0-10)</label>
            <input type="number" step="0.1" min="0" max="10" name="score_soporte" class="form-control" value="{{ old('score_soporte', $provider->score_soporte) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Uptime (%)</label>
            <input type="number" step="0.01" min="90" max="100" name="uptime" class="form-control" value="{{ old('uptime', $provider->uptime) }}" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Enlace de Afiliado (URL destino)</label>
        <input type="url" name="affiliate_url" class="form-control" value="{{ old('affiliate_url', $provider->affiliate_url) }}">
    </div>

    <div class="form-group">
        <label class="form-label">Veredicto / Análisis Editorial</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $provider->description) }}</textarea>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Puntos Fuertes (Pros)</label>
            <textarea name="pros" rows="3" class="form-control">{{ old('pros', $provider->pros) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Puntos Débiles (Contras)</label>
            <textarea name="cons" rows="3" class="form-control">{{ old('cons', $provider->cons) }}</textarea>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
        <input type="checkbox" name="active" id="active" value="1" {{ old('active', $provider->active) ? 'checked' : '' }}>
        <label for="active" class="form-label" style="margin-bottom: 0; cursor: pointer;">Publicar y mostrar activamente en el sitio</label>
    </div>

    <input type="hidden" name="period" value="{{ $provider->period ?: 'mes' }}">
    <input type="hidden" name="score_facilidad" value="{{ $provider->score_facilidad ?: 8.5 }}">

    <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="{{ route('admin.providers.index') }}" class="btn-action" style="background: #334155;">Cancelar</a>
        <button type="submit" class="btn-action">Actualizar Proveedor</button>
    </div>
</form>
@endsection
