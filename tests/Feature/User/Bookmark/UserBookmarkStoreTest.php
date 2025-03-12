<?php

namespace Tests\Feature\User\Bookmark;

use App\Jobs\FetchBookmarkMetadataJob;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UserBookmarkStoreTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/user/bookmarks';

    public function test_store_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $url = 'https://example.com';
        $responseData = [
            'url' => $url,
        ];

        $response = $this->postJson($this->url, $responseData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'url',
                'title',
                'description',
                'created_at',
                'deleted',
            ])
            ->assertJsonFragment([
                'url' => $url,
                'title' => null,
                'description' => null,
                'deleted' => false,
            ]);

        $this->assertDatabaseHas('bookmarks', [
            'url' => $url
        ]);

        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_store_duplicate_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $url = 'https://example.com';

        Bookmark::factory()->create([
            'url' => $url
        ]);

        $responseData = [
            'url' => $url
        ];

        $response = $this->postJson($this->url, $responseData);

        $response->assertStatus(422);
    }
}
