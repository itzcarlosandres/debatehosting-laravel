@extends('layouts.app')

@section('title', $review->meta_title ?: $review->title . ' — DebateHosting')
@section('meta_description', $review->meta_description ?: ($review->summary ?: Str::limit(strip_tags($review->content), 155)))

@section('content')
<article style="padding: 3rem 0 5rem 0;">
    <div class="container" style="max-width: 900px;">
        <!-- Migas de Pan -->
        <nav style="font-size: 0.82rem; color: var(--text-dim); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Inicio</a>
            <span>/</span>
            <a href="{{ route('reviews.index') }}" style="color: var(--text-muted); text-decoration: none;">Reseñas</a>
            <span>/</span>
            <span style="color: var(--text-main); font-weight: 600;">{{ Str::limit($review->title, 45) }}</span>
        </nav>

        <!-- Encabezado del Artículo -->
        <header style="margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <span style="font-family: var(--font-mono); font-size: 0.72rem; font-weight: 800; color: var(--emerald-primary); background: var(--emerald-subtle); border: 1px solid rgba(16, 185, 129, 0.25); padding: 0.25rem 0.65rem; border-radius: 4px; text-transform: uppercase;">
                    ★ AUDITORÍA EDITORIAL
                </span>

                @if($review->target_category)
                    <span style="font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: var(--text-muted); background: var(--bg-subtle); border: 1px solid var(--border-color); padding: 0.25rem 0.65rem; border-radius: 4px; text-transform: uppercase;">
                        {{ $review->target_category }}
                    </span>
                @endif
            </div>

            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; color: var(--text-main); line-height: 1.18; margin-bottom: 1.25rem; letter-spacing: -0.02em;">
                {{ $review->title }}
            </h1>

            <!-- Barra de Metadatos del Autor y Fecha -->
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--emerald-primary), #047857); display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 8px var(--emerald-glow);">
                        DH
                    </div>
                    <div>
                        <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-main);">Equipo Editorial DebateHosting</div>
                        <div style="font-size: 0.76rem; color: var(--text-dim); font-family: var(--font-mono);">
                            Actualizado el {{ $review->published_at ? $review->published_at->format('d M, Y') : $review->created_at->format('d M, Y') }}
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg-surface); border: 1px solid var(--border-color); padding: 0.4rem 0.85rem; border-radius: var(--radius-sm);">
                    <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono);">Calificación:</span>
                    <span style="font-family: var(--font-mono); font-size: 1.2rem; font-weight: 800; color: var(--emerald-primary);">
                        ★ {{ number_format($review->rating, 1) }}<span style="font-size: 0.75rem; color: var(--text-muted);">/10</span>
                    </span>
                </div>
            </div>
        </header>

        <!-- Resumen Lead Ejecutivo -->
        @if($review->summary)
        <div class="editorial-lead-card">
            <div class="editorial-lead-kicker">
                <i data-lucide="sparkles" style="width: 14px; height: 14px; color: var(--emerald-primary);"></i>
                <span>DICTAMEN EDITORIAL & RESUMEN EJECUTIVO</span>
            </div>
            <p class="editorial-lead-summary">
                {{ $review->summary }}
            </p>
        </div>
        @endif

        <!-- =====================================================================
             WIDGET INTELIGENTE DINÁMICO (OFICIAL O ALTERNATIVAS RECOMENDADAS)
             ===================================================================== -->
        <x-smart-review-widget 
            :review="$review" 
            :hasActiveProvider="$hasActiveProvider" 
            :alternatives="$alternatives" 
        />

        <!-- Cuerpo del Artículo Enriquecido para SEO con Secciones Destacadas -->
        <div class="review-article-body" style="margin-bottom: 3.5rem;">
            {!! $review->formatted_content !!}
        </div>

        <!-- Pros y Contras Visuales -->
        @if($review->pros || $review->cons)
        <section style="margin-bottom: 3rem;">
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="scale" style="width: 20px; height: 20px; color: var(--emerald-primary);"></i>
                <span>Puntos Fuertes y Aspectos a Considerar</span>
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <!-- Pros -->
                @if($review->pros)
                <div style="background-color: var(--bg-surface); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm);">
                    <h3 style="color: var(--emerald-primary); font-size: 1.02rem; font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="check-circle-2" style="width: 18px; height: 18px;"></i>
                        <span>Lo Mejor (Puntos Fuertes)</span>
                    </h3>
                    <div style="font-size: 0.92rem; line-height: 1.7; white-space: pre-line; color: var(--text-body);">
                        {{ $review->pros }}
                    </div>
                </div>
                @endif

                <!-- Contras -->
                @if($review->cons)
                <div style="background-color: var(--bg-surface); border: 1px solid rgba(244, 63, 94, 0.3); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm);">
                    <h3 style="color: var(--rose-primary); font-size: 1.02rem; font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i>
                        <span>Puntos a Mejorar (Contras)</span>
                    </h3>
                    <div style="font-size: 0.92rem; line-height: 1.7; white-space: pre-line; color: var(--text-body);">
                        {{ $review->cons }}
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif

        <!-- Veredicto Final -->
        @if($review->verdict)
        <section style="background: linear-gradient(145deg, rgba(16, 185, 129, 0.08) 0%, var(--bg-surface) 75%); border: 1.5px solid var(--emerald-primary); border-radius: var(--radius-lg); padding: 2.25rem; margin-bottom: 3.5rem; box-shadow: var(--shadow-md);">
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem;">
                <i data-lucide="award" style="width: 22px; height: 22px; color: var(--emerald-primary);"></i>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin: 0;">
                    Veredicto Final de DebateHosting
                </h2>
            </div>
            <p style="font-size: 1.02rem; line-height: 1.75; color: var(--text-body); margin-bottom: 1.5rem;">
                {{ $review->verdict }}
            </p>

            @if($hasActiveProvider && $review->provider)
            <div style="text-align: right;">
                <a href="{{ route('go', $review->provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-emerald" style="padding: 0.75rem 1.75rem; font-weight: 800;">
                    <span>Aprovechar Oferta en {{ $review->provider->name }}</span>
                    <i data-lucide="external-link" style="width: 14px; height: 14px;"></i>
                </a>
            </div>
            @endif
        </section>
        @endif

        <!-- Reseñas Relacionadas -->
        @if($relatedReviews->count() > 0)
        <section style="padding-top: 2.5rem; border-top: 1px solid var(--border-color);">
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="file-text" style="width: 18px; height: 18px; color: var(--emerald-primary);"></i>
                <span>Otros Análisis Recomendados</span>
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                @foreach($relatedReviews as $rel)
                <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">
                                {{ $rel->resolved_provider_name }}
                            </span>
                            <span style="font-family: var(--font-mono); font-size: 0.82rem; font-weight: 700; color: var(--emerald-primary);">
                                ★ {{ number_format($rel->rating, 1) }}
                            </span>
                        </div>
                        <h3 style="font-size: 0.98rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.75rem;">
                            <a href="{{ route('reviews.show', $rel->slug) }}" style="color: var(--text-main); text-decoration: none;">
                                {{ $rel->title }}
                            </a>
                        </h3>
                    </div>
                    <a href="{{ route('reviews.show', $rel->slug) }}" class="btn btn-secondary btn-sm" style="align-self: flex-start; margin-top: 0.5rem;">
                        <span>Leer Análisis</span>
                        <i data-lucide="arrow-right" style="width: 11px; height: 11px;"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</article>

