@extends('layouts.app')

@section('title', 'Directorio Completo de Proveedores de Hosting y VPS — Debatehosting')

@section('content')
<section style="max-width: 1200px; margin: 0 auto; padding: 3rem 1.5rem;">
    <!-- Encabezado de Sección -->
    <div style="margin-bottom: 2.5rem; text-align: center;">
        <span class="section-kicker">OBSERVATORIO TÉCNICO EDITORIAL</span>
        <h1 class="section-title" style="font-size: 2.5rem; margin: 0.5rem 0;">Directorio de Hosting y VPS</h1>
        <p class="section-subtitle">Filtra por categoría, presupuesto y rendimiento para encontrar el proveedor idóneo.</p>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div style="background: var(--bg-surface); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2.5rem; box-shadow: var(--shadow-subtle);">
        <form method="GET" action="{{ route('providers.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <!-- Categorías -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('providers.index') }}" class="badge-editorial" style="text-decoration: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); border: var(--border-width) solid var(--border-ink); background: {{ !request('categoria') || request('categoria') == 'todas' ? 'var(--green-primary)' : 'var(--bg-surface-elevated)' }}; color: {{ !request('categoria') || request('categoria') == 'todas' ? '#fff' : 'var(--text-ink)' }}; font-weight: 600;">
                    Todas
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('providers.index', ['categoria' => $cat->slug, 'orden' => request('orden')]) }}" class="badge-editorial" style="text-decoration: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); border: var(--border-width) solid var(--border-ink); background: {{ request('categoria') == $cat->slug ? 'var(--green-primary)' : 'var(--bg-surface-elevated)' }}; color: {{ request('categoria') == $cat->slug ? '#fff' : 'var(--text-ink)' }}; font-weight: 600;">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>

            <!-- Buscador y Orden -->
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar proveedor..." style="padding: 0.5rem 0.75rem; border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); font-size: 0.85rem;">
                <select name="orden" onchange="this.form.submit()" style="padding: 0.5rem; border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); font-size: 0.85rem; background: #fff;">
                    <option value="recomendados" {{ request('orden') == 'recomendados' ? 'selected' : '' }}>Recomendados</option>
                    <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                    <option value="rendimiento" {{ request('orden') == 'rendimiento' ? 'selected' : '' }}>Mejor Velocidad</option>
                    <option value="soporte" {{ request('orden') == 'soporte' ? 'selected' : '' }}>Mejor Soporte</option>
                    <option value="alfabetico" {{ request('orden') == 'alfabetico' ? 'selected' : '' }}>Alfabético (A-Z)</option>
                </select>
                <button type="submit" class="btn-primary-editorial" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Listado de Proveedores -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @forelse($providers as $p)
        <div class="provider-dossier-card" style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); box-shadow: var(--shadow-solid); padding: 1.5rem; display: grid; grid-template-columns: 240px 1fr 200px; gap: 2rem; align-items: center;">
            <!-- Marca -->
            <div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                    @if($p->resolved_logo_url)
                        <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 42px; max-width: 100px; object-fit: contain;">
                    @else
                        <div style="width: 42px; height: 42px; background: #0E6B41; color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                            {{ substr($p->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h2 style="font-family: var(--font-serif); font-size: 1.35rem; margin: 0;">
                            <a href="{{ route('providers.show', $p->slug) }}" style="color: inherit; text-decoration: none;">{{ $p->name }}</a>
                        </h2>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $p->plan }}</span>
                    </div>
                </div>

                @if($p->badge)
                <span class="badge-editorial" style="background: var(--green-tint); color: var(--green-dark); border: 1px solid var(--green-primary); font-size: 0.7rem; padding: 0.2rem 0.5rem; border-radius: 3px; font-weight: 600;">
                    ★ {{ $p->badge }}
                </span>
                @endif
            </div>

            <!-- Métricas Auditadas -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; border-left: 1px dashed var(--border-ink); border-right: 1px dashed var(--border-ink); padding: 0 1.5rem;">
                <div style="text-align: center;">
                    <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Precio</span>
                    <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_precio }}/10</div>
                </div>
                <div style="text-align: center;">
                    <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Velocidad</span>
                    <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_rendimiento }}/10</div>
                </div>
                <div style="text-align: center;">
                    <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Soporte</span>
                    <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--text-ink);">{{ $p->score_soporte }}/10</div>
                </div>
                <div style="text-align: center;">
                    <span style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Uptime</span>
                    <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; color: var(--green-primary);">{{ $p->uptime }}%</div>
                </div>
            </div>

            <!-- Precio y Acción -->
            <div style="text-align: right;">
                @if($p->discount_percent > 0)
                <span style="background: #FDE8E5; color: #B03A26; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 3px;">
                    -{{ $p->discount_percent }}% OFF
                </span>
                @endif
                <div style="font-family: var(--font-mono); font-size: 1.5rem; font-weight: 800; color: var(--text-ink); margin: 0.25rem 0;">
                    ${{ number_format($p->price_from, 2) }}<span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/{{ $p->period }}</span>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ route('providers.show', $p->slug) }}" class="btn-secondary-editorial" style="flex: 1; text-align: center; padding: 0.5rem; font-size: 0.8rem;">
                        Dossier
                    </a>
                    <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary-editorial" style="flex: 1; text-align: center; padding: 0.5rem; font-size: 0.8rem;">
                        Oferta ↗
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; background: var(--bg-surface); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md);">
            <p style="font-size: 1.1rem; color: var(--text-muted);">No se encontraron proveedores con los filtros seleccionados.</p>
            <a href="{{ route('providers.index') }}" class="btn-primary-editorial" style="margin-top: 1rem; display: inline-block;">Restablecer Filtros</a>
        </div>
        @endforelse
    </div>
</section>
@endsection
