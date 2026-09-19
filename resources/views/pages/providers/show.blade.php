@extends('layouts.app')

@section('title', 'Análisis y Auditoría de ' . $provider->name . ' — Debatehosting')
@section('meta_description', $provider->meta_description ?: 'Auditoría editorial de ' . $provider->name . '. Puntuación, planes desde $' . $provider->price_from . ', pros, contras y pruebas de rendimiento.')

@section('content')
<article style="max-width: 1000px; margin: 0 auto; padding: 3rem 1.5rem;">
    <!-- Migas de pan -->
    <nav style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2rem;">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Inicio</a> /
        <a href="{{ route('providers.index') }}" style="color: inherit; text-decoration: none;">Proveedores</a> /
        <span style="color: var(--text-ink); font-weight: 600;">{{ $provider->name }}</span>
    </nav>

    <!-- Cabecera de Dossier -->
    <header style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); box-shadow: var(--shadow-solid); padding: 2.5rem; margin-bottom: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                @if($provider->resolved_logo_url)
                    <img src="{{ $provider->resolved_logo_url }}" alt="{{ $provider->name }}" style="height: 64px; max-width: 150px; object-fit: contain;">
                @else
                    <div style="width: 64px; height: 64px; background: #0E6B41; color: #fff; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        {{ substr($provider->name, 0, 2) }}
                    </div>
                @endif
                <div>
                    <h1 style="font-family: var(--font-serif); font-size: 2.2rem; margin: 0; line-height: 1.1;">{{ $provider->name }}</h1>
                    <span style="font-size: 1rem; color: var(--text-muted);">Plan auditado: <strong>{{ $provider->plan }}</strong></span>
                </div>
            </div>

            <div style="text-align: right;">
                <span style="font-size: 0.8rem; color: var(--text-light); text-transform: uppercase;">Precio de entrada</span>
                <div style="font-family: var(--font-mono); font-size: 2rem; font-weight: 800; color: var(--text-ink);">
                    ${{ number_format($provider->price_from, 2) }}<span style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/{{ $provider->period }}</span>
                </div>
                <a href="{{ route('go', $provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary-editorial" style="margin-top: 0.5rem; font-size: 1rem; padding: 0.6rem 1.5rem;">
                    Ir a la Web Oficial ↗
                </a>
            </div>
        </div>

        <!-- Barra de métricas en el Header -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed var(--border-ink); text-align: center;">
            <div style="background: var(--bg-surface); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-ink);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Puntuación Global</div>
                <div style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800; color: var(--green-primary);">★ {{ $provider->overall_score }}/10</div>
            </div>
            <div style="background: var(--bg-surface); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-ink);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Precio / Calidad</div>
                <div style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800;">{{ $provider->score_precio }}/10</div>
            </div>
            <div style="background: var(--bg-surface); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-ink);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Rendimiento</div>
                <div style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800;">{{ $provider->score_rendimiento }}/10</div>
            </div>
            <div style="background: var(--bg-surface); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-ink);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Soporte Técnico</div>
                <div style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800;">{{ $provider->score_soporte }}/10</div>
            </div>
            <div style="background: var(--bg-surface); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-ink);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Disponibilidad</div>
                <div style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800; color: var(--green-primary);">{{ $provider->uptime }}%</div>
            </div>
        </div>
    </header>

    <!-- Contenido Editorial -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
        <!-- Columna Izquierda: Análisis, Pros/Contras y Veredicto -->
        <div>
            <!-- Descripción -->
            <section style="margin-bottom: 2.5rem;">
                <h2 style="font-family: var(--font-serif); font-size: 1.6rem; border-bottom: var(--border-width) solid var(--border-ink); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                    Veredicto Editorial
                </h2>
                <p style="font-size: 1.05rem; line-height: 1.7; color: var(--text-ink); margin-bottom: 1.5rem;">
                    {{ $provider->description }}
                </p>
                @if($provider->verdict)
                <div style="background: var(--green-tint); border-left: 4px solid var(--green-primary); padding: 1.25rem; border-radius: var(--radius-sm); font-style: italic;">
                    <strong>¿Para quién se recomienda?</strong> {{ $provider->verdict }}
                </div>
                @endif
            </section>

            <!-- Pros y Contras -->
            <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: var(--radius-sm); padding: 1.25rem;">
                    <h3 style="color: #15803D; font-size: 1rem; margin-top: 0; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        ✔ Lo que más nos gusta
                    </h3>
                    <div style="font-size: 0.9rem; line-height: 1.6; white-space: pre-line; color: #166534;">
                        {{ $provider->pros ?: '• Gran estabilidad de conexión\n• Panel intuitivo\n• Buena relación calidad/precio' }}
                    </div>
                </div>

                <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: var(--radius-sm); padding: 1.25rem;">
                    <h3 style="color: #B91C1C; font-size: 1rem; margin-top: 0; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        ✖ A tener en cuenta
                    </h3>
                    <div style="font-size: 0.9rem; line-height: 1.6; white-space: pre-line; color: #991B1B;">
                        {{ $provider->cons ?: '• Precio de renovación estándar superior a la oferta de bienvenida' }}
                    </div>
                </div>
            </section>
        </div>

        <!-- Columna Derecha: Cupones y Alternativas -->
        <div>
            <!-- Cupones -->
            @if($provider->coupons->count() > 0)
            <div style="background: var(--bg-surface); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem; box-shadow: var(--shadow-subtle);">
                <h3 style="font-family: var(--font-serif); font-size: 1.2rem; margin-top: 0; margin-bottom: 1rem;">
                    🎟️ Cupones Activos
                </h3>
                @foreach($provider->coupons as $c)
                <div style="background: #fff; border: 1px solid var(--border-ink); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-weight: 700; color: var(--green-primary);">{{ $c->discount }}</span>
                        <span style="font-family: var(--font-mono); font-size: 0.85rem; background: var(--bg-paper); padding: 0.2rem 0.5rem; border-radius: 3px;">{{ $c->code }}</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 0.5rem 0;">{{ $c->condition }}</p>
                    <a href="{{ route('go', $provider->slug) }}" target="_blank" class="btn-primary-editorial" style="width: 100%; justify-content: center; font-size: 0.8rem; padding: 0.4rem;">
                        Aplicar Cupón ↗
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Alternativas Similares -->
            <div style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="font-family: var(--font-serif); font-size: 1.2rem; margin-top: 0; margin-bottom: 1rem;">
                    Alternativas para Comparar
                </h3>
                @foreach($related as $rel)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px dashed var(--border-ink);">
                    <div>
                        <a href="{{ route('providers.show', $rel->slug) }}" style="font-weight: 700; color: inherit; text-decoration: none;">{{ $rel->name }}</a>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">${{ number_format($rel->price_from, 2) }}/mes</div>
                    </div>
                    <a href="{{ route('providers.show', $rel->slug) }}" class="btn-secondary-editorial" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">
                        Ver →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</article>
@endsection
