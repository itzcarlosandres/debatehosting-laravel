@extends('layouts.admin')

@section('title', 'Identidad de Marca, Logo & SEO')

@section('content')
<!-- Encabezado de Página -->
<div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem;">
    <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; color: var(--emerald-primary); flex-shrink: 0;">
        <i data-lucide="globe" style="width: 24px; height: 24px;"></i>
    </div>
    <div>
        <h1 style="font-size: 1.55rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.02em; margin-bottom: 0.25rem;">
            Identidad de Marca, Logo & SEO
        </h1>
        <p style="font-size: 0.85rem; color: #94A3B8; margin: 0;">
            Personaliza la marca, formato del logo (Texto + Ícono o Imagen), posicionamiento en buscadores y redes sociales.
        </p>
    </div>
</div>

<!-- Barra de Pestañas de Navegación (Slider Horizontal Sin Scrollbar Visible) -->
<div class="settings-tabs-nav">
    <button type="button" class="tab-btn {{ $activeTab === 'brand' ? 'active' : '' }}" onclick="switchSettingsTab('brand')">
        <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
        <span>Identidad de Marca & Logo</span>
    </button>
    <button type="button" class="tab-btn {{ $activeTab === 'seo' ? 'active' : '' }}" onclick="switchSettingsTab('seo')">
        <i data-lucide="search" style="width: 15px; height: 15px;"></i>
        <span>SEO & Indexación</span>
    </button>
    <button type="button" class="tab-btn {{ $activeTab === 'ethics' ? 'active' : '' }}" onclick="switchSettingsTab('ethics')">
        <i data-lucide="shield-check" style="width: 15px; height: 15px;"></i>
        <span>Afiliación & Ética</span>
    </button>
    <button type="button" class="tab-btn {{ $activeTab === 'account' ? 'active' : '' }}" onclick="switchSettingsTab('account')">
        <i data-lucide="user" style="width: 15px; height: 15px;"></i>
        <span>Cuenta & Seguridad</span>
    </button>
    <button type="button" class="tab-btn {{ $activeTab === 'engine' ? 'active' : '' }}" onclick="switchSettingsTab('engine')">
        <i data-lucide="cpu" style="width: 15px; height: 15px;"></i>
        <span>Motor & Base de Datos</span>
    </button>
    <button type="button" class="tab-btn {{ $activeTab === 'ai' ? 'active' : '' }}" onclick="switchSettingsTab('ai')">
        <i data-lucide="bot" style="width: 15px; height: 15px;"></i>
        <span>Inteligencia Artificial (Gemini)</span>
    </button>
</div>


<!-- =========================================================================
     PESTAÑA 1: IDENTIDAD DE MARCA & LOGO
     ========================================================================= -->
