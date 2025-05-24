<?php

namespace App\Http\Repositories\Interfaces;

interface SupplierRepoInterface
{
    public function create(array $data);
    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function search($query);
    public function getAllPaginated($perPage = 10);
    public function getAllForSelect();
}
