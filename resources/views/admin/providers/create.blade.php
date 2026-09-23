@extends('layouts.admin')

@section('title', 'Nuevo Proveedor')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Crear Nuevo Proveedor</h1>
        <p class="page-header-subtitle">Registra una nueva empresa de hosting con asistencia de IA para autocompletar métricas y redacción técnica.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.providers.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Volver a Proveedores</span>
        </a>
    </div>
</div>

@if($errors->any())
    <div class="toast-banner error">
        <div>
            <div style="font-weight: 700; margin-bottom: 0.25rem;">Por favor revisa los errores del formulario:</div>
            <ul style="margin-left: 1.25rem; font-size: 0.82rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Panel 4: Catálogo Multi-Producto & Planes (Hosting, VPS, Dedicados, etc.) -->
    <div class="form-panel">
        <div class="form-panel-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div class="form-panel-title">
                    <i data-lucide="layers" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>4. Catálogo Multi-Producto & Planes (Hosting, VPS, Dedicados...)</span>
                </div>
                <p class="form-panel-desc">
                    Asocia los diferentes productos que ofrece esta empresa para que al filtrar en <code>/ofertas?categoria=vps</code> o en su ficha pública aparezcan sus planes específicos con su precio real sin duplicar el proveedor.
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button type="button" onclick="autofillProductsWithAI()" id="btn-ai-products" class="btn-secondary" style="font-size: 0.8rem; background: rgba(16, 185, 129, 0.12); color: var(--emerald-light); border-color: rgba(16, 185, 129, 0.35);">
                    <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                    <span id="btn-ai-products-text">✨ Autocompletar Planes con IA</span>
                </button>
                <button type="button" onclick="addProductRow()" class="btn-primary" style="font-size: 0.8rem;">
                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                    <span>Añadir Producto</span>
                </button>
            </div>
        </div>

        <div id="ai-products-status" style="display: none; margin-bottom: 1rem; padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.8rem;"></div>

        <div id="products-container" style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Renderizado dinámicamente con JavaScript -->
        </div>
    </div>
@endif

<!-- =========================================================================
     ASISTENTE IA PARA GENERACIÓN RÁPIDA
     ========================================================================= -->
<div class="form-panel" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(20, 24, 33, 0.95) 100%); border: 1.5px solid rgba(16, 185, 129, 0.35); box-shadow: 0 4px 24px rgba(16, 185, 129, 0.08); max-width: 960px;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.2rem 0.55rem; border-radius: 4px; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.06em;">
                <i data-lucide="sparkles" style="width: 12px; height: 12px;"></i>
                <span>Generador Rápido con IA</span>
            </div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.01em;">Autocompletar Ficha Técnica con Inteligencia Artificial</h2>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                Introduce el nombre de la empresa y la IA investigará y redactará el plan, precios de referencia, notas de La Balanza, pros, contras, veredicto y metadatos SEO.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1.5fr auto; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="color: var(--emerald-primary);">Nombre del Proveedor</label>
            <input type="text" id="ai-provider-name" placeholder="ej: Cloudways, Hetzner, BanaHosting, Kinsta..." class="form-control" style="border-color: rgba(16, 185, 129, 0.3); background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Especialidad / Enfoque (Opcional)</label>
            <input type="text" id="ai-provider-focus" placeholder="ej: VPS Cloud, WordPress Gestionado..." class="form-control" style="background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <button type="button" id="btn-generate-ai" onclick="generateWithAI()" class="btn-primary" style="height: 40px; padding: 0 1.25rem; white-space: nowrap;">
            <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
            <span id="btn-ai-text">Generar con IA</span>
        </button>
    </div>

    <!-- Indicador de Carga y Mensajes del Asistente -->
    <div id="ai-status" style="display: none; margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.82rem;"></div>
</div>

