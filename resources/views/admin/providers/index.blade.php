@extends('layouts.admin')

@section('title', 'Gestionar Proveedores — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Proveedores</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Listado completo de proveedores auditados en DebateHosting.</p>
    </div>
    <a href="{{ route('admin.providers.create') }}" class="btn-action">+ Nuevo Proveedor</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nombre</th>
                <th>Plan Base</th>
                <th>Precio</th>
                <th>Score</th>
                <th>Clics</th>
                <th>Estado</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($providers as $p)
            <tr>
                <td>
                    @if($p->resolved_logo_url)
                        <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 32px; max-width: 60px; object-fit: contain;">
                    @else
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Sin logo</span>
                    @endif
                </td>
                <td style="font-weight: 700;">
                    <a href="{{ route('admin.providers.edit', $p) }}" style="color: #fff; text-decoration: none;">
                        {{ $p->name }}
                    </a>
                </td>
                <td style="color: var(--text-muted);">{{ $p->plan }}</td>
                <td style="font-family: 'IBM Plex Mono', monospace; font-weight: 600;">
                    ${{ number_format($p->price_from, 2) }}/{{ $p->period }}
                </td>
                <td style="font-weight: 700; color: var(--accent);">
                    ★ {{ $p->overall_score }}
                </td>
                <td style="font-family: 'IBM Plex Mono', monospace;">{{ $p->clicks }}</td>
                <td>
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $p->active ? 'var(--accent)' : 'var(--danger)' }}; margin-right: 0.25rem;"></span>
                    {{ $p->active ? 'Activo' : 'Pausado' }}
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.providers.edit', $p) }}" style="color: #38BDF8; text-decoration: none; margin-right: 1rem; font-size: 0.85rem;">Editar</a>
                    <form action="{{ route('admin.providers.destroy', $p) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este proveedor?');">
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
