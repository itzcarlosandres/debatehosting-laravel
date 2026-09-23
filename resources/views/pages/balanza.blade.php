@extends('layouts.app')

@section('title', 'La Balanza: Comparador Ponderado de Hosting y Servidores — Debatehosting')
@section('meta_description', 'La Balanza: Herramienta interactiva para comparar proveedores de hosting según tus prioridades: precio, rendimiento, soporte y facilidad de uso.')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <!-- Cabecera de La Balanza -->
        <div class="section-headline-wrap" style="margin-bottom: 3rem;">
            <div class="kicker">ALGORITMO EDITORIAL DE PONDERACIÓN</div>
            <h1 class="section-title">⚖️ La Balanza del Hosting</h1>
            <p class="section-subtitle">
                Ajusta la importancia que le das a cada factor. Nuestro motor recalcula en tiempo real la puntuación técnica exacta para tu caso específico.
            </p>
        </div>

        <!-- Presets Rápidos -->
        <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
            <span style="font-size: 0.82rem; color: var(--text-dim); display: flex; align-items: center; margin-right: 0.5rem;">Preajustes:</span>
            <button type="button" onclick="setPreset(90, 60, 40, 70)" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
                💰 Máximo Ahorro
            </button>
            <button type="button" onclick="setPreset(40, 95, 80, 50)" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
                ⚡ Máxima Velocidad TTFB
            </button>
            <button type="button" onclick="setPreset(50, 80, 95, 80)" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
                🛒 Tienda WooCommerce
            </button>
            <button type="button" onclick="setPreset(60, 90, 40, 40)" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
                💻 VPS para Desarrollador
            </button>
            <button type="button" onclick="setPreset(50, 50, 50, 50)" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
                ⚖️ Equilibrado (50%)
            </button>
        </div>

        <div class="balanza-controls-grid">
            <!-- Controles / Sliders -->
            <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="sliders" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>Ajusta tus Pesos y Prioridades</span>
                </h3>

                <div class="slider-group-wrap">
                    <!-- Slider 1: Precio -->
                    <div class="balanza-slider-box">
                        <div class="slider-header-meta">
                            <span>💰 Economía / Presupuesto</span>
                            <span id="val-precio" class="slider-val-pill">50%</span>
                        </div>
                        <input type="range" id="slider-precio" min="10" max="100" value="50" oninput="recalcBalanza()" class="clean-range-slider">
                    </div>

                    <!-- Slider 2: Rendimiento -->
                    <div class="balanza-slider-box">
                        <div class="slider-header-meta">
                            <span>⚡ Velocidad / Rendimiento TTFB</span>
                            <span id="val-rendimiento" class="slider-val-pill">50%</span>
                        </div>
                        <input type="range" id="slider-rendimiento" min="10" max="100" value="50" oninput="recalcBalanza()" class="clean-range-slider">
                    </div>

                    <!-- Slider 3: Soporte -->
                    <div class="balanza-slider-box">
                        <div class="slider-header-meta">
                            <span>🛠️ Soporte Técnico 24/7</span>
                            <span id="val-soporte" class="slider-val-pill">50%</span>
                        </div>
                        <input type="range" id="slider-soporte" min="10" max="100" value="50" oninput="recalcBalanza()" class="clean-range-slider">
                    </div>

                    <!-- Slider 4: Facilidad -->
                    <div class="balanza-slider-box">
                        <div class="slider-header-meta">
                            <span>🧩 Facilidad de Uso (Panel cPanel/hPanel)</span>
                            <span id="val-facilidad" class="slider-val-pill">50%</span>
                        </div>
                        <input type="range" id="slider-facilidad" min="10" max="100" value="50" oninput="recalcBalanza()" class="clean-range-slider">
                    </div>
                </div>
            </div>

            <!-- Resultados en Tiempo Real -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0;">
                        Ranking Ponderado Resultante
                    </h3>
                    <span class="badge-pill badge-emerald">En Vivo</span>
                </div>

                <div id="balanza-results" class="balanza-results-container">
                    <!-- Se inyecta con JavaScript -->
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const providersData = @json($providers);

    function setPreset(p, r, s, f) {
        document.getElementById('slider-precio').value = p;
        document.getElementById('slider-rendimiento').value = r;
        document.getElementById('slider-soporte').value = s;
        document.getElementById('slider-facilidad').value = f;
        recalcBalanza();
    }

    function recalcBalanza() {
        const wPrecio = parseInt(document.getElementById('slider-precio').value);
        const wRend = parseInt(document.getElementById('slider-rendimiento').value);
        const wSoporte = parseInt(document.getElementById('slider-soporte').value);
        const wFacil = parseInt(document.getElementById('slider-facilidad').value);

        document.getElementById('val-precio').innerText = wPrecio + '%';
        document.getElementById('val-rendimiento').innerText = wRend + '%';
        document.getElementById('val-soporte').innerText = wSoporte + '%';
        document.getElementById('val-facilidad').innerText = wFacil + '%';

        const totalWeight = wPrecio + wRend + wSoporte + wFacil;

        const scored = providersData.map(p => {
            const finalScore = (
                (p.score_precio * wPrecio) +
                (p.score_rendimiento * wRend) +
                (p.score_soporte * wSoporte) +
                (p.score_facilidad * wFacil)
            ) / totalWeight;

            return {
                ...p,
                customScore: finalScore.toFixed(1)
            };
        }).sort((a, b) => b.customScore - a.customScore);

        renderResults(scored);
    }

    function renderResults(list) {
        const container = document.getElementById('balanza-results');
        container.innerHTML = '';

        list.slice(0, 6).forEach((item, idx) => {
            const card = document.createElement('div');
            card.className = 'balanza-result-row';

            const logoHtml = item.resolved_logo_url
                ? `<img src="${item.resolved_logo_url}" alt="${item.name}" style="width: 48px; height: 48px; object-fit: contain; background: #fff; padding: 4px; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: var(--shadow-xs);">`
                : `<div style="width: 48px; height: 48px; border-radius: 10px; background: var(--bg-subtle); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; color: var(--text-main);">${item.name.substring(0,2)}</div>`;

            card.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span class="result-rank-num ${idx === 0 ? 'first' : ''}">#${idx + 1}</span>
                    ${logoHtml}
                    <div>
                        <div style="font-weight: 800; font-size: 1.05rem; color: var(--text-main);">
                            ${item.name}
                            ${idx === 0 ? '<span class="badge-pill badge-emerald" style="margin-left: 0.4rem; font-size: 0.65rem;">TOP RECOMENDADO</span>' : ''}
                        </div>
                        <div style="font-size: 0.78rem; color: var(--text-muted); font-family: var(--font-mono);">${item.plan} • $${parseFloat(item.price_from).toFixed(2)}/mes</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="text-align: right;">
                        <span style="font-family: var(--font-mono); font-size: 1.35rem; font-weight: 800; color: ${idx === 0 ? 'var(--emerald-primary)' : 'var(--text-main)'};">
                            ${item.customScore}
                        </span>
                        <span style="font-size: 0.68rem; color: var(--text-dim); display: block;">Nota Ajustada</span>
                    </div>
                    <div style="display: flex; gap: 0.35rem;">
                        <a href="{{ url('/proveedores') }}/${item.slug}" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.7rem;">
                            <span>Ficha</span>
                        </a>
                        <a href="{{ url('/go') }}/${item.slug}" target="_blank" class="btn btn-primary btn-sm" style="padding: 0.4rem 0.8rem;">
                            <span>Oferta ↗</span>
                        </a>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        if (window.lucide) lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', () => {
        recalcBalanza();
    });
</script>
@endpush
@endsection
