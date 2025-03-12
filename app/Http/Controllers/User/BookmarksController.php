<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommonIndexRequest;
use App\Http\Resources\User\Bookmark\UserBookmarkResource;
use App\Repositories\Interfaces\IBookmarkRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Bookmarks
 *
 * API endpoints for managing user Bookmarks
 *
 * @authenticated
 */
class BookmarksController extends Controller
{
    public function __construct(protected IBookmarkRepository $repository) {}

    /**
     * All bookmarks
     *
     * @queryParam q string
     * @queryParam page integer
     * @queryParam per_page integer
     *
     * @responseFile 200 resources/responses/User/Bookmark/index.json
     */
    public function index(CommonIndexRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->integer('per_page', config('pagination.per_page'));

        return UserBookmarkResource::collection(
            $this->repository
                ->search($request->string('q'))
                ->withTrashed()
                ->paginate($per_page, "created_at", true, [
                    "id", "url", "title", "description", "created_at", "deleted_at"
                ])
        );
    }
}
