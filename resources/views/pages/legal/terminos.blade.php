@extends('layouts.app')

@section('title', 'Términos de Servicio — Debatehosting')
@section('meta_description', 'Términos y condiciones de uso de la plataforma de comparativa y análisis de hosting Debatehosting.')

@section('content')
<div class="container container-narrow" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <header class="mb-5 text-center">
        <div class="section-kicker">CONDICIONES LEGALES</div>
        <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.03em; margin: 0.75rem 0 1rem 0; color: var(--text-heading);">
            Términos de Servicio
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 620px; margin: 0 auto; line-height: 1.6;">
            El acceso y uso de Debatehosting está sujeto a las siguientes condiciones de servicio.
        </p>
    </header>

    <div class="card p-5" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            1. Objeto y Naturaleza Editorial
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            Debatehosting es un portal independiente de análisis técnico, comparativa de planes e información sobre servicios de alojamiento web. No somos un proveedor de hosting ni comercializamos directamente servidores o dominios. Todas las contrataciones se realizan en las plataformas oficiales de los proveedores referenciados.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            2. Exactitud de Precios y Cupones
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            Nos esforzamos por verificar diariamente los precios, descuentos y cupones de promoción. Sin embargo, las empresas de hosting se reservan el derecho a modificar sus tarifas, condiciones de renovación y promociones en cualquier momento sin previo aviso. Recomendamos siempre verificar el importe final en el carrito del proveedor antes de formalizar el pago.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            3. Exención de Responsabilidad
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin-bottom: 2rem;">
            Debatehosting no asume responsabilidad alguna por incidencias técnicas, caídas de servicio, pérdidas de datos, discrepancias contractuales o problemas de facturación surgidos entre el usuario y los proveedores externos contratados.
        </p>

        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin-bottom: 0.75rem;">
            4. Propiedad Intelectual
        </h2>
        <p style="color: var(--text-ink); line-height: 1.7; margin: 0;">
            Los textos, algoritmos de cálculo, diseño gráfico y metodologías de evaluación son propiedad exclusiva de Debatehosting. Las marcas, logotipos e identidades de los proveedores mencionados son propiedad de sus respectivos titulares.
        </p>
    </div>
</div>
@endsection