<form id="provider-form" action="{{ route('admin.providers.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 960px;">
    @csrf

    <!-- Panel 1: Identidad -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="badge-info" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>1. Identidad de Marca</span>
            </div>
            <p class="form-panel-desc">Información básica y activos gráficos del proveedor.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Nombre de la Empresa *</label>
                <input type="text" id="field-name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="ej: Hostinger, Webempresa, SiteGround">
            </div>

            <div class="form-group">
                <label class="form-label">Slug URL (Opcional)</label>
                <input type="text" id="field-slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="ej: hostinger (generado automáticamente si queda vacío)">
            </div>
        </div>

        <div class="form-group" style="background: var(--bg-card-subtle); border: 1px dashed var(--border-medium); border-radius: var(--radius-sm); padding: 1.25rem; margin-top: 0.5rem;">
            <label class="form-label" style="color: #FFFFFF;">Logotipo Oficial (PNG, SVG, JPG, WebP)</label>
            <p class="form-help" style="margin-bottom: 0.75rem;">Se alojará de forma segura en el almacenamiento local.</p>
            <input type="file" name="logo" accept="image/*" class="form-control" style="background: var(--bg-input);">
        </div>
    </div>

    <!-- Panel 2: Categorías & Distintivo -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="layers" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>2. Categorías & Distintivo</span>
            </div>
            <p class="form-panel-desc">Selecciona las etiquetas aplicables para los filtros y define el distintivo visual del proveedor.</p>
        </div>

        <!-- Categorías del Proveedor -->
        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label" style="font-weight: 700; color: #FFFFFF; font-size: 0.82rem; letter-spacing: 0.05em; margin-bottom: 0.85rem;">
                CATEGORÍAS DEL PROVEEDOR * (SELECCIONA LAS APLICABLES PARA LOS FILTROS)
            </label>

            <div id="categories-container" style="display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 0.85rem;">
                @php
                    $selectedCategories = old('categories', ['hosting']);
                    if (is_string($selectedCategories)) {
                        $selectedCategories = json_decode($selectedCategories, true) ?: [];
                    }
                    if (!is_array($selectedCategories)) {
                        $selectedCategories = [];
                    }
                @endphp

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

            <!-- Input para añadir categoría personalizada -->
            <div style="display: flex; gap: 0.5rem; max-width: 600px;">
                <input type="text" id="custom-category-input" class="form-control" placeholder="Añadir otra categoría personalizada..." style="font-size: 0.85rem;" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addCustomCategory(); }">
                <button type="button" onclick="addCustomCategory()" class="btn-secondary" style="white-space: nowrap; padding: 0 1.1rem; height: 38px;">
                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                    <span>Añadir</span>
                </button>
            </div>
        </div>

        <!-- Badge / Distintivo del Proveedor -->
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="font-weight: 700; color: #FFFFFF; font-size: 0.82rem; letter-spacing: 0.05em; margin-bottom: 0.85rem;">
                BADGE / DISTINTIVO DEL PROVEEDOR (OPCIONAL)
            </label>

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

            <div id="badges-container" style="display: flex; flex-wrap: wrap; gap: 0.65rem;">
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

    <!-- Panel 3: Planes y Precios -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="tag" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <span>3. Plan Auditado & Tarifas</span>
            </div>
            <p class="form-panel-desc">Especifica el plan de referencia para calcular los ahorros en portada.</p>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Nombre del Plan Auditado *</label>
                <input type="text" id="field-plan" name="plan" class="form-control" value="{{ old('plan', 'Plan Premium') }}" required placeholder="ej: Cloud Startup, WordPress Pro">
            </div>

            <div class="form-group">
                <label class="form-label">Precio Oferta ($) *</label>
                <input type="number" step="0.01" id="field-price-from" name="price_from" class="form-control" value="{{ old('price_from', '2.99') }}" required style="font-family: var(--font-mono);">
            </div>

            <div class="form-group">
                <label class="form-label">Precio Regular Antes ($)</label>
                <input type="number" step="0.01" id="field-price-before" name="price_before" class="form-control" value="{{ old('price_before', '9.99') }}" required style="font-family: var(--font-mono);">
            </div>
        </div>
    </div>

    <!-- Panel 4: Catálogo Multi-Producto & Planes (Hosting, VPS, Dedicados, etc.) -->
    <div class="form-panel">
        <div class="form-panel-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div class="form-panel-title">
                    <i data-lucide="layers" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                    <span>4. Catálogo Multi-Producto & Planes (Hosting, VPS, Dedicados...)</span>
                </div>
                <p class="form-panel-desc">
                    Asocia los diferentes productos y planes que vende esta empresa para que al filtrar en <code>/ofertas?categoria=vps</code> o en su ficha pública aparezcan sus planes específicos con su precio real sin duplicar el proveedor.
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button type="button" onclick="autofillProductsWithAI()" id="btn-ai-products" class="btn-secondary" style="font-size: 0.8rem; background: rgba(16, 185, 129, 0.12); color: var(--emerald-light); border-color: rgba(16, 185, 129, 0.35);">
                    <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                    <span id="btn-ai-products-text">✨ Autocompletar Planes con IA</span>
                </button>
                <button type="button" onclick="addProductRow()" class="btn-primary" style="font-size: 0.8rem;">
                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                    <span>Añadir Producto</span>
                </button>
            </div>
        </div>

        <div id="ai-products-status" style="display: none; margin-bottom: 1rem; padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.8rem;"></div>

        <div id="products-container" style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Renderizado dinámicamente con JavaScript -->
        </div>
    </div>

    <!-- Panel 5: Puntuaciones de La Balanza -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="scale" style="width: 18px; height: 18px; color: var(--amber-primary);"></i>
                <span>3. Telemetría y Notas de La Balanza</span>
            </div>
            <p class="form-panel-desc">Parámetros que alimentan el comparador dinámico e interactivo (escala de 0.0 a 10.0).</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Precio (0-10)</label>
                <input type="number" step="0.1" min="0" max="10" id="field-score-precio" name="score_precio" class="form-control" value="{{ old('score_precio', '9.0') }}" required style="font-family: var(--font-mono);">
            </div>

            <div class="form-group">
                <label class="form-label">Velocidad / TTFB (0-10)</label>
                <input type="number" step="0.1" min="0" max="10" id="field-score-rendimiento" name="score_rendimiento" class="form-control" value="{{ old('score_rendimiento', '8.5') }}" required style="font-family: var(--font-mono);">
            </div>

            <div class="form-group">
                <label class="form-label">Soporte 24/7 (0-10)</label>
                <input type="number" step="0.1" min="0" max="10" id="field-score-soporte" name="score_soporte" class="form-control" value="{{ old('score_soporte', '8.0') }}" required style="font-family: var(--font-mono);">
            </div>

            <div class="form-group">
                <label class="form-label">Facilidad / Panel (0-10)</label>
                <input type="number" step="0.1" min="0" max="10" id="field-score-facilidad" name="score_facilidad" class="form-control" value="{{ old('score_facilidad', '8.5') }}" required style="font-family: var(--font-mono);">
            </div>

            <div class="form-group">
                <label class="form-label">Uptime Garantizado (%)</label>
                <input type="number" step="0.01" min="90" max="100" id="field-uptime" name="uptime" class="form-control" value="{{ old('uptime', '99.98') }}" required style="font-family: var(--font-mono);">
            </div>
        </div>
    </div>

    <!-- Panel 4: Afiliación & Veredicto -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="award" style="width: 18px; height: 18px; color: var(--rose-primary);"></i>
                <span>4. Veredicto Editorial & Afiliación</span>
            </div>
            <p class="form-panel-desc">Enlace de rastreo y análisis honesto para los usuarios.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Enlace Oficial / Afiliado (URL Destino)</label>
            <input type="url" id="field-affiliate-url" name="affiliate_url" class="form-control" value="{{ old('affiliate_url') }}" placeholder="https://proveedor.com/?ref=debatehosting" style="font-family: var(--font-mono);">
            <p class="form-help">Los usuarios serán redirigidos a esta dirección al pulsar en los botones /go/{slug}.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Análisis / Veredicto Editorial</label>
            <textarea id="field-description" name="description" rows="3" class="form-control" placeholder="Resumen del rendimiento, estabilidad del servidor y propuesta de valor...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">¿A quién se recomienda? (Veredicto en 1 línea)</label>
            <input type="text" id="field-verdict" name="verdict" class="form-control" value="{{ old('verdict') }}" placeholder="ej: La opción perfecta para programadores y tiendas WooCommerce con alto tráfico...">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Puntos Fuertes (Pros)</label>
                <textarea id="field-pros" name="pros" rows="4" class="form-control" placeholder="• Discos NVMe rápidos&#10;• Soporte en español&#10;• Copias de seguridad diarias">{{ old('pros') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Puntos Débiles (Contras)</label>
                <textarea id="field-cons" name="cons" rows="4" class="form-control" placeholder="• Renovación a precio regular más elevado&#10;• Dominio gratis solo el primer año">{{ old('cons') }}</textarea>
            </div>
        </div>

        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
            <label class="toggle-label-wrap">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                <span class="toggle-text">Publicar inmediatamente este proveedor en el portal</span>
            </label>
        </div>
    </div>

    <!-- Panel 5: Metadatos SEO -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="search" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>5. Optimización SEO & Indexación</span>
            </div>
            <p class="form-panel-desc">Etiquetas Meta optimizadas para mejorar la visibilidad en motores de búsqueda.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Meta Título (Título SEO)</label>
            <input type="text" id="field-meta-title" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="ej: Opiniones y Análisis de Hostinger: ¿Vale la pena en 2025? — DebateHosting">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Meta Descripción (Snippet de Google)</label>
            <textarea id="field-meta-description" name="meta_description" rows="2" class="form-control" placeholder="ej: Auditoría técnica independiente de Hostinger. Analizamos latencia TTFB, precios desde $2.49/mes, pros y contras sin patrocinios.">{{ old('meta_description') }}</textarea>
        </div>
    </div>

    <input type="hidden" name="period" value="mes">

    <!-- Barra de Acciones -->
    <div class="form-actions-bar">
        <a href="{{ route('admin.providers.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">
            <i data-lucide="check" style="width: 15px; height: 15px;"></i>
            <span>Guardar Proveedor</span>
        </button>
    </div>
</form>

@push('admin-scripts')
<script>
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
            statusBox.innerHTML = '⚠️ Por favor escribe el nombre de la empresa de hosting antes de generar.';
            nameInput.focus();
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Analizando y Generando...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Investigando arquitectura de <strong>' + name + '</strong> y redactando ficha editorial...';

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
            document.getElementById('field-score-precio').value = d.score_precio || '';
            document.getElementById('field-score-rendimiento').value = d.score_rendimiento || '';
            document.getElementById('field-score-soporte').value = d.score_soporte || '';
            document.getElementById('field-score-facilidad').value = d.score_facilidad || '';
            document.getElementById('field-uptime').value = d.uptime || '99.98';
            document.getElementById('field-description').value = d.description || '';
            document.getElementById('field-pros').value = d.pros || '';
            document.getElementById('field-cons').value = d.cons || '';
            document.getElementById('field-verdict').value = d.verdict || '';
            document.getElementById('field-meta-title').value = d.meta_title || '';
            document.getElementById('field-meta-description').value = d.meta_description || '';

            // Notificación de éxito
            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = '✔ ¡Ficha técnica completada con éxito para <strong>' + (d.name || name) + '</strong>! Revisa los campos y pulsa "Guardar Proveedor".';

            // Scroll suave hacia la ficha
            document.getElementById('field-name').scrollIntoView({ behavior: 'smooth', block: 'center' });
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

    // Sincronizar campo de nombre si se escribe directamente abajo
    document.getElementById('field-name').addEventListener('input', function(e) {
        const aiName = document.getElementById('ai-provider-name');
        if (!aiName.value) {
            aiName.value = e.target.value;
        }
    });

    // Gestión interactiva de Categorías
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
            if (!chk.checked) {
                toggleCategoryPill(existing, slug);
            }
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
            <span class="pill-name">${val}</span>
            <input type="checkbox" name="categories[]" value="${slug}" checked style="display: none;">
        `;

        container.appendChild(btn);
        input.value = '';
    }

    // Selección de Badge
    function selectBadgePill(btn, label, color) {
        document.getElementById('field-badge').value = label;
        document.getElementById('field-badge-color').value = color;

        document.querySelectorAll('.badge-select-pill').forEach(b => {
            b.classList.remove('is-selected');
            const prefix = b.querySelector('.pill-prefix');
            if (prefix) prefix.innerText = '';
        });

        btn.classList.add('is-selected');
        const prefix = btn.querySelector('.pill-prefix');
        if (prefix) prefix.innerText = '✓ ';
    }

    @php
        $catsData = $categories->map(function ($c) {
            return ['slug' => $c->slug, 'name' => $c->name];
        })->values();
    @endphp

    // Multi-Producto y Catálogo de Planes
    window.availableCategories = {!! json_encode($catsData) !!};
    window.initialProducts = [];

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

        let categoryOptions = '<option value="">(Sin categoría específica)</option>';
        window.availableCategories.forEach(cat => {
            const selected = (cat.slug.toLowerCase() === (categorySlug || '').toLowerCase()) ? 'selected' : '';
            categoryOptions += `<option value="${cat.slug}" ${selected}>${cat.name}</option>`;
        });

        const card = document.createElement('div');
        card.className = 'product-item-card';
        card.style.cssText = 'background: var(--bg-card-subtle); border: 1px solid var(--border-medium); border-radius: var(--radius-sm); padding: 1.25rem;';
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span class="product-item-badge" style="font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; color: var(--emerald-primary);">
                    PLAN / SERVICIO #<span class="product-index-num">${container.children.length + 1}</span>
                </span>
                <button type="button" onclick="removeProductRow(this)" style="background: none; border: none; color: #F43F5E; cursor: pointer; display: flex; align-items: center; gap: 0.3rem; font-size: 0.78rem;">
                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                    <span>Eliminar</span>
                </button>
            </div>

            <div style="display: grid; grid-template-columns: 1.5fr 2fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 0.85rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Categoría Destino</label>
                    <select name="products[${idx}][category_slug]" class="form-control" style="font-size: 0.82rem;">
                        ${categoryOptions}
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Nombre del Plan *</label>
                    <input type="text" name="products[${idx}][plan_name]" value="${escapeHtml(planName)}" placeholder="ej: Shared Lite NVMe, VPS KVM 1..." class="form-control" required style="font-size: 0.82rem;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Precio Oferta ($) *</label>
                    <input type="number" step="0.01" name="products[${idx}][price_from]" value="${priceFrom}" class="form-control" required style="font-family: var(--font-mono); font-size: 0.82rem;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Precio Antes ($)</label>
                    <input type="number" step="0.01" name="products[${idx}][price_before]" value="${priceBefore}" class="form-control" style="font-family: var(--font-mono); font-size: 0.82rem;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Período</label>
                    <select name="products[${idx}][period]" class="form-control" style="font-size: 0.82rem;">
                        <option value="mes" ${period === 'mes' ? 'selected' : ''}>/mes</option>
                        <option value="año" ${period === 'año' ? 'selected' : ''}>/año</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 2fr auto; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Especificaciones Clave (separadas por comas)</label>
                    <input type="text" name="products[${idx}][specs]" value="${escapeHtml(specs)}" placeholder="ej: 1 vCPU, 2GB RAM, 20GB NVMe, cPanel, LiteSpeed" class="form-control" style="font-size: 0.82rem;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem;">Enlace Afiliado Específico (Opcional)</label>
                    <input type="text" name="products[${idx}][affiliate_url]" value="${escapeHtml(affiliateUrl)}" placeholder="Hereda el del proveedor si se deja vacío" class="form-control" style="font-size: 0.82rem;">
                </div>
                <div class="form-group" style="margin-bottom: 0; padding-bottom: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.8rem; color: #FFFFFF;">
                        <input type="checkbox" name="products[${idx}][is_featured]" value="1" ${isFeatured ? 'checked' : ''}>
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
        btnText.innerText = 'Investigando planes...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Identificando líneas de productos (Hosting, VPS, Dedicados...) de <strong>' + name + '</strong>...';

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
            btnText.innerText = '✨ Autocompletar Planes con IA';

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

            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = `✔ ¡Se han agregado ${res.data.length} planes y productos detectados para <strong>${name}</strong>! Revisa y pulsa "Guardar Proveedor".`;
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = '✨ Autocompletar Planes con IA';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '❌ Error al conectar con el asistente de IA.';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // En creación, si hay datos precargados o plan por defecto, agregamos una primera fila
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
    });
</script>
@endpush
@endsection
