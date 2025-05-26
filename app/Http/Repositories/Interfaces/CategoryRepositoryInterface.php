<?php

namespace App\Http\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
    public function find(string $id);
    public function findAll();
    public function search(string $query);
    public function paginate(int $perPage = 10);
}
