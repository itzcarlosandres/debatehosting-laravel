@extends('layouts.app')

@section('title', 'Reseñas y Análisis de Hosting y VPS — DebateHosting')
@section('meta_description', 'Análisis a fondo y pruebas reales de proveedores de hosting y servidores VPS. Descubre métricas técnicas, pros, contras, veredictos y cupones exclusivos.')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <!-- Encabezado de Sección -->
        <div class="section-headline-wrap" style="margin-bottom: 2.5rem;">
            <div class="kicker">AUDITORÍAS & BANCO DE PRUEBAS</div>
            <h1 class="section-title">Reseñas y Análisis a Fondo</h1>
            <p class="section-subtitle">
                Examinamos servidores, paneles de control, atención técnica y velocidad de respuesta para que tomes la mejor decisión de contratación.
            </p>
        </div>

        <!-- Barra de Filtros y Búsqueda -->
        <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; box-shadow: var(--shadow-sm);">
            <form method="GET" action="{{ route('reviews.index') }}" style="display: flex; gap: 1.25rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                <!-- Categorías -->
                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                    <a href="{{ route('reviews.index') }}" class="btn btn-sm {{ !request('categoria') || request('categoria') == 'todas' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                        Todas
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('reviews.index', ['categoria' => $cat->slug, 'orden' => request('orden'), 'q' => request('q')]) }}" class="btn btn-sm {{ request('categoria') == $cat->slug ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>

                <!-- Buscador y Ordenación -->
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    <div style="position: relative;">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por hosting..." style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.5rem 0.85rem 0.5rem 2rem; font-size: 0.85rem; outline: none; color: var(--text-main);">
                        <i data-lucide="search" style="width: 14px; height: 14px; color: var(--text-dim); position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%);"></i>
                    </div>

                    <select name="orden" onchange="this.form.submit()" style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.5rem 0.75rem; font-size: 0.85rem; outline: none; color: var(--text-main);">
                        <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="puntuacion" {{ request('orden') == 'puntuacion' ? 'selected' : '' }}>Mejor Calificación</option>
                    </select>

                    <button type="submit" class="btn btn-emerald btn-sm">Filtrar</button>
                </div>
            </form>
        </div>

        <!-- Reseñas Destacadas si existen -->
        @if($featuredReviews->count() > 0 && !request('q') && (!request('categoria') || request('categoria') == 'todas'))
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <i data-lucide="sparkles" style="width: 18px; height: 18px; color: var(--amber-primary);"></i>
                <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0; text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--font-mono);">
                    Análisis Destacados
                </h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
                @foreach($featuredReviews as $feat)
                <article style="background: linear-gradient(145deg, rgba(16, 185, 129, 0.06) 0%, var(--bg-surface) 60%); border: 1.5px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <span style="font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.2rem 0.55rem; border-radius: 4px; text-transform: uppercase;">
                                ★ Análisis Recomendado
                            </span>
                            <span style="font-family: var(--font-mono); font-size: 1.1rem; font-weight: 800; color: var(--emerald-primary);">
                                ★ {{ number_format($feat->rating, 1) }}/10
                            </span>
                        </div>

                        <h3 style="font-size: 1.25rem; font-weight: 800; line-height: 1.3; margin-bottom: 0.75rem;">
                            <a href="{{ route('reviews.show', $feat->slug) }}" style="color: var(--text-main); text-decoration: none;">
                                {{ $feat->title }}
                            </a>
                        </h3>

                        <p style="font-size: 0.88rem; color: var(--text-body); line-height: 1.6; margin-bottom: 1.25rem;">
                            {{ Str::limit($feat->summary ?: strip_tags($feat->content), 120) }}
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                        <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">
                            {{ $feat->published_at ? $feat->published_at->format('d M, Y') : '' }}
                        </span>
                        <a href="{{ route('reviews.show', $feat->slug) }}" class="btn btn-secondary btn-sm">
                            <span>Leer Análisis</span>
                            <i data-lucide="arrow-right" style="width: 12px; height: 12px;"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Grid Principal de Reseñas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem; margin-bottom: 3rem;">
            @forelse($reviews as $rev)
            <article style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.15s ease, border-color 0.15s ease;">
                <div>
                    <!-- Cabecera de la Tarjeta -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            @if($rev->provider && $rev->provider->resolved_logo_url)
                                <img src="{{ $rev->provider->resolved_logo_url }}" alt="{{ $rev->provider->name }}" style="height: 24px; max-width: 60px; object-fit: contain;">
                            @else
                                <div style="width: 24px; height: 24px; border-radius: 4px; background: var(--bg-subtle); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: var(--text-main);">
                                    {{ substr($rev->resolved_provider_name, 0, 2) }}
                                </div>
                            @endif
                            <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">
                                {{ $rev->resolved_provider_name }}
                            </span>
                        </div>

                        <span style="font-family: var(--font-mono); font-size: 0.95rem; font-weight: 800; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.15rem 0.5rem; border-radius: 4px;">
                            ★ {{ number_format($rev->rating, 1) }}
                        </span>
                    </div>

                    <h2 style="font-size: 1.15rem; font-weight: 800; line-height: 1.35; margin-bottom: 0.75rem;">
                        <a href="{{ route('reviews.show', $rev->slug) }}" style="color: var(--text-main); text-decoration: none;">
                            {{ $rev->title }}
                        </a>
                    </h2>

                    <p style="font-size: 0.88rem; color: var(--text-body); line-height: 1.6; margin-bottom: 1.25rem;">
                        {{ Str::limit($rev->summary ?: strip_tags($rev->content), 140) }}
                    </p>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">
                        <i data-lucide="calendar" style="width: 13px; height: 13px;"></i>
                        <span>{{ $rev->published_at ? $rev->published_at->format('d/m/Y') : '' }}</span>
                    </div>

                    <a href="{{ route('reviews.show', $rev->slug) }}" class="btn btn-emerald btn-sm" style="font-size: 0.8rem; font-weight: 700;">
                        <span>Ver Análisis Completo</span>
                        <i data-lucide="arrow-right" style="width: 12px; height: 12px;"></i>
                    </a>
                </div>
            </article>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                <div style="font-size: 2rem; margin-bottom: 0.75rem;">🔍</div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">No se encontraron reseñas con los filtros seleccionados</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Prueba ajustando el término de búsqueda o cambiando la categoría.</p>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Ver Todas las Reseñas</a>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($reviews->hasPages())
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
