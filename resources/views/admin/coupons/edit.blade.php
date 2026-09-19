@extends('layouts.admin')

@section('title', 'Editar Cupón ' . $coupon->code . ' — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Editar Cupón: {{ $coupon->code }}</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Modifica los detalles del cupón promocional.</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Volver</a>
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

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; max-width: 650px;">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label class="form-label">Proveedor Asociado *</label>
        <select name="provider_id" class="form-control" required>
            @foreach($providers as $p)
                <option value="{{ $p->id }}" {{ old('provider_id', $coupon->provider_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label class="form-label">Código del Cupón *</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase; font-family: 'IBM Plex Mono', monospace;">
        </div>

        <div class="form-group">
            <label class="form-label">Texto del Descuento *</label>
            <input type="text" name="discount" class="form-control" value="{{ old('discount', $coupon->discount) }}" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Condición / Términos de Aplicación</label>
        <input type="text" name="condition" class="form-control" value="{{ old('condition', $coupon->condition) }}">
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
        <input type="checkbox" name="verified" id="verified" value="1" {{ old('verified', $coupon->verified) ? 'checked' : '' }}>
        <label for="verified" class="form-label" style="margin-bottom: 0; cursor: pointer;">Cupón verificado y activo</label>
    </div>

    <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="{{ route('admin.coupons.index') }}" class="btn-action" style="background: #334155;">Cancelar</a>
        <button type="submit" class="btn-action">Actualizar Cupón</button>
    </div>
</form>
@endsection
