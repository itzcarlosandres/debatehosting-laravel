<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditorialReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_reviews_index(): void
    {
        $provider = Provider::factory()->create(['name' => 'Hostinger']);
        Review::factory()->create([
            'title' => 'Reseña de Hostinger 2026',
            'slug' => 'hostinger-analisis',
            'provider_id' => $provider->id,
            'published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('reviews.index'));

        $response->assertStatus(200);
        $response->assertSee('Reseña de Hostinger 2026');
        $response->assertSee('Hostinger');
    }

    public function test_public_review_displays_direct_widget_when_provider_is_active(): void
    {
        $provider = Provider::factory()->create([
            'name' => 'Alexhost',
            'slug' => 'alexhost',
            'active' => true,
            'price_from' => 3.50,
        ]);

        $review = Review::factory()->create([
            'provider_id' => $provider->id,
            'title' => 'Reseña de Alexhost: Pruebas y Veredicto',
            'slug' => 'alexhost-opinion',
            'published' => true,
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('reviews.show', $review->slug));

        $response->assertStatus(200);
        $response->assertSee('Reseña de Alexhost: Pruebas y Veredicto');
        // Debe mostrar el widget oficial del proveedor
        $response->assertSee('Proveedor Auditado');
        $response->assertSee('Aprovechar Oferta en Alexhost');
        $response->assertSee(route('go', 'alexhost'));
    }

    public function test_public_review_displays_alternatives_widget_when_provider_is_not_active_or_external(): void
    {
        // Proveedor recomendado del catálogo
        $recommended = Provider::factory()->create([
            'name' => 'Hostinger Pro',
            'slug' => 'hostinger-pro',
            'active' => true,
            'score_precio' => 9.5,
            'score_rendimiento' => 9.8,
        ]);

        // Reseña de un proveedor externo que no está en el catálogo
        $review = Review::factory()->create([
            'provider_id' => null,
            'provider_name' => 'Hosting Desconocido',
            'title' => 'Reseña de Hosting Desconocido: ¿Es seguro?',
            'slug' => 'hosting-desconocido-opinion',
            'published' => true,
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get(route('reviews.show', $review->slug));

        $response->assertStatus(200);
        $response->assertSee('Reseña de Hosting Desconocido: ¿Es seguro?');
        // Debe mostrar el widget de alternativas recomendadas
        $response->assertSee('Alternativas Recomendadas con Máximo Rendimiento');
        $response->assertSee('Hostinger Pro');
        $response->assertSee(route('go', 'hostinger-pro'));
    }

    public function test_guest_cannot_view_draft_review(): void
    {
        $review = Review::factory()->create([
            'title' => 'Reseña en Borrador',
            'slug' => 'resena-borrador',
            'published' => false,
        ]);

        $response = $this->get(route('reviews.show', $review->slug));

        $response->assertStatus(404);
    }

    public function test_admin_can_access_and_create_review_in_admin_panel(): void
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create(['name' => 'SiteGround', 'active' => true]);

        $response = $this->actingAs($user)->get(route('admin.reviews.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($user)->post(route('admin.reviews.store'), [
            'provider_id' => $provider->id,
            'title' => 'Análisis Técnico de SiteGround',
            'slug' => 'siteground-analisis-tecnico',
            'target_category' => 'hosting-web',
            'rating' => 9.2,
            'summary' => 'Un hosting de calidad premium con servidores en Google Cloud.',
            'content' => 'Contenido extenso de la auditoría técnica con métricas y datos.',
            'pros' => '• Excelente velocidad NVMe',
            'cons' => '• Renovación alta',
            'verdict' => 'Muy recomendado para webs serias.',
            'published' => 1,
        ]);

        $storeResponse->assertRedirect(route('admin.reviews.index'));
        $this->assertDatabaseHas('reviews', [
            'slug' => 'siteground-analisis-tecnico',
            'title' => 'Análisis Técnico de SiteGround',
            'published' => true,
        ]);
    }

    public function test_admin_can_toggle_publish_status(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['published' => false]);

        $response = $this->actingAs($user)->post(route('admin.reviews.toggle-publish', $review));

        $response->assertRedirect();
        $this->assertTrue($review->fresh()->published);
    }

    public function test_admin_can_delete_review(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.reviews.destroy', $review));

        $response->assertRedirect(route('admin.reviews.index'));
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_can_generate_review_with_ai(): void
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create([
            'name' => 'Hostinger',
            'plan' => 'Plan Premium SSD',
            'price_from' => 2.99,
        ]);

        $response = $this->actingAs($user)->postJson(route('admin.ai.generate-review'), [
            'provider_id' => $provider->id,
            'name' => 'Hostinger',
            'focus' => 'WordPress LiteSpeed',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'title',
                'slug',
                'target_category',
                'rating',
                'summary',
                'content',
                'pros',
                'cons',
                'verdict',
                'meta_title',
                'meta_description',
            ],
        ]);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertNotEmpty($response->json('data.content'));
        $this->assertNotEmpty($response->json('data.title'));
    }

    public function test_guest_cannot_access_ai_review_generator(): void
    {
        $response = $this->postJson(route('admin.ai.generate-review'), [
            'name' => 'Hostinger',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_can_test_gemini_connection_endpoint(): void
    {
        $user = User::factory()->create();

        // Sin key
        $response = $this->actingAs($user)->postJson(route('admin.ai.test-gemini'), [
            'api_key' => '',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_published_reviews_appear_automatically_in_sitemap(): void
    {
        $publishedReview = Review::factory()->create([
            'title' => 'Reseña de Raiola Networks Publicada',
            'slug' => 'resena-raiola-networks-publicada',
            'published' => true,
            'published_at' => now()->subDay(),
        ]);

        $draftReview = Review::factory()->create([
            'title' => 'Reseña Secreta en Borrador',
            'slug' => 'resena-secreta-borrador',
            'published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee(route('reviews.show', $publishedReview->slug));
        $response->assertDontSee(route('reviews.show', $draftReview->slug));
    }
}
