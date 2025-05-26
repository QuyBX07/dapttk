<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
interface ProductRepoInterface extends BaseRepositoryInterface, SearchRepositoryInterface
{
    public function findAll();

    public function find(string $id);

    public function create(array $data);

    public function update(string $id, array $data);

    public function delete(string $id);
    public function search(string $query);

}