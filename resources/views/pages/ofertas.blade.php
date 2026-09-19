@extends('layouts.app')

@section('title', 'Ofertas Exclusivas de Hosting y Servidores — Debatehosting')

@section('content')
<section style="max-width: 1200px; margin: 0 auto; padding: 3rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span class="section-kicker">DESCUENTOS ESPECIALES DE BIENVENIDA</span>
        <h1 class="section-title" style="font-size: 2.5rem; margin: 0.5rem 0;">Directorio de Ofertas de Hosting</h1>
        <p class="section-subtitle">Ahorra hasta un 80% en tu primer año de contratación con estos enlaces verificados.</p>
    </div>

    <!-- Lista de Ofertas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
        @foreach($providers as $p)
        <div style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 1.75rem; box-shadow: var(--shadow-solid); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($p->resolved_logo_url)
                            <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 38px; max-width: 90px; object-fit: contain;">
                        @endif
                        <div>
                            <h2 style="font-family: var(--font-serif); font-size: 1.35rem; margin: 0;">{{ $p->name }}</h2>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $p->plan }}</span>
                        </div>
                    </div>
                    @if($p->discount_percent > 0)
                    <span style="background: #FDE8E5; color: #B03A26; font-size: 0.8rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid #B03A26;">
                        -{{ $p->discount_percent }}% OFF
                    </span>
                    @endif
                </div>

                <div style="background: var(--bg-surface); padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px dashed var(--border-ink); margin-bottom: 1.25rem;">
                    <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Precio Promocional:</span>
                    <div style="font-family: var(--font-mono); font-size: 1.6rem; font-weight: 800; color: var(--green-primary);">
                        ${{ number_format($p->price_from, 2) }}
                        <span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/{{ $p->period }}</span>
                    </div>
                    @if($p->price_before > $p->price_from)
                    <span style="font-size: 0.8rem; color: var(--text-light); text-decoration: line-through;">
                        Normal: ${{ number_format($p->price_before, 2) }}
                    </span>
                    @endif
                </div>

                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.5rem;">
                    {{ Str::limit($p->description, 120) }}
                </p>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('providers.show', $p->slug) }}" class="btn-secondary-editorial" style="flex: 1; text-align: center; padding: 0.6rem; font-size: 0.85rem;">
                    Ver Análisis
                </a>
                <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-primary-editorial" style="flex: 1; text-align: center; padding: 0.6rem; font-size: 0.85rem;">
                    Activar Oferta ↗
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
