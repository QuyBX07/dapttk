<?php
namespace App\Http\Services;

use App\Http\DTOs\Requests\CustomerCreateData;
use App\Models\Customer;
use App\Http\Repositories\Interfaces\CustomerRepoInterface;

class CustomerService
{
    public function __construct(protected CustomerRepoInterface $customerRepo) {
    }

    public function getAll()
    {
        return $this->customerRepo->findAll();
    }

    public function create(CustomerCreateData $dto)
    {
        return $this->customerRepo->create($dto->toArray());
    }

    public function update(string $id, CustomerCreateData $dto): bool
    {
        return $this->customerRepo->update($id, $dto->toArray());
    }

    public function delete(string $id): bool
    {
        return $this->customerRepo->delete($id);
    }

    public function getDetail(string $id)
    {
        return $this->customerRepo->find($id);
    }

    // search
    public function search(string $query)
    {
        return $this->customerRepo->search($query);
    }
}

