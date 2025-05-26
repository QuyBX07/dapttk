<?php
namespace App\Http\Repositories\Eloquent;
use App\Models\Product;
use App\Http\Repositories\Interfaces\ProductRepoInterface;
class ProductRepository implements ProductRepoInterface
{
    public function findAll()
    {
        return Product::with('category')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
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

    public function search(string $query)
    {
        return Product::with('category')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function ($sub) use ($query) {
                      $sub->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
    }
    
}