<div id="tab-content-brand" class="tab-content" style="{{ $activeTab === 'brand' ? 'display: block;' : 'display: none;' }}">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="form-settings-brand">
        @csrf
        <input type="hidden" name="current_tab" value="brand">

        <!-- Card: Parámetros Editoriales -->
        <div class="form-panel" style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
                <div>
                    <h2 style="font-size: 1.2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.25rem;">
                        Parámetros Editoriales
                    </h2>
                    <p style="font-size: 0.8rem; color: #64748B; margin: 0;">
                        Identidad pública de Debatehosting, denominación editorial y formato de precios.
                    </p>
                </div>
                <div>
                    <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 20px; font-size: 0.75rem; padding: 0.25rem 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span>
                        <span>Activo</span>
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nombre del Medio / Publicación</label>
                    <input type="text" name="siteName" id="input-site-name" class="form-control" value="{{ old('siteName', $data['siteName']) }}" oninput="updateLogoLivePreview()">
                    <span class="form-helper">Utilizado en títulos, marcas de agua y esquemas OpenGraph.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">URL Canónica Oficial</label>
                    <input type="text" name="siteUrl" class="form-control" value="{{ old('siteUrl', $data['siteUrl']) }}">
                    <span class="form-helper">Dominio raíz para generación de sitemap y canonical links.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label">Eslogan / Subtítulo Institucional</label>
                <input type="text" name="siteTagline" class="form-control" value="{{ old('siteTagline', $data['siteTagline']) }}">
                <span class="form-helper">Aparece en el encabezado principal y pie de imprenta.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Email de Contacto de Redacción</label>
                    <input type="email" name="contactEmail" class="form-control" value="{{ old('contactEmail', $data['contactEmail']) }}">
                    <span class="form-helper">Dirección para comunicaciones institucionales y prensa.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Símbolo de Moneda Oficial</label>
                    <select name="currency" class="form-control">
                        <option value="$" {{ $data['currency'] === '$' ? 'selected' : '' }}>$ (Dólar estadounidense - USD)</option>
                        <option value="€" {{ $data['currency'] === '€' ? 'selected' : '' }}>€ (Euro - EUR)</option>
                        <option value="£" {{ $data['currency'] === '£' ? 'selected' : '' }}>£ (Libra esterlina - GBP)</option>
                        <option value="MXN $" {{ $data['currency'] === 'MXN $' ? 'selected' : '' }}>MXN $ (Peso mexicano - MXN)</option>
                    </select>
                    <span class="form-helper">Moneda por defecto mostrada en comparadores y podio.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Compromiso de Independencia Editorial</label>
                <textarea name="independenceCommitment" rows="2" class="form-control">{{ old('independenceCommitment', $data['independenceCommitment']) }}</textarea>
            </div>
        </div>

        <!-- ✦ 1. FORMATO Y PRESENTACIÓN DEL LOGO -->
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--emerald-primary); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.06em; margin-bottom: 1rem; text-transform: uppercase;">
                <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                <span>1. Formato y Presentación del Logo</span>
            </div>

            <!-- 3 Tarjetas de Selección de Formato -->
            <input type="hidden" name="logoType" id="input-logo-type" value="{{ $data['logoType'] }}">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <!-- Opción 1: Texto + Ícono -->
                <div class="logo-format-card {{ $data['logoType'] === 'icon_text' ? 'is-selected' : '' }}" onclick="selectLogoFormat('icon_text')">
                    <div class="format-card-check">
                        <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                        <div style="width: 28px; height: 28px; border-radius: 4px; background: rgba(16, 185, 129, 0.15); color: #10B981; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="server" style="width: 15px; height: 15px;"></i>
                        </div>
                        <span style="font-size: 0.92rem; font-weight: 700; color: #FFFFFF;">Texto + Ícono</span>
                    </div>
                    <p style="font-size: 0.76rem; color: #94A3B8; margin-bottom: 0.75rem; line-height: 1.4;">
                        Ícono dinámico + nombre con sufijo resaltado. Moderno y personalizable.
                    </p>
                    <span style="background: rgba(16, 185, 129, 0.12); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 4px; font-size: 0.68rem; font-weight: 800; padding: 0.15rem 0.5rem;">
                        Recomendado
                    </span>
                </div>

                <!-- Opción 2: Logo en Imagen -->
                <div class="logo-format-card {{ $data['logoType'] === 'image' ? 'is-selected' : '' }}" onclick="selectLogoFormat('image')">
                    <div class="format-card-check">
                        <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                        <div style="width: 28px; height: 28px; border-radius: 4px; background: rgba(255, 255, 255, 0.05); color: #CBD5E1; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="image" style="width: 15px; height: 15px;"></i>
                        </div>
                        <span style="font-size: 0.92rem; font-weight: 700; color: #FFFFFF;">Logo en Imagen</span>
                    </div>
                    <p style="font-size: 0.76rem; color: #94A3B8; margin: 0; line-height: 1.4;">
                        Carga tu logotipo vectorizado en formato SVG, PNG o WebP desde una URL o archivo.
                    </p>
                </div>

                <!-- Opción 3: Solo Texto -->
                <div class="logo-format-card {{ $data['logoType'] === 'text_only' ? 'is-selected' : '' }}" onclick="selectLogoFormat('text_only')">
                    <div class="format-card-check">
                        <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                        <div style="width: 28px; height: 28px; border-radius: 4px; background: rgba(255, 255, 255, 0.05); color: #CBD5E1; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                            T
                        </div>
                        <span style="font-size: 0.92rem; font-weight: 700; color: #FFFFFF;">Solo Texto</span>
                    </div>
                    <p style="font-size: 0.76rem; color: #94A3B8; margin: 0; line-height: 1.4;">
                        Muestra únicamente el texto de la marca con tipografía limpia y minimalista.
                    </p>
                </div>
            </div>

            <!-- Panel Específico para Logo en Imagen -->
            <div id="panel-logo-image" class="form-panel" style="{{ $data['logoType'] === 'image' ? 'display: block;' : 'display: none;' }} margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">URL del Logotipo (Imagen)</label>
                    <input type="text" name="logoUrl" id="input-logo-url" class="form-control" value="{{ old('logoUrl', $data['logoUrl']) }}" placeholder="/logo.png o https://...">
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <input type="file" name="logo_file" id="input-logo-file" accept="image/*" style="display: none;" onchange="handleFileUpload(this, 'input-logo-url')">
                    <button type="button" onclick="document.getElementById('input-logo-file').click()" class="btn-secondary">
                        <i data-lucide="upload" style="width: 14px; height: 14px;"></i>
                        <span>Subir Archivo de Logo</span>
                    </button>
                    <span style="font-size: 0.75rem; color: #64748B;">Formatos admitidos: SVG, PNG, WebP</span>
                </div>
            </div>

            <!-- Configuración para "Texto + Ícono" -->
            <div id="panel-logo-icon-text" style="{{ $data['logoType'] === 'icon_text' ? 'display: block;' : 'display: none;' }}">
                <!-- Selector de Ícono -->
                <div class="form-panel" style="margin-bottom: 1.5rem; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF;">Seleccionar Ícono del Logo</span>
                        <span style="font-size: 0.76rem; color: #64748B;">
                            Ícono actual: <strong id="current-icon-name" style="color: var(--emerald-primary); text-transform: capitalize;">{{ $data['logoIcon'] }}</strong>
                        </span>
                    </div>

                    <input type="hidden" name="logoIcon" id="input-logo-icon" value="{{ $data['logoIcon'] }}">
                    
                    @php
                        $iconCatalog = [
                            'rocket' => 'Cohete',
                            'flame' => 'Fuego',
                            'sparkles' => 'Brillo',
                            'zap' => 'Rayo',
                            'globe' => 'Mundo',
                            'compass' => 'Brújula',
                            'layers' => 'Capas',
                            'bot' => 'Bot IA',
                            'code' => 'Código',
                            'terminal' => 'Consola',
                            'cpu' => 'Chip',
                            'star' => 'Estrella',
                            'shield' => 'Escudo',
                            'crosshair' => 'Diana',
                            'gem' => 'Gema',
                            'scale' => 'Balanza',
                            'server' => 'Servidor',
                        ];
                    @endphp

                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($iconCatalog as $iconKey => $iconLabel)
                            <button type="button" 
                                    class="logo-icon-picker-btn {{ $data['logoIcon'] === $iconKey ? 'is-selected' : '' }}" 
                                    data-icon="{{ $iconKey }}" 
                                    data-label="{{ $iconLabel }}"
                                    onclick="selectLogoIcon('{{ $iconKey }}', '{{ $iconLabel }}')">
                                <i data-lucide="{{ $iconKey }}" style="width: 17px; height: 17px;"></i>
                                <span style="font-size: 0.68rem; margin-top: 0.25rem; font-weight: 600;">{{ $iconLabel }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Textos del Logo y Color -->
                <div class="form-panel" style="margin-bottom: 1.5rem; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: 2fr 2fr 1.5fr; gap: 1.25rem; align-items: flex-end; margin-bottom: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Texto Base (Prefijo)</label>
                            <input type="text" name="logoTextPrefix" id="input-logo-prefix" class="form-control" value="{{ old('logoTextPrefix', $data['logoTextPrefix']) }}" oninput="updateLogoLivePreview()">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Texto Destacado (Color Primario)</label>
                            <input type="text" name="logoTextHighlight" id="input-logo-highlight" class="form-control" value="{{ old('logoTextHighlight', $data['logoTextHighlight']) }}" oninput="updateLogoLivePreview()">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Color del Contenedor del Ícono</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="color" id="input-color-picker" value="{{ $data['logoColor'] }}" style="width: 38px; height: 38px; padding: 2px; border-radius: 4px; border: 1px solid var(--border-subtle); background: var(--bg-input); cursor: pointer;" oninput="syncColorInput(this.value)">
                                <input type="text" name="logoColor" id="input-logo-color" class="form-control" value="{{ old('logoColor', $data['logoColor']) }}" style="font-family: var(--font-mono); text-transform: uppercase;" oninput="syncColorPicker(this.value)">
                            </div>
                        </div>
                    </div>

                    <!-- Paleta de colores rápidos -->
                    <div>
                        <div style="font-size: 0.75rem; color: #64748B; margin-bottom: 0.5rem; font-weight: 600;">
                            Paleta de colores rápidos para el ícono:
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.45rem;">
                            @php
                                $quickColors = [
                                    ['name' => 'Verde Editorial', 'color' => '#0E6B41'],
                                    ['name' => 'Verde Esmeralda', 'color' => '#10B981'],
                                    ['name' => 'Verde Bosque', 'color' => '#065F46'],
                                    ['name' => 'Tinta Negra', 'color' => '#111827'],
                                    ['name' => 'Rojo Imprenta', 'color' => '#EF4444'],
                                    ['name' => 'Naranja Alerta', 'color' => '#F97316'],
                                    ['name' => 'Oro Balanza', 'color' => '#F59E0B'],
                                    ['name' => 'Azul Servidor', 'color' => '#0EA5E9'],
                                    ['name' => 'Índigo Cloud', 'color' => '#6366F1'],
                                    ['name' => 'Púrpura IA', 'color' => '#8B5CF6'],
                                ];
                            @endphp
                            @foreach($quickColors as $qc)
                                <button type="button" class="quick-color-pill" onclick="applyQuickColor('{{ $qc['color'] }}')">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $qc['color'] }}; display: inline-block;"></span>
                                    <span>{{ $qc['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Vista Previa Dual del Logo en la Barra de Navegación -->
                <div style="margin-bottom: 2rem;">
                    <div style="font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: #64748B; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i data-lucide="layout" style="width: 14px; height: 14px;"></i>
                        <span>Vista Previa del Logo en la Barra de Navegación</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <!-- MODO CLARO -->
                        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 6px; padding: 1.25rem 1.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div style="font-size: 0.65rem; font-family: var(--font-mono); font-weight: 800; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.75rem;">
                                Modo Claro
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <div id="preview-box-light-icon" style="width: 32px; height: 32px; border-radius: 6px; background: {{ $data['logoColor'] }}; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i id="preview-icon-light" data-lucide="{{ $data['logoIcon'] }}" style="width: 17px; height: 17px;"></i>
                                </div>
                                <div style="font-size: 1.2rem; font-weight: 800; color: #0F172A; letter-spacing: -0.02em; display: flex; align-items: baseline;">
                                    <span id="preview-light-prefix">{{ $data['logoTextPrefix'] }}</span><span id="preview-light-highlight" style="color: {{ $data['logoColor'] }};">{{ $data['logoTextHighlight'] }}</span>
                                </div>
                                <span style="font-size: 0.62rem; font-weight: 800; font-family: var(--font-mono); background: #000000; color: #FFFFFF; padding: 0.15rem 0.45rem; border-radius: 3px; letter-spacing: 0.05em; margin-left: 0.35rem;">
                                    EDITORIAL
                                </span>
                            </div>
                        </div>

                        <!-- MODO OSCURO -->
                        <div style="background: #0B0F17; border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1.25rem 1.75rem;">
                            <div style="font-size: 0.65rem; font-family: var(--font-mono); font-weight: 800; color: #64748B; text-transform: uppercase; margin-bottom: 0.75rem;">
                                Modo Oscuro
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <div id="preview-box-dark-icon" style="width: 32px; height: 32px; border-radius: 6px; background: {{ $data['logoColor'] }}; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);">
                                    <i id="preview-icon-dark" data-lucide="{{ $data['logoIcon'] }}" style="width: 17px; height: 17px;"></i>
                                </div>
                                <div style="font-size: 1.2rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.02em; display: flex; align-items: baseline;">
                                    <span id="preview-dark-prefix">{{ $data['logoTextPrefix'] }}</span><span id="preview-dark-highlight" style="color: #10B981;">{{ $data['logoTextHighlight'] }}</span>
                                </div>
                                <span style="font-size: 0.62rem; font-weight: 800; font-family: var(--font-mono); background: #FFFFFF; color: #000000; padding: 0.15rem 0.45rem; border-radius: 3px; letter-spacing: 0.05em; margin-left: 0.35rem;">
                                    EDITORIAL
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assets Gráficos: Favicon & OG Image -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                <!-- Favicon -->
                <div class="form-panel" style="margin-bottom: 0; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF; display: flex; align-items: center; gap: 0.4rem;">
                            <i data-lucide="sparkles" style="width: 13px; height: 13px; color: var(--emerald-primary);"></i>
                            <span>Favicon del Sitio</span>
                        </span>
                        <span style="font-family: var(--font-mono); font-size: 0.68rem; color: #64748B;">ICO, PNG o SVG</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div id="favicon-preview-thumb" style="width: 42px; height: 42px; border-radius: 6px; background: rgba(16, 185, 129, 0.1); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-lucide="server" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                        </div>

                        <input type="file" name="favicon_file" id="input-favicon-file" accept=".ico,.png,.svg" style="display: none;" onchange="handleFileUpload(this, 'input-favicon-url', 'favicon-preview-thumb')">
                        <button type="button" onclick="document.getElementById('input-favicon-file').click()" class="btn-secondary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem;">
                            <i data-lucide="upload" style="width: 13px; height: 13px;"></i>
                            <span>Subir Favicon</span>
                        </button>
                        <button type="button" onclick="clearInput('input-favicon-url')" class="btn-secondary" style="padding: 0.4rem 0.6rem; color: #EF4444;" title="Limpiar">
                            <i data-lucide="x" style="width: 13px; height: 13px;"></i>
                        </button>
                    </div>

                    <input type="text" name="faviconUrl" id="input-favicon-url" class="form-control" value="{{ old('faviconUrl', $data['faviconUrl']) }}" style="font-family: var(--font-mono); font-size: 0.78rem;">
                </div>

                <!-- Imagen Compartir (OG) -->
                <div class="form-panel" style="margin-bottom: 0; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF; display: flex; align-items: center; gap: 0.4rem;">
                            <i data-lucide="share-2" style="width: 13px; height: 13px; color: var(--emerald-primary);"></i>
                            <span>Imagen Compartir (OG)</span>
                        </span>
                        <span style="font-family: var(--font-mono); font-size: 0.68rem; color: #64748B;">1200 × 630 px</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div id="og-preview-thumb" style="width: 54px; height: 42px; border-radius: 6px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-lucide="image" style="width: 18px; height: 18px; color: #94A3B8;"></i>
                        </div>

                        <input type="file" name="og_image_file" id="input-og-file" accept="image/*" style="display: none;" onchange="handleFileUpload(this, 'input-og-url', 'og-preview-thumb')">
                        <button type="button" onclick="document.getElementById('input-og-file').click()" class="btn-secondary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem;">
                            <i data-lucide="upload" style="width: 13px; height: 13px;"></i>
                            <span>Subir Imagen OG</span>
                        </button>
                        <button type="button" onclick="clearInput('input-og-url')" class="btn-secondary" style="padding: 0.4rem 0.6rem; color: #EF4444;" title="Limpiar">
                            <i data-lucide="x" style="width: 13px; height: 13px;"></i>
                        </button>
                    </div>

                    <input type="text" name="ogImageUrl" id="input-og-url" class="form-control" value="{{ old('ogImageUrl', $data['ogImageUrl']) }}" style="font-family: var(--font-mono); font-size: 0.78rem;">
                </div>
            </div>
        </div>

        <!-- ✦ BARRA SUPERIOR DE NOTICIERO (TOP BAR TICKER) -->
        <div class="form-panel" style="margin-bottom: 2rem;">
            <input type="hidden" name="has_topbar_toggle" value="1">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.85rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--emerald-primary); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase;">
                        <i data-lucide="radio" style="width: 14px; height: 14px;"></i>
                        <span>Barra Superior de Noticiero (Top Bar Ticker)</span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748B; margin-top: 0.35rem; margin-bottom: 0;">
                        Cinta informativa fijada en la parte más alta de la cabecera (arriba del menú) para emitir avisos en vivo, auditorías de servidores y sellos de independencia.
                    </p>
                </div>

                <div>
                    <label class="switch-toggle-label">
                        <input type="checkbox" name="showTopBar" value="1" {{ $data['showTopBar'] ? 'checked' : '' }} onchange="updateToggleBadge(this, 'topbar-status-badge')">
                        <span id="topbar-status-badge" class="toggle-pill {{ $data['showTopBar'] ? 'active' : '' }}">
                            <i data-lucide="check" style="width: 11px; height: 11px;"></i>
                            <span>{{ $data['showTopBar'] ? 'ACTIVADA' : 'DESACTIVADA' }}</span>
                        </span>
                    </label>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.5fr 3fr 1.5fr 1.5fr; gap: 1rem; margin-top: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Insignia Izquierda (Badge Pulsante)</label>
                    <input type="text" name="topBarBadge" class="form-control" value="{{ old('topBarBadge', $data['topBarBadge']) }}">
                    <span class="form-helper">Acompaña al punto verde pulsante en vivo.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Texto Informativo Central</label>
                    <input type="text" name="topBarText" class="form-control" value="{{ old('topBarText', $data['topBarText']) }}">
                    <span class="form-helper">Mensaje técnico o editorial principal.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Insignia Derecha</label>
                    <input type="text" name="topBarRightBadge" class="form-control" value="{{ old('topBarRightBadge', $data['topBarRightBadge']) }}">
                    <span class="form-helper">Píldora destacada de rigor editorial.</span>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Texto Derecho (Fecha / Edición)</label>
                    <input type="text" name="topBarRightText" class="form-control" value="{{ old('topBarRightText', $data['topBarRightText']) }}">
                    <span class="form-helper">Sello de edición o actualización.</span>
                </div>
            </div>
        </div>

        <!-- ✦ CINTA DE NOTICIAS EN MARQUESINA (TICKER) -->
        <div class="form-panel" style="margin-bottom: 2rem;">
            <input type="hidden" name="has_ticker_toggle" value="1">
            <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--emerald-primary); font-size: 0.78rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase;">
                        <i data-lucide="play" style="width: 14px; height: 14px;"></i>
                        <span>Cinta de Noticias en Marquesina (Ticker)</span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748B; margin-top: 0.35rem; margin-bottom: 0;">
                        Controla si la cinta continua de titulares y noticias de última hora se muestra en la cabecera de todas las páginas públicas del sitio web.
                    </p>
                </div>

                <div>
                    <label class="switch-toggle-label">
                        <input type="checkbox" name="showTicker" value="1" {{ $data['showTicker'] ? 'checked' : '' }} onchange="updateToggleBadge(this, 'ticker-status-badge')">
                        <span id="ticker-status-badge" class="toggle-pill {{ $data['showTicker'] ? 'active' : '' }}">
                            <i data-lucide="{{ $data['showTicker'] ? 'check' : 'x' }}" style="width: 11px; height: 11px;"></i>
                            <span>{{ $data['showTicker'] ? 'ACTIVADA' : 'DESACTIVADA' }}</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botón Guardar Cambios -->
        <div style="display: flex; justify-content: flex-end; margin-top: 2rem; margin-bottom: 3rem;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 0.92rem; font-weight: 700;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                <span>Guardar Cambios</span>
            </button>
        </div>
    </form>
</div>

<!-- =========================================================================
     PESTAÑA 2: SEO & INDEXACIÓN
     ========================================================================= -->
<div id="tab-content-seo" class="tab-content" style="{{ $activeTab === 'seo' ? 'display: block;' : 'display: none;' }}">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <input type="hidden" name="current_tab" value="seo">

        <div class="form-panel" style="margin-bottom: 2rem;">
            <div class="form-panel-header">
                <div class="form-panel-title">
                    <i data-lucide="search" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>Meta Tags & Parámetros para Motores de Búsqueda</span>
                </div>
                <p class="form-panel-desc">Configura los títulos, descripciones y fragmentos que leerán Google y redes sociales.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Meta Título Por Defecto</label>
                <input type="text" name="defaultMetaTitle" class="form-control" value="{{ old('defaultMetaTitle', $data['defaultMetaTitle']) }}">
                <span class="form-helper">Utilizado en la página de inicio y cuando una vista no declare un título específico.</span>
            </div>

            <div class="form-group">
                <label class="form-label">Meta Descripción Por Defecto</label>
                <textarea name="defaultMetaDescription" rows="3" class="form-control">{{ old('defaultMetaDescription', $data['defaultMetaDescription']) }}</textarea>
                <span class="form-helper">Recomendado entre 140 y 160 caracteres.</span>
            </div>

            <div class="form-group">
                <label class="form-label">Palabras Clave (Keywords)</label>
                <input type="text" name="defaultKeywords" class="form-control" value="{{ old('defaultKeywords', $data['defaultKeywords']) }}">
                <span class="form-helper">Separadas por comas.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Google Analytics ID</label>
                    <input type="text" name="googleAnalyticsId" class="form-control" placeholder="G-XXXXXXXXXX" value="{{ old('googleAnalyticsId', $data['googleAnalyticsId']) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Google Search Console (Meta Tag Code)</label>
                    <input type="text" name="googleSearchConsoleCode" class="form-control" placeholder="código de verificación" value="{{ old('googleSearchConsoleCode', $data['googleSearchConsoleCode']) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Scripts Personalizados en &lt;head&gt;</label>
                <textarea name="customHeadCode" rows="3" class="form-control" style="font-family: var(--font-mono); font-size: 0.8rem;" placeholder="Pixels o scripts antes del cierre de head...">{{ old('customHeadCode', $data['customHeadCode']) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Scripts Personalizados antes de &lt;/body&gt;</label>
                <textarea name="customBodyCode" rows="3" class="form-control" style="font-family: var(--font-mono); font-size: 0.8rem;" placeholder="Scripts de tracking antes del cierre de body...">{{ old('customBodyCode', $data['customBodyCode']) }}</textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                <span>Guardar Configuración SEO</span>
            </button>
        </div>
    </form>
</div>

<!-- =========================================================================
     PESTAÑA 3: AFILIACIÓN & ÉTICA
     ========================================================================= -->
<div id="tab-content-ethics" class="tab-content" style="{{ $activeTab === 'ethics' ? 'display: block;' : 'display: none;' }}">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <input type="hidden" name="current_tab" value="ethics">

        <div class="form-panel" style="margin-bottom: 2rem;">
            <div class="form-panel-header">
                <div class="form-panel-title">
                    <i data-lucide="shield-check" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>Políticas de Afiliación & Aviso de Transparencia</span>
                </div>
                <p class="form-panel-desc">Garantiza el cumplimiento ético, regulación de enlaces comerciales y aviso a los lectores.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Atributo rel para Enlaces de Afiliados</label>
                <input type="text" name="affiliateRel" class="form-control" value="{{ old('affiliateRel', $data['affiliateRel']) }}">
                <span class="form-helper">Recomendado por Google: sponsored noopener noreferrer</span>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Aviso Legal de Transparencia (Pie de Página)</label>
                <textarea name="disclosureNotice" rows="4" class="form-control">{{ old('disclosureNotice', $data['disclosureNotice']) }}</textarea>
                <span class="form-helper">Se renderiza en el footer de todas las páginas para total independencia declarada.</span>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                <span>Guardar Políticas de Afiliación</span>
            </button>
        </div>
    </form>
</div>

<!-- =========================================================================
     PESTAÑA 4: CUENTA & SEGURIDAD
     ========================================================================= -->
<div id="tab-content-account" class="tab-content" style="{{ $activeTab === 'account' ? 'display: block;' : 'display: none;' }}">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <input type="hidden" name="current_tab" value="account">
        <input type="hidden" name="update_account" value="1">

        <div class="form-panel" style="margin-bottom: 2rem;">
            <div class="form-panel-header">
                <div class="form-panel-title">
                    <i data-lucide="user" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>Credenciales del Administrador</span>
                </div>
                <p class="form-panel-desc">Actualiza tu información de inicio de sesión en el panel de control.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nombre del Administrador</label>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name ?? 'Administrador' }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ Auth::user()->email ?? 'admin@debatehosting.com' }}" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                <span>Actualizar Credenciales</span>
            </button>
        </div>
    </form>
