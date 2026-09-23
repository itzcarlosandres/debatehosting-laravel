<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Provider>
 */
class ProviderFactory extends Factory
{
    protected $model = Provider::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'slug' => Str::slug($name),
            'name' => $name,
            'logo_url' => null,
            'categories' => ['hosting-web', 'vps'],
            'plan' => 'Plan Estándar',
            'price_from' => fake()->randomFloat(2, 1, 10),
            'price_before' => fake()->randomFloat(2, 11, 20),
            'period' => 'mes',
            'score_precio' => 9.2,
            'score_rendimiento' => 9.0,
            'score_soporte' => 8.8,
            'score_facilidad' => 9.1,
            'uptime' => 99.95,
            'affiliate_url' => 'https://example.com/go/affiliate',
            'active' => true,
            'clicks' => 0,
            'badge' => 'Top Elección',
            'badge_color' => 'green',
            'description' => fake()->paragraph(),
            'pros' => "• Alto rendimiento NVMe\n• Soporte 24/7 en español",
            'cons' => '• Precio de renovación superior',
            'verdict' => 'Ideal para proyectos en crecimiento.',
            'meta_title' => $name.' Hosting Review',
            'meta_description' => 'Auditoría completa de '.$name,
        ];
    }
}
