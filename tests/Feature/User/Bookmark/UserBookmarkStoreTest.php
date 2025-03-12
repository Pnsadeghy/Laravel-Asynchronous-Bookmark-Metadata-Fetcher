<?php

namespace Tests\Feature\User\Bookmark;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBookmarkStoreTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/user/bookmarks';

    public function testStoreSuccessfully(): void
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
            'url' => $url,
            'title' => null,
            'description' => null,
        ]);

        #TODO check job
    }

    public function testStoreDuplicateUrl()
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
