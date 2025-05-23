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
    $exports = Export::with(['customer', 'account'])
        ->where('is_delete', 0)
        ->paginate(10);

    $products = Product::all();
    $customers = Customer::all();

    return view('layout.export.content', compact('exports', 'products', 'customers'));
}


public function show($id)
{
    $export = Export::with(['customer', 'account', 'details.product'])
        ->where('export_id', $id)
        ->where('is_delete', 0)
        ->firstOrFail();

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

    // Hiển thị chi tiết đơn xuất

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
    // Xóa đơn xuất
    public function destroy($id)
    {
        $export = Export::where('export_id', $id)->firstOrFail();
        $export->is_delete = 1;
        $export->save();

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

    // Tạo chi tiết phiếu xuất và trừ số lượng sản phẩm
    foreach ($request->details as $detail) {
        // Tạo chi tiết phiếu xuất
        ExportDetail::create([
            'exportdetail_id' => Str::uuid(), // Tạo UUID cho khóa chính
            'export_id' => $export->export_id,
            'product_id' => $detail['product_id'],
            'quantity' => $detail['quantity'],
            'price' => $detail['price'],
        ]);

        // Cập nhật tồn kho sản phẩm
        $product = Product::findOrFail($detail['product_id']);

        // Kiểm tra nếu số lượng tồn kho còn đủ để xuất
        if ($product->quantity < $detail['quantity']) {
            return back()->with('error', 'Sản phẩm "' . $product->name . '" không đủ số lượng tồn kho.');
        }

        // Trừ số lượng sản phẩm trong kho
        $product->quantity -= $detail['quantity'];
        $product->save();
    }

    return redirect('/exports')->with('success', 'Tạo phiếu xuất thành công.');
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
