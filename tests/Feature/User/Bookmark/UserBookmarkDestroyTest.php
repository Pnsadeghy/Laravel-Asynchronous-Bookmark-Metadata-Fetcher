<?php

namespace Tests\Feature\User\Bookmark;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBookmarkDestroyTest extends TestCase
{
    use RefreshDatabase;

    private function getUrl(string $id): string
    {
        return "/api/user/bookmarks/{$id}";
    }

    public function test_destroy_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $bookmark = Bookmark::factory()->create();
        $this->assertFalse($bookmark->trashed());

        $url = $this->getUrl($bookmark->id);

        $response = $this->deleteJson($url);
        $response->assertStatus(204);

        $this->assertSoftDeleted('bookmarks', [
            'id' => $bookmark->id,
        ]);

        $response = $this->deleteJson($url);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('bookmarks', [
            'id' => $bookmark->id,
        ]);

        $response = $this->deleteJson($url);
        $response->assertStatus(404);
    }
}
