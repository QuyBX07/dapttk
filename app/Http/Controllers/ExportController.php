<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Export;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Account;
use App\Models\ExportDetail;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function index()
        {
            $exports = Export::with(['customer', 'account'])->paginate(10);
            $products = Product::all();
            $customers = Customer::all();

            return view('layout.export.content', compact('exports', 'products', 'customers'));
        }

        public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $export = Export::create([
                'customer_id' => $request->customer_id,
                'account_id' => $request->account_id,
                'note' => $request->note,
                'total_amount' => $request->total_amount,
            ]);

            foreach ($request->details as $detail) {
                ExportDetail::create([
                    'export_id' => $export->export_id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);
            }

            DB::commit();
            return redirect('/exports')->with('success', 'Tạo đơn xuất thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $export = Export::with(['customer', 'account', 'details.product'])->findOrFail($id);
        return response()->json($export);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $export = Export::findOrFail($id);
            $export->update([
                'customer_id' => $request->customer_id,
                'note' => $request->note,
                'total_amount' => $request->total_amount,
            ]);

            ExportDetail::where('export_id', $id)->delete();

            foreach ($request->details as $detail) {
                ExportDetail::create([
                    'export_id' => $id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);
            }

            DB::commit();
            return redirect('/exports')->with('success', 'Cập nhật đơn xuất thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Export::findOrFail($id)->delete();
        return redirect('/exports')->with('success', 'Xóa đơn xuất thành công.');
    }
}
