@extends('layouts.admin')

@section('title', 'Redactar Reseña Editorial')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Redactar Nueva Reseña Editorial</h1>
        <p class="page-header-subtitle">Análisis técnico a fondo con inyección inteligente de Widget Oficial o Alternativas de Afiliación.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.reviews.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Volver a Reseñas</span>
        </a>
    </div>
</div>

@if($errors->any())
    <div class="toast-banner error">
        <div>
            <div style="font-weight: 700; margin-bottom: 0.25rem;">Por favor corrige los siguientes errores:</div>
            <ul style="margin-left: 1.25rem; font-size: 0.82rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- =========================================================================
     ASISTENTE IA PARA REDACCIÓN EDITORIAL DE RESEÑAS
     ========================================================================= -->
<div class="form-panel" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(20, 24, 33, 0.95) 100%); border: 1.5px solid rgba(16, 185, 129, 0.35); box-shadow: 0 4px 24px rgba(16, 185, 129, 0.08); max-width: 960px; margin-bottom: 2rem;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.2rem 0.55rem; border-radius: 4px; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.06em;">
                <i data-lucide="sparkles" style="width: 12px; height: 12px;"></i>
                <span>Redactor Experto con IA</span>
            </div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.01em;">Generar Análisis Editorial Completo con IA</h2>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                Selecciona un proveedor del catálogo o escribe el nombre de cualquier hosting externo. La IA redactará el artículo técnico completo con pruebas de velocidad, panel, soporte, pros, contras, veredicto y metadatos SEO.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.5fr 1.5fr auto; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="color: var(--emerald-primary);">Proveedor para la Reseña</label>
            <input type="text" id="ai-review-name" placeholder="ej: Hostinger, SiteGround, Hetzner, Alexhost..." class="form-control" style="border-color: rgba(16, 185, 129, 0.3); background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Enfoque / Ángulo del Análisis (Opcional)</label>
            <input type="text" id="ai-review-focus" placeholder="ej: Hosting WordPress, VPS Cloud NVMe, Precios..." class="form-control" style="background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <button type="button" id="btn-ai-review" onclick="generateReviewWithAI()" class="btn-primary" style="height: 40px; padding: 0 1.25rem; white-space: nowrap;">
            <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
            <span id="btn-ai-review-text">Redactar con IA</span>
        </button>
    </div>

    <!-- Indicador de Carga y Mensajes del Asistente -->
    <div id="ai-review-status" style="display: none; margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.82rem;"></div>
</div>

