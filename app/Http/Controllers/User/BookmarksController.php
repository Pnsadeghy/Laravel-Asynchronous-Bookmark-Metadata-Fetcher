<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommonIndexRequest;
use App\Http\Requests\User\Bookmark\UserBookmarkStoreRequest;
use App\Http\Resources\User\Bookmark\UserBookmarkResource;
use App\Models\Bookmark;
use App\Repositories\Interfaces\IBookmarkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

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

    /**
     * Store bookmark
     *
     * @bodyParam url string required
     *
     * @responseFile 201 resources/responses/User/Bookmark/store.json
     */
    public function store(UserBookmarkStoreRequest $request): JsonResponse
    {
        $bookmark = $this->repository->store($request->validated());

        return response()->json(UserBookmarkResource::make($bookmark), 201);
    }

    /**
     * Restore bookmark
     *
     * @responseFile 200 resources/responses/User/Bookmark/restore.json
     */
    public function restore(Bookmark $bookmark): JsonResponse
    {
        if ($bookmark->trashed()) {
            $bookmark->restore();
        }

        return response()->json(UserBookmarkResource::make($bookmark), 200);
    }

    /**
     * Destroy bookmark
     *
     * soft delete or force delete if already trashed.
     *
     * @response 204
     */
    public function destroy(Bookmark $bookmark): Response
    {
        $this->repository->smartDelete($bookmark);

        return response()->noContent();
    }
}
