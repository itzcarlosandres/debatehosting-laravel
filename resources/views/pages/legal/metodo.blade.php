@extends('layouts.app')

@section('title', 'Nuestra Metodología de Evaluación — Debatehosting')

@section('content')
<article style="max-width: 800px; margin: 0 auto; padding: 4rem 1.5rem; line-height: 1.8;">
    <span class="section-kicker">INDEPENDENCIA Y RIGOR TÉCNICO</span>
    <h1 style="font-family: var(--font-serif); font-size: 2.5rem; margin: 0.5rem 0 2rem 0;">Metodología de Análisis</h1>

    <p style="font-size: 1.1rem; color: var(--text-ink); margin-bottom: 1.5rem;">
        En <strong>Debatehosting</strong> no puntuamos en base a comisiones de afiliados. Nuestro sistema evalúa cuatro ejes fundamentales ponderables en <em>La Balanza</em>:
    </p>

    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 2rem; margin-bottom: 0.5rem;">1. Relación Calidad / Precio (Puntuación 0 a 10)</h2>
    <p>Comparamos el precio de entrada frente al precio de renovación estándar, analizando si el hardware ofrecido justifica el desembolso mensual y anual.</p>

    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 2rem; margin-bottom: 0.5rem;">2. Rendimiento Real y Latencia TTFB</h2>
    <p>Monitorizamos servidores con pings recurrentes y peticiones HTTP completas para registrar el <em>Time to First Byte</em> (TTFB) y el porcentaje de disponibilidad (Uptime) en periodos de 30 días.</p>

    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 2rem; margin-bottom: 0.5rem;">3. Soporte Técnico</h2>
    <p>Ponemos a prueba los canales de soporte (chat en vivo, tickets, teléfono) en horarios críticos para medir el tiempo de respuesta real y la competencia técnica del agente.</p>

    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-top: 2rem; margin-bottom: 0.5rem;">4. Experiencia de Usuario y Panel</h2>
    <p>Analizamos si la plataforma usa cPanel, hPanel u otros paneles propietarios, valorando la facilidad para migrar webs, gestionar DNS y crear copias de seguridad.</p>
</article>
@endsection