<form action="{{ route('admin.reviews.store') }}" method="POST" style="max-width: 960px;">
    @csrf

    <!-- Panel 1: Vinculación con Proveedor y Tipo de Widget -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="link" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>1. Proveedor y Detección de Widget</span>
            </div>
            <p class="form-panel-desc">Si el proveedor está en catálogo activo, se mostrará el widget con su cupón y botón de afiliado. Si no está publicado, el widget recomendará 3 alternativas líderes.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Seleccionar Proveedor del Catálogo</label>
                <select name="provider_id" id="provider_id" class="form-control" onchange="handleProviderChange()">
                    <option value="">-- Proveedor Externo / No Listado en Catálogo --</option>
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}" data-active="{{ $p->active ? '1' : '0' }}" data-name="{{ $p->name }}" {{ old('provider_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->active ? 'Activo en Catálogo' : 'Inactivo' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="group-external-name" style="{{ old('provider_id') ? 'display: none;' : '' }}">
                <label class="form-label">Nombre del Proveedor Externo</label>
                <input type="text" name="provider_name" id="provider_name" value="{{ old('provider_name') }}" placeholder="ej. Alexhost, HostGator..." class="form-control" oninput="syncAiNameFromInput()">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Categoría para Alternativas Recomendadas (en caso de que el proveedor no esté publicado)</label>
            <select name="target_category" id="target_category" class="form-control">
                <option value="">-- Categoría General (Podio DebateHosting) --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ old('target_category') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->slug }})
                    </option>
                @endforeach
            </select>
            <span style="font-size: 0.75rem; color: var(--text-dim); display: block; margin-top: 0.3rem;">
                Si el proveedor analizado no está activo, el widget recomendará los 3 mejores proveedores de esta categoría.
            </span>
        </div>

        <!-- Indicador en tiempo real del Widget que se mostrará -->
        <div id="widget-preview-indicator" style="margin-top: 1rem; padding: 1rem; border-radius: 8px; border: 1px dashed rgba(245, 158, 11, 0.4); background: rgba(245, 158, 11, 0.08); display: flex; align-items: center; gap: 0.75rem;">
            <i data-lucide="shuffle" id="widget-icon" style="width: 20px; height: 20px; color: var(--amber-primary); flex-shrink: 0;"></i>
            <div style="font-size: 0.85rem;" id="widget-desc">
                <strong style="color: #FFFFFF;">Widget de Alternativas Recomendadas Activo:</strong>
                <span style="color: var(--text-muted);">Como no hay un proveedor activo seleccionado, la reseña mostrará automáticamente las 3 mejores alternativas recomendadas con enlaces de afiliado.</span>
            </div>
        </div>
    </div>

    <!-- Panel 2: Contenido Editorial -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="file-text" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>2. Título, Calificación y Resumen</span>
            </div>
            <p class="form-panel-desc">Estructura del artículo para captar tráfico y dar veredicto claro.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Título de la Reseña <span style="color: var(--rose-primary);">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="ej. Reseña de Alexhost: Rendimiento, Uptime y Veredicto Real" class="form-control" required oninput="generateSlug()">
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Slug de la URL (amigable para SEO)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="ej. alexhost-analisis-y-opinion" class="form-control">
                <span style="font-size: 0.72rem; color: var(--text-dim); display: block; margin-top: 0.25rem;">
                    Se autogenera desde el título. Quedará en /resenas/slug
                </span>
            </div>

            <div class="form-group">
                <label class="form-label">Puntuación Global (1.0 a 10.0) <span style="color: var(--rose-primary);">*</span></label>
                <input type="number" step="0.1" min="1" max="10" name="rating" id="rating" value="{{ old('rating', '9.0') }}" class="form-control" style="font-family: var(--font-mono); font-size: 1.1rem; font-weight: 700; color: var(--emerald-primary);" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Resumen Ejecutivo / Lead</label>
            <textarea name="summary" id="summary" rows="3" class="form-control" placeholder="Breve introducción de 2 a 3 oraciones que sintetice la experiencia y a quién va dirigido...">{{ old('summary') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Cuerpo del Análisis Detallado <span style="color: var(--rose-primary);">*</span></label>
            <textarea name="content" id="content" rows="15" class="form-control" style="font-family: var(--font-sans); line-height: 1.7;" placeholder="Redacta el análisis en profundidad con subtítulos, pruebas y secciones técnicas..." required>{{ old('content') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label" style="color: var(--emerald-primary);">Puntos Fuertes (Pros)</label>
                <textarea name="pros" id="pros" rows="4" class="form-control" placeholder="• Servidores rápidos con discos NVMe&#10;• Excelente relación calidad/precio&#10;• Panel moderno y fluido">{{ old('pros') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" style="color: var(--rose-primary);">Aspectos a Considerar (Contras)</label>
                <textarea name="cons" id="cons" rows="4" class="form-control" placeholder="• Soporte solo por ticket en inglés&#10;• No incluye dominio gratuito">{{ old('cons') }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Veredicto Final de la Redacción</label>
            <textarea name="verdict" id="verdict" rows="3" class="form-control" placeholder="Conclusión contundente: ¿Para quién se recomienda este proveedor y cuándo es mejor elegir otra opción?">{{ old('verdict') }}</textarea>
        </div>
    </div>

    <!-- Panel 3: SEO y Publicación -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="globe" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>3. Metadatos SEO y Publicación</span>
            </div>
            <p class="form-panel-desc">Ajustes para posicionamiento orgánico en Google y visibilidad.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Meta Título (SEO)</label>
            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" placeholder="ej. ¿Vale la pena Alexhost en 2026? Opinión y Pruebas Reales" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Meta Descripción (SEO)</label>
            <textarea name="meta_description" id="meta_description" rows="2" class="form-control" placeholder="Descripción atractiva para los snippets de Google (máximo 160 caracteres)...">{{ old('meta_description') }}</textarea>
        </div>


        <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; color: #FFFFFF; font-weight: 600;">
                <input type="checkbox" name="published" value="1" {{ old('published', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--emerald-primary);">
                <span>Publicar inmediatamente en vivo</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; color: #FFFFFF; font-weight: 600;">
                <input type="checkbox" name="featured" value="1" {{ old('featured') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--amber-primary);">
                <span>Destacar reseña en la cabecera</span>
            </label>
        </div>
    </div>

    <!-- Botón Guardar -->
    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
        <a href="{{ route('admin.reviews.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
            <i data-lucide="save" style="width: 16px; height: 16px;"></i>
            <span>Guardar Reseña</span>
        </button>
    </div>
</form>

<script>
    function handleProviderChange() {
        const select = document.getElementById('provider_id');
        const selectedOption = select.options[select.selectedIndex];
        const extGroup = document.getElementById('group-external-name');
        const indicator = document.getElementById('widget-preview-indicator');
        const icon = document.getElementById('widget-icon');
        const desc = document.getElementById('widget-desc');
        const aiName = document.getElementById('ai-review-name');

        if (select.value === "") {
            extGroup.style.display = 'block';
            indicator.style.borderColor = 'rgba(245, 158, 11, 0.4)';
            indicator.style.backgroundColor = 'rgba(245, 158, 11, 0.08)';
            desc.innerHTML = '<strong style="color: #FFFFFF;">Widget de Alternativas Recomendadas Activo:</strong> <span style="color: var(--text-muted);">Como el proveedor no está en catálogo, la reseña mostrará automáticamente las 3 mejores alternativas líderes con enlaces de afiliado para monetizar el tráfico.</span>';
            if (window.lucide) {
                icon.setAttribute('data-lucide', 'shuffle');
                icon.style.color = 'var(--amber-primary)';
                lucide.createIcons();
            }
        } else {
            extGroup.style.display = 'none';
            const isActive = selectedOption.getAttribute('data-active') === '1';
            const name = selectedOption.getAttribute('data-name');

            if (aiName && !aiName.value) {
                aiName.value = name;
            }

            if (isActive) {
                indicator.style.borderColor = 'rgba(16, 185, 129, 0.4)';
                indicator.style.backgroundColor = 'rgba(16, 185, 129, 0.08)';
                desc.innerHTML = '<strong style="color: #A7F3D0;">Widget Oficial de ' + name + ' Activo:</strong> <span style="color: var(--text-muted);">Se mostrará la tarjeta promocional destacada con el logo de ' + name + ', precio oficial, cupón activo y botón de afiliado directo.</span>';
                if (window.lucide) {
                    icon.setAttribute('data-lucide', 'zap');
                    icon.style.color = 'var(--emerald-primary)';
                    lucide.createIcons();
                }
            } else {
                indicator.style.borderColor = 'rgba(244, 63, 94, 0.4)';
                indicator.style.backgroundColor = 'rgba(244, 63, 94, 0.08)';
                desc.innerHTML = '<strong style="color: #FECDD3;">Proveedor Inactivo en Catálogo:</strong> <span style="color: var(--text-muted);">' + name + ' está marcado como inactivo. La reseña mostrará las 3 alternativas recomendadas de respaldo.</span>';
                if (window.lucide) {
                    icon.setAttribute('data-lucide', 'shuffle');
                    icon.style.color = 'var(--rose-primary)';
                    lucide.createIcons();
                }
            }
        }
    }

    function syncAiNameFromInput() {
        const ext = document.getElementById('provider_name');
        const ai = document.getElementById('ai-review-name');
        if (ext && ext.value) {
            ai.value = ext.value;
        }
    }

    function generateReviewWithAI() {
        const nameInput = document.getElementById('ai-review-name');
        const focusInput = document.getElementById('ai-review-focus');
        const btn = document.getElementById('btn-ai-review');
        const btnText = document.getElementById('btn-ai-review-text');
        const statusBox = document.getElementById('ai-review-status');
        const select = document.getElementById('provider_id');
        const selectedOption = select.options[select.selectedIndex];

        let name = nameInput.value.trim();
        let providerId = select.value || null;

        if (!name && providerId) {
            name = selectedOption.getAttribute('data-name') || '';
            nameInput.value = name;
        }

        if (!name) {
            statusBox.style.display = 'block';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '⚠️ Por favor escribe el nombre de un proveedor o selecciónalo del catálogo.';
            nameInput.focus();
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Redactando con IA...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Analizando arquitectura técnica de <strong>' + name + '</strong> y redactando reseña editorial completa...';

        fetch('{{ route("admin.ai.generate-review") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                provider_id: providerId,
                name: name,
                focus: focusInput.value.trim()
            })
        })
        .then(response => response.json())
        .then(res => {
            btn.disabled = false;
            btnText.innerText = 'Redactar con IA';

            if (!res.success || !res.data) {
                statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
                statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
                statusBox.style.color = '#FECDD3';
                statusBox.innerHTML = '❌ ' + (res.message || 'Error al generar la reseña.');
                return;
            }

            const d = res.data;

            // Rellenar campos del formulario
            if (d.title) document.getElementById('title').value = d.title;
            if (d.slug) {
                const slugEl = document.getElementById('slug');
                slugEl.value = d.slug;
                slugEl.dataset.manual = "1";
            }
            if (d.rating) document.getElementById('rating').value = d.rating;
            if (d.summary) document.getElementById('summary').value = d.summary;
            if (d.content) document.getElementById('content').value = d.content;
            if (d.pros) document.getElementById('pros').value = d.pros;
            if (d.cons) document.getElementById('cons').value = d.cons;
            if (d.verdict) document.getElementById('verdict').value = d.verdict;
            if (d.meta_title) document.getElementById('meta_title').value = d.meta_title;
            if (d.meta_description) document.getElementById('meta_description').value = d.meta_description;

            // Ajustar categoría si coincide
            if (d.target_category) {
                const catSelect = document.getElementById('target_category');
                for (let i = 0; i < catSelect.options.length; i++) {
                    if (catSelect.options[i].value.toLowerCase() === d.target_category.toLowerCase()) {
                        catSelect.selectedIndex = i;
                        break;
                    }
                }
            }

            // Si es proveedor externo y no está seleccionado en el select
            if (!providerId) {
                const provNameInput = document.getElementById('provider_name');
                if (provNameInput) provNameInput.value = name;
            }

            // Mensaje de éxito
            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = '✔ ¡Reseña editorial completada con éxito para <strong>' + name + '</strong>! Revisa los campos y pulsa "Guardar Reseña".';

            // Scroll suave al formulario
            document.getElementById('title').scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Redactar con IA';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '❌ Ocurrió un error inesperado al conectar con el asistente IA.';
        });
    }

    function generateSlug() {
        const title = document.getElementById('title').value;
        const slugInput = document.getElementById('slug');
        if (!slugInput.dataset.manual) {
            slugInput.value = title.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        }
    }

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manual = "1";
    });

    document.addEventListener('DOMContentLoaded', handleProviderChange);
</script>
@endsection

