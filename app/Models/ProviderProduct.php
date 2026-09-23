<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'category_slug',
        'plan_name',
        'price_from',
        'price_before',
        'period',
        'specs',
        'affiliate_url',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'price_from' => 'float',
        'price_before' => 'float',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Resuelve la URL de afiliado: la del producto o la del proveedor
     */
    public function getResolvedAffiliateUrlAttribute(): ?string
    {
        return ! empty($this->affiliate_url)
            ? $this->affiliate_url
            : $this->provider?->affiliate_url;
    }

    /**
     * Porcentaje de descuento si hay price_before
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->price_before > $this->price_from && $this->price_before > 0) {
            return (int) round((($this->price_before - $this->price_from) / $this->price_before) * 100);
        }

        return null;
    }
}
