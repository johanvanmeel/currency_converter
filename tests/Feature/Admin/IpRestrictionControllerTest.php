<?php

namespace Tests\Feature\Admin;

use App\Models\AllowedIp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IpRestrictionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_all_allowed_ips(): void
    {
        AllowedIp::create(['ip_address' => '127.0.0.1', 'description' => 'Localhost']);
        AllowedIp::create(['ip_address' => '192.168.1.1', 'description' => 'Home']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.ips.index'));

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['ip_address' => '127.0.0.1'])
            ->assertJsonFragment(['ip_address' => '192.168.1.1']);
    }

    public function test_store_creates_new_allowed_ip(): void
    {
        AllowedIp::create(['ip_address' => '127.0.0.1', 'description' => 'Localhost']);

        $response = $this->actingAs($this->user)
            ->post(route('admin.ips.store'), [
                'ip_address' => '1.2.3.4',
                'description' => 'New IP',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'IP address added successfully.']);

        $this->assertDatabaseHas('allowed_ips', [
            'ip_address' => '1.2.3.4',
            'description' => 'New IP',
        ]);
    }

    public function test_destroy_removes_allowed_ip(): void
    {
        AllowedIp::create(['ip_address' => '127.0.0.1', 'description' => 'Localhost']);
        $allowedIp = AllowedIp::create(['ip_address' => '1.2.3.4', 'description' => 'To be deleted']);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.ips.destroy', $allowedIp));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'IP address removed successfully.']);

        $this->assertDatabaseMissing('allowed_ips', [
            'id' => $allowedIp->id,
        ]);
    }
}