<!-- Schema.org JSON-LD para SEO en Google -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@type": "Review",
  "itemReviewed": {
    "@type": "Product",
    "name": "{{ $review->resolved_provider_name }}"
  },
  "reviewRating": {
    "@type": "Rating",
    "ratingValue": "{{ $review->rating }}",
    "bestRating": "10",
    "worstRating": "1"
  },
  "headline": "{{ addslashes($review->title) }}",
  "author": {
    "@type": "Organization",
    "name": "DebateHosting"
  },
  "publisher": {
    "@type": "Organization",
    "name": "DebateHosting",
    "url": "{{ url('/') }}"
  },
  "datePublished": "{{ $review->published_at ? $review->published_at->toIso8601String() : $review->created_at->toIso8601String() }}"
}
</script>

@push('scripts')
<style>
    /* Tarjeta de Resumen Editorial */
    .editorial-lead-card {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%);
        border: 1px solid rgba(16, 185, 129, 0.25);
        border-left: 4px solid var(--emerald-primary);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.75rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }
    .editorial-lead-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--emerald-primary);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }
    .editorial-lead-summary {
        font-size: 1.15rem;
        line-height: 1.75;
        color: var(--text-main);
        font-weight: 500;
        margin: 0;
    }

    /* Contenedor Unificado del Artículo */
    .review-article-body {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        margin-bottom: 3.5rem;
    }

    /* Tarjeta individual de cada bloque de análisis técnico */
    .editorial-card-section {
        background: var(--bg-surface, #FFFFFF);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: var(--radius-lg, 14px);
        padding: 2rem 2.25rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }
    .editorial-card-section:hover {
        border-color: rgba(16, 185, 129, 0.35);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }

    /* Cabecera del bloque: número de sección alineado con el título */
    .editorial-card-header {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-subtle, #F1F5F9);
    }

    /* Píldora con número de auditoría (01, 02, etc.) sin la palabra 'SECCIÓN' */
    .editorial-num-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(16, 185, 129, 0.1);
        color: var(--emerald-primary, #059669);
        border: 1px solid rgba(16, 185, 129, 0.25);
        font-family: var(--font-mono, monospace);
        font-weight: 800;
        font-size: 0.85rem;
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
        flex-shrink: 0;
        letter-spacing: 0.02em;
    }

    /* Título de la tarjeta editorial */
    .editorial-card-title {
        font-size: 1.18rem;
        font-weight: 800;
        color: var(--text-main, #0F172A);
        line-height: 1.35;
        margin: 0;
        letter-spacing: -0.015em;
    }

    /* Cuerpo y párrafos */
    .editorial-card-body {
        font-size: 1rem;
        line-height: 1.85;
        color: var(--text-body, #334155);
    }

    .editorial-p {
        font-size: 1.02rem;
        line-height: 1.85;
        color: var(--text-body, #334155);
        margin-bottom: 1.15rem;
    }
    .editorial-p:last-child {
        margin-bottom: 0;
    }

    /* Lista de viñetas en la auditoría */
    .editorial-list-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.75rem 1rem;
        background: var(--bg-subtle, #F8FAFC);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 8px;
        margin-bottom: 0.65rem;
        font-size: 0.95rem;
        line-height: 1.65;
        color: var(--text-main, #1E293B);
    }

    /* Resaltados SEO Enriquecidos */
    .seo-keyword {
        color: #047857;
        font-weight: 700;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.25);
        padding: 0.1rem 0.45rem;
        border-radius: 5px;
        font-size: 0.92em;
        display: inline-block;
        vertical-align: baseline;
    }

    .seo-highlight {
        color: var(--text-main, #0F172A);
        font-weight: 700;
        background: rgba(245, 158, 11, 0.1);
        border-bottom: 2px solid #F59E0B;
        padding: 0.05rem 0.3rem;
        border-radius: 3px;
        display: inline;
    }

    @media (max-width: 640px) {
        .editorial-card-section {
            padding: 1.35rem 1.15rem;
        }
        .editorial-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .editorial-card-title {
            font-size: 1.08rem;
        }
        .editorial-p {
            font-size: 0.96rem;
            line-height: 1.75;
        }
    }
</style>
<script>
    function copyVoucher(code, redirectUrl) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                if (window.showToast) {
                    window.showToast('¡Cupón "' + code + '" copiado! Abriendo sitio oficial...', 'success');
                } else {
                    alert('¡Cupón "' + code + '" copiado!');
                }
                setTimeout(() => {
                    window.open(redirectUrl, '_blank');
                }, 800);
            });
        } else {
            window.open(redirectUrl, '_blank');
        }
    }
</script>
@endpush
@endsection
