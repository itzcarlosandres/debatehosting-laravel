@extends('layouts.app')

@section('title', 'Política de Privacidad — Debatehosting')
@section('meta_description', 'Política de privacidad y protección de datos personales de Debatehosting en conformidad con el RGPD.')

@section('content')
<div class="container container-narrow" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <header class="mb-5 text-center">
        <div class="section-kicker">PROTECCIÓN DE DATOS</div>
        <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.03em; margin: 0.75rem 0 1rem 0; color: var(--text-heading);">
            Política de Privacidad
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 620px; margin: 0 auto; line-height: 1.6;">
            Última actualización: Septiembre 2026. Debatehosting respeta rigurosamente tu privacidad y cumple con el RGPD y la normativa europea.
        </p>
    </header>

    <div class="card p-5" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            1. Responsable del Tratamiento
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            El responsable del tratamiento de los datos recabados en este sitio web es el equipo editorial de Debatehosting. Puedes contactar con nosotros en cualquier momento para ejercer tus derechos ARCO (Acceso, Rectificación, Cancelación y Oposición).
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            2. Datos recopilados
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            Únicamente recopilamos tu dirección de correo electrónico cuando decides voluntariamente suscribirte a nuestro boletín de ofertas y análisis técnicos. Jamás vendemos, alquilamos ni compartimos tus datos de contacto con ninguna entidad comercial o publicitaria externa.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            3. Cookies y Enlaces Salientes
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            Utilizamos cookies técnicas y analíticas de navegación anónimas estrictamente necesarias para el rendimiento de la web y la medición agregada del tráfico. Al hacer clic en enlaces de afiliados que conducen a las plataformas de los proveedores, se pueden registrar cookies de seguimiento en los servidores del proveedor de destino conforme a sus respectivas políticas de privacidad.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            4. Conservación y Supresión de Datos
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
            Los datos de correo para el boletín se conservan hasta que solicitas tu baja mediante el enlace automático al pie de cada correo o enviándonos una notificación.
        </p>
    </div>
</div>
@endsection
