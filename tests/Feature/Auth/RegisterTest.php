<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/auth/register';

    public function test_user_can_register_with_validData()
    {
        $email = 'john@example.com';
        $payload = [
            'name' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson($this->url, $payload);

        $response->assertStatus(201)
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
                    'email' => $email,
                    'name' => 'John Doe',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $user = User::query()->where('email', $email)->first();
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson($this->url, $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
