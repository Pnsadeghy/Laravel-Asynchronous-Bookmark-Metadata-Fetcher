<?php

namespace Tests\Feature\User\Bookmark;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBookmarkIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/user/bookmarks';

    public function test_index_without_parameters(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Bookmark::factory(5)->create();
        Bookmark::factory(5)->trashed()->create();

        $response = $this->getJson($this->url);

        $response->assertStatus(200);

        $response->assertJsonCount(10, "data");

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'url',
                    'title',
                    'description',
                    'created_at',
                    'deleted'
                ]
            ]
        ]);
    }

    public function test_index_with_q_parameter(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Bookmark::factory()->create([
            'title' => "test123 World"
        ]);
        Bookmark::factory()->create([
            'description' => "This is test123 description"
        ]);
        Bookmark::factory()->create([
            'url' => "/test123/hhhh"
        ]);
        Bookmark::factory(10)->create();

        $response = $this->getJson($this->url . '?q=test123');

        $response->assertStatus(200);

        $response->assertJsonCount(3, "data");
    }
}
