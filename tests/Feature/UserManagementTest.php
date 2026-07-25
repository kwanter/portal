<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_list_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route("users.index"));

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_update_other_user(): void
    {
        $attacker = User::factory()->create();
        $target = User::factory()->create();

        $response = $this->actingAs($attacker)->put(
            route("users.update", $target),
            [
                "name" => "Hijacked Name",
                "email" => "hijacked@example.com",
            ],
        );

        $response->assertStatus(403);
        $this->assertDatabaseMissing("users", [
            "id" => $target->id,
            "name" => "Hijacked Name",
        ]);
    }

    public function test_non_admin_cannot_verify_other_user(): void
    {
        $attacker = User::factory()->create();
        $target = User::factory()->unverified()->create();

        $response = $this->actingAs($attacker)->post(
            route("users.verify", $target),
        );

        $response->assertStatus(403);
        $this->assertFalse($target->fresh()->hasVerifiedEmail());
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route("users.index"));

        $response->assertStatus(200);
        $response->assertViewIs("users.index");
    }

    public function test_admin_can_verify_user(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->unverified()->create();

        $response = $this->actingAs($admin)->post(
            route("users.verify", $target),
        );

        $response->assertRedirect();
        $this->assertTrue($target->fresh()->hasVerifiedEmail());
    }
}
