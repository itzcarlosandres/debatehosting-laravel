<?php

namespace Tests\Feature;

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
}
