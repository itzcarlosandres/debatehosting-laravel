@extends('layouts.admin')

@section('title', 'Crear Nuevo Proveedor')

@push('admin-styles')
<style>
    /* =========================================================================
       ESTILOS PREMIUM: CREAR PROVEEDOR (DARK MINIMALISTA / HIGH-TECH)
       ========================================================================= */
    /* =========================================================================
       ESTILOS PREMIUM: CREAR PROVEEDOR (DARK MINIMALISTA / HIGH-TECH)
       ========================================================================= */
    .create-provider-container {
        max-width: 1280px;
        margin: 0 auto;
        padding-bottom: 5.5rem;
    }

    /* Command Bar de IA */
    .ai-assistant-banner {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(19, 22, 28, 0.95) 100%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35), 0 0 15px rgba(16, 185, 129, 0.08);
        position: relative;
        overflow: hidden;
    }
    .ai-assistant-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--emerald-primary), transparent);
    }
    .ai-banner-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .ai-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--emerald-primary);
        background: rgba(16, 185, 129, 0.12);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        border: 1px solid rgba(16, 185, 129, 0.25);
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .ai-banner-inputs {
        display: grid;
        grid-template-columns: 2fr 1.5fr auto;
        gap: 0.85rem;
        align-items: flex-end;
    }
    @media (max-width: 800px) {
        .ai-banner-inputs {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        .ai-banner-inputs #btn-generate-ai {
            width: 100%;
            justify-content: center;
            height: 42px;
        }
    }

    /* Layout Principal de 2 Columnas */
    .provider-grid-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 350px;
        gap: 1.75rem;
        align-items: start;
    }

    /* Tarjetas de Contenido */
    .editor-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .editor-card:hover {
        border-color: var(--border-medium);
    }
    .editor-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding-bottom: 1rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
    }
    .editor-card-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1.05rem;
        font-weight: 700;
        color: #FFFFFF;
        letter-spacing: -0.01em;
    }
    .editor-card-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-subtle);
    }
    .editor-card-desc {
        font-size: 0.8rem;
        color: var(--text-dim);
        margin-top: 0.2rem;
    }

    /* Grids Responsivos de Sección */
    .provider-identity-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .plan-auditado-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    /* Slug Input Group Moderno */
    .slug-input-wrapper {
        display: flex;
        align-items: stretch;
        background: var(--bg-input);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-sm);
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .slug-input-wrapper:focus-within {
        border-color: var(--emerald-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
    .slug-input-prefix {
        display: flex;
        align-items: center;
        padding: 0 0.75rem;
        background: rgba(255, 255, 255, 0.04);
        border-right: 1px solid var(--border-subtle);
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--text-dim);
        white-space: nowrap;
        user-select: none;
    }
    .slug-input-field {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0.65rem 0.85rem !important;
        flex: 1;
        min-width: 0;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        color: #FFFFFF;
        outline: none;
    }

    /* Enlace Maestro de Afiliado */
    .main-affiliate-card {
        margin-bottom: 1.5rem;
        background: rgba(16, 185, 129, 0.03);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: var(--radius-sm);
        padding: 1.15rem;
    }
    .main-affiliate-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .main-affiliate-badge {
        font-size: 0.72rem;
        color: var(--emerald-primary);
        font-family: var(--font-mono);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Header de Sección Multi-Producto */
    .products-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .products-section-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Tarjetas de Producto / Planes Secundarios */
    .product-item-card {
        background: rgba(0, 0, 0, 0.25);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-sm);
        padding: 1rem;
        transition: border-color 0.2s ease;
    }
    .product-item-card:hover {
        border-color: rgba(16, 185, 129, 0.35);
    }
    .product-card-grid-top {
        display: grid;
        grid-template-columns: 1.4fr 2fr 1fr 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .product-card-grid-bottom {
        display: grid;
        grid-template-columns: 2.2fr 1.8fr auto;
        gap: 0.75rem;
        align-items: flex-end;
    }
    .product-featured-checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        font-size: 0.78rem;
        color: #FFFFFF;
        user-select: none;
        padding: 0.45rem 0.65rem;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-sm);
        white-space: nowrap;
    }

    /* Sidebar (Sticky únicamente en pantallas de escritorio > 1024px) */
    .sticky-sidebar-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: static;
    }
    @media (min-width: 1025px) {
        .sticky-sidebar-content {
            position: sticky;
            top: calc(var(--header-h) + 1.5rem);
        }
    }

    /* Dropzone de Logotipo */
    .logo-dropzone {
        border: 1.5px dashed var(--border-medium);
        border-radius: var(--radius-md);
        background: rgba(0, 0, 0, 0.2);
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }
    .logo-dropzone:hover, .logo-dropzone.dragover {
        border-color: var(--emerald-primary);
        background: rgba(16, 185, 129, 0.04);
    }
    .logo-preview-box {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-sm);
        background: #FFFFFF;
        border: 1px solid var(--border-subtle);
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .logo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Widget de Telemetría La Balanza */
    .telemetry-score-hero {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(19, 22, 28, 0.9) 100%);
        border: 1px solid rgba(16, 185, 129, 0.25);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-align: center;
        margin-bottom: 1.25rem;
        position: relative;
    }
    .telemetry-hero-value {
        font-family: var(--font-mono);
        font-size: 2.75rem;
        font-weight: 800;
        color: var(--emerald-primary);
        letter-spacing: -0.03em;
        line-height: 1;
        margin: 0.4rem 0;
        text-shadow: 0 0 20px var(--emerald-glow);
    }
    .telemetry-hero-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-dim);
    }
    .telemetry-slider-row {
        margin-bottom: 1.1rem;
    }
    .telemetry-slider-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
        font-size: 0.78rem;
    }
    .telemetry-slider-val {
        font-family: var(--font-mono);
        font-weight: 700;
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.08);
        padding: 0.1rem 0.45rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    /* Pros & Contras Dual Cards */
    .pros-cons-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .pros-card {
        background: rgba(16, 185, 129, 0.03);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: var(--radius-sm);
        padding: 1rem 1.15rem;
    }
    .cons-card {
        background: rgba(244, 63, 94, 0.03);
        border: 1px solid rgba(244, 63, 94, 0.2);
        border-radius: var(--radius-sm);
        padding: 1rem 1.15rem;
    }

    /* Previsualizador Google SERP */
    .google-serp-preview {
        background: #14171F;
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-sm);
        padding: 1.25rem;
        margin-top: 1rem;
        font-family: Arial, sans-serif;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    .google-serp-url {
        font-size: 0.76rem;
        color: #8AB4F8;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.25rem;
        overflow-wrap: anywhere;
    }
    .google-serp-title {
        font-size: 1.05rem;
        color: #8AB4F8;
        text-decoration: underline;
        font-weight: 400;
        cursor: pointer;
        line-height: 1.3;
        margin-bottom: 0.35rem;
        word-break: break-word;
    }
    .google-serp-desc {
        font-size: 0.82rem;
        color: #BDC1C6;
        line-height: 1.4;
        word-break: break-word;
    }

    /* Barra Flotante de Guardado (Sticky Action Dock) */
    .sticky-action-dock {
        position: fixed;
        bottom: 0;
        left: var(--sidebar-w);
        right: 0;
        height: 68px;
        background: rgba(13, 15, 18, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid var(--border-subtle);
        z-index: 35;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2.5rem;
        transition: left 0.28s ease;
    }
    .dock-shortcut-badge {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--border-medium);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        color: var(--text-dim);
    }

    /* =========================================================================
       OPTIMIZACIÓN RESPONSIVA PROFUNDA (MÓVILES Y TABLETS)
       ========================================================================= */
    @media (max-width: 1024px) {
        .provider-grid-layout {
            display: flex !important;
            flex-direction: column !important;
            gap: 1.25rem !important;
        }
        .provider-main-column,
        .provider-sidebar-column,
        .sticky-sidebar-content {
            display: contents !important;
            position: static !important;
            top: auto !important;
        }
        .editor-card {
            margin-bottom: 0 !important;
            position: static !important;
            top: auto !important;
        }
        #card-status {
            order: 1 !important;
            position: static !important;
            top: auto !important;
        }
        #card-logo {
            order: 2 !important;
            position: static !important;
            top: auto !important;
        }
        #card-identity {
            order: 3 !important;
            position: static !important;
            top: auto !important;
        }
        #card-plans {
            order: 4 !important;
            position: static !important;
            top: auto !important;
        }
        #card-verdict {
            order: 5 !important;
            position: static !important;
            top: auto !important;
        }
        #card-seo {
            order: 6 !important;
            position: static !important;
            top: auto !important;
        }
        #card-telemetry {
            order: 7 !important;
            position: static !important;
            top: auto !important;
        }
    }

    @media (max-width: 992px) {
        .product-card-grid-top {
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }
        .product-grid-col-plan {
            grid-column: 1 / -1;
        }
        .product-card-grid-bottom {
            grid-template-columns: 1fr;
            gap: 0.65rem;
            align-items: stretch;
        }
    }

    @media (max-width: 768px) {
        .create-provider-container {
            padding-bottom: 7rem;
        }
        .editor-card {
            padding: 1.15rem 1rem;
            margin-bottom: 1.15rem;
            border-radius: var(--radius-sm);
        }
        .editor-card-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
        }
        .editor-card-title {
            font-size: 0.95rem;
        }
        .editor-card-icon {
            width: 28px;
            height: 28px;
        }
        .editor-card-desc {
            font-size: 0.75rem;
        }
        .provider-identity-grid {
            grid-template-columns: 1fr;
            gap: 0.85rem;
            margin-bottom: 1rem;
        }
        .plan-auditado-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .plan-auditado-name-col {
            grid-column: 1 / -1;
        }
        .pros-cons-grid {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }
        .sticky-sidebar-content {
            gap: 1.15rem;
        }
        .telemetry-hero-value {
            font-size: 2.3rem;
        }
        .sticky-action-dock {
            left: 0;
            padding: 0 1rem;
            height: 64px;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
    }

    @media (max-width: 600px) {
        .create-provider-container {
            padding-bottom: 7.5rem;
        }
        .ai-assistant-banner {
            padding: 1rem 0.85rem;
            margin-bottom: 1.25rem;
        }
        .main-affiliate-card {
            padding: 0.85rem;
            margin-bottom: 1rem;
        }
        .product-item-card {
            padding: 0.85rem;
        }
        .product-card-grid-top {
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }
        .product-grid-col-category {
            grid-column: 1 / -1;
        }
        .product-grid-col-plan {
            grid-column: 1 / -1;
        }
        .product-grid-col-period {
            grid-column: 1 / -1;
        }
        .product-featured-checkbox-label {
            width: 100%;
            justify-content: flex-start;
        }
        .products-section-actions {
            width: 100%;
        }
        .products-section-actions button {
            flex: 1;
            justify-content: center;
            height: 38px;
        }
        .sticky-action-dock {
            padding: 0.5rem 0.75rem;
            height: auto;
            min-height: 62px;
        }
        .dock-status-info {
            display: none !important;
        }
        .dock-actions-group {
            width: 100% !important;
            display: flex !important;
            gap: 0.5rem !important;
        }
        .dock-actions-group .btn-secondary {
            flex: 1 !important;
            justify-content: center !important;
            height: 42px !important;
            font-size: 0.82rem !important;
        }
        .dock-actions-group .btn-primary {
            flex: 2 !important;
            justify-content: center !important;
            height: 42px !important;
            font-size: 0.84rem !important;
        }
        .slug-input-prefix {
            font-size: 0.7rem;
            padding: 0 0.5rem;
        }
        .google-serp-preview {
            padding: 0.85rem;
        }
        .google-serp-title {
            font-size: 0.95rem;
        }
        .google-serp-desc {
            font-size: 0.78rem;
        }
        .logo-dropzone {
            padding: 0.9rem 0.85rem;
            flex-direction: row;
            gap: 1rem;
            text-align: left;
        }
        .logo-preview-box {
            margin-bottom: 0;
            flex-shrink: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="create-provider-container">
    <!-- Encabezado de Página -->
    <div class="admin-page-header">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                <a href="{{ route('admin.providers.index') }}" style="color: var(--text-dim); text-decoration: none; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                    <i data-lucide="server" style="width: 13px; height: 13px;"></i>
                    <span>Proveedores</span>
                </a>
                <span style="color: var(--border-medium); font-size: 0.75rem;">/</span>
                <span style="color: var(--emerald-primary); font-size: 0.8rem; font-weight: 600;">Nuevo Registro</span>
            </div>
            <h1 class="page-header-title">Crear Nuevo Proveedor</h1>
            <p class="page-header-subtitle">Registra una empresa de hosting en el catálogo con asistencia inteligente y métricas automatizadas.</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.providers.index') }}" class="btn-secondary">
                <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
                <span>Volver al Catálogo</span>
            </a>
            <button type="button" onclick="document.getElementById('provider-form').requestSubmit()" class="btn-primary">
                <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                <span>Guardar Proveedor</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="toast-banner error" style="margin-bottom: 1.5rem;">
            <div>
                <div style="font-weight: 700; margin-bottom: 0.25rem;">Se encontraron errores de validación:</div>
                <ul style="margin-left: 1.25rem; font-size: 0.82rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- =========================================================================
         BARRA DE ASISTENCIA IA (COMMAND PALETTE STYLE)
         ========================================================================= -->
    <div class="ai-assistant-banner">
        <div class="ai-banner-header">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <span class="ai-badge-pill">
                    <i data-lucide="sparkles" style="width: 12px; height: 12px;"></i>
                    <span>Asistente IA</span>
                </span>
                <span style="font-weight: 700; color: #FFFFFF; font-size: 0.95rem;">Autocompletar Ficha Técnica</span>
            </div>
            <span style="font-size: 0.78rem; color: var(--text-dim);">Escribe el nombre de la empresa y la IA investigará sus planes, métricas y pros/contras.</span>
        </div>

        <div class="ai-banner-inputs">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem; color: var(--emerald-primary);">Nombre de la Empresa</label>
                <div style="position: relative;">
                    <i data-lucide="building-2" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text-dim);"></i>
                    <input type="text" id="ai-provider-name" placeholder="ej: Cloudways, Hetzner, BanaHosting, Kinsta..." class="form-control" style="padding-left: 2.3rem; border-color: rgba(16, 185, 129, 0.35); background: rgba(0, 0, 0, 0.35);">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Enfoque Principal (Opcional)</label>
                <input type="text" id="ai-provider-focus" placeholder="ej: VPS Cloud NVMe, WordPress Gestionado..." class="form-control" style="background: rgba(0, 0, 0, 0.35);">
            </div>

            <button type="button" id="btn-generate-ai" onclick="generateWithAI()" class="btn-primary" style="height: 38px; padding: 0 1.25rem; white-space: nowrap;">
                <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                <span id="btn-ai-text">Generar con IA</span>
            </button>
        </div>

        <!-- Indicador de Carga y Mensajes del Asistente -->
        <div id="ai-status" style="display: none; margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.82rem;"></div>
    </div>

    <!-- =========================================================================
         FORMULARIO PRINCIPAL EN GRID DE 2 COLUMNAS
         ========================================================================= -->
    <form id="provider-form" action="{{ route('admin.providers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="period" value="mes">

        <div class="provider-grid-layout">
            <!-- COLUMNA PRINCIPAL (70%) -->
            <div class="provider-main-column">
                
                <!-- SECCIÓN 1: IDENTIDAD, ENLACE PRINCIPAL & CATEGORÍAS -->
                <div class="editor-card" id="card-identity">
                    <div class="editor-card-header">
                        <div>
                            <div class="editor-card-title">
                                <div class="editor-card-icon" style="color: var(--emerald-primary);">
                                    <i data-lucide="shield" style="width: 17px; height: 17px;"></i>
                                </div>
                                <span>1. Identidad de Marca, Enlace & Categorías</span>
                            </div>
                            <div class="editor-card-desc">Información básica, enlace maestro de afiliado y taxonomía editorial para los filtros.</div>
                        </div>
                    </div>

                    <div class="provider-identity-grid">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="field-name">Nombre de la Empresa *</label>
                            <input type="text" id="field-name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="ej: Hostinger, Webempresa, SiteGround">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="field-slug">Slug URL (Ruta Amigable)</label>
                            <div class="slug-input-wrapper">
                                <span class="slug-input-prefix">/proveedores/</span>
                                <input type="text" id="field-slug" name="slug" class="slug-input-field" value="{{ old('slug') }}" placeholder="hostinger">
                            </div>
                        </div>
                    </div>

                    <!-- ENLACE OFICIAL / AFILIADO (URL PRINCIPAL) SITUADO ARRIBA -->
                    <div class="main-affiliate-card">
                        <div class="main-affiliate-header">
                            <label class="form-label" for="field-affiliate-url" style="margin-bottom: 0; color: #FFFFFF; font-weight: 700;">
                                Enlace Oficial / Afiliado (URL de Destino Principal)
                            </label>
                            <span class="main-affiliate-badge">
                                <i data-lucide="zap" style="width: 12px; height: 12px;"></i>
                                <span>Heredado automáticamente por todos los planes</span>
                            </span>
                        </div>
                        <div style="position: relative;">
                            <i data-lucide="link" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text-dim);"></i>
                            <input type="url" id="field-affiliate-url" name="affiliate_url" class="form-control" value="{{ old('affiliate_url') }}" placeholder="https://proveedor.com/?ref=debatehosting" style="padding-left: 2.3rem; font-family: var(--font-mono); font-size: 0.88rem;">
                        </div>
                        <div class="form-help" style="margin-top: 0.45rem;">
                            Al colocar tu enlace aquí, <strong>todos los planes del catálogo y botones <code>/go/{slug}</code> lo adoptan automáticamente</strong> sin tener que escribirlo una y otra vez.
                        </div>
                    </div>

                    <!-- Categorías con Chips Seleccionables -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="margin-bottom: 0.65rem;">
                            Categorías Aplicables * (Selecciona las áreas de especialidad)
                        </label>

                        @php
                            $selectedCategories = old('categories', ['hosting']);
                            if (is_string($selectedCategories)) {
                                $selectedCategories = json_decode($selectedCategories, true) ?: [];
                            }
                            if (!is_array($selectedCategories)) {
                                $selectedCategories = [];
                            }
                        @endphp

                        <div id="categories-container" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.85rem;">
                            @foreach($categories as $cat)
                                @php
                                    $isSelected = in_array($cat->slug, $selectedCategories) || in_array($cat->name, $selectedCategories);
                                @endphp
                                <button type="button" 
                                        class="category-toggle-pill {{ $isSelected ? 'is-selected' : '' }}" 
                                        data-slug="{{ $cat->slug }}"
                                        onclick="toggleCategoryPill(this, '{{ $cat->slug }}')">
                                    <span class="pill-prefix">{{ $isSelected ? '✓' : '+' }}</span>
                                    <span class="pill-name">{{ $cat->name }}</span>
                                    <input type="checkbox" name="categories[]" value="{{ $cat->slug }}" {{ $isSelected ? 'checked' : '' }} style="display: none;">
                                </button>
                            @endforeach
                        </div>

                        <!-- Añadir categoría personalizada -->
                        <div style="display: flex; gap: 0.5rem; max-width: 500px;">
                            <input type="text" id="custom-category-input" class="form-control" placeholder="Añadir otra categoría..." style="font-size: 0.82rem; height: 36px;" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addCustomCategory(); }">
                            <button type="button" onclick="addCustomCategory()" class="btn-secondary" style="height: 36px; padding: 0 0.9rem; font-size: 0.8rem; white-space: nowrap;">
                                <i data-lucide="plus" style="width: 13px; height: 13px;"></i>
                                <span>Añadir</span>
                            </button>
                        </div>
                    </div>

                    <!-- Distintivo / Badge Oficial -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="margin-bottom: 0.65rem;">Badge o Distintivo Visual (Opcional)</label>
                        @php
                            $currentBadge = old('badge', '');
                            $currentBadgeColor = old('badge_color', 'green');

                            $presetBadges = [
                                ['label' => 'HOT', 'color' => 'red'],
                                ['label' => 'MEJOR PRECIO', 'color' => 'green'],
                                ['label' => 'TOP RENDIMIENTO', 'color' => 'gold'],
                                ['label' => 'ELECCIÓN EDITORIAL', 'color' => 'dark'],
                                ['label' => 'RECOMENDADO', 'color' => 'green'],
                            ];

                            $allBadges = $presetBadges;
                            foreach($badges as $b) {
                                $exists = false;
                                foreach($allBadges as $ab) {
                                    if (strtoupper($ab['label']) === strtoupper($b->label)) { $exists = true; break; }
                                }
                                if (!$exists) {
                                    $allBadges[] = ['label' => $b->label, 'color' => $b->color];
                                }
                            }
                        @endphp

                        <input type="hidden" name="badge" id="field-badge" value="{{ $currentBadge }}">
                        <input type="hidden" name="badge_color" id="field-badge-color" value="{{ $currentBadgeColor }}">

                        <div id="badges-container" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <button type="button" 
                                    class="badge-select-pill {{ empty($currentBadge) ? 'is-selected' : '' }}" 
                                    data-label="" 
                                    data-color="" 
                                    onclick="selectBadgePill(this, '', '')">
                                <span class="pill-prefix">{{ empty($currentBadge) ? '✓ ' : '' }}</span>
                                <span>Sin Badge</span>
                            </button>

                            @foreach($allBadges as $b)
                                @php
                                    $isSelected = (strtoupper($currentBadge) === strtoupper($b['label']));
                                @endphp
                                <button type="button" 
                                        class="badge-select-pill badge-pill-{{ $b['color'] }} {{ $isSelected ? 'is-selected' : '' }}" 
                                        data-label="{{ $b['label'] }}" 
                                        data-color="{{ $b['color'] }}" 
                                        onclick="selectBadgePill(this, '{{ $b['label'] }}', '{{ $b['color'] }}')">
                                    <span class="pill-prefix">{{ $isSelected ? '✓ ' : '' }}</span>
                                    <span>{{ $b['label'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: PLAN AUDITADO & CATÁLOGO MULTI-PRODUCTO -->
                <div class="editor-card" id="card-plans">
                    <div class="editor-card-header">
                        <div>
                            <div class="editor-card-title">
                                <div class="editor-card-icon" style="color: var(--sky-primary);">
                                    <i data-lucide="tag" style="width: 17px; height: 17px;"></i>
                                </div>
                                <span>2. Plan Auditado de Referencia</span>
                            </div>
                            <div class="editor-card-desc">Tarifas base para el cálculo de ahorro porcentual mostrado en el portal.</div>
                        </div>
                        <div id="discount-calc-badge" style="display: inline-flex; align-items: center; gap: 0.35rem; font-family: var(--font-mono); font-size: 0.78rem; font-weight: 700; color: var(--rose-primary); background: var(--rose-subtle); padding: 0.25rem 0.65rem; border-radius: 4px; border: 1px solid rgba(244, 63, 94, 0.25);">
                            <span id="discount-calc-text">-70% OFF</span>
                        </div>
                    </div>

                    <div class="plan-auditado-grid">
                        <div class="form-group plan-auditado-name-col" style="margin-bottom: 0;">
                            <label class="form-label" for="field-plan">Nombre del Plan Principal *</label>
                            <input type="text" id="field-plan" name="plan" class="form-control" value="{{ old('plan', 'Plan Premium') }}" required placeholder="ej: Cloud Startup, WordPress Pro">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="field-price-from">Precio Oferta ($) *</label>
                            <input type="number" step="0.01" id="field-price-from" name="price_from" class="form-control" value="{{ old('price_from', '2.99') }}" required oninput="onPriceChangeLive()" style="font-family: var(--font-mono); font-weight: 700;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="field-price-before">Precio Regular ($) *</label>
                            <input type="number" step="0.01" id="field-price-before" name="price_before" class="form-control" value="{{ old('price_before', '9.99') }}" required oninput="onPriceChangeLive()" style="font-family: var(--font-mono);">
                        </div>
                    </div>

                    <!-- Catálogo Multi-Producto -->
                    <div style="padding-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
                        <div class="products-section-header">
                            <div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #FFFFFF; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem;">
                                    <i data-lucide="layers" style="width: 15px; height: 15px; color: var(--emerald-primary);"></i>
                                    <span>Planes & Líneas de Producto Específicas</span>
                                </h3>
                                <p style="font-size: 0.78rem; color: var(--text-dim); margin: 0;">Permite que el proveedor aparezca en filtros dedicados (/ofertas?categoria=vps) sin duplicar la empresa.</p>
                            </div>
                            <div class="products-section-actions">
                                <button type="button" onclick="autofillProductsWithAI()" id="btn-ai-products" class="btn-secondary" style="font-size: 0.78rem; padding: 0.4rem 0.75rem; background: rgba(16, 185, 129, 0.08); color: var(--emerald-primary); border-color: rgba(16, 185, 129, 0.3);">
                                    <i data-lucide="sparkles" style="width: 13px; height: 13px;"></i>
                                    <span id="btn-ai-products-text">Detectar con IA</span>
                                </button>
                                <button type="button" onclick="addProductRow()" class="btn-secondary" style="font-size: 0.78rem; padding: 0.4rem 0.75rem;">
                                    <i data-lucide="plus" style="width: 13px; height: 13px;"></i>
                                    <span>Añadir Fila</span>
                                </button>
                            </div>
                        </div>

                        <div id="ai-products-status" style="display: none; margin-bottom: 1rem; padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.8rem;"></div>

                        <div id="products-container" style="display: flex; flex-direction: column; gap: 0.85rem;">
                            <!-- Renderizado dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: VEREDICTO EDITORIAL & ANÁLISIS TÉCNICO -->
                <div class="editor-card" id="card-verdict">
                    <div class="editor-card-header">
                        <div>
                            <div class="editor-card-title">
                                <div class="editor-card-icon" style="color: var(--amber-primary);">
                                    <i data-lucide="file-text" style="width: 17px; height: 17px;"></i>
                                </div>
                                <span>3. Veredicto Editorial & Análisis Técnico</span>
                            </div>
                            <div class="editor-card-desc">Análisis objetivo de la redacción, público objetivo y balance de pros y contras.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-verdict">¿A quién se recomienda? (Veredicto en 1 línea)</label>
                        <input type="text" id="field-verdict" name="verdict" class="form-control" value="{{ old('verdict') }}" placeholder="ej: La opción perfecta para programadores y tiendas WooCommerce con alto tráfico...">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-description">Resumen del Análisis Técnico</label>
                        <textarea id="field-description" name="description" rows="3" class="form-control" placeholder="Resumen del rendimiento, estabilidad del servidor, panel de control y propuesta de valor...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Dual Cards: Pros & Contras -->
                    <div class="pros-cons-grid">
                        <div class="pros-card">
                            <div style="display: flex; align-items: center; gap: 0.4rem; color: var(--emerald-primary); font-weight: 700; font-size: 0.82rem; margin-bottom: 0.65rem;">
                                <i data-lucide="check-circle" style="width: 15px; height: 15px;"></i>
                                <span>Puntos Fuertes (Pros)</span>
                            </div>
                            <textarea id="field-pros" name="pros" rows="4" class="form-control" placeholder="• Discos NVMe rápidos&#10;• Soporte en español 24/7&#10;• Copias de seguridad automáticas" style="font-size: 0.82rem; background: rgba(0,0,0,0.25);">{{ old('pros') }}</textarea>
                        </div>

                        <div class="cons-card">
                            <div style="display: flex; align-items: center; gap: 0.4rem; color: var(--rose-primary); font-weight: 700; font-size: 0.82rem; margin-bottom: 0.65rem;">
                                <i data-lucide="alert-circle" style="width: 15px; height: 15px;"></i>
                                <span>Puntos Débiles (Contras)</span>
                            </div>
                            <textarea id="field-cons" name="cons" rows="4" class="form-control" placeholder="• Renovación a precio regular más elevado&#10;• Dominio gratis solo el primer año" style="font-size: 0.82rem; background: rgba(0,0,0,0.25);">{{ old('cons') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: OPTIMIZACIÓN SEO -->
                <div class="editor-card" id="card-seo">
                    <div class="editor-card-header">
                        <div>
                            <div class="editor-card-title">
                                <div class="editor-card-icon" style="color: var(--emerald-primary);">
                                    <i data-lucide="search" style="width: 17px; height: 17px;"></i>
                                </div>
                                <span>4. Optimización SEO & Snippet de Google</span>
                            </div>
                            <div class="editor-card-desc">Personaliza los títulos y descripciones indexadas por los motores de búsqueda.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-meta-title">Meta Título (Etiqueta Title)</label>
                        <input type="text" id="field-meta-title" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="ej: Opiniones y Análisis de Hostinger: ¿Vale la pena en 2026? — DebateHosting" oninput="updateGooglePreview()">
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" for="field-meta-description">Meta Descripción (Snippet)</label>
                        <textarea id="field-meta-description" name="meta_description" rows="2" class="form-control" placeholder="ej: Auditoría técnica independiente de Hostinger. Analizamos latencia TTFB, precios desde $2.49/mes, pros y contras sin patrocinios." oninput="updateGooglePreview()">{{ old('meta_description') }}</textarea>
                    </div>

                    <!-- Previsualización en Google -->
                    <div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                            VISTA PREVIA EN RESULTADOS DE BÚSQUEDA
                        </div>
                        <div class="google-serp-preview">
                            <div class="google-serp-url">
                                <span>https://debatehosting.com › proveedores › <span id="serp-slug-preview">nombre</span></span>
                            </div>
                            <div class="google-serp-title" id="serp-title-preview">
                                Opiniones y Análisis de Proveedor — DebateHosting
                            </div>
                            <div class="google-serp-desc" id="serp-desc-preview">
                                Análisis técnico y comparativa de características, precios y pruebas de rendimiento en tiempo real.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- COLUMNA LATERAL STICKY (30%) -->
            <div class="provider-sidebar-column">
                <div class="sticky-sidebar-content">
                    
                    <!-- WIDGET: ESTADO DE PUBLICACIÓN -->
                    <div class="editor-card" id="card-status" style="margin-bottom: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <div style="font-weight: 700; color: #FFFFFF; font-size: 0.92rem;">Visibilidad Pública</div>
                                <div style="font-size: 0.76rem; color: var(--text-dim);" id="status-label-text">Visible en el portal</div>
                            </div>
                            <label class="toggle-label-wrap" style="margin: 0;">
                                <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }} onchange="updateVisibilityStatus(this)">
                            </label>
                        </div>
                    </div>

                    <!-- WIDGET: LOGOTIPO OFICIAL -->
                    <div class="editor-card" id="card-logo" style="margin-bottom: 0;">
                        <div class="editor-card-header" style="margin-bottom: 1rem; padding-bottom: 0.75rem;">
                            <div class="editor-card-title" style="font-size: 0.95rem;">
                                <i data-lucide="image" style="width: 15px; height: 15px; color: var(--emerald-primary);"></i>
                                <span>Logotipo Oficial</span>
                            </div>
                        </div>

                        <div class="logo-dropzone" onclick="document.getElementById('field-logo').click()">
                            <div class="logo-preview-box" id="logo-preview-container">
                                <i data-lucide="image" style="width: 24px; height: 24px; color: #94A3B8;" id="logo-placeholder-icon"></i>
                                <img id="logo-preview-img" src="" alt="Vista previa" style="display: none;">
                            </div>
                            <div style="font-size: 0.8rem; font-weight: 600; color: #FFFFFF;">Haz clic para subir imagen</div>
                            <div style="font-size: 0.72rem; color: var(--text-dim);">SVG, PNG, WebP o JPG (Máx. 5MB)</div>
                            <input type="file" id="field-logo" name="logo" accept="image/*" style="display: none;" onchange="previewLogoFile(this)">
                        </div>

                        <!-- Opción URL alternativa -->
                        <div style="margin-top: 0.85rem;">
                            <label class="form-label" style="font-size: 0.72rem; margin-bottom: 0.25rem;">O enlace URL externo del logo</label>
                            <input type="text" id="field-logo-url" name="logo_url" class="form-control" value="{{ old('logo_url') }}" placeholder="https://dominio.com/logo.png" style="font-size: 0.8rem; font-family: var(--font-mono);" oninput="previewLogoUrl(this.value)">
                        </div>
                    </div>

                    <!-- WIDGET: TELEMETRÍA DE LA BALANZA (CON CÁLCULO AUTOMÁTICO INTELIGENTE) -->
                    <div class="editor-card" id="card-telemetry" style="margin-bottom: 0;">
                        <div class="editor-card-header" style="margin-bottom: 1rem; padding-bottom: 0.75rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                <div>
                                    <div class="editor-card-title" style="font-size: 0.95rem;">
                                        <i data-lucide="scale" style="width: 15px; height: 15px; color: var(--amber-primary);"></i>
                                        <span>Notas de La Balanza</span>
                                    </div>
                                    <div style="font-size: 0.74rem; color: var(--text-dim);">Escala ponderada de 0.0 a 10.0</div>
                                </div>
                                <button type="button" onclick="autoCalculateTelemetryScores(true)" class="btn-secondary" style="font-size: 0.72rem; padding: 0.25rem 0.6rem; color: var(--emerald-primary); border-color: rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.08); display: inline-flex; align-items: center; gap: 0.3rem;" title="Recalcular métricas automáticamente según precios y características">
                                    <i data-lucide="sparkles" style="width: 11px; height: 11px;"></i>
                                    <span>Auto-Calcular</span>
                                </button>
                            </div>
                        </div>

                        <!-- Marcador Score Global Hero -->
                        <div class="telemetry-score-hero">
                            <div class="telemetry-hero-label">SCORE GLOBAL ESTIMADO</div>
                            <div class="telemetry-hero-value" id="live-overall-score">★ 8.5</div>
                            <div id="telemetry-mode-indicator" style="font-size: 0.72rem; color: var(--emerald-primary); font-family: var(--font-mono); font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                                <i data-lucide="zap" style="width: 11px; height: 11px;"></i>
                                <span>Cálculo Automático Activo</span>
                            </div>
                        </div>

                        <!-- Controles Numéricos -->
                        <div class="telemetry-slider-row">
                            <div class="telemetry-slider-header">
                                <span style="color: var(--text-muted);">Relación Calidad/Precio</span>
                                <span class="telemetry-slider-val" id="val-badge-precio">9.0</span>
                            </div>
                            <input type="number" step="0.1" min="0" max="10" id="field-score-precio" name="score_precio" class="form-control" value="{{ old('score_precio', '9.0') }}" required oninput="onManualScoreEdit()" style="font-family: var(--font-mono); font-size: 0.85rem;">
                        </div>

                        <div class="telemetry-slider-row">
                            <div class="telemetry-slider-header">
                                <span style="color: var(--text-muted);">Velocidad & TTFB</span>
                                <span class="telemetry-slider-val" id="val-badge-rendimiento">8.5</span>
                            </div>
                            <input type="number" step="0.1" min="0" max="10" id="field-score-rendimiento" name="score_rendimiento" class="form-control" value="{{ old('score_rendimiento', '8.5') }}" required oninput="onManualScoreEdit()" style="font-family: var(--font-mono); font-size: 0.85rem;">
                        </div>

                        <div class="telemetry-slider-row">
                            <div class="telemetry-slider-header">
                                <span style="color: var(--text-muted);">Soporte Técnico 24/7</span>
                                <span class="telemetry-slider-val" id="val-badge-soporte">8.0</span>
                            </div>
                            <input type="number" step="0.1" min="0" max="10" id="field-score-soporte" name="score_soporte" class="form-control" value="{{ old('score_soporte', '8.0') }}" required oninput="onManualScoreEdit()" style="font-family: var(--font-mono); font-size: 0.85rem;">
                        </div>

                        <div class="telemetry-slider-row">
                            <div class="telemetry-slider-header">
                                <span style="color: var(--text-muted);">Facilidad & Panel</span>
                                <span class="telemetry-slider-val" id="val-badge-facilidad">8.5</span>
                            </div>
                            <input type="number" step="0.1" min="0" max="10" id="field-score-facilidad" name="score_facilidad" class="form-control" value="{{ old('score_facilidad', '8.5') }}" required oninput="onManualScoreEdit()" style="font-family: var(--font-mono); font-size: 0.85rem;">
                        </div>

                        <div class="telemetry-slider-row" style="margin-bottom: 0;">
                            <div class="telemetry-slider-header">
                                <span style="color: var(--text-muted);">Uptime Garantizado (%)</span>
                                <span class="telemetry-slider-val" id="val-badge-uptime">99.98%</span>
                            </div>
                            <input type="number" step="0.01" min="90" max="100" id="field-uptime" name="uptime" class="form-control" value="{{ old('uptime', '99.98') }}" required oninput="onManualScoreEdit()" style="font-family: var(--font-mono); font-size: 0.85rem;">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- =========================================================================
             BARRA DE ACCIONES FLOTANTE DOCK (BOTTOM STICKY BAR)
             ========================================================================= -->
        <div class="sticky-action-dock">
            <div class="dock-status-info" style="display: flex; align-items: center; gap: 0.75rem;">
                <span class="pulse-live-dot" style="background-color: var(--emerald-primary);"></span>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Editor de Proveedor Activo</span>
                <span class="dock-shortcut-badge">Ctrl + S</span>
            </div>
            <div class="dock-actions-group" style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ route('admin.providers.index') }}" class="btn-secondary" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn-primary" style="font-size: 0.84rem; padding: 0.55rem 1.35rem;">
                    <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                    <span>Guardar Proveedor</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('admin-scripts')
<script>
    // =========================================================================
    // LÓGICA INTERACTIVA: IA, LOGOS, MULTI-PRODUCTO Y LA BALANZA AUTOMÁTICA
    // =========================================================================

    let isTelemetryAuto = true;

    // Auto-cálculo inteligente de La Balanza
    function autoCalculateTelemetryScores(force = false) {
        if (!isTelemetryAuto && !force) return;

        const pFrom = parseFloat(document.getElementById('field-price-from')?.value) || 2.99;
        const pBefore = parseFloat(document.getElementById('field-price-before')?.value) || 9.99;
        const name = (document.getElementById('field-name')?.value || '').toLowerCase();

        // 1. Puntuación de Precio (Inversamente proporcional al costo de entrada)
        let scorePrecio = 9.0;
        if (pFrom <= 1.99) scorePrecio = 9.8;
        else if (pFrom <= 2.99) scorePrecio = 9.4;
        else if (pFrom <= 4.99) scorePrecio = 8.9;
        else if (pFrom <= 8.99) scorePrecio = 8.4;
        else if (pFrom <= 14.99) scorePrecio = 7.8;
        else scorePrecio = 7.2;

        // Bonificación por gran descuento de bienvenida
        if (pBefore > pFrom) {
            const discountPct = ((pBefore - pFrom) / pBefore) * 100;
            if (discountPct >= 65) scorePrecio = Math.min(9.9, scorePrecio + 0.3);
        }

        // 2. Rendimiento
        let scoreRendimiento = 8.6;
        if (name.includes('kinsta') || name.includes('cloudways') || name.includes('hetzner') || name.includes('wp engine') || name.includes('siteground')) {
            scoreRendimiento = 9.4;
        } else if (name.includes('hostinger') || name.includes('webempresa') || name.includes('banahosting') || name.includes('alexhost')) {
            scoreRendimiento = 8.9;
        }

        // 3. Soporte
        let scoreSoporte = 8.4;
        if (name.includes('webempresa') || name.includes('siteground') || name.includes('kinsta')) {
            scoreSoporte = 9.3;
        }

        // 4. Facilidad de Uso
        let scoreFacilidad = 8.7;

        // 5. Uptime
        let uptime = 99.98;
        if (scoreRendimiento >= 9.2) uptime = 99.99;

        document.getElementById('field-score-precio').value = scorePrecio.toFixed(1);
        document.getElementById('field-score-rendimiento').value = scoreRendimiento.toFixed(1);
        document.getElementById('field-score-soporte').value = scoreSoporte.toFixed(1);
        document.getElementById('field-score-facilidad').value = scoreFacilidad.toFixed(1);
        document.getElementById('field-uptime').value = uptime.toFixed(2);

        isTelemetryAuto = true;
        const indicator = document.getElementById('telemetry-mode-indicator');
        if (indicator) {
            indicator.innerHTML = '<i data-lucide="zap" style="width: 11px; height: 11px;"></i><span>Cálculo Automático Activo</span>';
            indicator.style.color = 'var(--emerald-primary)';
            if (window.lucide) window.lucide.createIcons();
        }

        syncScoreLive();
    }

    function onManualScoreEdit() {
        isTelemetryAuto = false;
        const indicator = document.getElementById('telemetry-mode-indicator');
        if (indicator) {
            indicator.innerHTML = '<span>✏️ Ajuste Manual (Pulsa Auto-Calcular para resetear)</span>';
            indicator.style.color = 'var(--amber-primary)';
        }
        syncScoreLive();
    }

    // Sincronización cuando cambia el precio
    function onPriceChangeLive() {
        calculateDiscountPreview();
        if (isTelemetryAuto) {
            autoCalculateTelemetryScores();
        }
    }

    // Sincronización del enlace de afiliados principal con todos los productos secundarios
    function syncProductAffiliatePlaceholders() {
        const mainUrl = document.getElementById('field-affiliate-url')?.value.trim() || '';
        document.querySelectorAll('#products-container .product-item-card').forEach(card => {
            const input = card.querySelector('.product-affiliate-input');
            const indicator = card.querySelector('.product-affiliate-indicator');
            if (input) {
                if (!input.value.trim()) {
                    input.placeholder = mainUrl ? `Hereda: ${mainUrl}` : 'Vacío = hereda enlace principal';
                    if (indicator) {
                        indicator.innerText = mainUrl ? '✓ Heredando enlace principal' : '✓ Automático';
                        indicator.style.color = 'var(--emerald-primary)';
                    }
                } else {
                    if (indicator) {
                        indicator.innerText = 'Enlace personalizado';
                        indicator.style.color = 'var(--sky-primary)';
                    }
                }
            }
        });
    }

    function syncSingleProductAffiliate(input) {
        const card = input.closest('.product-item-card');
        const indicator = card?.querySelector('.product-affiliate-indicator');
        const mainUrl = document.getElementById('field-affiliate-url')?.value.trim() || '';
        if (indicator) {
            if (input.value.trim()) {
                indicator.innerText = 'Enlace personalizado';
                indicator.style.color = 'var(--sky-primary)';
            } else {
                indicator.innerText = mainUrl ? '✓ Heredando enlace principal' : '✓ Automático';
                indicator.style.color = 'var(--emerald-primary)';
            }
        }
    }

    // Generador con IA
    function generateWithAI() {
        const nameInput = document.getElementById('ai-provider-name');
        const focusInput = document.getElementById('ai-provider-focus');
        const btn = document.getElementById('btn-generate-ai');
        const btnText = document.getElementById('btn-ai-text');
        const statusBox = document.getElementById('ai-status');

        const name = nameInput.value.trim() || document.getElementById('field-name').value.trim();

        if (!name) {
            statusBox.style.display = 'block';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '⚠️ Escribe el nombre de la empresa de hosting antes de generar.';
            nameInput.focus();
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Analizando...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Investigando arquitectura de <strong>' + escapeHtml(name) + '</strong> y redactando ficha editorial...';

        fetch('{{ route("admin.ai.generate-provider") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: name,
                focus: focusInput.value.trim()
            })
        })
        .then(response => response.json())
        .then(res => {
            btn.disabled = false;
            btnText.innerText = 'Generar con IA';

            if (!res.success || !res.data) {
                statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
                statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
                statusBox.style.color = '#FECDD3';
                statusBox.innerHTML = '❌ ' + (res.message || 'Error al generar la información.');
                return;
            }

            const d = res.data;

            // Rellenar campos del formulario
            document.getElementById('field-name').value = d.name || name;
            document.getElementById('field-slug').value = d.slug || '';
            document.getElementById('field-plan').value = d.plan || '';
            document.getElementById('field-price-from').value = d.price_from || '';
            document.getElementById('field-price-before').value = d.price_before || '';
            document.getElementById('field-description').value = d.description || '';
            document.getElementById('field-pros').value = d.pros || '';
            document.getElementById('field-cons').value = d.cons || '';
            document.getElementById('field-verdict').value = d.verdict || '';
            document.getElementById('field-meta-title').value = d.meta_title || '';
            document.getElementById('field-meta-description').value = d.meta_description || '';

            // Si la IA entregó notas, se aplican; de lo contrario se auto-calculan
            if (d.score_precio) document.getElementById('field-score-precio').value = d.score_precio;
            if (d.score_rendimiento) document.getElementById('field-score-rendimiento').value = d.score_rendimiento;
            if (d.score_soporte) document.getElementById('field-score-soporte').value = d.score_soporte;
            if (d.score_facilidad) document.getElementById('field-score-facilidad').value = d.score_facilidad;
            if (d.uptime) document.getElementById('field-uptime').value = d.uptime;

            syncScoreLive();
            calculateDiscountPreview();
            updateGooglePreview();
            syncProductAffiliatePlaceholders();

            // Notificación de éxito
            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = '✔ ¡Ficha técnica completada con éxito para <strong>' + escapeHtml(d.name || name) + '</strong>! Revisa los campos y pulsa "Guardar Proveedor".';
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Generar con IA';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '❌ Ocurrió un error inesperado al conectar con el asistente IA.';
        });
    }

    // Sincronización en vivo del nombre
    document.getElementById('field-name').addEventListener('input', function(e) {
        const val = e.target.value.trim();
        const aiName = document.getElementById('ai-provider-name');
        if (!aiName.value) aiName.value = val;
        updateGooglePreview();
        if (isTelemetryAuto) autoCalculateTelemetryScores();
    });

    // Listener para el enlace principal de afiliado
    document.getElementById('field-affiliate-url').addEventListener('input', function() {
        syncProductAffiliatePlaceholders();
    });

    // Gestión de Categorías
    function toggleCategoryPill(btn, slug) {
        const chk = btn.querySelector('input[type="checkbox"]');
        chk.checked = !chk.checked;
        if (chk.checked) {
            btn.classList.add('is-selected');
            btn.querySelector('.pill-prefix').innerText = '✓';
        } else {
            btn.classList.remove('is-selected');
            btn.querySelector('.pill-prefix').innerText = '+';
        }
        if (isTelemetryAuto) autoCalculateTelemetryScores();
    }

    function addCustomCategory() {
        const input = document.getElementById('custom-category-input');
        const val = input.value.trim();
        if (!val) return;

        const slug = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        const container = document.getElementById('categories-container');

        const existing = container.querySelector(`[data-slug="${slug}"]`);
        if (existing) {
            const chk = existing.querySelector('input[type="checkbox"]');
            if (!chk.checked) toggleCategoryPill(existing, slug);
            input.value = '';
            return;
        }

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'category-toggle-pill is-selected';
        btn.setAttribute('data-slug', slug);
        btn.onclick = function() { toggleCategoryPill(this, slug); };
        btn.innerHTML = `
            <span class="pill-prefix">✓</span>
            <span class="pill-name">${escapeHtml(val)}</span>
            <input type="checkbox" name="categories[]" value="${slug}" checked style="display: none;">
        `;

        container.appendChild(btn);
        input.value = '';
    }

    // Selección de Badges
    function selectBadgePill(btn, label, color) {
        document.getElementById('field-badge').value = label;
        document.getElementById('field-badge-color').value = color;

        document.querySelectorAll('#badges-container .badge-select-pill').forEach(b => {
            b.classList.remove('is-selected');
            const prefix = b.querySelector('.pill-prefix');
            if (prefix) prefix.innerText = '';
        });

        btn.classList.add('is-selected');
        const prefix = btn.querySelector('.pill-prefix');
        if (prefix) prefix.innerText = '✓ ';
    }

    // Previsualización y Cálculo de Descuento
    function calculateDiscountPreview() {
        const pFrom = parseFloat(document.getElementById('field-price-from').value) || 0;
        const pBefore = parseFloat(document.getElementById('field-price-before').value) || 0;
        const badge = document.getElementById('discount-calc-badge');
        const text = document.getElementById('discount-calc-text');

        if (pBefore > pFrom && pFrom > 0) {
            const pct = Math.round(((pBefore - pFrom) / pBefore) * 100);
            text.innerText = `-${pct}% OFF`;
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    }

    // Previsualización de Logotipo
    function previewLogoFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('logo-preview-img');
                const placeholder = document.getElementById('logo-placeholder-icon');
                img.src = e.target.result;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewLogoUrl(url) {
        url = url.trim();
        const img = document.getElementById('logo-preview-img');
        const placeholder = document.getElementById('logo-placeholder-icon');
        if (url) {
            img.src = url;
            img.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
            img.onerror = function() {
                img.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            };
        } else {
            img.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
        }
    }

    // Sincronización en Vivo del Score Global de La Balanza
    function syncScoreLive() {
        const p = parseFloat(document.getElementById('field-score-precio').value) || 0;
        const r = parseFloat(document.getElementById('field-score-rendimiento').value) || 0;
        const s = parseFloat(document.getElementById('field-score-soporte').value) || 0;
        const f = parseFloat(document.getElementById('field-score-facilidad').value) || 0;
        const u = parseFloat(document.getElementById('field-uptime').value) || 99.98;

        document.getElementById('val-badge-precio').innerText = p.toFixed(1);
        document.getElementById('val-badge-rendimiento').innerText = r.toFixed(1);
        document.getElementById('val-badge-soporte').innerText = s.toFixed(1);
        document.getElementById('val-badge-facilidad').innerText = f.toFixed(1);
        document.getElementById('val-badge-uptime').innerText = u.toFixed(2) + '%';

        // Ponderación oficial de La Balanza
        const overall = (p * 0.35 + r * 0.35 + s * 0.15 + f * 0.15).toFixed(1);
        document.getElementById('live-overall-score').innerText = '★ ' + overall;
    }

    function updateVisibilityStatus(chk) {
        const lbl = document.getElementById('status-label-text');
        if (chk.checked) {
            lbl.innerText = 'Visible en el portal';
            lbl.style.color = 'var(--emerald-primary)';
        } else {
            lbl.innerText = 'Borrador pausado';
            lbl.style.color = 'var(--text-dim)';
        }
    }

    // Google SERP Snippet Preview
    function updateGooglePreview() {
        const name = document.getElementById('field-name').value.trim();
        const slug = document.getElementById('field-slug').value.trim() || (name ? name.toLowerCase().replace(/[^a-z0-9]+/g, '-') : 'nombre');
        const title = document.getElementById('field-meta-title').value.trim() || (name ? `Opiniones y Análisis de ${name}: ¿Vale la pena en 2026? — DebateHosting` : 'Opiniones y Análisis de Proveedor — DebateHosting');
        const desc = document.getElementById('field-meta-description').value.trim() || (name ? `Auditoría técnica independiente de ${name}. Analizamos latencia TTFB, precios desde $2.49/mes, pros y contras sin patrocinios.` : 'Análisis técnico y comparativa de características, precios y pruebas de rendimiento en tiempo real.');

        document.getElementById('serp-slug-preview').innerText = slug;
        document.getElementById('serp-title-preview').innerText = title;
        document.getElementById('serp-desc-preview').innerText = desc;
    }

    // =========================================================================
    // CATÁLOGO MULTI-PRODUCTO
    // =========================================================================
    @php
        $catsData = $categories->map(function ($c) {
            return ['slug' => $c->slug, 'name' => $c->name];
        })->values();
    @endphp

    window.availableCategories = {!! json_encode($catsData) !!};
    let productCount = 0;

    function renderProductRow(prod = {}, index = null) {
        const container = document.getElementById('products-container');
        if (!container) return;
        const idx = index !== null ? index : productCount++;

        const categorySlug = prod.category_slug || (window.availableCategories[0] ? window.availableCategories[0].slug : '');
        const planName = prod.plan_name || '';
        const priceFrom = prod.price_from !== undefined ? prod.price_from : '';
        const priceBefore = prod.price_before !== undefined ? prod.price_before : '';
        const period = prod.period || 'mes';
        const specs = prod.specs || '';
        const affiliateUrl = prod.affiliate_url || '';
        const isFeatured = prod.is_featured ? true : false;
        const mainAffiliate = document.getElementById('field-affiliate-url')?.value.trim() || '';

        let categoryOptions = '<option value="">(Sin categoría específica)</option>';
        window.availableCategories.forEach(cat => {
            const selected = (cat.slug.toLowerCase() === (categorySlug || '').toLowerCase()) ? 'selected' : '';
            categoryOptions += `<option value="${cat.slug}" ${selected}>${cat.name}</option>`;
        });

        const card = document.createElement('div');
        card.className = 'product-item-card';
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; padding-bottom: 0.4rem; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span style="font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; color: var(--emerald-primary); display: flex; align-items: center; gap: 0.35rem;">
                    <i data-lucide="box" style="width: 13px; height: 13px;"></i>
                    <span>PLAN #<span class="product-index-num">${container.children.length + 1}</span></span>
                </span>
                <button type="button" onclick="removeProductRow(this)" style="background: none; border: none; color: var(--rose-primary); cursor: pointer; display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem;">
                    <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                    <span>Eliminar</span>
                </button>
            </div>

            <div class="product-card-grid-top">
                <div class="form-group product-grid-col-category" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Categoría</label>
                    <select name="products[${idx}][category_slug]" class="form-control" style="font-size: 0.8rem; height: 36px;">
                        ${categoryOptions}
                    </select>
                </div>
                <div class="form-group product-grid-col-plan" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Nombre del Plan *</label>
                    <input type="text" name="products[${idx}][plan_name]" value="${escapeHtml(planName)}" placeholder="ej: Shared Lite NVMe, VPS KVM 1..." class="form-control" required style="font-size: 0.8rem; height: 36px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Precio Oferta ($) *</label>
                    <input type="number" step="0.01" name="products[${idx}][price_from]" value="${priceFrom}" class="form-control" required style="font-family: var(--font-mono); font-size: 0.8rem; height: 36px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Precio Regular ($)</label>
                    <input type="number" step="0.01" name="products[${idx}][price_before]" value="${priceBefore}" class="form-control" style="font-family: var(--font-mono); font-size: 0.8rem; height: 36px;">
                </div>
                <div class="form-group product-grid-col-period" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Período</label>
                    <select name="products[${idx}][period]" class="form-control" style="font-size: 0.8rem; height: 36px;">
                        <option value="mes" ${period === 'mes' ? 'selected' : ''}>/mes</option>
                        <option value="año" ${period === 'año' ? 'selected' : ''}>/año</option>
                    </select>
                </div>
            </div>

            <div class="product-card-grid-bottom">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.72rem;">Especificaciones (separadas por comas)</label>
                    <input type="text" name="products[${idx}][specs]" value="${escapeHtml(specs)}" placeholder="ej: 1 vCPU, 2GB RAM, 20GB NVMe, cPanel, LiteSpeed" class="form-control" style="font-size: 0.8rem; height: 36px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem; flex-wrap: wrap; gap: 0.25rem;">
                        <label class="form-label" style="font-size: 0.72rem; margin-bottom: 0;">Enlace Específico (Opcional)</label>
                        <span class="product-affiliate-indicator" style="font-size: 0.68rem; color: ${affiliateUrl ? 'var(--sky-primary)' : 'var(--emerald-primary)'}; font-family: var(--font-mono);">
                            ${affiliateUrl ? 'Enlace personalizado' : (mainAffiliate ? '✓ Hereda enlace principal' : '✓ Automático')}
                        </span>
                    </div>
                    <input type="text" name="products[${idx}][affiliate_url]" value="${escapeHtml(affiliateUrl)}" class="form-control product-affiliate-input" placeholder="${mainAffiliate ? 'Hereda: ' + escapeHtml(mainAffiliate) : 'Vacío = hereda enlace principal'}" oninput="syncSingleProductAffiliate(this)" style="font-size: 0.8rem; height: 36px; background: rgba(0,0,0,0.3);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="product-featured-checkbox-label">
                        <input type="checkbox" name="products[${idx}][is_featured]" value="1" ${isFeatured ? 'checked' : ''} style="accent-color: var(--emerald-primary);">
                        <span>Destacado</span>
                    </label>
                </div>
            </div>
        `;

        container.appendChild(card);
        if (window.lucide) window.lucide.createIcons();
    }

    function addProductRow(data = {}) {
        renderProductRow(data);
        renumberProducts();
        syncProductAffiliatePlaceholders();
    }

    function removeProductRow(btn) {
        const card = btn.closest('.product-item-card');
        if (card) {
            card.remove();
            renumberProducts();
        }
    }

    function renumberProducts() {
        const container = document.getElementById('products-container');
        if (!container) return;
        const badges = container.querySelectorAll('.product-index-num');
        badges.forEach((b, i) => {
            b.innerText = i + 1;
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function autofillProductsWithAI() {
        const name = document.getElementById('field-name').value.trim() || document.getElementById('ai-provider-name').value.trim();
        const statusBox = document.getElementById('ai-products-status');
        const btn = document.getElementById('btn-ai-products');
        const btnText = document.getElementById('btn-ai-products-text');

        if (!name) {
            statusBox.style.display = 'block';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '⚠️ Escribe el nombre del proveedor para investigar sus productos con IA.';
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Detectando...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Identificando líneas de productos de <strong>' + escapeHtml(name) + '</strong>...';

        fetch('{{ route("admin.ai.generate-products") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: name })
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btnText.innerText = 'Detectar con IA';

            if (!res.success || !res.data || !Array.isArray(res.data) || res.data.length === 0) {
                statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
                statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
                statusBox.style.color = '#FECDD3';
                statusBox.innerHTML = '❌ ' + (res.message || 'No se pudieron generar los productos con IA.');
                return;
            }

            const container = document.getElementById('products-container');
            container.innerHTML = '';
            productCount = 0;

            res.data.forEach(p => {
                const specsStr = Array.isArray(p.specs) ? p.specs.join(', ') : (p.specs || '');
                renderProductRow({
                    category_slug: p.category_slug,
                    plan_name: p.plan_name,
                    price_from: p.price_from,
                    price_before: p.price_before,
                    period: p.period || 'mes',
                    specs: specsStr,
                    affiliate_url: p.affiliate_url || '',
                    is_featured: p.is_featured || false,
                });
            });

            renumberProducts();
            syncProductAffiliatePlaceholders();

            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = `✔ ¡Se han agregado ${res.data.length} planes detectados para <strong>${escapeHtml(name)}</strong>!`;
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Detectar con IA';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '❌ Error al conectar con el asistente de IA.';
        });
    }

    // Atajo de teclado: Ctrl + S o Cmd + S para guardar inmediatamente
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('provider-form').requestSubmit();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        autoCalculateTelemetryScores();
        calculateDiscountPreview();
        updateGooglePreview();

        // En creación, si hay datos base o default, añadimos una fila inicial
        const basePlan = document.getElementById('field-plan')?.value || '';
        const basePrice = document.getElementById('field-price-from')?.value || '';
        const baseBefore = document.getElementById('field-price-before')?.value || '';
        if (basePlan || basePrice) {
            renderProductRow({
                plan_name: basePlan,
                price_from: basePrice,
                price_before: baseBefore,
                is_featured: true,
            });
        }
        renumberProducts();
        syncProductAffiliatePlaceholders();

        const urlInput = document.getElementById('field-logo-url');
        if (urlInput && urlInput.value) {
            previewLogoUrl(urlInput.value);
        }
    });
</script>
@endpush
@endsection
