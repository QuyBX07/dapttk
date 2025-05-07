<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\DTOs\Requests\ProductCreateData;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchRequest;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products.
     */
    public function getAll(): JsonResponse
    {
        $products = $this->productService->getAll();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get product details by ID.
     */
    public function getDetail(string $id): JsonResponse
    {
        $product = $this->productService->getDetail($id);
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Create a new product.
     */
    public function create(ProductRequest $request)
    {
        // Lấy dữ liệu đã được xác thực từ request
        $validatedData = $request->validated();
        $productDTO = ProductCreateData::fromArray($validatedData);
        // Chuyển đổi dữ liệu thành ProductDTO
        // $productDTO = new ProductCreateData(
        //     $validatedData['name'],
        //     $validatedData['category_id'],
        //     $validatedData['description'],
        //     $validatedData['unit'],
        //     $validatedData['quantity'],
        //     $validatedData['image'],
        //     (float) $validatedData['price'],
        // );
        
        // Gửi DTO vào service để xử lý tạo sản phẩm
        $product = $this->productService->create($productDTO);

        return response()->json([
            'success' => true,
            'data' => $product
        ], 201);
    }

    /**
     * Update an existing product.
     */
    public function update(ProductRequest $request, string $id): JsonResponse
    {
        // Lấy dữ liệu đã được xác thực từ request
        $validatedData = $request->validated();

        $productDTO = ProductCreateData::fromArray($validatedData);


        // Chuyển đổi dữ liệu thành ProductDTO
        // $productDTO = new ProductCreateData(
        //     $validatedData['name'],
        //     $validatedData['category_id'],
        //     $validatedData['description'],
        //     $validatedData['unit'],
        //     $validatedData['quantity'],
        //     $validatedData['image'],
        //     (float) $validatedData['price'],
        // );
        

        // Gửi DTO vào service để xử lý cập nhật sản phẩm
        $product = $this->productService->update($id, $productDTO);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Delete a product by ID.
     */
    public function delete(string $id): JsonResponse
    {
        $this->productService->delete($id);
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }

    public function search(SearchRequest $request): JsonResponse
{
    $query = $request->input('query');
    $products = $this->productService->search($query);

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}
}
