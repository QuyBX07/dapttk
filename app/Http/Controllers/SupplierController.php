<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Services\SupplierService;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    // Hiển thị tất cả nhà cung cấp
    public function getAll()
    {
        // Phân trang danh sách nhà cung cấp
        $suppliers = Supplier::paginate(10); // 10 là số bản ghi trên mỗi trang
        return view('layout.supplier.content', compact('suppliers'));
    }

    public function __construct(protected SupplierService $supplierService) {}

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

    public function getDetail($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.detail', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        // Tìm nhà cung cấp
        $supplier = Supplier::findOrFail($id);

        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'nullable|string',
        ]);

        // Cập nhật thông tin nhà cung cấp
        $supplier->update($validated);
        return redirect()->back()->with('success', 'Updated khách hàng thành công!');
    }

    public function delete($id)
    {
        // Tìm và xóa nhà cung cấp
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->back()->with('success', 'Xóa khách hàng thành công!');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate(10);
        return view('layout.supplier.content', compact('suppliers'));
    }

    public function getAllSuppliers()
    {
        return Supplier::select('supplier_id', 'name')->get();
    }
}
