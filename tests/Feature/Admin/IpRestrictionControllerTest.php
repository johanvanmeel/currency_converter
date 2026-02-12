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
        AllowedIp::create(['ip_address' => '127.0.0.1', 'description' => 'Test IP']);
    }

    public function test_index_returns_all_allowed_ips(): void
    {
        AllowedIp::create(['ip_address' => '192.168.1.1', 'description' => 'Home']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.ips.index'));

        $response->assertStatus(200)
            ->assertViewIs('admin.ips.index')
            ->assertViewHas('ips')
            ->assertSee('127.0.0.1')
            ->assertSee('192.168.1.1');
    }

    public function test_store_creates_new_allowed_ip(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.ips.store'), [
                'ip_address' => '1.2.3.4',
                'description' => 'New IP',
            ]);

        $response->assertRedirect(route('admin.ips.index'))
            ->assertSessionHas('status', 'ip-created');

        $this->assertDatabaseHas('allowed_ips', [
            'ip_address' => '1.2.3.4',
            'description' => 'New IP',
        ]);
    }

    public function test_destroy_removes_allowed_ip(): void
    {
        $allowedIp = AllowedIp::create(['ip_address' => '1.2.3.4', 'description' => 'To be deleted']);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.ips.destroy', $allowedIp));

        $response->assertRedirect(route('admin.ips.index'))
            ->assertSessionHas('status', 'ip-deleted');

        $this->assertDatabaseMissing('allowed_ips', [
            'id' => $allowedIp->id,
        ]);
    }

    public function test_update_updates_allowed_ip(): void
    {
        $allowedIp = AllowedIp::create(['ip_address' => '1.2.3.4', 'description' => 'Old IP']);

        $response = $this->actingAs($this->user)
            ->patch(route('admin.ips.update', $allowedIp), [
                'ip_address' => '4.3.2.1',
                'description' => 'Updated IP',
            ]);

        $response->assertRedirect(route('admin.ips.index'))
            ->assertSessionHas('status', 'ip-updated');

        $this->assertDatabaseHas('allowed_ips', [
            'id' => $allowedIp->id,
            'ip_address' => '4.3.2.1',
            'description' => 'Updated IP',
        ]);
    }
}
