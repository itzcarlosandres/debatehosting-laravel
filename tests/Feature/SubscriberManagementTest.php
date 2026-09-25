<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_subscribe_to_newsletter(): void
    {
        $response = $this->postJson(route('api.subscribe'), [
            'email' => 'nuevo.lector@debatehosting.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'already_subscribed' => false,
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'nuevo.lector@debatehosting.com',
        ]);
    }

    public function test_duplicate_subscription_returns_friendly_message(): void
    {
        Subscriber::create(['email' => 'existente@debatehosting.com']);

        $response = $this->postJson(route('api.subscribe'), [
            'email' => 'existente@debatehosting.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'already_subscribed' => true,
        ]);
    }

    public function test_invalid_email_subscription_returns_validation_error(): void
    {
        $response = $this->postJson(route('api.subscribe'), [
            'email' => 'no-es-un-email',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_subscribers(): void
    {
        $response = $this->get(route('admin.subscribers.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_subscribers_list(): void
    {
        $user = User::factory()->create();
        Subscriber::create(['email' => 'lector1@example.com']);
        Subscriber::create(['email' => 'lector2@example.com']);

        $response = $this->actingAs($user)->get(route('admin.subscribers.index'));

        $response->assertStatus(200);
        $response->assertSee('lector1@example.com');
        $response->assertSee('lector2@example.com');
        $response->assertSee('Suscriptores del Boletín');
    }

    public function test_admin_can_add_subscriber_manually(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.subscribers.store'), [
            'email' => 'manual@example.com',
        ]);

        $response->assertRedirect(route('admin.subscribers.index'));
        $this->assertDatabaseHas('subscribers', [
            'email' => 'manual@example.com',
        ]);
    }

    public function test_admin_can_delete_subscriber(): void
    {
        $user = User::factory()->create();
        $subscriber = Subscriber::create(['email' => 'eliminar@example.com']);

        $response = $this->actingAs($user)->delete(route('admin.subscribers.destroy', $subscriber->id));

        $response->assertRedirect(route('admin.subscribers.index'));
        $this->assertDatabaseMissing('subscribers', [
            'id' => $subscriber->id,
        ]);
    }

    public function test_admin_can_export_subscribers_csv(): void
    {
        $user = User::factory()->create();
        Subscriber::create(['email' => 'exportar@example.com']);

        $response = $this->actingAs($user)->get(route('admin.subscribers.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