</div>

<!-- =========================================================================
     PESTAÑA 5: MOTOR & BASE DE DATOS
     ========================================================================= -->
<div id="tab-content-engine" class="tab-content" style="{{ $activeTab === 'engine' ? 'display: block;' : 'display: none;' }}">
    <div class="form-panel" style="margin-bottom: 2rem;">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="cpu" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>Diagnóstico del Motor & Base de Datos</span>
            </div>
            <p class="form-panel-desc">Estado actual de la infraestructura del backend Laravel y caché del sistema.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1rem;">
                <div style="font-size: 0.72rem; color: #64748B; font-family: var(--font-mono); text-transform: uppercase;">Versión PHP</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: #FFFFFF; font-family: var(--font-mono); margin-top: 0.35rem;">
                    PHP {{ $systemInfo['phpVersion'] }}
                </div>
            </div>

            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1rem;">
                <div style="font-size: 0.72rem; color: #64748B; font-family: var(--font-mono); text-transform: uppercase;">Framework</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--emerald-primary); font-family: var(--font-mono); margin-top: 0.35rem;">
                    Laravel {{ $systemInfo['laravelVersion'] }}
                </div>
            </div>

            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1rem;">
                <div style="font-size: 0.72rem; color: #64748B; font-family: var(--font-mono); text-transform: uppercase;">Motor DB</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: #FFFFFF; font-family: var(--font-mono); margin-top: 0.35rem; text-transform: uppercase;">
                    {{ $systemInfo['dbDriver'] }}
                </div>
            </div>

            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1rem;">
                <div style="font-size: 0.72rem; color: #64748B; font-family: var(--font-mono); text-transform: uppercase;">Suscriptores</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: var(--sky-primary); font-family: var(--font-mono); margin-top: 0.35rem;">
                    {{ $systemInfo['subscribersCount'] }}
                </div>
            </div>
        </div>

        <div style="padding: 1.5rem; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <div>
                <div style="font-weight: 700; color: #FFFFFF; font-size: 0.95rem; margin-bottom: 0.25rem;">
                    Optimización y Vaciado de Caché
                </div>
                <p style="font-size: 0.8rem; color: #94A3B8; margin: 0;">
                    Borra las vistas Blade compiladas, rutas y caché de configuración para aplicar cualquier cambio de código en vivo.
                </p>
            </div>

            <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary" style="border-color: rgba(16, 185, 129, 0.3); color: var(--emerald-primary);">
                    <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                    <span>Vaciar Caché del Sistema</span>
                </button>
            </form>
        </div>

        <div style="padding: 1.5rem; background: rgba(239, 68, 68, 0.04); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-weight: 700; color: #FFFFFF; font-size: 0.95rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="alert-triangle" style="width: 16px; height: 16px; color: #F87171;"></i>
                    <span>Puesta a Cero de Estadísticas (Preparación para Producción)</span>
                </div>
                <p style="font-size: 0.8rem; color: #94A3B8; margin: 0; max-width: 650px;">
                    Restablece a cero todos los contadores de clics de proveedores, cupones y los registros de telemetría acumulados en localhost. Al desplegar en producción, las métricas comenzarán limpias desde 0.
                </p>
            </div>

            <form action="{{ route('admin.settings.reset-stats') }}" method="POST" onsubmit="return confirm('¿Confirmas que deseas restablecer TODOS los clics y telemetría a cero (0)? Esta acción dejará el sitio listo para producción.');">
                @csrf
                <button type="submit" class="btn-secondary" style="border-color: rgba(239, 68, 68, 0.4); color: #F87171;">
                    <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                    <span>Restablecer Estadísticas a 0</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     PESTAÑA 6: INTELIGENCIA ARTIFICIAL (GOOGLE GEMINI)
     ========================================================================= -->
