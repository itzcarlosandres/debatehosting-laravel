@extends('layouts.admin')

@section('title', 'Configuración de Portada y Sitio — DebateHosting Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 700;">Configuración de Portada y Sitio</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Personaliza los textos del Hero principal, Podio de recomendados y datos del sitio.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" style="max-width: 900px;">
    @csrf

    <!-- Bloque 1: General del Sitio -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            🏷️ Datos Generales
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Nombre del Sitio</label>
                <input type="text" name="siteName" class="form-control" value="{{ old('siteName', $siteName) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email de Contacto</label>
                <input type="email" name="contactEmail" class="form-control" value="{{ old('contactEmail', $contactEmail) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Subtítulo / Tagline</label>
            <input type="text" name="siteTagline" class="form-control" value="{{ old('siteTagline', $siteTagline) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Aviso de Afiliación (Footer)</label>
            <textarea name="disclosureNotice" rows="2" class="form-control">{{ old('disclosureNotice', $disclosureNotice) }}</textarea>
        </div>
    </div>

    <!-- Bloque 2: Textos del Hero Principal -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            ✨ Hero Principal (Cabecera de Portada)
        </h2>

        <div class="form-group">
            <label class="form-label">Antetítulo (Kicker con punto verde)</label>
            <input type="text" name="hero[kickerText]" class="form-control" value="{{ old('hero.kickerText', $hero['kickerText'] ?? 'OBSERVATORIO DE HOSTING Y NUBE') }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label">Título Parte 1</label>
                <input type="text" name="hero[titleBefore]" class="form-control" value="{{ old('hero.titleBefore', $hero['titleBefore'] ?? 'El gran') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Palabra Destacada (Cursiva)</label>
                <input type="text" name="hero[titleHighlight]" class="form-control" value="{{ old('hero.titleHighlight', $hero['titleHighlight'] ?? 'debate') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Título Parte 2</label>
                <input type="text" name="hero[titleAfter]" class="form-control" value="{{ old('hero.titleAfter', $hero['titleAfter'] ?? 'del hosting.') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción Editorial del Hero</label>
            <textarea name="hero[description]" rows="3" class="form-control">{{ old('hero.description', $hero['description'] ?? '') }}</textarea>
        </div>
    </div>

    <!-- Bloque 3: El Podio (Top 3 Picks) -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            🏆 El Podio (Los 3 Elegidos de la Portada)
        </h2>

        @for($i = 1; $i <= 3; $i++)
            @php $currentPick = $picks->firstWhere('position', $i); @endphp
            <div style="background: #0F172A; border: 1px solid var(--border-color); border-radius: 6px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    Posición #{{ $i }} {{ $i == 1 ? '🥇 (Oro)' : ($i == 2 ? '🥈 (Plata)' : '🥉 (Bronce)') }}
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Proveedor Seleccionado</label>
                        <select name="picks[{{ $i }}][provider_id]" class="form-control">
                            @foreach($providers as $p)
                                <option value="{{ $p->id }}" {{ ($currentPick && $currentPick->provider_id == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} (${{ $p->price_from }}/mes)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Etiqueta Superior</label>
                        <input type="text" name="picks[{{ $i }}][tag]" class="form-control" value="{{ $currentPick ? $currentPick->tag : 'DESTACADO EDITORIAL' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Titular del Veredicto</label>
                    <input type="text" name="picks[{{ $i }}][titulo]" class="form-control" value="{{ $currentPick ? $currentPick->titulo : '' }}">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Explicación del Veredicto</label>
                    <textarea name="picks[{{ $i }}][veredicto]" rows="2" class="form-control">{{ $currentPick ? $currentPick->veredicto : '' }}</textarea>
                </div>
            </div>
        @endfor
    </div>

    <div style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn-action" style="font-size: 1rem; padding: 0.8rem 2rem;">
            💾 Guardar Todas las Configuraciones
        </button>
    </div>
</form>
@endsection
