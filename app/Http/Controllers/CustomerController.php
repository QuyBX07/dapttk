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

    // lấy danh sách khách hàng
    public function getAll()
    {
        $customers = $this->customerService->getAll();

        return view('layout.customer.content', [
            'customers' => $customers
        ]);

        // return response()->json([
        //     'success' => true,
        //     'data'    => $customers,
        // ]);
    }

    // lấy chi tiết khách hàng
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
    public function create(CustomerRequest $request)
    {
        $validatedData = $request->validated();
        $dto = CustomerCreateData::fromArray($validatedData);

        $customer = $this->customerService->create($dto);

        // return response()->json([
        //     'success' => true,
        //     'data'    => $customer,
        // ], 201);

        return redirect()->back()->with('success', 'Thêm khách hàng thành công!');
    }

    /**
     * Update an existing customer.
     */
    public function update(CustomerRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $dto = CustomerCreateData::fromArray($validatedData);

        $updated = $this->customerService->update($id, $dto);

        // return response()->json([
        //     'success' => true,
        //     'message' => $updated ? 'Cập nhật khách hàng thành công.' : 'Không thể cập nhật khách hàng.'
        // ]);
        return redirect()->back()->with('success', 'Cập nhật khách hàng thành công!');
    }

    /**
     * Delete a customer by ID.
     */
    public function delete(string $id)
    {
        $deleted = $this->customerService->delete($id);

        // return response()->json([
        //     'success' => $deleted,
        //     'message' => $deleted ? 'Xóa khách hàng thành công.' : 'Không thể xóa khách hàng.'
        // ]);
        return redirect()->back()->with('success', 'Xóa khách hàng thành công!');
    }


    /**
     * Search customers by query.
     */
    public function search(SearchRequest $request)
    {
        $query = $request->query('query');
        $customers = $this->customerService->search($query);

        // return response()->json([
        //     'success' => true,
        //     'data'    => $customers,
        // ]);
        return view('layout.customer.content', [
            'customers' => $customers
        ]);
    }
}
