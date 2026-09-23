<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $title = 'Reseña Completa de '.fake()->company();

        return [
            'provider_id' => Provider::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'provider_name' => null,
            'target_category' => 'hosting-web',
            'rating' => 9.4,
            'summary' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'pros' => "• Servidores ultrarrápidos\n• Excelente soporte técnico",
            'cons' => '• Sin soporte telefónico en algunos países',
            'verdict' => 'Una de las opciones más recomendadas de este año.',
            'meta_title' => $title,
            'meta_description' => 'Análisis editorial detallado y pruebas de velocidad.',
            'published' => true,
            'featured' => false,
            'published_at' => now(),
        ];
    }
}
