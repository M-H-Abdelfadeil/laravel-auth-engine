<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->paginate($perPage);
    }

    public function get(array $filters = [])
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->get();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model;
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function count(array $filters = []): int
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->count();
    }

    public function sum(string $column, array $filters = [])
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->sum($column);
    }

    public function avg(string $column, array $filters = [])
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->avg($column);
    }

    public function findByCol(string $column, $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($column, $value)->first($columns);
    }

    public function getByCol(string $column, $value, array $columns = ['*'])
    {
        return $this->model->where($column, $value)->get($columns);
    }

    public function maxByCol(string $column, array $filters = [])
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->max($column);
    }

    public function minByCol(string $column, array $filters = [])
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->min($column);
    }

    public function deleteWhere(array $filters = []): bool
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->delete();
    }

    public function exists(array $filters = []): bool
    {
        $query = $this->model->query();
        return $this->applyFilter($query, $filters)->exists();
    }

    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * Apply generic filters. Child classes should override this or extend it.
     */
    public function applyFilter(Builder $builder, array $filters = []): Builder
    {
        return $builder;
    }
}
