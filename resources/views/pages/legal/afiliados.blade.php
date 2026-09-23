@extends('layouts.app')

@section('title', 'Aviso de Afiliación y Transparencia — Debatehosting')
@section('meta_description', 'Descubre nuestra política de transparencia sobre enlaces de afiliados y cómo garantizamos análisis 100% independientes y objetivos.')

@section('content')
<div class="container container-narrow" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <header class="mb-5 text-center">
        <div class="section-kicker">TRANSPARENCIA EDITORIAL</div>
        <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.03em; margin: 0.75rem 0 1rem 0; color: var(--text-heading);">
            Aviso de Enlaces de Afiliados
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 620px; margin: 0 auto; line-height: 1.6;">
            Creemos en la honestidad total con nuestros lectores. Aquí te explicamos con absoluta claridad cómo se financia Debatehosting.
        </p>
    </header>

    <div class="card p-5 mb-4" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <div style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.25); border-left: 4px solid var(--primary); padding: 1.25rem 1.5rem; margin-bottom: 2rem; border-radius: var(--radius-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: #065f46; margin-bottom: 0.25rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Declaración Obligatoria y Ética
            </div>
            <p style="font-size: 0.95rem; color: #047857; line-height: 1.6; margin: 0;">
                Algunos de los enlaces salientes hacia proveedores de hosting en esta plataforma son enlaces de afiliación comercial.
            </p>
        </div>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            ¿Cómo funciona el modelo de afiliación?
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 1.5rem;">
            Cuando haces clic en uno de nuestros enlaces de oferta o utilizas un cupón de descuento y contratas un servicio en la web oficial del proveedor, podemos recibir una comisión de venta por parte del proveedor sin coste adicional alguno para ti. De hecho, gracias al volumen de nuestra comunidad y acuerdos directos, con frecuencia obtienes descuentos superiores a los disponibles directamente en su web.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            ¿Afecta la comisión al ranking o a las puntuaciones?
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 1.5rem;">
            <strong style="color: var(--text-heading);">No. De forma categórica y permanente.</strong> Las posiciones del Podio y las valoraciones del algoritmo de <em>La Balanza</em> se calculan exclusivamente mediante criterios técnicos objetivos (velocidad de respuesta, disponibilidad, calidad de hardware y solvencia del soporte técnico).
        </p>
        <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
            Si un proveedor comisiona generosamente pero sus servidores sufren caídas constantes o lentitud inaceptable, su calificación descenderá o será directamente desaconsejado. La confianza de nuestra comunidad está por encima de cualquier acuerdo mercantil.
        </p>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('metodo') }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; font-weight: 600;">
            Leer Metodología de Pruebas &rarr;
        </a>
    </div>
</div>
@endsection
