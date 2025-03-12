<?php

namespace App\Utils\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IResourceRepository
{
    public function find(Model|string $model, array $columns = ['*']);

    public function all(array $columns = ['*']);

    public function paginate(
        int $perPage,
        string $sortBy = "created_at",
        bool $sortDesc = true,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null
    );

    public function take(
        int $count,
        string $sortBy = "created_at",
        bool $sortDesc = true
    );

    public function store(array $data);

    public function update(Model|string $model, array $data);

    public function delete(Model|string $model);
}
