@extends('layouts.app')

@section('title', 'Ofertas Exclusivas de Hosting y Servidores — Debatehosting')
@section('meta_description', 'Las mejores ofertas de hosting web, servidores VPS y servidores dedicados con descuentos de bienvenida comprobados. Filtra por categoría y ahorra en tu infraestructura.')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <!-- Encabezado de Sección -->
        <div class="section-headline-wrap" style="margin-bottom: 2.5rem;">
            <div class="kicker">DESCUENTOS ESPECIALES DE BIENVENIDA</div>
            <h1 class="section-title">Radar de Ofertas de Hosting</h1>
            <p class="section-subtitle">
                Ahorra hasta un 80% en tu primer ciclo de contratación con estos enlaces verificados por el observatorio.
            </p>
        </div>

        <!-- Categorías -->
        <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 3rem;">
            <a href="{{ route('ofertas') }}" class="btn btn-sm {{ !request('categoria') ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                Todas las Ofertas
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('ofertas', ['categoria' => $cat->slug]) }}" class="btn btn-sm {{ request('categoria') == $cat->slug ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: var(--radius-full);">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <!-- Lista de Ofertas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
            @foreach($providers as $p)
            @php
                $selectedCat = request('categoria') && request('categoria') !== 'todas' ? request('categoria') : null;
                $matchedProduct = $p->getProductForCategory($selectedCat);

                $displayPlan = $matchedProduct ? $matchedProduct->plan_name : $p->plan;
                $displayPrice = $matchedProduct ? $matchedProduct->price_from : $p->price_from;
                $displayBefore = $matchedProduct ? $matchedProduct->price_before : $p->price_before;
                $displayPeriod = $matchedProduct ? $matchedProduct->period : $p->period;
                $displaySpecs = $matchedProduct && !empty($matchedProduct->specs) ? $matchedProduct->specs : [];
                $discountPct = ($displayBefore > $displayPrice && $displayBefore > 0)
                    ? (int) round((($displayBefore - $displayPrice) / $displayBefore) * 100)
                    : 0;
                $displayUrl = ($matchedProduct && $matchedProduct->affiliate_url) ? $matchedProduct->affiliate_url : route('go', $p->slug);
            @endphp
            <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.15s ease;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            @if($p->resolved_logo_url)
                                <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 38px; max-width: 90px; object-fit: contain;">
                            @else
                                <div style="width: 38px; height: 38px; background: var(--bg-subtle); border: 1px solid var(--border-color); color: var(--text-main); font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 6px;">
                                    {{ substr($p->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0; color: var(--text-main);">{{ $p->name }}</h2>
                                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">{{ $displayPlan }}</span>
                            </div>
                        </div>

                        @if($discountPct > 0)
                        <span class="badge-pill badge-rose" style="font-weight: 800;">
                            -{{ $discountPct }}% OFF
                        </span>
                        @endif
                    </div>

                    <!-- Caja de Precios -->
                    <div style="background-color: var(--bg-subtle); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 1.25rem;">
                        <span style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.2rem;">Tarifa Seleccionada:</span>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                            <div style="font-family: var(--font-mono); font-size: 1.85rem; font-weight: 800; color: var(--emerald-primary); line-height: 1;">
                                ${{ number_format($displayPrice, 2) }}
                                <span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/{{ $displayPeriod }}</span>
                            </div>
                            @if($displayBefore > $displayPrice)
                            <span style="font-size: 0.85rem; color: var(--text-dim); text-decoration: line-through; font-family: var(--font-mono);">
                                ${{ number_format($displayBefore, 2) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($displaySpecs))
                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1rem;">
                        @foreach(array_slice($displaySpecs, 0, 3) as $spec)
                            <span style="font-family: var(--font-mono); font-size: 0.7rem; background: var(--bg-surface); border: 1px solid var(--border-color); padding: 0.15rem 0.45rem; border-radius: 4px; color: var(--text-body);">
                                {{ $spec }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.25rem;">
                        {{ Str::limit($p->description, 130) }}
                    </p>

                    @if($p->products->count() > 1)
                    <div style="font-size: 0.72rem; color: var(--emerald-primary); font-family: var(--font-mono); font-weight: 600; margin-bottom: 1.25rem;">
                        ✦ {{ $p->products->count() }} planes disponibles en catálogo
                    </div>
                    @endif
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary btn-sm" style="flex: 1; justify-content: center;">
                        <span>Ver Análisis</span>
                    </a>
                    <a href="{{ $displayUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="flex: 1.3; justify-content: center;">
                        <span>Activar Oferta ↗</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
