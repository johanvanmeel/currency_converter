<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_unauthenticated_user_is_redirected_from_admin_ips(): void
    {
        $response = $this->get('/admin/ips');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_authenticated_user_can_access_admin_ips(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/ips');

        $response->assertStatus(200);
    }
}
