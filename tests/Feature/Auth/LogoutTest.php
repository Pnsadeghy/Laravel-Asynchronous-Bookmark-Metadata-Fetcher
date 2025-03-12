<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/auth/logout';

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200);

        $this->assertCount(0, $user->tokens);
    }

    public function test_guest_user_cannot_logout()
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }
}
