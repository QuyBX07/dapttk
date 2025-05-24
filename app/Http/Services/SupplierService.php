<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\SupplierRepoInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupplierService
{
    public function __construct(protected SupplierRepoInterface $supplierRepo) {}

    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->supplierRepo->create($validator->validated());
    }

    public function getPaginated($perPage = 10)
    {
        return $this->supplierRepo->getAllPaginated($perPage);
    }

    public function getDetail($id)
    {
        return $this->supplierRepo->findById($id);
    }

    public function update($id, array $data)
    {
        return $this->supplierRepo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->supplierRepo->delete($id);
    }

    public function search($query)
    {
        return $this->supplierRepo->search($query);
    }

    public function getAllForSelect()
    {
        return $this->supplierRepo->getAllForSelect();
    }
}
