<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Provider;
use App\Models\ProviderProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderMultiProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_can_have_multiple_products(): void
    {
        $provider = Provider::create([
            'name' => 'MegaHost',
            'slug' => 'megahost',
            'categories' => ['hosting', 'vps'],
            'plan' => 'Hosting Base',
            'price_from' => 2.99,
            'price_before' => 5.99,
            'period' => 'mes',
            'score_precio' => 9.0,
            'score_rendimiento' => 8.5,
            'score_soporte' => 8.0,
            'score_facilidad' => 8.5,
            'uptime' => 99.9,
            'active' => true,
        ]);

        $hostingProd = ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'hosting',
            'plan_name' => 'Mega Shared NVMe',
            'price_from' => 2.99,
            'price_before' => 5.99,
            'period' => 'mes',
            'specs' => ['SSD NVMe', 'cPanel'],
            'is_featured' => true,
        ]);

        $vpsProd = ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'vps',
            'plan_name' => 'Mega VPS KVM 1',
            'price_from' => 5.50,
            'price_before' => 8.50,
            'period' => 'mes',
            'specs' => ['2 vCPU', '4GB RAM'],
            'is_featured' => false,
        ]);

        $this->assertCount(2, $provider->fresh()->products);
        $this->assertEquals('Mega Shared NVMe', $provider->getProductForCategory('hosting')->plan_name);
        $this->assertEquals('Mega VPS KVM 1', $provider->getProductForCategory('vps')->plan_name);
    }

    public function test_ofertas_displays_category_specific_product(): void
    {
        $catHosting = Category::create(['slug' => 'hosting', 'name' => 'Hosting web', 'order' => 1]);
        $catVps = Category::create(['slug' => 'vps', 'name' => 'VPS', 'order' => 2]);

        $provider = Provider::create([
            'name' => 'OmniHost',
            'slug' => 'omnihost',
            'categories' => ['hosting', 'vps'],
            'plan' => 'Omni Base',
            'price_from' => 3.00,
            'price_before' => 6.00,
            'period' => 'mes',
            'score_precio' => 9.0,
            'score_rendimiento' => 8.5,
            'score_soporte' => 8.0,
            'score_facilidad' => 8.5,
            'uptime' => 99.9,
            'active' => true,
        ]);

        ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'hosting',
            'plan_name' => 'Omni Hosting Compartido',
            'price_from' => 3.00,
            'price_before' => 6.00,
            'period' => 'mes',
            'is_featured' => true,
        ]);

        ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'vps',
            'plan_name' => 'Omni VPS Dedicado Turbo',
            'price_from' => 9.99,
            'price_before' => 14.99,
            'period' => 'mes',
            'is_featured' => false,
        ]);

        // Consulta en categoría Hosting
        $resHosting = $this->get('/ofertas?categoria=hosting');
        $resHosting->assertStatus(200);
        $resHosting->assertSee('Omni Hosting Compartido');
        $resHosting->assertSee('3.00');

        // Consulta en categoría VPS
        $resVps = $this->get('/ofertas?categoria=vps');
        $resVps->assertStatus(200);
        $resVps->assertSee('Omni VPS Dedicado Turbo');
        $resVps->assertSee('9.99');
    }

    public function test_provider_show_displays_all_products(): void
    {
        $provider = Provider::create([
            'name' => 'AlphaHost',
            'slug' => 'alphahost',
            'categories' => ['hosting', 'vps'],
            'plan' => 'Alpha Plan',
            'price_from' => 3.00,
            'price_before' => 6.00,
            'period' => 'mes',
            'score_precio' => 9.0,
            'score_rendimiento' => 8.5,
            'score_soporte' => 8.0,
            'score_facilidad' => 8.5,
            'uptime' => 99.9,
            'active' => true,
        ]);

        ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'hosting',
            'plan_name' => 'Alpha Web cPanel',
            'price_from' => 2.50,
            'period' => 'mes',
        ]);

        ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'vps',
            'plan_name' => 'Alpha KVM Cloud',
            'price_from' => 6.50,
            'period' => 'mes',
        ]);

        $response = $this->get('/proveedores/alphahost');
        $response->assertStatus(200);
        $response->assertSee('Planes y Servicios Disponibles');
        $response->assertSee('Alpha Web cPanel');
        $response->assertSee('Alpha KVM Cloud');
    }

    public function test_admin_can_generate_products_via_ai_endpoint(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->postJson('/admin/ai/generate-products', [
            'name' => 'Alexhost',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'category_slug',
                    'plan_name',
                    'price_from',
                ],
            ],
        ]);
    }

    public function test_admin_can_view_provider_create_and_edit_forms_with_products(): void
    {
        $admin = User::factory()->create();
        $provider = Provider::factory()->create(['name' => 'Alexhost']);
        ProviderProduct::create([
            'provider_id' => $provider->id,
            'category_slug' => 'vps',
            'plan_name' => 'VPS Test Plan',
            'price_from' => 5.00,
        ]);

        $responseCreate = $this->actingAs($admin)->get(route('admin.providers.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Catálogo Multi-Producto');

        $responseEdit = $this->actingAs($admin)->get(route('admin.providers.edit', $provider));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Catálogo Multi-Producto');
        $responseEdit->assertSee('VPS Test Plan');
    }
}
