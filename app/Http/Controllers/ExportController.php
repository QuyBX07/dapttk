<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Export;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Account;
use App\Models\ExportDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    // Hiển thị danh sách đơn xuất
    public function index()
    {
        $exports = Export::with(['customer', 'account'])->paginate(10);
        $products = Product::all();
        $customers = Customer::all();

        return view('layout.export.content', compact('exports', 'products', 'customers'));
    }

    public function show($id)
{
    $export = Export::with(['customer', 'account', 'details.product'])->findOrFail($id);

    return response()->json([
        'export_id' => $export->export_id,
        'customer' => $export->customer->name,
        'account' => $export->account->name,
        'total_amount' => $export->total_amount,
        'created_at' => $export->created_at->format('Y-m-d H:i:s'),
        'products' => $export->details->map(function ($detail) {
            return [
                'name' => $detail->product->name,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'subtotal' => $detail->quantity * $detail->price,
            ];
        })
    ]);
}

public function detail($id)
{
    $export = Export::with(['customer', 'account'])->find($id);
    $details = ExportDetail::with('product')->where('export_id', $id)->get();

    if (!$export) {
        return response()->json(['error' => 'Export not found'], 404);
    }

    return response()->json([
        'export' => $export,
        'details' => $details
    ]);
}



    // // Cập nhật đơn xuất
    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $export = Export::findOrFail($id);

    //         $export->update([
    //             'customer_id'   => $request->customer_id,
    //             'note'          => $request->note,
    //             'total_amount'  => $request->total_amount,
    //         ]);

    //         // Xóa chi tiết cũ
    //         ExportDetail::where('export_id', $id)->delete();

    //         // Lưu chi tiết mới
    //         foreach ($request->details as $detail) {
    //             ExportDetail::create([
    //                 'export_id' => $id,
    //                 'product_id' => $detail['product_id'],
    //                 'quantity' => $detail['quantity'],
    //                 'price' => $detail['price'],
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect('/exports')->with('success', 'Cập nhật đơn xuất thành công.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return back()->with('error', 'Lỗi: ' . $e->getMessage());
    //     }
    // }

    // Xóa đơn xuất
    public function destroy($id)
    {
        $export = Export::where('export_id', $id)->firstOrFail();
        $export->delete();

        return redirect('/exports')->with('success', 'Xóa đơn xuất thành công.');
    }

public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|uuid|exists:customers,customer_id',
        'details' => 'required|array|min:1',
        'details.*.product_id' => 'required|uuid|exists:products,product_id',
        'details.*.quantity' => 'required|numeric|min:1',
        'details.*.price' => 'required|numeric|min:0',
        'account_id' => 'required|uuid|exists:accounts,id',  // Kiểm tra account_id hợp lệ và tồn tại
    ]);

  

    // Tính tổng tiền
    $totalAmount = array_reduce($request->details, function ($carry, $item) {
        return $carry + ($item['quantity'] * $item['price']);
    }, 0);

    // Tạo phiếu xuất
    $export = Export::create([
        'export_id' => Str::uuid(),
        'customer_id' => $request->customer_id,
        'account_id' => $request->account_id, // Đảm bảo trường này có giá trị hợp lệ
        'total_amount' => $totalAmount,
        'export_date' => now()->toDateString(),
        'is_delete' => 0, // Thiết lập mặc định is_delete là 0 (chưa xóa)
    ]);

    // Tạo chi tiết phiếu xuất
    foreach ($request->details as $detail) {
    ExportDetail::create([
        'exportdetail_id' => Str::uuid(), // Tạo UUID cho khóa chính
        'export_id' => $export->export_id,
        'product_id' => $detail['product_id'],
        'quantity' => $detail['quantity'],
        'price' => $detail['price'],
    ]);
}

    return redirect()->back()->with('success', 'Thêm phiếu xuất thành công.');
}

public function search(Request $request)
{
    $query = $request->input('query');

    $exports = Export::with(['customer', 'account'])
        ->whereHas('customer', function ($q) use ($query) {
            $q->where('name', 'like', "%$query%");
        })
        ->orWhereHas('account', function ($q) use ($query) {
            $q->where('name', 'like', "%$query%");
        })
        ->where('is_delete', 0) // nếu bạn có soft delete
        ->paginate(10);

    return view('layout.export.content', compact('exports'));

}
}
