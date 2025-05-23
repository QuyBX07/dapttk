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
    public function getAll()
    {
        $products = $this->productService->getAll();
        // return response()->json([
        //     'success' => true,
        //     'data' => $products
        // ]);
        return view('layout.product.content', [
            'products' => $products
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
        // Lấy dữ liệu đã được validate
        $validatedData = $request->validated();

        // Xử lý lưu ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Lưu vào storage/app/public/images
            $validatedData['image'] = 'storage/' . $imagePath; // Lưu đường dẫn để sử dụng khi hiển thị
        } else {
            $validatedData['image'] = null;
        }

        // Tạo DTO
        $productDTO = ProductCreateData::fromArray($validatedData);

        // Gọi service
        $product = $this->productService->create($productDTO);

        
        return redirect()->back()->with('success', 'Thêm sản phẩm thành công!');
    }


    /**
     * Update an existing product.
     */
    public function update(ProductRequest $request, string $id)
    {
        // Lấy dữ liệu đã được xác thực từ request
        $validatedData = $request->validated();
        // Xử lý lưu ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Lưu vào storage/app/public/images
            $validatedData['image'] = 'storage/' . $imagePath; // Lưu đường dẫn để sử dụng khi hiển thị
        } else {
            $validatedData['image'] = $request->input('old_image');
        }


        $productDTO = ProductCreateData::fromArray($validatedData);




        // Gửi DTO vào service để xử lý cập nhật sản phẩm
        $product = $this->productService->update($id, $productDTO);

     
        return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Delete a product by ID.
     */
    public function delete(string $id)
    {
        $this->productService->delete($id);
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!');
    }

    public function search(SearchRequest $request)
    {
        $query = $request->input('query');
        $products = $this->productService->search($query);

        return view('layout.product.content', [
            'products' => $products
        ]);
    }
}
