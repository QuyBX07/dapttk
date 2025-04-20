<?php
namespace App\Http\Services;

use App\Http\Repositories\Eloquent\CustomerRepository;
use App\Http\DTOs\Requests\CustomerCreateData;
use App\Models\Customer;


class CustomerService
{
    protected CustomerRepository $customerRepo;
    public function __construct(CustomerRepository $customerRepo) {
        $this->customerRepo = $customerRepo;
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
}

