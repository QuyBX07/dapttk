<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // Hiển thị tất cả nhà cung cấp
public function getAll()
{
    // Phân trang danh sách nhà cung cấp
    $suppliers = Supplier::paginate(10); // 10 là số bản ghi trên mỗi trang
    return view('layout.supplier.content', compact('suppliers'));
}

    // Thêm mới nhà cung cấp
    public function create(Request $request)
    {
        // Validate dữ liệu từ form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Tạo mới nhà cung cấp
        $supplier = new Supplier();
        $supplier->name = $validated['name'];
        $supplier->phone = $validated['phone'];
        $supplier->email = $validated['email'];
        $supplier->address = $validated['address'];
        $supplier->save();

        // Chuyển hướng lại trang danh sách với thông báo thành công
        return redirect()->back()->with('success', 'Thêm khách hàng thành công!');
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

}
