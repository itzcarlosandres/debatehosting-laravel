<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'slug',
        'provider_name',
        'target_category',
        'rating',
        'summary',
        'content',
        'pros',
        'cons',
        'verdict',
        'meta_title',
        'meta_description',
        'published',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'rating' => 'float',
        'published' => 'boolean',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Verifica si el proveedor asociado existe y está publicado en el catálogo.
     */
    public function hasActiveProvider(): bool
    {
        return $this->provider !== null && (bool) $this->provider->active;
    }

    /**
     * Nombre público del proveedor analizado.
     */
    public function getResolvedProviderNameAttribute(): string
    {
        return $this->provider?->name ?? $this->provider_name ?? 'Proveedor';
    }

    /**
     * Obtiene los proveedores alternativos recomendados cuando el proveedor analizado no está activo.
     *
     * @return Collection<int, Provider>
     */
    public function getRecommendedAlternatives(int $limit = 3): Collection
    {
        $query = Provider::query()->where('active', true);

        if ($this->provider_id) {
            $query->where('id', '!=', $this->provider_id);
        }

        if ($this->target_category) {
            $categoryMatches = (clone $query)
                ->whereJsonContains('categories', $this->target_category)
                ->orderByDesc('score_rendimiento')
                ->take($limit)
                ->get();

            if ($categoryMatches->count() >= $limit) {
                return $categoryMatches;
            }

            $needed = $limit - $categoryMatches->count();
            $excludeIds = $categoryMatches->pluck('id')->all();
            if ($this->provider_id) {
                $excludeIds[] = $this->provider_id;
            }

            $fallbacks = Provider::where('active', true)
                ->whereNotIn('id', $excludeIds)
                ->orderByDesc('score_precio')
                ->take($needed)
                ->get();

            return $categoryMatches->concat($fallbacks);
        }

        return $query->orderByDesc('score_precio')
            ->orderByDesc('score_rendimiento')
            ->take($limit)
            ->get();
    }

    /**
     * Renderizado enriquecido y optimizado para SEO del contenido editorial
     */
    public function getFormattedContentAttribute(): string
    {
        $raw = $this->content;
        if (empty($raw)) {
            return '';
        }

        // Normalizar saltos de línea
        $text = str_replace(["\r\n", "\r"], "\n", trim($raw));
        $lines = explode("\n", $text);

        $html = '';
        $currentSection = false;
        $paragraphBuffer = [];

        $flushParagraph = function () use (&$html, &$paragraphBuffer) {
            if (! empty($paragraphBuffer)) {
                $pText = trim(implode(' ', $paragraphBuffer));
                if (! empty($pText)) {
                    $enriched = $this->enrichSeoText(e($pText));
                    $html .= '<p class="editorial-p">' . $enriched . '</p>';
                }
                $paragraphBuffer = [];
            }
        };

        $closeSection = function () use (&$html, &$currentSection, $flushParagraph) {
            $flushParagraph();
            if ($currentSection) {
                $html .= '</div></div>';
                $currentSection = false;
            }
        };

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                $flushParagraph();
                continue;
            }

            // Detectar encabezados numerados (ej: "1. INFRAESTRUCTURA..." o "## 1. Titulo" o "SECCIÓN 1: ...")
            if (preg_match('/^(?:#{1,4}\s*)?(?:(?:SECCI[ÓO]N\s*)?(\d+)[\.:\)\-]\s*)?([A-ZÁÉÍÓÚÑ0-9\s,\-\/¿\?:]{5,100})$/ui', $trimmed, $matches) && ! str_ends_with($trimmed, '.')) {
                $closeSection();
                $num = ! empty($matches[1]) ? str_pad($matches[1], 2, '0', STR_PAD_LEFT) : null;
                $title = trim($matches[2]);
                // Eliminar cualquier residuo de la palabra SECCIÓN o números al inicio del título
                $title = trim(preg_replace('/^SECCI[ÓO]N\s*\d*[:\.\-]?\s*/ui', '', $title));
                $title = trim(preg_replace('/^\d+[\.\)]\s*/u', '', $title));

                $numPill = $num
                    ? '<span class="editorial-num-pill">' . $num . '</span>'
                    : '<span class="editorial-num-pill"><i data-lucide="shield-check" style="width: 14px; height: 14px;"></i></span>';

                $html .= '<div class="editorial-card-section">';
                $html .= '<div class="editorial-card-header">';
                $html .= $numPill;
                $html .= '<h3 class="editorial-card-title">' . e($title) . '</h3>';
                $html .= '</div>';
                $html .= '<div class="editorial-card-body">';
                $currentSection = true;
                continue;
            }

            // Detectar viñetas o listas con guiones
            if (preg_match('/^[•\-\*]\s+(.+)$/u', $trimmed, $m)) {
                $flushParagraph();
                $itemText = $this->enrichSeoText(e(trim($m[1])));
                $html .= '<div class="editorial-list-item"><i data-lucide="check" style="width: 16px; height: 16px; color: var(--emerald-primary); flex-shrink: 0; margin-top: 3px;"></i><span>' . $itemText . '</span></div>';
                continue;
            }

            // Párrafo normal
            $paragraphBuffer[] = $trimmed;
        }

        $closeSection();

        return $html;
    }

    /**
     * Enriquecer texto resaltando negritas Markdown y entidades clave para SEO
     */
    protected function enrichSeoText(string $text): string
    {
        // 1. Markdown bold (**texto** o __texto__)
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong class="seo-highlight">$1</strong>', $text);
        $text = preg_replace('/__(.+?)__/s', '<strong class="seo-highlight">$1</strong>', $text);

        // 2. Términos técnicos y palabras clave SEO que deben resaltar visualmente
        $keywords = [
            'SSD NVMe',
            'NVMe',
            'LiteSpeed',
            'cPanel',
            'KVM',
            'anti-DDoS',
            'Bitcoin',
            'Monero',
            'USDT',
            'criptomonedas',
            'DMCA',
            'centro de datos',
            'uptime',
            'TTFB',
            'offshore',
            'libertad de expresión',
            'privacidad de datos',
            'WordPress',
            'Cloudflare',
            'Google Cloud',
            'Nginx',
            'Apache',
        ];

        // Resaltar palabras clave únicamente fuera de tags HTML existentes
        $escapedKeywords = array_map(fn ($k) => preg_quote($k, '/'), $keywords);
        $regex = '/<[^>]+>|(\b(?:' . implode('|', $escapedKeywords) . ')\b)/iu';

        $text = preg_replace_callback($regex, function ($m) {
            if (! isset($m[1]) || $m[1] === '') {
                return $m[0];
            }

            return '<strong class="seo-keyword">' . $m[1] . '</strong>';
        }, $text);

        return $text;
    }
}

