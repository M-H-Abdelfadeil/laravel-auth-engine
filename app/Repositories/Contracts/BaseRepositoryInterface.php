<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function get(array $filters = []);

    public function find(int $id): ?Model;

    public function findOrFail(int $id): Model;

    public function create(array $data): Model;

    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;

    public function count(array $filters = []): int;

    public function sum(string $column, array $filters = []);

    public function avg(string $column, array $filters = []);

    public function findByCol(string $column, $value, array $columns = ['*']): ?Model;

    public function getByCol(string $column, $value, array $columns = ['*']);

    public function maxByCol(string $column, array $filters = []);

    public function minByCol(string $column, array $filters = []);

    public function deleteWhere(array $filters = []): bool;

    public function exists(array $filters = []): bool;

    public function insert(array $data): bool;

    public function updateOrCreate(array $attributes, array $values = []): Model;
}
