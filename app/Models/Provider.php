<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'logo_url',
        'categories',
        'plan',
        'price_from',
        'price_before',
        'period',
        'score_precio',
        'score_rendimiento',
        'score_soporte',
        'score_facilidad',
        'uptime',
        'affiliate_url',
        'active',
        'clicks',
        'badge',
        'badge_color',
        'description',
        'pros',
        'cons',
        'verdict',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'categories' => 'array',
        'active' => 'boolean',
        'price_from' => 'float',
        'price_before' => 'float',
        'score_precio' => 'float',
        'score_rendimiento' => 'float',
        'score_soporte' => 'float',
        'score_facilidad' => 'float',
        'uptime' => 'float',
        'clicks' => 'integer',
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function picks(): HasMany
    {
        return $this->hasMany(Pick::class);
    }

    public function clickEvents(): HasMany
    {
        return $this->hasMany(ClickEvent::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function latestReview(): HasOne
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProviderProduct::class)->orderBy('order')->orderBy('price_from');
    }

    public function featuredProducts(): HasMany
    {
        return $this->hasMany(ProviderProduct::class)->where('is_featured', true)->orderBy('order');
    }

    /**
     * Obtener el producto correspondiente a una categoría dada, o el primero/destacado por defecto
     */
    public function getProductForCategory(?string $categorySlug = null): ?ProviderProduct
    {
        if ($categorySlug) {
            $product = $this->products->firstWhere('category_slug', $categorySlug);
            if ($product) {
                return $product;
            }
        }

        return $this->products->firstWhere('is_featured', true) ?: $this->products->first();
    }

    /**
     * Score global promedio (sobre 10)
     */
    public function getOverallScoreAttribute(): float
    {
        $avg = ($this->score_precio + $this->score_rendimiento + $this->score_soporte + $this->score_facilidad) / 4;

        return round($avg, 1);
    }

    /**
     * URL normalizada para la imagen del logo
     */
    public function getResolvedLogoUrlAttribute(): ?string
    {
        if (empty($this->logo_url)) {
            return null;
        }

        // Si ya es una URL externa completa
        if (Str::startsWith($this->logo_url, ['http://', 'https://'])) {
            return $this->logo_url;
        }

        // Si es una ruta relativa que apunta a /uploads/... o /storage/...
        if (Str::startsWith($this->logo_url, '/storage/')) {
            return asset(ltrim($this->logo_url, '/'));
        }

        if (Str::startsWith($this->logo_url, '/uploads/logos/')) {
            $filename = basename($this->logo_url);

            return asset('storage/logos/'.$filename);
        }

        return asset('storage/'.ltrim($this->logo_url, '/'));
    }

    /**
     * Porcentaje de descuento calculado
     */
    public function getDiscountPercentAttribute(): int
    {
        if ($this->price_before > $this->price_from && $this->price_before > 0) {
            return (int) round((($this->price_before - $this->price_from) / $this->price_before) * 100);
        }

        return 0;
    }
}
