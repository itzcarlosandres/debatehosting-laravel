@extends('layouts.app')

@section('title', 'Auditor de Servidores y Dominios en Vivo — Debatehosting')
@section('meta_description', 'Auditor técnico de servidores y dominios en vivo. Analiza DNS, tiempo de respuesta TTFB, motor web HTTP/3 y estado de conexión de cualquier página web.')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container-narrow">
        <!-- Encabezado de Sección -->
        <div class="section-headline-wrap" style="margin-bottom: 2.5rem;">
            <div class="kicker">HERRAMIENTA PÚBLICA DE DIAGNÓSTICO</div>
            <h1 class="section-title">Auditor de Servidores TTFB</h1>
            <p class="section-subtitle">
                Introduce cualquier dominio para medir en tiempo real el tiempo de respuesta (TTFB), servidor web y estabilidad de conexión.
            </p>
        </div>

        <!-- Caja de Búsqueda -->
        <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem 2.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem;">
            <form id="auditor-form" onsubmit="runAudit(event)" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 280px; position: relative;">
                    <input type="text" id="audit-domain" placeholder="ejemplo.com o https://misitio.com" required style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.4rem; font-size: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-family: var(--font-mono); outline: none; background-color: var(--bg-subtle);">
                    <i data-lucide="globe" style="width: 16px; height: 16px; color: var(--text-dim); position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%);"></i>
                </div>
                <button type="submit" id="btn-audit" class="btn btn-emerald btn-lg" style="padding: 0.75rem 1.75rem;">
                    <i data-lucide="activity" style="width: 16px; height: 16px;"></i>
                    <span id="btn-audit-text">Auditar en Vivo</span>
                </button>
            </form>

            <div id="audit-loading" style="display: none; text-align: center; padding: 2rem 0;">
                <div class="pulse-dot-anim" style="display: inline-block; width: 10px; height: 10px;"></div>
                <span style="font-family: var(--font-mono); margin-left: 0.5rem; font-size: 0.88rem; color: var(--text-muted);">
                    Realizando peticiones HTTP concurrentes y calculando latencia TTFB...
                </span>
            </div>
        </div>

        <!-- Resultados del Diagnóstico -->
        <div id="audit-results" style="display: none; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-md);">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.75rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="check-circle-2" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>Diagnóstico Técnico Finalizado</span>
                </h3>
                <span id="res-health-badge" class="badge-pill badge-emerald">LATENCIA EXCELENTE</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem;">
                <div style="background-color: var(--bg-subtle); padding: 1.25rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 0.35rem;">Dominio Analizado</div>
                    <div id="res-domain" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.05rem; color: var(--text-main); word-break: break-all;">-</div>
                </div>

                <div style="background-color: var(--bg-subtle); padding: 1.25rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 0.35rem;">Tiempo de Respuesta (TTFB)</div>
                    <div id="res-ttfb" style="font-family: var(--font-mono); font-weight: 800; font-size: 1.35rem; color: var(--emerald-primary);">-</div>
                </div>

                <div style="background-color: var(--bg-subtle); padding: 1.25rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 0.35rem;">Código de Estado HTTP</div>
                    <div id="res-status" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.05rem; color: var(--text-main);">-</div>
                </div>

                <div style="background-color: var(--bg-subtle); padding: 1.25rem; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); margin-bottom: 0.35rem;">Dirección IP del Host</div>
                    <div id="res-ip" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.05rem; color: var(--text-main);">-</div>
                </div>
            </div>

            <!-- Recomendación de la Redacción -->
            <div id="res-recommendation" style="margin-top: 1.75rem; background-color: var(--emerald-subtle); border-left: 4px solid var(--emerald-primary); padding: 1rem 1.25rem; border-radius: var(--radius-sm); font-size: 0.88rem; color: var(--text-body);">
                <strong>Veredicto:</strong> El servidor responde con rapidez y estabilidad óptima para producción.
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function runAudit(e) {
        e.preventDefault();
        const domain = document.getElementById('audit-domain').value.trim();
        const btn = document.getElementById('btn-audit');
        const btnText = document.getElementById('btn-audit-text');
        const loading = document.getElementById('audit-loading');
        const results = document.getElementById('audit-results');

        btn.disabled = true;
        btnText.innerText = 'Analizando...';
        loading.style.display = 'block';
        results.style.display = 'none';

        fetch('{{ route("auditor.inspect") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ domain: domain })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btnText.innerText = 'Auditar en Vivo';
            loading.style.display = 'none';

            if (data.error) {
                window.showToast(data.error, 'info');
                return;
            }

            document.getElementById('res-domain').innerText = data.domain;
            document.getElementById('res-ttfb').innerText = data.ttfb;
            document.getElementById('res-status').innerText = 'HTTP ' + data.http_code;
            document.getElementById('res-ip').innerText = data.ip;

            // Evaluación de salud del TTFB
            const ttfbNum = parseFloat(data.ttfb);
            const badge = document.getElementById('res-health-badge');
            const reco = document.getElementById('res-recommendation');

            if (ttfbNum < 150) {
                badge.className = 'badge-pill badge-emerald';
                badge.innerText = 'VELOCIDAD EXCELENTE';
                reco.innerHTML = '<strong>Veredicto:</strong> El servidor cuenta con una latencia de respuesta ejemplar (<150ms). Excelente optimización.';
            } else if (ttfbNum < 350) {
                badge.className = 'badge-pill badge-sky';
                badge.innerText = 'VELOCIDAD ÓPTIMA';
                reco.innerHTML = '<strong>Veredicto:</strong> Tiempo de respuesta bueno y adecuado para la gran mayoría de sitios web dinámicos.';
            } else {
                badge.className = 'badge-pill badge-rose';
                badge.innerText = 'LATENCIA ELEVADA';
                reco.innerHTML = '<strong>Veredicto:</strong> El tiempo de primer byte supera los 350ms. Se recomienda activar caché a nivel de servidor o evaluar un hosting más rápido.';
            }

            results.style.display = 'block';
            window.showToast('Auditoría completada para ' + data.domain, 'success');
        })
        .catch(() => {
            btn.disabled = false;
            btnText.innerText = 'Auditar en Vivo';
            loading.style.display = 'none';
            window.showToast('Error al conectar con el servidor. Revisa el dominio ingresado.', 'info');
        });
    }
</script>
@endpush
@endsection
