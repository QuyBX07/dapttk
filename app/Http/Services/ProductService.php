<?php
namespace App\Http\Services;

use App\Http\DTOs\Requests\ProductCreateData;
use App\Models\Product;
use App\Http\Repositories\Interfaces\ProductRepoInterface;
use Illuminate\Support\Collection;


class ProductService
{
    public function __construct(protected ProductRepoInterface $productRepo) {
    }

    public function getAll()
    {
        return $this->productRepo->findAll();
    }

    public function create(ProductCreateData $dto): Product
    {
        return $this->productRepo->create($dto->toArray());
    }

    public function update(string $id, ProductCreateData $dto): bool
    {
        return $this->productRepo->update($id, $dto->toArray());
    }

    public function delete(string $id): bool
    {
        return $this->productRepo->delete($id);
    }

    public function getDetail(string $id)
    {
        return $this->productRepo->find($id);
    }

    // search
    public function search(string $quesry)
    {
        return $this->productRepo->search($quesry);
    }
}
