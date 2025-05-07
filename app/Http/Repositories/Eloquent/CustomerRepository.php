<?php
namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\Interfaces\BaseRepositoryInterface;
use App\Models\Customer;
use App\Http\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Support\Collection;
class CustomerRepository implements BaseRepositoryInterface, SearchRepositoryInterface
{
    public function findAll()
    {
        return Customer::orderBy('updated_at', 'desc')->paginate(20);
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

    public function search(string $query)
    {
        return Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
    }
}
