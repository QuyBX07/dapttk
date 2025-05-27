<?php

namespace App\Http\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete(string $id): bool;
    public function search(string $query);
}