<div id="tab-content-ai" class="tab-content" style="{{ $activeTab === 'ai' ? 'display: block;' : 'display: none;' }}">
    <form action="{{ route('admin.settings.update') }}" method="POST" id="form-settings-ai">
        @csrf
        <input type="hidden" name="current_tab" value="ai">

        <!-- Card: Configuración de Google Gemini (Versiones 2.5 y 3.0+) -->
        <div class="form-panel" style="margin-bottom: 2rem; background: #0c1017; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 1.75rem;">
            <!-- Header con botón Probar Gemini en Vivo arriba a la derecha -->
            <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.35rem;">
                        <i data-lucide="sparkles" style="width: 20px; height: 20px; color: #c084fc;"></i>
                        <h2 style="font-size: 1.15rem; font-weight: 700; color: #FFFFFF; margin: 0; letter-spacing: -0.01em;">
                            Google Gemini AI (Versiones 2.5 y 3.0+)
                        </h2>
                    </div>
                    <p style="font-size: 0.84rem; color: #94A3B8; margin: 0;">
                        Integración oficial con la API de Google Gemini para autocompletar descripciones, metatags SEO y fichas técnicas con 1 clic.
                    </p>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    @if(!empty($data['geminiApiKey']))
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 700; color: #34d399; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.35rem 0.75rem; border-radius: 9999px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #34d399;"></span>
                            <span>API ACTIVA</span>
                        </span>
                    @endif

                    <button type="button" id="btn-test-gemini" onclick="testGeminiLive()" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(147, 51, 234, 0.18); border: 1px solid rgba(168, 85, 247, 0.45); color: #d8b4fe; padding: 0.5rem 1.2rem; border-radius: 9999px; font-size: 0.84rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;">
                        <i data-lucide="activity" style="width: 15px; height: 15px;"></i>
                        <span id="btn-test-gemini-text">Probar Gemini en Vivo</span>
                    </button>
                </div>
            </div>

            <!-- Fila 1: Modelo de Google Gemini y Temperatura -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label" style="display: block; font-size: 0.82rem; font-weight: 600; color: #94A3B8; margin-bottom: 0.5rem;">
                        Modelo de Google Gemini
                    </label>
                    <select name="geminiModel" id="select-gemini-model" class="form-control" style="width: 100%; background: #080c14; border: 1px solid rgba(255, 255, 255, 0.12); color: #FFFFFF; border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.875rem;">
                        <option value="gemini-2.5-flash" {{ ($data['geminiModel'] ?? 'gemini-2.5-flash') === 'gemini-2.5-flash' ? 'selected' : '' }}>
                            Google Gemini 2.5 Flash (Recomendado — Ultra Rápido)
                        </option>
                        <option value="gemini-2.5-pro" {{ ($data['geminiModel'] ?? '') === 'gemini-2.5-pro' ? 'selected' : '' }}>
                            Google Gemini 2.5 Pro (Máximo Razonamiento)
                        </option>
                        <option value="gemini-3.0-flash" {{ ($data['geminiModel'] ?? '') === 'gemini-3.0-flash' ? 'selected' : '' }}>
                            Google Gemini 3.0 Flash (Nueva Generación 3.0)
                        </option>
                        <option value="gemini-3.0-pro" {{ ($data['geminiModel'] ?? '') === 'gemini-3.0-pro' ? 'selected' : '' }}>
                            Google Gemini 3.0 Pro (Máxima Potencia 3.0)
                        </option>
                        <option value="gemini-2.0-flash" {{ ($data['geminiModel'] ?? '') === 'gemini-2.0-flash' ? 'selected' : '' }}>
                            Google Gemini 2.0 Flash
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="display: block; font-size: 0.82rem; font-weight: 600; color: #94A3B8; margin-bottom: 0.5rem;">
                        Temperatura (Creatividad 0.0 a 1.0)
                    </label>
                    <input type="number" step="0.1" min="0.0" max="1.0" name="geminiTemperature" id="input-gemini-temperature" value="{{ $data['geminiTemperature'] ?? '0.5' }}" class="form-control" style="width: 100%; background: #080c14; border: 1px solid rgba(255, 255, 255, 0.12); color: #FFFFFF; border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.875rem;">
                </div>
            </div>

            <!-- Fila 2: Google Gemini API Key -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <label class="form-label" style="font-size: 0.82rem; font-weight: 600; color: #94A3B8; margin: 0;">
                        Google Gemini API Key
                    </label>
                    <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noopener noreferrer" style="font-size: 0.82rem; color: #38bdf8; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <span>Obtener clave en Google AI Studio</span>
                        <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                    </a>
                </div>
                <div style="position: relative;">
                    <input type="password" name="geminiApiKey" id="input-gemini-key" value="{{ $data['geminiApiKey'] }}" placeholder="AIzaSy..." class="form-control" style="width: 100%; font-family: var(--font-mono); padding-right: 45px; background: #080c14; border: 1px solid rgba(255, 255, 255, 0.12); color: #FFFFFF; border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.875rem;">
                    <button type="button" onclick="togglePasswordVisibility('input-gemini-key', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748B; cursor: pointer; display: flex; align-items: center;">
                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                    </button>
                </div>
                <p class="form-help" style="margin-top: 0.45rem; font-size: 0.78rem; color: #64748B; margin-bottom: 0;">
                    Conexión directa con endpoints oficiales de Google Gemini.
                </p>
            </div>

            <!-- Caja de Feedback de Prueba -->
            <div id="gemini-test-feedback" style="display: none; padding: 0.85rem 1.15rem; border-radius: 8px; font-size: 0.84rem; margin-bottom: 1.5rem;"></div>

            <!-- Nota de Fallback Inteligente -->
            <div style="padding: 0.9rem 1.15rem; background: rgba(59, 130, 246, 0.04); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 8px; font-size: 0.8rem; color: #93C5FD; display: flex; gap: 0.75rem; align-items: flex-start;">
                <i data-lucide="info" style="width: 17px; height: 17px; color: #60A5FA; flex-shrink: 0; margin-top: 2px;"></i>
                <div>
                    <strong style="color: #FFFFFF;">Protección contra fallos (Fallback Heurístico):</strong> Si en algún momento la API de Google Gemini se queda sin cuota o no tiene conexión, DebateHosting conmuta de manera transparente a su motor heurístico offline integrado, garantizando que el panel nunca se bloquee ni arroje errores al redactar.
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
                <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                <span>Guardar Configuración de IA</span>
            </button>
        </div>
    </form>
