<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Services\CustomerService;
use App\Http\DTOs\Requests\CustomerCreateData;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get all customers.
     */
    public function getAll(): JsonResponse
    {
        $customers = $this->customerService->getAll();

        return response()->json([
            'success' => true,
            'data'    => $customers,
        ]);
    }

    /**
     * Get customer details by ID.
     */
    public function getDetail(string $id): JsonResponse
    {
        $customer = $this->customerService->getDetail($id);

        return response()->json([
            'success' => true,
            'data'    => $customer,
        ]);
    }

    /**
     * Create a new customer.
     */
    public function create(CustomerRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $dto = CustomerCreateData::fromArray($validatedData);

        $customer = $this->customerService->create($dto);

        return response()->json([
            'success' => true,
            'data'    => $customer,
        ], 201);
    }

    /**
     * Update an existing customer.
     */
    public function update(CustomerRequest $request, string $id): JsonResponse
    {
        $validatedData = $request->validated();
        $dto = CustomerCreateData::fromArray($validatedData);

        $updated = $this->customerService->update($id, $dto);

        return response()->json([
            'success' => true,
            'message' => $updated ? 'Cập nhật khách hàng thành công.' : 'Không thể cập nhật khách hàng.'
        ]);
    }

    /**
     * Delete a customer by ID.
     */
    public function delete(string $id): JsonResponse
    {
        $deleted = $this->customerService->delete($id);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Xóa khách hàng thành công.' : 'Không thể xóa khách hàng.'
        ]);
    }


    /**
     * Search customers by query.
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $query = $request->input('query');
        $customers = $this->customerService->search($query);

        return response()->json([
            'success' => true,
            'data'    => $customers,
        ]);
    }
}
