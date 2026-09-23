@extends('layouts.admin')

@section('title', 'Nuevo Cupón')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Crear Nuevo Cupón</h1>
        <p class="page-header-subtitle">Asocia un código promocional con descuento a un proveedor existente.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Volver a Cupones</span>
        </a>
    </div>
</div>

@if($errors->any())
    <div class="toast-banner error">
        <div>
            <div style="font-weight: 700; margin-bottom: 0.25rem;">Revisa los siguientes campos:</div>
            <ul style="margin-left: 1.25rem; font-size: 0.82rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('admin.coupons.store') }}" method="POST" style="max-width: 680px;">
    @csrf

    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="ticket" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <span>Datos del Cupón</span>
            </div>
            <p class="form-panel-desc">Define el código que los usuarios copiarán en portada.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Proveedor Asociado *</label>
            <select name="provider_id" class="form-control" required>
                <option value="">-- Seleccionar Proveedor --</option>
                @foreach($providers as $p)
                    <option value="{{ $p->id }}" {{ old('provider_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Código del Cupón *</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="ej: DEBATE10" style="text-transform: uppercase; font-family: var(--font-mono); font-weight: 700; color: var(--sky-primary);">
            </div>

            <div class="form-group">
                <label class="form-label">Texto del Descuento *</label>
                <input type="text" name="discount" class="form-control" value="{{ old('discount') }}" required placeholder="ej: -10% Extra o -75% OFF">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Condición / Términos de Aplicación</label>
            <input type="text" name="condition" class="form-control" value="{{ old('condition') }}" placeholder="ej: Válido en contrataciones anuales de hosting">
        </div>

        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
            <label class="toggle-label-wrap">
                <input type="checkbox" name="verified" id="verified" value="1" {{ old('verified', true) ? 'checked' : '' }}>
                <span class="toggle-text">Cupón verificado y activo (mostrado en listados)</span>
            </label>
        </div>
    </div>

    <!-- Barra de Acciones -->
    <div class="form-actions-bar">
        <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">
            <i data-lucide="check" style="width: 15px; height: 15px;"></i>
            <span>Guardar Cupón</span>
        </button>
    </div>
</form>
@endsection
