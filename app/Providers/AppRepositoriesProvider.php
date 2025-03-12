<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookmarkRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\IArticleRepository;
use App\Repositories\Interfaces\IAuthorRepository;
use App\Repositories\Interfaces\IBookmarkRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\INewsReaderSourceRepository;
use App\Repositories\Interfaces\INewsSourceRepository;
use App\Repositories\Interfaces\IUserFeedRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\NewsReaderSourceRepository;
use App\Repositories\NewsSourceRepository;
use App\Repositories\UserFeedRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoriesProvider extends ServiceProvider
{
    protected array $repositoryBindings = [
        IUserRepository::class => UserRepository::class,
        IBookmarkRepository::class => BookmarkRepository::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->repositoryBindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
