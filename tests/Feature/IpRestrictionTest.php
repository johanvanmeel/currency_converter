<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AllowedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IpRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_if_no_ip_restrictions_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_login_if_ip_is_allowed(): void
    {
        $user = User::factory()->create();
        AllowedIp::create(['ip_address' => '127.0.0.1']);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_login_from_allowed_subnet(): void
    {
        $user = User::factory()->create();
        AllowedIp::create(['ip_address' => '192.168.1.0/24']);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.50'])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticated();
    }

    public function test_authenticated_user_is_restricted_by_middleware(): void
    {
        $user = User::factory()->create();
        AllowedIp::create(['ip_address' => '192.168.1.1']);

        $response = $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->get('/admin');

        $response->assertStatus(403);
    }
}
