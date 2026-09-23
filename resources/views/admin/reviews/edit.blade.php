@extends('layouts.admin')

@section('title', 'Editar Reseña Editorial')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Editar Reseña: {{ $review->title }}</h1>
        <p class="page-header-subtitle">Modifica el análisis, la calificación o la configuración de widgets y alternativas.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('reviews.show', $review->slug) }}" target="_blank" class="btn-secondary">
            <i data-lucide="external-link" style="width: 14px; height: 14px;"></i>
            <span>Ver en la Web</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Volver al Listado</span>
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
     ASISTENTE IA PARA REDACCIÓN / MEJORA EDITORIAL DE RESEÑAS
     ========================================================================= -->
<div class="form-panel" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(20, 24, 33, 0.95) 100%); border: 1.5px solid rgba(16, 185, 129, 0.35); box-shadow: 0 4px 24px rgba(16, 185, 129, 0.08); max-width: 960px; margin-bottom: 2rem;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.2rem 0.55rem; border-radius: 4px; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.06em;">
                <i data-lucide="sparkles" style="width: 12px; height: 12px;"></i>
                <span>Asistente de Redacción IA</span>
            </div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.01em;">Regenerar o Actualizar Análisis con IA</h2>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                Permite a la IA actualizar los datos técnicos, pruebas de velocidad, pros, contras, veredicto y metadatos SEO de esta reseña de forma automática.
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.5fr 1.5fr auto; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="color: var(--emerald-primary);">Proveedor para la Reseña</label>
            <input type="text" id="ai-review-name" value="{{ $review->provider ? $review->provider->name : ($review->provider_name ?? '') }}" placeholder="ej: Hostinger, SiteGround, Hetzner, Alexhost..." class="form-control" style="border-color: rgba(16, 185, 129, 0.3); background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Enfoque / Ángulo del Análisis (Opcional)</label>
            <input type="text" id="ai-review-focus" placeholder="ej: Hosting WordPress, VPS Cloud NVMe, Precios..." class="form-control" style="background-color: rgba(16, 20, 26, 0.9);">
        </div>

        <button type="button" id="btn-ai-review" onclick="generateReviewWithAI()" class="btn-primary" style="height: 40px; padding: 0 1.25rem; white-space: nowrap;">
            <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
            <span id="btn-ai-review-text">Regenerar con IA</span>
        </button>
    </div>

    <!-- Indicador de Carga y Mensajes del Asistente -->
    <div id="ai-review-status" style="display: none; margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.82rem;"></div>
</div>

