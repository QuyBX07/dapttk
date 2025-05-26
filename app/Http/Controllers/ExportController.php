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
use App\Http\Services\ExportService;

class ExportController extends Controller
{

    public function __construct(protected ExportService $exportService)
    {
        $this->exportService = $exportService;
    }


    // Thống kê doanh thu
    public function getTotalRevenueByYear(Request $request)
    {
        $year = $request->input('year', now()->year); // Mặc định là năm hiện tại nếu không truyền
        $totalRevenue = $this->exportService->getTotalRevenueByYear($year);

        return response()->json([
            'total_revenue' => $totalRevenue,
            'year' => $year,
        ]);
    }

    public function getTotalRevenueByMonth(Request $request)
    {
        $year = $request->input('year', now()->year);   // Mặc định năm hiện tại
        $month = $request->input('month', now()->month); // Mặc định tháng hiện tại

        $totalRevenue = $this->exportService->getTotalRevenueByMonth($year, $month);

        return response()->json([
            'total_revenue' => $totalRevenue,
            'year' => $year,
            'month' => $month,
        ]);
    }




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
        return response()->json($this->exportService->getExportDetails($id));
    }

    // Hiển thị chi tiết đơn xuất

    public function detail($id)
    {
        $data = $this->exportService->getExportWithDetails($id);

        if (!$data) {
            return response()->json(['error' => 'Export not found'], 404);
        }

        return response()->json($data);
    }
    // Xóa đơn xuất
    public function destroy($id)
    {
        $this->exportService->softDeleteExport($id);

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
            ->where(function ($q) use ($query) {
                $q->whereHas('customer', function ($q2) use ($query) {
                    $q2->where('name', 'like', "%$query%");
                })->orWhereHas('account', function ($q2) use ($query) {
                    $q2->where('name', 'like', "%$query%");
                });
            })
            ->where('is_delete', 0)
            ->paginate(10);

        $products = Product::all();
        $customers = Customer::all();

        return view('layout.export.content', compact('exports', 'products', 'customers'));
    }

    public function removeIndex()
    {
        $exports = Export::with(['customer', 'account'])
            ->where('is_delete', 1)
            ->paginate(10);

        $products = Product::all();
        $customers = Customer::all();

        return view('layout.export.viewDelete', compact('exports', 'products', 'customers'));
    }
}
