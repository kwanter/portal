<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_applications_index(): void
    {
        $response = $this->actingAs($this->user)->get(
            route("applications.index"),
        );

        $response->assertStatus(200);
        $response->assertViewIs("applications.index");
    }

    public function test_user_can_create_application(): void
    {
        $applicationData = [
            "name" => "Test Application",
            "url" => "https://test.example.com",
            "description" => "This is a test application",
            "category" => "kesekretariatan",
        ];

        $response = $this->actingAs($this->user)->post(
            route("applications.store"),
            $applicationData,
        );

        $response->assertRedirect(route("applications.index"));
        $this->assertDatabaseHas("applications", [
            "name" => "Test Application",
            "category" => "kesekretariatan",
        ]);
    }

    public function test_user_can_update_application(): void
    {
        $application = Application::factory()->create([
            "created_by" => $this->user->id,
        ]);

        $updateData = [
            "name" => "Updated Application",
            "url" => "https://updated.example.com",
            "description" => "Updated description",
            "category" => "kepaniteraan",
        ];

        $response = $this->actingAs($this->user)->put(
            route("applications.update", $application),
            $updateData,
        );

        $response->assertRedirect(route("applications.index"));
        $this->assertDatabaseHas("applications", [
            "id" => $application->id,
            "name" => "Updated Application",
            "category" => "kepaniteraan",
        ]);
    }

    public function test_user_can_delete_application(): void
    {
        $application = Application::factory()->create([
            "created_by" => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(
            route("applications.destroy", $application),
        );

        $response->assertRedirect(route("applications.index"));
        $this->assertSoftDeleted("applications", [
            "id" => $application->id,
        ]);
    }

    public function test_application_requires_authentication(): void
    {
        $response = $this->get(route("applications.index"));

        $response->assertRedirect(route("login"));
    }

    public function test_application_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(
            route("applications.store"),
            [],
        );

        $response->assertSessionHasErrors([
            "name",
            "url",
            "description",
            "category",
        ]);
    }

    public function test_application_validates_url_format(): void
    {
        $applicationData = [
            "name" => "Test Application",
            "url" => "not-a-valid-url",
            "description" => "This is a test application",
            "category" => "kesekretariatan",
        ];

        $response = $this->actingAs($this->user)->post(
            route("applications.store"),
            $applicationData,
        );

        $response->assertSessionHasErrors(["url"]);
    }

    public function test_application_validates_category(): void
    {
        $applicationData = [
            "name" => "Test Application",
            "url" => "https://test.example.com",
            "description" => "This is a test application",
            "category" => "invalid_category",
        ];

        $response = $this->actingAs($this->user)->post(
            route("applications.store"),
            $applicationData,
        );

        $response->assertSessionHasErrors(["category"]);
    }

    public function test_user_can_filter_applications_by_category(): void
    {
        Application::factory()->create([
            "category" => "kesekretariatan",
            "created_by" => $this->user->id,
        ]);

        Application::factory()->create([
            "category" => "kepaniteraan",
            "created_by" => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route("applications.index", ["category" => "kesekretariatan"]),
        );

        $response->assertStatus(200);
    }

    public function test_user_can_search_applications(): void
    {
        Application::factory()->create([
            "name" => "Searchable Application",
            "created_by" => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route("applications.index", ["search" => "Searchable"]),
        );

        $response->assertStatus(200);
    }

    public function test_application_tracks_creator(): void
    {
        $applicationData = [
            "name" => "Test Application",
            "url" => "https://test.example.com",
            "description" => "This is a test application",
            "category" => "kesekretariatan",
        ];

        $this->actingAs($this->user)->post(
            route("applications.store"),
            $applicationData,
        );

        $application = Application::where("name", "Test Application")->first();
        $this->assertEquals($this->user->id, $application->created_by);
    }
}
