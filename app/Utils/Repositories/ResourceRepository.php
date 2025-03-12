<?php

namespace App\Utils\Repositories;

use App\Utils\Interfaces\IResourceRepository;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ResourceRepository implements IResourceRepository
{
    protected string $modelClass;

    protected Builder $model;

    protected array $stringSearchFilters;
    protected array $searchProperties;

    public function __construct()
    {
        $this->boot();
        if (isset($this->modelClass)) {
            $this->makeModel($this->modelClass);
        }
    }

    public function boot(): void
    {
    }

    public function find(Model|string $model, array $columns = ['*']): Model
    {
        if (is_string($model)) {
            $model = $this->model->find($model, $columns);
        } elseif($columns[0] = "*") {
            return $model;
        } {
            $model = $this->model->find($model->id, $columns);
        }

        if (!$model) {
            throw new ModelNotFoundException();
        }
        return $model;
    }

    public function paginate(
        int $perPage,
        string $sortBy = "created_at",
        bool $sortDesc = true,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null
    ): Paginator
    {
        return $this->model
            ->orderBy($sortBy, $sortDesc ? "desc" : "asc")
            ->paginate($perPage, $columns, $pageName, $page);
    }

    public function take(int $count, string $sortBy = "created_at", bool $sortDesc = true): IResourceRepository {
        $this->model = $this->model
            ->orderBy($sortBy, $sortDesc ? "desc" : "asc")
            ->take($count);
        return $this;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->select($columns)->get();
    }

    public function search(string $search): IResourceRepository
    {
        if (empty($search)) {
            return $this;
        }
        $filters = $this->stringSearchFilters;
        $this->model = $this->model->where(function ($query) use ($search, $filters) {
            foreach ($filters as $filter) {
                $query->orWhere($filter, 'like', "%$search%");
            }
        });

        return $this;
    }

    public function filter(Request $request): IResourceRepository
    {
        foreach ($this->searchProperties as $filter) {
            if ($request->has($filter)) {
                $methodName = 'filterBy'.Str::camel($filter);
                $this->{$methodName}($request->get($filter));
            }
        }

        return $this;
    }

    public function store(array $data): Model
    {
        $modelInstance = new $this->modelClass();
        foreach ($data as $key => $value) {
            $modelInstance->{$key} = $value;
        }
        $modelInstance->save();

        return $modelInstance;
    }

    public function update(Model|string $model, array $data): Model
    {
        $model = $this->find($model);
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }
        $model->update();

        return $model;
    }

    public function delete(Model|string $model): void
    {
        $model = $this->find($model);
        $model->delete();
    }

    public function __call(string $method, array $arguments)
    {
        $result = $this->model->{$method}(...$arguments);

        if ($result instanceof Builder) {
            $this->model = $result;
            return $this;
        }

        return $result;
    }



    protected function searchHelper(string $search, array $filters): void {
        if (empty($search)) return;

        $this->model = $this->model->where(function ($query) use ($search, $filters) {
            foreach ($filters as $filter) {
                $query->orWhere($filter, 'like', "%$search%");
            }
        });
    }

    protected function makeModel(string $modelClass): void
    {
        if (class_exists($modelClass)
            && is_subclass_of(
                $modelClass,
                Model::class
            )) {
            $this->model = $modelClass::query();
        } else {
            throw new InvalidArgumentException(
                'Invalid Model Class: '.$modelClass
            );
        }
    }

    protected function getModel(): Builder
    {
        return $this->model;
    }
}