<form action="{{ route('admin.reviews.update', $review) }}" method="POST" style="max-width: 960px;">
    @csrf
    @method('PUT')

    <!-- Panel 1: Vinculación con Proveedor y Tipo de Widget -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="link" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>1. Proveedor y Detección de Widget</span>
            </div>
            <p class="form-panel-desc">Define si esta reseña promociona directamente a un proveedor del catálogo o muestra 3 alternativas recomendadas.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Seleccionar Proveedor del Catálogo</label>
                <select name="provider_id" id="provider_id" class="form-control" onchange="handleProviderChange()">
                    <option value="">-- Proveedor Externo / No Listado en Catálogo --</option>
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}" data-active="{{ $p->active ? '1' : '0' }}" data-name="{{ $p->name }}" {{ old('provider_id', $review->provider_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->active ? 'Activo en Catálogo' : 'Inactivo' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="group-external-name" style="{{ old('provider_id', $review->provider_id) ? 'display: none;' : '' }}">
                <label class="form-label">Nombre del Proveedor Externo</label>
                <input type="text" name="provider_name" id="provider_name" value="{{ old('provider_name', $review->provider_name) }}" placeholder="ej. Alexhost, HostGator..." class="form-control" oninput="syncAiNameFromInput()">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Categoría para Alternativas Recomendadas (en caso de que el proveedor no esté publicado)</label>
            <select name="target_category" id="target_category" class="form-control">
                <option value="">-- Categoría General (Podio DebateHosting) --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ old('target_category', $review->target_category) == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->slug }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Indicador en tiempo real del Widget que se mostrará -->
        <div id="widget-preview-indicator" style="margin-top: 1rem; padding: 1rem; border-radius: 8px; border: 1px dashed rgba(245, 158, 11, 0.4); background: rgba(245, 158, 11, 0.08); display: flex; align-items: center; gap: 0.75rem;">
            <i data-lucide="shuffle" id="widget-icon" style="width: 20px; height: 20px; color: var(--amber-primary); flex-shrink: 0;"></i>
            <div style="font-size: 0.85rem;" id="widget-desc">
                <strong style="color: #FFFFFF;">Widget Activo:</strong>
                <span style="color: var(--text-muted);">Cargando estado del widget...</span>
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
            <p class="form-panel-desc">Estructura del análisis editorial.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Título de la Reseña <span style="color: var(--rose-primary);">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $review->title) }}" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label">Slug de la URL (amigable para SEO)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $review->slug) }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Puntuación Global (1.0 a 10.0) <span style="color: var(--rose-primary);">*</span></label>
                <input type="number" step="0.1" min="1" max="10" name="rating" id="rating" value="{{ old('rating', $review->rating) }}" class="form-control" style="font-family: var(--font-mono); font-size: 1.1rem; font-weight: 700; color: var(--emerald-primary);" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Resumen Ejecutivo / Lead</label>
            <textarea name="summary" id="summary" rows="3" class="form-control">{{ old('summary', $review->summary) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Cuerpo del Análisis Detallado <span style="color: var(--rose-primary);">*</span></label>
            <textarea name="content" id="content" rows="15" class="form-control" style="font-family: var(--font-sans); line-height: 1.7;" required>{{ old('content', $review->content) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div class="form-group">
                <label class="form-label" style="color: var(--emerald-primary);">Puntos Fuertes (Pros)</label>
                <textarea name="pros" id="pros" rows="4" class="form-control">{{ old('pros', $review->pros) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" style="color: var(--rose-primary);">Aspectos a Considerar (Contras)</label>
                <textarea name="cons" id="cons" rows="4" class="form-control">{{ old('cons', $review->cons) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Veredicto Final de la Redacción</label>
            <textarea name="verdict" id="verdict" rows="3" class="form-control">{{ old('verdict', $review->verdict) }}</textarea>
        </div>
    </div>

    <!-- Panel 3: SEO y Publicación -->
    <div class="form-panel">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="globe" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>3. Metadatos SEO y Publicación</span>
            </div>
            <p class="form-panel-desc">Ajustes para posicionamiento en motores de búsqueda.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Meta Título (SEO)</label>
            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $review->meta_title) }}" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Meta Descripción (SEO)</label>
            <textarea name="meta_description" id="meta_description" rows="2" class="form-control">{{ old('meta_description', $review->meta_description) }}</textarea>
        </div>


        <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; color: #FFFFFF; font-weight: 600;">
                <input type="checkbox" name="published" value="1" {{ old('published', $review->published) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--emerald-primary);">
                <span>Publicada en vivo</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; color: #FFFFFF; font-weight: 600;">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $review->featured) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--amber-primary);">
                <span>Destacar reseña</span>
            </label>
        </div>
    </div>

    <!-- Botón Guardar -->
    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
        <a href="{{ route('admin.reviews.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">
            <i data-lucide="save" style="width: 16px; height: 16px;"></i>
            <span>Actualizar Reseña</span>
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

        if (!confirm('¿Deseas que la IA actualice los campos del formulario con una nueva versión del análisis técnico para ' + name + '?')) {
            return;
        }

        btn.disabled = true;
        btnText.innerText = 'Regenerando con IA...';
        statusBox.style.display = 'block';
        statusBox.style.background = 'rgba(16, 185, 129, 0.08)';
        statusBox.style.border = '1px solid rgba(16, 185, 129, 0.25)';
        statusBox.style.color = '#A7F3D0';
        statusBox.innerHTML = '✦ Analizando arquitectura técnica de <strong>' + name + '</strong> y redactando actualización editorial...';

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
            btnText.innerText = 'Regenerar con IA';

            if (!res.success || !res.data) {
                statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
                statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
                statusBox.style.color = '#FECDD3';
                statusBox.innerHTML = '❌ ' + (res.message || 'Error al regenerar la reseña.');
                return;
            }

            const d = res.data;

            // Rellenar campos del formulario
            if (d.title) document.getElementById('title').value = d.title;
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

            // Mensaje de éxito
            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
            statusBox.style.color = '#6EE7B7';
            statusBox.innerHTML = '✔ ¡Reseña regenerada con éxito para <strong>' + name + '</strong>! Revisa los cambios y pulsa "Actualizar Reseña".';

            // Scroll suave al formulario
            document.getElementById('title').scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
        .catch(err => {
            btn.disabled = false;
            btnText.innerText = 'Regenerar con IA';
            statusBox.style.background = 'rgba(244, 63, 94, 0.12)';
            statusBox.style.border = '1px solid rgba(244, 63, 94, 0.3)';
            statusBox.style.color = '#FECDD3';
            statusBox.innerHTML = '❌ Ocurrió un error inesperado al conectar con el asistente IA.';
        });
    }

    document.addEventListener('DOMContentLoaded', handleProviderChange);
</script>
@endsection

