<?php
namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\Interfaces\BaseRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;
class ProductRepository implements BaseRepositoryInterface
{
    public function findAll()
    {
        return Product::with('category')->paginate(2);
    }

    public function find(string $id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(string $id, array $data): bool
    {
        return Product::findOrFail($id)->update($data);
    }

    public function delete(string $id): bool
    {
        return Product::destroy($id);
    }
}
