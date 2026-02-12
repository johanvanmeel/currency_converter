<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for the User Management in the admin panel.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin can view the user listing.
     */
    public function test_admin_can_view_user_listing(): void
    {
        $admin = User::factory()->create();
        $otherUser = User::factory()->create(['name' => 'Other User']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee($otherUser->name);
        $response->assertSee($otherUser->email);
    }

    /**
     * Test that an admin can view the create user form.
     */
    public function test_admin_can_view_create_user_form(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee(__('Add User'));
    }

    /**
     * Test that an admin can create a new user.
     */
    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test that an admin can view the edit user form.
     */
    public function test_admin_can_view_edit_user_form(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    /**
     * Test that an admin can update a user.
     */
    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test that an admin can delete a user.
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
