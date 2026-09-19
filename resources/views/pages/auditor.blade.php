@extends('layouts.app')

@section('title', 'Auditor de Servidores y Dominios en Vivo — Debatehosting')

@section('content')
<section style="max-width: 900px; margin: 0 auto; padding: 4rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="section-kicker">HERRAMIENTA PÚBLICA DE DIAGNÓSTICO</span>
        <h1 class="section-title" style="font-size: 2.6rem; margin: 0.5rem 0;">Auditor de Servidores</h1>
        <p class="section-subtitle">Introduce un dominio para medir en vivo su tiempo de respuesta (TTFB), servidor y estado de conexión.</p>
    </div>

    <!-- Caja de Búsqueda -->
    <div style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 2.5rem; box-shadow: var(--shadow-solid); margin-bottom: 2.5rem;">
        <form id="auditor-form" onsubmit="runAudit(event)" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <input type="text" id="audit-domain" placeholder="ejemplo.com o https://misitio.com" required style="flex: 1; min-width: 280px; padding: 0.8rem 1.25rem; font-size: 1.1rem; border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); font-family: var(--font-mono);">
            <button type="submit" id="btn-audit" class="btn-primary-editorial" style="padding: 0.8rem 2rem; font-size: 1.05rem;">
                🔍 Auditar Servidor
            </button>
        </form>

        <div id="audit-loading" style="display: none; text-align: center; padding: 2rem 0;">
            <span class="pulse-dot" style="width: 12px; height: 12px;"></span>
            <span style="font-family: var(--font-mono); margin-left: 0.5rem;">Conectando con el servidor y midiendo latencia TTFB...</span>
        </div>
    </div>

    <!-- Resultados del Diagnóstico -->
    <div id="audit-results" style="display: none; background: var(--bg-surface); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 2rem; box-shadow: var(--shadow-solid);">
        <h3 style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px dashed var(--border-ink); padding-bottom: 0.75rem;">
            Resultado de la Auditoría Técnica
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div style="background: #fff; padding: 1rem; border: 1px solid var(--border-ink); border-radius: var(--radius-sm);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Dominio Analizado</div>
                <div id="res-domain" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; word-break: break-all;">-</div>
            </div>

            <div style="background: #fff; padding: 1rem; border: 1px solid var(--border-ink); border-radius: var(--radius-sm);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Tiempo de Respuesta (TTFB)</div>
                <div id="res-ttfb" style="font-family: var(--font-mono); font-weight: 800; font-size: 1.3rem; color: var(--green-primary);">-</div>
            </div>

            <div style="background: #fff; padding: 1rem; border: 1px solid var(--border-ink); border-radius: var(--radius-sm);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Código de Estado HTTP</div>
                <div id="res-status" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem;">-</div>
            </div>

            <div style="background: #fff; padding: 1rem; border: 1px solid var(--border-ink); border-radius: var(--radius-sm);">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Dirección IP del Host</div>
                <div id="res-ip" style="font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem;">-</div>
            </div>
        </div>
    </div>
</section>

<script>
    function runAudit(e) {
        e.preventDefault();
        const domain = document.getElementById('audit-domain').value;
        const btn = document.getElementById('btn-audit');
        const loading = document.getElementById('audit-loading');
        const results = document.getElementById('audit-results');

        btn.disabled = true;
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
            loading.style.display = 'none';

            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('res-domain').innerText = data.domain;
            document.getElementById('res-ttfb').innerText = data.ttfb;
            document.getElementById('res-status').innerText = 'HTTP ' + data.http_code;
            document.getElementById('res-ip').innerText = data.ip;

            results.style.display = 'block';
        })
        .catch(() => {
            btn.disabled = false;
            loading.style.display = 'none';
            alert('Ocurrió un error al contactar con el servidor. Inténtalo de nuevo.');
        });
    }
</script>
@endsection
