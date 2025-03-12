<?php

namespace Tests\Unit\Jobs;

use App\Jobs\FetchBookmarkMetadataJob;
use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchBookmarkMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_bookmark_metadata_updates_bookmark_successfully(): void
    {
        $bookmark = Bookmark::factory()->create([
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
        ]);

        Http::fake([
            'https://example.com' => Http::response(
                '<html><head><title>Example Title</title><meta name="description" content="Example Description"></head></html>',
                200
            ),
        ]);

        $job = new FetchBookmarkMetadataJob($bookmark->id);
        $job->handle();

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'url' => 'https://example.com',
            'title' => 'Example Title',
            'description' => 'Example Description',
        ]);
    }

    public function test_fetch_bookmark_metadata_releases_on_failure(): void
    {
        $bookmark = Bookmark::factory()->create([
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
        ]);

        Http::fake([
            'https://example.com' => Http::response('Server Error', 500),
        ]);

        $job = new FetchBookmarkMetadataJob($bookmark->id);
        $job->handle();

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'title' => null,
            'description' => null,
        ]);
    }

    public function test_fetch_bookmark_metadata_sets_default_values_on_missing_metadata(): void
    {
        $bookmark = Bookmark::factory()->create([
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
        ]);

        Http::fake([
            'https://example.com' => Http::response('<html><body>No metadata here</body></html>', 200),
        ]);

        $job = new FetchBookmarkMetadataJob($bookmark->id);
        $job->handle();

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'url' => 'https://example.com',
            'title' => 'Unknown',
            'description' => 'No description available',
        ]);
    }
}