</div>



@push('admin-scripts')
<style>
    /* Slider de Pestañas de Navegación Horizontal (1 sola fila, scrollbar invisible) */
    .settings-tabs-nav {
        display: flex;
        align-items: center;
        flex-wrap: nowrap !important;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-subtle);
        padding-bottom: 0.85rem;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        white-space: nowrap !important;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none !important; /* Firefox */
        -ms-overflow-style: none !important; /* IE y Edge */
        cursor: grab;
    }
    .settings-tabs-nav:active {
        cursor: grabbing;
    }
    .settings-tabs-nav::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        background: transparent !important;
    }

    .tab-btn {
        flex-shrink: 0 !important;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-subtle);
        color: var(--text-muted);
        padding: 0.65rem 1.15rem;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all var(--transition-fast);
        white-space: nowrap;
        user-select: none;
    }
    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #FFFFFF;
        border-color: var(--border-medium);
    }
    .tab-btn.active {
        background: rgba(16, 185, 129, 0.08);
        border-color: rgba(16, 185, 129, 0.4);
        color: var(--emerald-primary);
    }

    /* Tarjetas de Formato de Logo */
    .logo-format-card {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    .logo-format-card:hover {
        border-color: var(--border-medium);
        transform: translateY(-1px);
    }
    .logo-format-card.is-selected {
        border-color: #10B981;
        background: rgba(16, 185, 129, 0.04);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.08);
    }
    .format-card-check {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #10B981;
        color: #000000;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .logo-format-card.is-selected .format-card-check {
        display: flex;
    }

    /* Selector de Íconos */
    .logo-icon-picker-btn {
        width: 60px;
        height: 56px;
        border-radius: 6px;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        color: var(--text-muted);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    .logo-icon-picker-btn:hover {
        border-color: var(--border-medium);
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.04);
    }
    .logo-icon-picker-btn.is-selected {
        background: rgba(16, 185, 129, 0.15);
        border-color: #10B981;
        color: #10B981;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
    }

    /* Píldoras de colores rápidos */
    .quick-color-pill {
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        color: var(--text-muted);
        padding: 0.3rem 0.65rem;
        border-radius: 4px;
        font-size: 0.74rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all var(--transition-fast);
    }
    .quick-color-pill:hover {
        border-color: var(--border-medium);
        color: #FFFFFF;
    }

    /* Switch Toggles */
    .switch-toggle-label {
        cursor: pointer;
        display: inline-block;
    }
    .switch-toggle-label input {
        display: none;
    }
    .toggle-pill {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-subtle);
        color: var(--text-dim);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        letter-spacing: 0.05em;
        transition: all var(--transition-fast);
    }
    .toggle-pill.active {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.4);
        color: #10B981;
    }
