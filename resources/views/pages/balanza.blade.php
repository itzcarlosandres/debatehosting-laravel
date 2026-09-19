@extends('layouts.app')

@section('title', 'La Balanza: Comparador Ponderado de Hosting y Servidores — Debatehosting')

@section('content')
<section style="background: var(--bg-dark-paper); color: var(--text-dark-ink); padding: 4rem 1.5rem; min-height: 80vh;">
    <div style="max-width: 1100px; margin: 0 auto;">
        <!-- Cabecera de La Balanza -->
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <span style="font-family: var(--font-mono); font-size: 0.8rem; color: #46C285; letter-spacing: 0.1em; text-transform: uppercase;">
                ALGORITMO EDITORIAL DE PONDERACIÓN
            </span>
            <h1 style="font-family: var(--font-serif); font-size: 2.8rem; margin: 0.5rem 0 1rem 0; color: #FAF7EE;">
                ⚖️ La Balanza del Hosting
            </h1>
            <p style="font-size: 1.1rem; color: var(--text-dark-muted); max-width: 650px; margin: 0 auto;">
                Ajusta la importancia que le das a cada factor. Nuestro motor recalculará la puntuación exacta de cada proveedor para tu caso específico.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; align-items: flex-start;">
            <!-- Controles / Sliders -->
            <div style="background: var(--bg-dark-surface); border: 1.5px solid var(--border-dark); border-radius: var(--radius-md); padding: 2rem; box-shadow: 4px 4px 0 rgba(0,0,0,0.5);">
                <h3 style="font-family: var(--font-serif); font-size: 1.4rem; margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-dark); padding-bottom: 0.75rem;">
                    Ajusta tus Prioridades
                </h3>

                <!-- Slider 1: Precio -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <span>💰 Economía / Presupuesto</span>
                        <span id="val-precio" style="font-family: var(--font-mono); color: #46C285; font-weight: 700;">50%</span>
                    </div>
                    <input type="range" id="slider-precio" min="10" max="100" value="50" oninput="recalcBalanza()" style="width: 100%; accent-color: #0E6B41;">
                </div>

                <!-- Slider 2: Rendimiento -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <span>⚡ Velocidad / Rendimiento</span>
                        <span id="val-rendimiento" style="font-family: var(--font-mono); color: #46C285; font-weight: 700;">50%</span>
                    </div>
                    <input type="range" id="slider-rendimiento" min="10" max="100" value="50" oninput="recalcBalanza()" style="width: 100%; accent-color: #0E6B41;">
                </div>

                <!-- Slider 3: Soporte -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <span>🛠️ Soporte Técnico 24/7</span>
                        <span id="val-soporte" style="font-family: var(--font-mono); color: #46C285; font-weight: 700;">50%</span>
                    </div>
                    <input type="range" id="slider-soporte" min="10" max="100" value="50" oninput="recalcBalanza()" style="width: 100%; accent-color: #0E6B41;">
                </div>

                <!-- Slider 4: Facilidad -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <span>🧩 Facilidad de Uso (Panel)</span>
                        <span id="val-facilidad" style="font-family: var(--font-mono); color: #46C285; font-weight: 700;">50%</span>
                    </div>
                    <input type="range" id="slider-facilidad" min="10" max="100" value="50" oninput="recalcBalanza()" style="width: 100%; accent-color: #0E6B41;">
                </div>
            </div>

            <!-- Resultados en Tiempo Real -->
            <div>
                <h3 style="font-family: var(--font-serif); font-size: 1.4rem; margin-top: 0; margin-bottom: 1rem; color: #FAF7EE;">
                    Ranking Recomendado según tus Pesos:
                </h3>
                <div id="balanza-results" style="display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Se inyecta con JS -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const providersData = @json($providers);

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
                customScore: finalScore.toFixed(2)
            };
        }).sort((a, b) => b.customScore - a.customScore);

        renderResults(scored);
    }

    function renderResults(list) {
        const container = document.getElementById('balanza-results');
        container.innerHTML = '';

        list.slice(0, 5).forEach((item, idx) => {
            const card = document.createElement('div');
            card.style.background = idx === 0 ? '#1F2A1E' : '#1B1813';
            card.style.border = idx === 0 ? '2px solid #46C285' : '1px solid #322D24';
            card.style.borderRadius = '6px';
            card.style.padding = '1.25rem';
            card.style.display = 'flex';
            card.style.justifyContent = 'space-between';
            card.style.alignItems = 'center';

            card.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-family: var(--font-mono); font-size: 1.2rem; font-weight: 700; color: ${idx === 0 ? '#46C285' : '#9E9687'};">#${idx + 1}</span>
                    <div>
                        <div style="font-weight: 700; font-size: 1.1rem; color: #FAF7EE;">${item.name}</div>
                        <div style="font-size: 0.8rem; color: #9E9687;">${item.plan} — $${item.price_from}/mes</div>
                    </div>
                </div>
                <div style="text-align: right; display: flex; align-items: center; gap: 1.5rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: #9E9687;">Ajuste</span>
                        <div style="font-family: var(--font-mono); font-size: 1.3rem; font-weight: 800; color: ${idx === 0 ? '#46C285' : '#FAF7EE'};">${item.customScore}</div>
                    </div>
                    <a href="/go/${item.slug}" target="_blank" class="btn-primary-editorial" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                        Ver ↗
                    </a>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Inicializar al cargar
    document.addEventListener('DOMContentLoaded', recalcBalanza);
</script>
@endsection
