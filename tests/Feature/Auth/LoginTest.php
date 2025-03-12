<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/auth/login';

    public function testUserCanLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson($this->url, [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'access_token',
                'user' => [
                    'email',
                    'name',
                ],
            ])
            ->assertJson([
                'token_type' => 'Bearer',
                'user' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
            ]);
    }

    public function testUserCannotLoginWithInvalidCredentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson($this->url, [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
