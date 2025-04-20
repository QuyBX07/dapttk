<?php
namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\Interfaces\BaseRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Collection;
class CustomerRepository implements BaseRepositoryInterface
{
    public function findAll()
    {
        return Customer::paginate(2);
    }

    public function find(string $id)
    {
        return Customer::findOrFail($id);
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(string $id, array $data): bool
    {
        return Customer::findOrFail($id)->update($data);
    }

    public function delete(string $id): bool
    {
        return Customer::destroy($id);
    }
}
