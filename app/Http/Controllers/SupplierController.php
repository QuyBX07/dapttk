<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\SupplierService;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}


    // Hiển thị danh sách nhà cung cấp (có phân trang)
    public function getAll()
    {
        $suppliers = $this->supplierService->getPaginated(10); // Sử dụng Service
        return view('layout.supplier.content', compact('suppliers'));
    }

    // Tạo mới nhà cung cấp
    public function create(Request $request)
    {
        try {
            $this->supplierService->create($request->all());
            return redirect()->back()->with('success', 'Thêm nhà cung cấp thành công!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    // Chi tiết nhà cung cấp
    public function getDetail($id)
    {
        $supplier = $this->supplierService->getDetail($id);
        return view('suppliers.detail', compact('supplier'));
    }

    // Cập nhật nhà cung cấp
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email',
                'address' => 'nullable|string',
            ]);

            $this->supplierService->update($id, $validated);
            return redirect()->back()->with('success', 'Cập nhật nhà cung cấp thành công!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    // Xóa nhà cung cấp
    public function delete($id)
    {
        try {
            $this->supplierService->delete($id);
            return redirect()->back()->with('success', 'Xóa nhà cung cấp thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

    // Tìm kiếm nhà cung cấp
    public function search(Request $request)
    {
        $query = $request->get('query');
        $suppliers = $this->supplierService->search($query);
        return view('layout.supplier.content', compact('suppliers'));
    }

    // Lấy danh sách tất cả nhà cung cấp (dùng cho dropdown/select box)
    public function getAllSuppliers()
    {
        return $this->supplierService->getAllForSelect();
    }
}
