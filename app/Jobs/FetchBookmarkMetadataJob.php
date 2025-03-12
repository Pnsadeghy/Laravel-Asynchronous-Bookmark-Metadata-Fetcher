<?php

namespace App\Jobs;

use App\Models\Bookmark;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchBookmarkMetadataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    /**
     * @var string
     */
    protected $bookmarkId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $bookmarkId)
    {
        $this->bookmarkId = $bookmarkId;
        $this->onQueue('bookmarks_queue');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bookmark = Bookmark::query()->findOrFail($this->bookmarkId);

        try {
            $response = Http::get($bookmark->url);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch URL: ' . $bookmark->url);
            }

            $html = $response->body();

            preg_match('/<title[^>]*>(.*?)<\/title>/si', $html, $titleMatch);
            preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/si', $html, $descMatch);

            $bookmark->update([
                'title' => $titleMatch[1] ?? 'Unknown',
                'description' => $descMatch[1] ?? 'No description available',
            ]);
        } catch (\Exception $e) {
            $this->release(10);
        }
    }
}
