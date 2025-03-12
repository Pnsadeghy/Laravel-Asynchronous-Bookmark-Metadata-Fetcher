<?php

namespace App\Repositories;

use App\Models\Bookmark;
use App\Repositories\Interfaces\IBookmarkRepository;
use App\Utils\Repositories\ResourceRepository;

class BookmarkRepository extends ResourceRepository implements IBookmarkRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->stringSearchFilters = ["url", "title", "description"];
    }

    protected string $modelClass = Bookmark::class;
}
