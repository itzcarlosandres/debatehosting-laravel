@extends('layouts.app')

@section('title', 'Nuestra Metodología de Evaluación — Debatehosting')
@section('meta_description', 'Conoce el riguroso protocolo de pruebas técnicas, monitorización de latencia TTFB y análisis de precios con el que evaluamos cada proveedor de hosting.')

@section('content')
<div class="container container-narrow" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <header class="mb-5 text-center">
        <div class="section-kicker">INDEPENDENCIA Y RIGOR TÉCNICO</div>
        <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.03em; margin: 0.75rem 0 1rem 0; color: var(--text-heading);">
            Metodología de Análisis
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 620px; margin: 0 auto; line-height: 1.6;">
            En <strong>Debatehosting</strong> no vendemos rankings. Nuestro laboratorio somete cada servidor a pruebas empíricas continuas para ofrecerte datos 100% verificables.
        </p>
    </header>

    <div class="card p-5 mb-4" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: rgba(16,185,129,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
                01
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.35rem;">
                    Relación Calidad / Precio (0 a 10)
                </h2>
                <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
                    Comparamos la tarifa promocional de entrada frente a la cuota de renovación estándar. Evaluamos minuciosamente si el hardware asignado (núcleos vCPU, RAM dedicada, almacenamiento NVMe y límites de inodos) justifica de forma sostenible el desembolso mensual y anual.
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: rgba(14,165,233,0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
                02
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.35rem;">
                    Rendimiento Real y Latencia TTFB
                </h2>
                <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
                    Monitorizamos cada proveedor mediante peticiones HTTP concurrentes cada 60 segundos desde nodos en Madrid, Frankfurt y Virginia. Registramos el <em>Time to First Byte</em> (TTFB), el comportamiento bajo cargas concurrentes y la tasa de uptime garantizado en periodos móviles de 30 y 90 días.
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: rgba(245,158,11,0.1); color: #d97706; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
                03
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.35rem;">
                    Soporte Técnico en Horarios Críticos
                </h2>
                <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
                    Ponemos a prueba los canales de soporte (chat en vivo 24/7, tickets con incidencias complejas de servidor y atención telefónica). Medimos con cronómetro el tiempo de primera respuesta y la pericia técnica de los ingenieros ante caídas reales de MySQL o fallos de certificados SSL.
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: flex-start; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: rgba(99,102,241,0.1); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
                04
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.35rem;">
                    Experiencia de Usuario, Panel y Facilidad
                </h2>
                <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
                    Valoramos la fluidez del panel de control (cPanel, hPanel, CyberPanel o interfaces personalizadas). Evaluamos la sencillez del instalador en 1-clic, la integración de copias de seguridad automáticas externas, el gestor DNS y las facilidades para migraciones gratuitas sin cortes.
                </p>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('balanza') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 600;">
            Calibrar Hosting en La Balanza &rarr;
        </a>
    </div>
</div>
@endsection