</style>

<script>
    // Cambio entre pestañas superiores
    function switchSettingsTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

        const target = document.getElementById('tab-content-' + tabName);
        if (target) {
            target.style.display = 'block';
        }

        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            if (btn.getAttribute('onclick').includes(tabName)) {
                btn.classList.add('active');
                btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        });

        // Actualizar URL sin recarga
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);

        if (window.lucide) lucide.createIcons();
    }

    // Selección de formato del logo
    function selectLogoFormat(type) {
        document.getElementById('input-logo-type').value = type;
        document.querySelectorAll('.logo-format-card').forEach(card => card.classList.remove('is-selected'));

        event.currentTarget.classList.add('is-selected');

        const panelIcon = document.getElementById('panel-logo-icon-text');
        const panelImg = document.getElementById('panel-logo-image');

        if (type === 'icon_text') {
            panelIcon.style.display = 'block';
            panelImg.style.display = 'none';
        } else if (type === 'image') {
            panelIcon.style.display = 'none';
            panelImg.style.display = 'block';
        } else {
            panelIcon.style.display = 'none';
            panelImg.style.display = 'none';
        }
    }

    // Selección de icono del logo
    function selectLogoIcon(iconKey, label) {
        document.getElementById('input-logo-icon').value = iconKey;
        document.getElementById('current-icon-name').innerText = label;

        document.querySelectorAll('.logo-icon-picker-btn').forEach(btn => btn.classList.remove('is-selected'));
        event.currentTarget.classList.add('is-selected');

        // Actualizar vista previa
        const iconLight = document.getElementById('preview-icon-light');
        const iconDark = document.getElementById('preview-icon-dark');
        if (iconLight && iconDark) {
            iconLight.setAttribute('data-lucide', iconKey);
            iconDark.setAttribute('data-lucide', iconKey);
            if (window.lucide) lucide.createIcons();
        }
    }

    // Sincronización de color picker y hex
    function syncColorInput(val) {
        document.getElementById('input-logo-color').value = val.toUpperCase();
        applyLiveColorToPreviews(val);
    }

    function syncColorPicker(val) {
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            document.getElementById('input-color-picker').value = val;
            applyLiveColorToPreviews(val);
        }
    }

    function applyQuickColor(hex) {
        document.getElementById('input-color-picker').value = hex;
        document.getElementById('input-logo-color').value = hex.toUpperCase();
        applyLiveColorToPreviews(hex);
    }

    function applyLiveColorToPreviews(color) {
        const boxLight = document.getElementById('preview-box-light-icon');
        const boxDark = document.getElementById('preview-box-dark-icon');
        const hlLight = document.getElementById('preview-light-highlight');

        if (boxLight) boxLight.style.backgroundColor = color;
        if (boxDark) boxDark.style.backgroundColor = color;
        if (hlLight) hlLight.style.color = color;
    }

    // Actualización reactiva de textos en la vista previa
    function updateLogoLivePreview() {
        const prefix = document.getElementById('input-logo-prefix').value || 'Debate';
        const highlight = document.getElementById('input-logo-highlight').value || 'hosting';

        document.getElementById('preview-light-prefix').innerText = prefix;
        document.getElementById('preview-light-highlight').innerText = highlight;
        document.getElementById('preview-dark-prefix').innerText = prefix;
        document.getElementById('preview-dark-highlight').innerText = highlight;
    }

    // Toggle status badge updater
    function updateToggleBadge(checkbox, badgeId) {
        const badge = document.getElementById(badgeId);
        if (checkbox.checked) {
            badge.classList.add('active');
            badge.innerHTML = '<i data-lucide="check" style="width: 11px; height: 11px;"></i><span>ACTIVADA</span>';
        } else {
            badge.classList.remove('active');
            badge.innerHTML = '<i data-lucide="x" style="width: 11px; height: 11px;"></i><span>DESACTIVADA</span>';
        }
        if (window.lucide) lucide.createIcons();
    }

    // Limpieza de inputs
    function clearInput(id) {
        document.getElementById(id).value = '';
    }

    // Previsualización instantánea de archivos subidos
    function handleFileUpload(input, targetInputId, thumbId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById(targetInputId).value = '/uploads/branding/' + file.name;

            if (thumbId) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumb = document.getElementById(thumbId);
                    thumb.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px;">';
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Alternar visibilidad de contraseñas / API Keys
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            if (icon) icon.setAttribute('data-lucide', 'eye');
        }
        if (window.lucide) lucide.createIcons();
    }

    // Prueba de conectividad en vivo con Google Gemini
    function testGeminiLive() {
        const key = document.getElementById('input-gemini-key').value.trim();
        const model = document.getElementById('select-gemini-model').value;
        const btn = document.getElementById('btn-test-gemini');
        const btnText = document.getElementById('btn-test-gemini-text');
        const fb = document.getElementById('gemini-test-feedback');

        if (!key) {
            fb.style.display = 'block';
            fb.style.background = 'rgba(245, 158, 11, 0.12)';
            fb.style.border = '1px solid rgba(245, 158, 11, 0.35)';
            fb.style.color = '#FCD34D';
            fb.innerHTML = '⚠️ Por favor introduce o pega tu Gemini API Key antes de probar la conexión.';
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Verificando con Google...';
        fb.style.display = 'block';
        fb.style.background = 'rgba(59, 130, 246, 0.1)';
        fb.style.border = '1px solid rgba(59, 130, 246, 0.3)';
        fb.style.color = '#93C5FD';
        fb.innerHTML = '✦ Conectando con Google Generative Language API (' + model + ')...';

        fetch('{{ route("admin.ai.test-gemini") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                api_key: key,
                model: model
            })
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btnText.innerText = 'Probar Gemini en Vivo';

            if (res.success) {
                fb.style.background = 'rgba(16, 185, 129, 0.15)';
                fb.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                fb.style.color = '#6EE7B7';
                fb.innerHTML = '✔ ' + res.message;
            } else {
                fb.style.background = 'rgba(244, 63, 94, 0.15)';
                fb.style.border = '1px solid rgba(244, 63, 94, 0.4)';
                fb.style.color = '#FECDD3';
                fb.innerHTML = '❌ ' + res.message;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Probar Gemini en Vivo';
            fb.style.background = 'rgba(244, 63, 94, 0.15)';
            fb.style.border = '1px solid rgba(244, 63, 94, 0.4)';
            fb.style.color = '#FECDD3';
            fb.innerHTML = '❌ Error de red al intentar conectar con el servidor: ' + err.message;
        });
    }

    // Slider horizontal de pestañas: deslizamiento con rueda del ratón y arrastre (drag-to-scroll)
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.querySelector('.settings-tabs-nav');
        if (!nav) return;

        // Desplazamiento horizontal con rueda del ratón
        nav.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                nav.scrollLeft += e.deltaY;
            }
        }, { passive: false });

        // Desplazamiento por arrastre del ratón (drag to scroll)
        let isDown = false;
        let startX, scrollLeft;

        nav.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - nav.offsetLeft;
            scrollLeft = nav.scrollLeft;
        });

        nav.addEventListener('mouseleave', () => {
            isDown = false;
        });

        nav.addEventListener('mouseup', () => {
            isDown = false;
        });

        nav.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - nav.offsetLeft;
            const walk = (x - startX) * 1.5;
            nav.scrollLeft = scrollLeft - walk;
        });

        // Asegurar que la pestaña activa quede visible al cargar
        const activeBtn = nav.querySelector('.tab-btn.active');
        if (activeBtn) {
            activeBtn.scrollIntoView({ behavior: 'auto', block: 'nearest', inline: 'center' });
        }
    });
</script>
@endpush

@endsection
