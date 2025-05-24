<?php

namespace App\Http\Repositories\Eloquent;

use App\Models\Export;
use App\Http\Repositories\Interfaces\ExportRepoInterface;
use Illuminate\Support\Facades\DB;
use App\Models\ExportDetail;
use App\Models\Product;
use Illuminate\Support\Str;

class ExportRepository implements ExportRepoInterface
{


    /**
     * Tạo phiếu xuất và chi tiết, trừ tồn kho sản phẩm.
     * 
     * @param array $exportData  Các trường cho bảng Export (customer_id, account_id, total_amount,...)
     * @param array $details     Mảng chi tiết sản phẩm: product_id, quantity, price
     * @return Export           Phiếu xuất vừa tạo
     * @throws \Exception       Nếu lỗi trong quá trình tạo
     */

    public function getTotalRevenueByYear($year)
    {
        return DB::table('exports')
            ->whereYear('created_at', $year)
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->value('total_revenue');
    }

    public function getTotalRevenueByMonth($year, $month)
    {
        return DB::table('exports')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->value('total_revenue');
    }

    public function findByIdWithRelations($id)
    {
        return Export::with(['customer', 'account', 'details.product'])
            ->where('export_id', $id)
            ->where('is_delete', 0)
            ->firstOrFail();
    }

    public function getExportWithDetails($id)
    {
        $export = Export::with(['customer', 'account'])->find($id);
        if (!$export) {
            return null; // hoặc throw exception tùy bạn
        }

        $details = ExportDetail::with('product')->where('export_id', $id)->get();

        return [
            'export' => $export,
            'details' => $details
        ];
    }

    public function softDelete($id)
    {
        $export = Export::where('export_id', $id)->firstOrFail();
        $export->is_delete = 1;
        return $export->save();
    }

    public function createExport(array $exportData, array $details)
    {
        return DB::transaction(function () use ($exportData, $details) {
            // Tạo phiếu xuất
            $export = Export::create(array_merge($exportData, [
                'export_id' => Str::uuid(),
                'is_delete' => 0,
                'export_date' => now()->toDateString(),
            ]));

            // Tạo chi tiết và trừ tồn kho
            foreach ($details as $detail) {
                $product = Product::findOrFail($detail['product_id']);

                if ($product->quantity < $detail['quantity']) {
                    throw new \Exception('Sản phẩm "' . $product->name . '" không đủ số lượng tồn kho.');
                }

                // Tạo chi tiết phiếu xuất
                ExportDetail::create([
                    'exportdetail_id' => Str::uuid(),
                    'export_id' => $export->export_id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);

                // Trừ tồn kho
                $product->quantity -= $detail['quantity'];
                $product->save();
            }

            return $export;
        });
    }

    public function revenueByCategory(int $month)
    {
        return DB::table('export_details')
            ->join('products', 'export_details.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('exports', 'export_details.export_id', '=', 'exports.export_id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(export_details.quantity * export_details.price) as total_revenue')
            )
            ->whereMonth('exports.created_at', $month)
            ->groupBy('categories.name')
            ->get();
    }

    public function getDailyRevenue(int $month, int $year)
    {
        return DB::table('exports')
            ->selectRaw('DAY(export_date) as day, SUM(total_amount) as total')
            ->whereMonth('export_date', $month)
            ->whereYear('export_date', $year)
            ->where('is_delete', 0)
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }
}
