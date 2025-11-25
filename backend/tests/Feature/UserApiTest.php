<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_users_list_when_authenticated(): void
    {
        Sanctum::actingAs(User::factory()->create());
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email']
            ])
            ->assertJsonCount(6); // 5 created + 1 authenticated user
    }

    public function test_cannot_get_users_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }

    public function test_users_list_only_includes_specific_fields(): void
    {
        Sanctum::actingAs(User::factory()->create());
        User::factory()->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);

        $users = $response->json();
        foreach ($users as $user) {
            $this->assertArrayHasKey('id', $user);
            $this->assertArrayHasKey('name', $user);
            $this->assertArrayHasKey('email', $user);
            $this->assertArrayNotHasKey('password', $user);
            $this->assertArrayNotHasKey('created_at', $user);
            $this->assertArrayNotHasKey('updated_at', $user);
        }
    }
}
