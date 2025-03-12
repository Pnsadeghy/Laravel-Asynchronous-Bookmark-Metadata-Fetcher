<?php

namespace Tests\Feature\User\Bookmark;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserBookmarkRestoreTest extends TestCase
{
    use RefreshDatabase;

    private function getUrl(string $id): string
    {
        return "/api/user/bookmarks/{$id}/restore";
    }

    public function test_restore_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $bookmark = Bookmark::factory()->create();

        $url = $this->getUrl($bookmark->id);

        $response = $this->postJson($url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $bookmark->id,
            'deleted' => false,
        ]);

        $bookmark->delete();

        $this->assertSoftDeleted('bookmarks', [
            'id' => $bookmark->id,
        ]);

        $response = $this->postJson($url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $bookmark->id,
            'deleted' => false,
        ]);

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'deleted_at' => null,
        ]);
    }
}
