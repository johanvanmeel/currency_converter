<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AllowedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminIpManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_allowed_ips(): void
    {
        $admin = User::factory()->create();
        AllowedIp::create(['ip_address' => '127.0.0.1', 'description' => 'Home']);

        $response = $this->actingAs($admin)
            ->getJson('/admin/ips');

        $response->assertStatus(200)
            ->assertJsonFragment(['ip_address' => '127.0.0.1', 'description' => 'Home']);
    }

    public function test_admin_can_add_allowed_ip(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)
            ->postJson("/admin/ips", [
                'ip_address' => '10.0.0.1/8',
                'description' => 'Office Network',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('allowed_ips', [
            'ip_address' => '10.0.0.1/8',
            'description' => 'Office Network',
        ]);
    }

    public function test_admin_can_remove_allowed_ip(): void
    {
        $admin = User::factory()->create();
        $ip = AllowedIp::create(['ip_address' => '127.0.0.1']);

        $response = $this->actingAs($admin)
            ->deleteJson("/admin/ips/{$ip->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('allowed_ips', ['id' => $ip->id]);
    }
}
