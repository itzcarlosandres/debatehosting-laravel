<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMobileLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_renders_mobile_hamburger_and_drawer_elements(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Verifica la presencia del botón hamburguesa
        $response->assertSee('id="sidebar-toggle-btn"', false);
        $response->assertSee('class="topbar-sidebar-toggle"', false);

        // Verifica la presencia del botón de cerrar el drawer móvil
        $response->assertSee('id="sidebar-close-btn"', false);
        $response->assertSee('class="sidebar-close-btn"', false);

        // Verifica la presencia del backdrop translúcido
        $response->assertSee('id="sidebar-backdrop"', false);
        $response->assertSee('class="sidebar-backdrop"', false);

        // Verifica clases y scripts para el control de apertura y cierre
        $response->assertSee('sidebar-open', false);
        $response->assertSee('live-status-text', false);
    }

    public function test_providers_index_renders_responsive_header_and_table_elements(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.providers.index'));

        $response->assertStatus(200);
        $response->assertSee('id="sidebar-toggle-btn"', false);
        $response->assertSee('class="table-responsive"', false);
        $response->assertSee('id="filter-providers"', false);
    }

    public function test_admin_providers_are_ordered_by_latest_and_metrics_column_is_removed(): void
    {
        $user = User::factory()->create();

        $oldProvider = Provider::factory()->create([
            'name' => 'Alpha Provider (Antiguo)',
            'created_at' => now()->subDays(5),
        ]);

        $newProvider = Provider::factory()->create([
            'name' => 'Zulu Provider (Nuevo)',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.providers.index'));

        $response->assertStatus(200);

        // Se verifica que no exista la columna de Métricas (P/R/S)
        $response->assertDontSee('Métricas (P/R/S)');

        // Se verifica que el más reciente aparezca antes que el antiguo
        $content = $response->getContent();
        $posNew = strpos($content, 'Zulu Provider (Nuevo)');
        $posOld = strpos($content, 'Alpha Provider (Antiguo)');

        $this->assertNotFalse($posNew);
        $this->assertNotFalse($posOld);
        $this->assertTrue($posNew < $posOld, 'El proveedor más reciente debe aparecer antes que el más antiguo.');
    }

    public function test_create_provider_renders_redesigned_layout_and_elements(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.providers.create'));

        $response->assertStatus(200);
        $response->assertSee('provider-grid-layout', false);
        $response->assertSee('ai-assistant-banner', false);
        $response->assertSee('telemetry-score-hero', false);
        $response->assertSee('sticky-action-dock', false);
        $response->assertSee('dock-actions-group', false);
        $response->assertSee('provider-identity-grid', false);
        $response->assertSee('plan-auditado-grid', false);
        $response->assertSee('slug-input-wrapper', false);
        $response->assertSee('main-affiliate-card', false);
        $response->assertSee('id="card-logo"', false);
        $response->assertSee('id="card-identity"', false);
        $response->assertSee('field-name', false);
        $response->assertSee('field-plan', false);
        $response->assertSee('field-price-from', false);
    }
}
