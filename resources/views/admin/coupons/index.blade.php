@extends('layouts.admin')

@section('title', 'Gestionar Cupones — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Cupones Verificados</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Gestiona los códigos de descuento activos en el sitio.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn-action">+ Nuevo Cupón</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Proveedor</th>
                <th>Código</th>
                <th>Descuento</th>
                <th>Condición</th>
                <th>Usos / Clics</th>
                <th>Estado</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $c)
            <tr>
                <td style="font-weight: 700;">{{ $c->provider->name }}</td>
                <td>
                    <span style="font-family: 'IBM Plex Mono', monospace; background: #0F172A; border: 1px solid var(--border-color); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; color: #38BDF8;">
                        {{ $c->code }}
                    </span>
                </td>
                <td style="font-weight: 700; color: var(--accent);">{{ $c->discount }}</td>
                <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $c->condition }}</td>
                <td style="font-family: 'IBM Plex Mono', monospace;">{{ $c->clicks }}</td>
                <td>
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $c->verified ? 'var(--accent)' : 'var(--danger)' }}; margin-right: 0.25rem;"></span>
                    {{ $c->verified ? 'Verificado' : 'Expirado' }}
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.coupons.edit', $c) }}" style="color: #38BDF8; text-decoration: none; margin-right: 1rem; font-size: 0.85rem;">Editar</a>
                    <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar cupón?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #EF4444; cursor: pointer; font-size: 0.85rem;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
