@extends('layouts.app')

@section('title', 'Directorio de Proveedores de Hosting y Servidores VPS — Debatehosting')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <!-- Encabezado de Sección -->
        <div class="section-headline-wrap" style="margin-bottom: 2.5rem;">
            <div class="kicker">OBSERVATORIO TÉCNICO EDITORIAL</div>
            <h1 class="section-title">Directorio de Hosting y VPS</h1>
            <p class="section-subtitle">
                Filtra por categoría, presupuesto y rendimiento técnico para encontrar el proveedor idóneo para tu proyecto.
            </p>
        </div>

        <!-- Barra de Filtros y Búsqueda -->
        <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; box-shadow: var(--shadow-sm);">
            <form method="GET" action="{{ route('providers.index') }}" style="display: flex; gap: 1.25rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                <!-- Categorías -->
                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                    <a href="{{ route('providers.index') }}" class="btn btn-sm {{ !request('categoria') || request('categoria') == 'todas' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                        Todas
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('providers.index', ['categoria' => $cat->slug, 'orden' => request('orden')]) }}" class="btn btn-sm {{ request('categoria') == $cat->slug ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>

                <!-- Buscador y Ordenación -->
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <div style="position: relative;">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar proveedor..." style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.5rem 0.85rem 0.5rem 2rem; font-size: 0.85rem; outline: none;">
                        <i data-lucide="search" style="width: 14px; height: 14px; color: var(--text-dim); position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%);"></i>
                    </div>

                    <select name="orden" onchange="this.form.submit()" style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.5rem 0.75rem; font-size: 0.85rem; outline: none;">
                        <option value="recientes" {{ request('orden', 'recientes') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="recomendados" {{ request('orden') == 'recomendados' ? 'selected' : '' }}>Recomendados</option>
                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="rendimiento" {{ request('orden') == 'rendimiento' ? 'selected' : '' }}>Mejor Velocidad TTFB</option>
                        <option value="soporte" {{ request('orden') == 'soporte' ? 'selected' : '' }}>Mejor Soporte</option>
                        <option value="alfabetico" {{ request('orden') == 'alfabetico' ? 'selected' : '' }}>Alfabético (A-Z)</option>
                    </select>

                    <button type="submit" class="btn btn-emerald btn-sm">Filtrar</button>
                </div>
            </form>
        </div>

        <!-- Listado de Fichas de Proveedores -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($providers as $p)
            <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm); display: grid; grid-template-columns: 240px 1fr 220px; gap: 2rem; align-items: center; transition: all 0.15s ease;">
                <!-- Marca & Identidad -->
                <div>
                    <div style="display: flex; align-items: center; gap: 0.9rem; margin-bottom: 0.75rem;">
                        @if($p->resolved_logo_url)
                            <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 52px; max-width: 125px; object-fit: contain;">
                        @else
                            <div style="width: 52px; height: 52px; background: var(--bg-subtle); border: 1px solid var(--border-color); color: var(--text-main); font-weight: 800; font-size: 1.15rem; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                                {{ substr($p->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0;">
                                <a href="{{ route('providers.show', $p->slug) }}" style="color: var(--text-main); text-decoration: none;">{{ $p->name }}</a>
                            </h2>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $p->plan }}</span>
                        </div>
                    </div>

                    @if($p->badge)
                    <span class="badge-pill badge-emerald">
                        ★ {{ $p->badge }}
                    </span>
                    @endif
                </div>

                <!-- Métricas Auditadas de La Balanza -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); padding: 0 1.75rem;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); display: block; margin-bottom: 0.2rem;">Economía</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.15rem; color: var(--text-main);">{{ $p->score_precio }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); display: block; margin-bottom: 0.2rem;">Velocidad</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.15rem; color: var(--text-main);">{{ $p->score_rendimiento }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); display: block; margin-bottom: 0.2rem;">Soporte</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.15rem; color: var(--text-main);">{{ $p->score_soporte }}/10</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); display: block; margin-bottom: 0.2rem;">Uptime</span>
                        <div style="font-family: var(--font-mono); font-weight: 700; font-size: 1.15rem; color: var(--emerald-primary);">{{ $p->uptime }}%</div>
                    </div>
                </div>

                <!-- Precio y Acción -->
                <div style="text-align: right;">
                    <div style="margin-bottom: 0.75rem;">
                        <span style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; display: block;">Desde</span>
                        <div style="font-family: var(--font-mono); font-size: 1.65rem; font-weight: 800; color: var(--text-main); line-height: 1;">
                            ${{ number_format($p->price_from, 2) }}
                            <span style="font-size: 0.78rem; font-weight: 400; color: var(--text-muted);">/{{ $p->period }}</span>
                        </div>
                        @if($p->discount_percent > 0)
                            <span class="badge-pill badge-rose" style="margin-top: 0.35rem;">
                                -{{ $p->discount_percent }}% DESCUENTO
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                        <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary btn-sm">
                            <span>Análisis</span>
                        </a>
                        <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
                            <span>Oferta ↗</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 4rem 2rem; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">No se encontraron proveedores que coincidan con los filtros seleccionados.</p>
                <a href="{{ route('providers.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
