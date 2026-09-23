@extends('layouts.admin')

@section('title', 'Portada & Secciones Editoriales')

@section('content')
<!-- Encabezado de Página -->
<div class="admin-page-header">
    <div>
        <h1 class="page-header-title">Portada & Secciones Editoriales</h1>
        <p class="page-header-subtitle">
            Edita los textos de la página de inicio (Hero, Duelo, selecciones de Los Elegidos, Tabla Comparativa y Metodología).
        </p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('home') }}" target="_blank" class="btn-secondary">
            <i data-lucide="external-link" style="width: 14px; height: 14px;"></i>
            <span>Ver Portada en Vivo</span>
        </a>
    </div>
</div>

<form action="{{ route('admin.sections.update') }}" method="POST">
    @csrf

    <!-- =========================================================================
         1. HERO PRINCIPAL (PORTADA)
         ========================================================================= -->
    <div class="form-panel" id="hero" style="margin-bottom: 2rem;">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="sparkles" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>1. Cabecera Hero Principal (Arriba del Todo)</span>
            </div>
            <p class="form-panel-desc">Textos de apertura y bienvenida que reciben al usuario en la portada.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Antetítulo (Kicker Superior)</label>
            <input type="text" name="hero[kickerText]" class="form-control" value="{{ old('hero.kickerText', $hero['kickerText'] ?? 'OBSERVATORIO DE HOSTING Y NUBE') }}">
            <span class="form-helper">Texto en mayúsculas pequeñas sobre el título principal.</span>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Título Parte 1</label>
                <input type="text" name="hero[titleBefore]" class="form-control" value="{{ old('hero.titleBefore', $hero['titleBefore'] ?? 'El observatorio técnico de') }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Palabra Destacada (Verde)</label>
                <input type="text" name="hero[titleHighlight]" class="form-control" value="{{ old('hero.titleHighlight', $hero['titleHighlight'] ?? 'hosting y servidores') }}" style="color: var(--emerald-primary); font-weight: 700;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Título Parte 2 (Final)</label>
                <input type="text" name="hero[titleAfter]" class="form-control" value="{{ old('hero.titleAfter', $hero['titleAfter'] ?? '.') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción Editorial Principal</label>
            <textarea name="hero[description]" rows="3" class="form-control">{{ old('hero.description', $hero['description'] ?? 'Comparamos proveedores en vivo con pruebas empíricas de latencia TTFB, tiempos de respuesta y estrés de CPU. Sin sesgos comerciales ni puestos comprados.') }}</textarea>
        </div>

        <!-- Botones CTA del Hero -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
            <div>
                <label class="form-label" style="color: var(--emerald-primary);">Botón Primario (Verde)</label>
                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 0.75rem;">
                    <input type="text" name="hero[primaryBtnText]" class="form-control" placeholder="Texto botón" value="{{ old('hero.primaryBtnText', $hero['primaryBtnText'] ?? 'Pesar en La Balanza') }}">
                    <input type="text" name="hero[primaryBtnUrl]" class="form-control" placeholder="Enlace URL" value="{{ old('hero.primaryBtnUrl', $hero['primaryBtnUrl'] ?? '/balanza') }}">
                </div>
            </div>

            <div>
                <label class="form-label" style="color: #CBD5E1;">Botón Secundario (Borde)</label>
                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 0.75rem;">
                    <input type="text" name="hero[secondaryBtnText]" class="form-control" placeholder="Texto botón" value="{{ old('hero.secondaryBtnText', $hero['secondaryBtnText'] ?? 'Ver Todos los Proveedores') }}">
                    <input type="text" name="hero[secondaryBtnUrl]" class="form-control" placeholder="Enlace URL" value="{{ old('hero.secondaryBtnUrl', $hero['secondaryBtnUrl'] ?? '/proveedores') }}">
                </div>
            </div>
        </div>

        <!-- Métricas / Contadores del Hero -->
        <div style="padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                Etiquetas de la Barra de Telemetría (4 Bloques)
            </div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;">
                <div>
                    <label class="form-label" style="font-size: 0.75rem;">Métrica 1 (Automático)</label>
                    <input type="text" name="hero[counter1Label]" class="form-control" value="{{ old('hero.counter1Label', $hero['counter1Label'] ?? 'Proveedores auditados') }}">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.75rem;">Métrica 2 (Automático)</label>
                    <input type="text" name="hero[counter2Label]" class="form-control" value="{{ old('hero.counter2Label', $hero['counter2Label'] ?? 'Cupones verificados') }}">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.75rem;">Métrica 3 (Fijo 100%)</label>
                    <input type="text" name="hero[counter3Label]" class="form-control" value="{{ old('hero.counter3Label', $hero['counter3Label'] ?? 'Metodología abierta') }}">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.75rem;">Métrica 4 (Fijo 0)</label>
                    <input type="text" name="hero[counter4Label]" class="form-control" value="{{ old('hero.counter4Label', $hero['counter4Label'] ?? 'Patrocinios encubiertos') }}">
                </div>
            </div>
        </div>

        <!-- Duelo Lateral del Hero -->
        <div style="padding-top: 1.25rem; margin-top: 1.25rem; border-top: 1px solid var(--border-subtle);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #FFFFFF; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="swords" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                <span>Tarjeta Lateral: Duelo Editorial en Vivo</span>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
                <div>
                    <label class="form-label">Título del Duelo</label>
                    <input type="text" name="hero[previewTitle]" class="form-control" value="{{ old('hero.previewTitle', $hero['previewTitle'] ?? 'Duelo Editorial en Vivo') }}">
                </div>
                <div>
                    <label class="form-label">Contendiente 1 (Nombre & Precio)</label>
                    <input type="text" name="hero[fighter1Name]" class="form-control" placeholder="Nombre" value="{{ old('hero.fighter1Name', $hero['fighter1Name'] ?? 'Hostinger') }}" style="margin-bottom: 0.35rem;">
                    <input type="text" name="hero[fighter1Price]" class="form-control" placeholder="Precio" value="{{ old('hero.fighter1Price', $hero['fighter1Price'] ?? '$2.49/mes') }}">
                </div>
                <div>
                    <label class="form-label">Contendiente 2 (Nombre & Precio)</label>
                    <input type="text" name="hero[fighter2Name]" class="form-control" placeholder="Nombre" value="{{ old('hero.fighter2Name', $hero['fighter2Name'] ?? 'SiteGround') }}" style="margin-bottom: 0.35rem;">
                    <input type="text" name="hero[fighter2Price]" class="form-control" placeholder="Precio" value="{{ old('hero.fighter2Price', $hero['fighter2Price'] ?? '$2.99/mes') }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
                <div>
                    <label class="form-label">Métrica 1 (Nombre y Comparación)</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="hero[metric1Label]" class="form-control" value="{{ old('hero.metric1Label', $hero['metric1Label'] ?? 'Velocidad TTFB & Carga') }}">
                        <input type="text" name="hero[metric1Scores]" class="form-control" style="width: 140px; font-family: var(--font-mono);" value="{{ old('hero.metric1Scores', $hero['metric1Scores'] ?? '9.4 vs 8.9') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label">Métrica 2 (Nombre y Comparación)</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="hero[metric2Label]" class="form-control" value="{{ old('hero.metric2Label', $hero['metric2Label'] ?? 'Soporte Técnico 24/7') }}">
                        <input type="text" name="hero[metric2Scores]" class="form-control" style="width: 140px; font-family: var(--font-mono);" value="{{ old('hero.metric2Scores', $hero['metric2Scores'] ?? '7.8 vs 9.5') }}">
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Veredicto de la Redacción (Pie del Duelo)</label>
                <textarea name="hero[previewVerdict]" rows="2" class="form-control">{{ old('hero.previewVerdict', $hero['previewVerdict'] ?? 'Si buscas máxima economía y discos NVMe veloces, Hostinger lidera. Si tu prioridad es soporte instantáneo en español y WordPress de élite, SiteGround gana el duelo.') }}</textarea>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         2. LOS ELEGIDOS / EL PODIO EDITORIAL (TOP 3)
         ========================================================================= -->
    <div class="form-panel" id="podio" style="margin-bottom: 2rem;">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="trophy" style="width: 18px; height: 18px; color: #F59E0B;"></i>
                <span>2. Los Elegidos — El Podio Editorial (Top 3 Selecciones)</span>
            </div>
            <p class="form-panel-desc">Define los 3 proveedores destacados en las posiciones Oro, Plata y Bronce de la portada.</p>
        </div>

        <!-- Textos de Cabecera del Podio -->
        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF; text-transform: uppercase; margin-bottom: 0.85rem;">
                Encabezado de la Sección del Podio
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem; margin-bottom: 0.75rem;">
                <div>
                    <label class="form-label">Kicker Superior</label>
                    <input type="text" name="sectionHeaders[podio][kicker]" class="form-control" value="{{ old('sectionHeaders.podio.kicker', $sectionHeaders['podio']['kicker'] ?? 'SELECCIÓN DIRECTA DE LA REDACCIÓN') }}">
                </div>
                <div>
                    <label class="form-label">Título Principal de la Sección</label>
                    <input type="text" name="sectionHeaders[podio][titleBefore]" class="form-control" value="{{ old('sectionHeaders.podio.titleBefore', $sectionHeaders['podio']['titleBefore'] ?? 'El Podio Editorial') }}">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Subtítulo / Bajada Descriptiva</label>
                <input type="text" name="sectionHeaders[podio][subtitle]" class="form-control" value="{{ old('sectionHeaders.podio.subtitle', $sectionHeaders['podio']['subtitle'] ?? 'Los tres mejores servicios clasificados por categoría tras cientos de horas de auditoría técnica.') }}">
            </div>
        </div>

        <!-- Asignación de los 3 Puestos -->
        @php
            $podiumSlots = [
                1 => ['name' => 'Oro (#1 Primer Puesto)', 'color' => '#F59E0B', 'defaultTag' => 'MEJOR OPCIÓN GLOBAL'],
                2 => ['name' => 'Plata (#2 Segundo Puesto)', 'color' => '#94A3B8', 'defaultTag' => 'VELOCIDAD TTFB'],
                3 => ['name' => 'Bronce (#3 Tercer Puesto)', 'color' => '#D97706', 'defaultTag' => 'CALIDAD / PRECIO'],
            ];
        @endphp

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem;">
            @foreach($podiumSlots as $pos => $slot)
                @php
                    $currentPick = $picks->firstWhere('position', $pos);
                @endphp
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1.25rem;">
                    <div style="font-size: 0.88rem; font-weight: 800; color: {{ $slot['color'] }}; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i data-lucide="award" style="width: 16px; height: 16px;"></i>
                        <span>{{ $slot['name'] }}</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Proveedor Asignado *</label>
                        <select name="picks[{{ $pos }}][provider_id]" class="form-control" required>
                            <option value="">-- Seleccionar Proveedor --</option>
                            @foreach($providers as $prov)
                                <option value="{{ $prov->id }}" {{ ($currentPick && $currentPick->provider_id == $prov->id) ? 'selected' : '' }}>
                                    {{ $prov->name }} (Score: {{ $prov->overall_score }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Distintivo / Tag</label>
                        <input type="text" name="picks[{{ $pos }}][tag]" class="form-control" value="{{ old('picks.'.$pos.'.tag', $currentPick->tag ?? $slot['defaultTag']) }}" placeholder="ej: MEJOR OPCIÓN GLOBAL">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Título de Recomendación</label>
                        <input type="text" name="picks[{{ $pos }}][titulo]" class="form-control" value="{{ old('picks.'.$pos.'.titulo', $currentPick->titulo ?? '') }}" placeholder="ej: El equilibrio perfecto">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Veredicto Resumido</label>
                        <textarea name="picks[{{ $pos }}][veredicto]" rows="3" class="form-control" placeholder="¿Por qué la redacción lo recomienda en esta posición?">{{ old('picks.'.$pos.'.veredicto', $currentPick->veredicto ?? '') }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         3. TABLA COMPARATIVA DE RENDIMIENTO
         ========================================================================= -->
    <div class="form-panel" id="tabla" style="margin-bottom: 2rem;">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="table" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <span>3. Tabla Comparativa de Rendimiento (Sección Central)</span>
            </div>
            <p class="form-panel-desc">Títulos de la tabla que ordena y clasifica a todos los proveedores recién agregados.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Kicker Superior</label>
                <input type="text" name="sectionHeaders[tabla][kicker]" class="form-control" value="{{ old('sectionHeaders.tabla.kicker', $sectionHeaders['tabla']['kicker'] ?? 'AUDITORÍA DIRECTA • ORDENADOS POR INCORPORACIÓN RECIENTE') }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Título Principal</label>
                <input type="text" name="sectionHeaders[tabla][titleBefore]" class="form-control" value="{{ old('sectionHeaders.tabla.titleBefore', $sectionHeaders['tabla']['titleBefore'] ?? 'Tabla Comparativa de Rendimiento') }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Subtítulo Descriptivo</label>
            <input type="text" name="sectionHeaders[tabla][subtitle]" class="form-control" value="{{ old('sectionHeaders.tabla.subtitle', $sectionHeaders['tabla']['subtitle'] ?? 'Datos consolidados de proveedores ordenados por los últimos añadidos a nuestro observatorio técnico, con hardware y latencias probadas.') }}">
        </div>
    </div>

    <!-- =========================================================================
         4. METODOLOGÍA & BOLETÍN NEWSLETTER
         ========================================================================= -->
    <div class="form-panel" id="otros" style="margin-bottom: 2rem;">
        <div class="form-panel-header">
            <div class="form-panel-title">
                <i data-lucide="layers" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>4. Metodología de Auditoría & Boletín</span>
            </div>
            <p class="form-panel-desc">Encabezados de las secciones de rigor técnico y suscripción.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Metodología -->
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1.25rem;">
                <div style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF; text-transform: uppercase; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="shield-check" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                    <span>Metodología Editorial</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Kicker</label>
                    <input type="text" name="sectionHeaders[metodo][kicker]" class="form-control" value="{{ old('sectionHeaders.metodo.kicker', $sectionHeaders['metodo']['kicker'] ?? 'INDEPENDENCIA Y RIGOR') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Título</label>
                    <input type="text" name="sectionHeaders[metodo][titleBefore]" class="form-control" value="{{ old('sectionHeaders.metodo.titleBefore', $sectionHeaders['metodo']['titleBefore'] ?? 'Nuestra Metodología de Auditoría') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Subtítulo</label>
                    <textarea name="sectionHeaders[metodo][subtitle]" rows="2" class="form-control">{{ old('sectionHeaders.metodo.subtitle', $sectionHeaders['metodo']['subtitle'] ?? 'Cuatro pilares técnicos en los que basamos cada puntuación y veredicto del observatorio.') }}</textarea>
                </div>
            </div>

            <!-- Newsletter -->
            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 6px; padding: 1.25rem;">
                <div style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF; text-transform: uppercase; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="mail" style="width: 14px; height: 14px; color: var(--sky-primary);"></i>
                    <span>Caja de Newsletter</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Título Principal</label>
                    <input type="text" name="sectionHeaders[news][titleBefore]" class="form-control" value="{{ old('sectionHeaders.news.titleBefore', $sectionHeaders['news']['titleBefore'] ?? 'Suscríbete al Boletín Técnico de Hosting') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Subtítulo Descriptivo</label>
                    <textarea name="sectionHeaders[news][subtitle]" rows="4" class="form-control">{{ old('sectionHeaders.news.subtitle', $sectionHeaders['news']['subtitle'] ?? 'Recibe semanalmente alertas de caídas masivas de servidores, auditorías de nuevos proveedores y cupones exclusivos probados.') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Guardar Cambios -->
    <div style="display: flex; justify-content: flex-end; margin-top: 2rem; margin-bottom: 3rem;">
        <button type="submit" class="btn-primary" style="padding: 0.85rem 2.25rem; font-size: 0.95rem; font-weight: 700;">
            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
            <span>Guardar Textos de la Portada</span>
        </button>
    </div>
</form>
@endsection
