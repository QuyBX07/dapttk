<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\SupplierRepoInterface;
use App\Models\Supplier;


class SupplierRepository implements SupplierRepoInterface
{
    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $supplier = $this->findById($id);
        return $supplier->update($data);
    }

    public function delete($id)
    {
        $supplier = $this->findById($id);
        return $supplier->delete();
    }

    public function search($query)
    {
        return Supplier::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate(10);
    }

    public function getAllPaginated($perPage = 10)
    {
        return Supplier::paginate($perPage);
    }

    public function getAllForSelect()
    {
        return Supplier::select('supplier_id', 'name')->get();
    }
}
