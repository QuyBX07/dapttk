<?php

namespace App\Http\Repositories\Eloquent;


use App\Models\Import;
use App\Http\Repositories\Interfaces\ImportRepoInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\Interfaces\OnlyDeleteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Repositories\Interfaces\StatisticsRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Product;

class ImportRepository implements ImportRepoInterface
{
    public function findAll()
    {
        return Import::with('account:id,name')
            ->with('supplier:supplier_id,name')
            ->orWhere('is_delete', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);;
    }

    public function find(string $id)
    {
        return Import::with([
            'importDetails.product:product_id,name',
            'supplier:supplier_id,name',
            'account:id,name'
        ])->findOrFail($id);
    }



    public function update(string $id, array $data): bool
    {
        return Import::findOrFail($id)->update($data);
    }



    public function delete(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $import = Import::findOrFail($id);

            // Gán account_id người dùng đang đăng nhập

            $import->account_id = Auth::user()->id; // hoặc Auth::id() nếu là khóa chính

            // Đánh dấu là đã xóa
            $import->is_delete = 1;

            return $import->save();
        });
    }



    public function create(array $importData)
    {
        return DB::transaction(function () use ($importData) {
            // Tính tổng tiền từ chi tiết
            $totalAmount = collect($importData['details'])->reduce(function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            // Gán vào dữ liệu import
            $importData['import']['total_amount'] = $totalAmount;

            // Tạo phiếu nhập
            $import = Import::create($importData['import']);

            // Tạo chi tiết và cập nhật tồn kho
            foreach ($importData['details'] as $detail) {
                $import->importDetails()->create($detail);
                Product::where('product_id', $detail['product_id'])->increment('quantity', $detail['quantity']);
            }

            return $import;
        });
    }




    public function search(string $query)
    {
        return Import::with('account', 'supplier') // bỏ :id,name đi
            ->where(function ($q) use ($query) {
                $q->where('import_id', 'like', '%' . $query . '%')
                    ->orWhereHas('supplier', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('account', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }

    public function getDeleted()
    {
        return Import::with('account:id,name')
            ->with('supplier:supplier_id,name')
            ->where('is_delete', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }



    public function getTotalImportCostByYear($year)
    {
        return DB::table('imports')
            ->whereYear('created_at', $year)
            ->where('is_delete', false)
            ->sum('total_amount');
    }

    public function getTotalImportByMonth($year, $month)
    {
        return DB::table('imports')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('is_delete', false)
            ->sum('total_amount');
    }

    public function importCostByCategory(?int $month, ?int $year)
    {
        return DB::table('import_details')
            ->join('products', 'import_details.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->selectRaw('categories.name as label, SUM(import_details.price * import_details.quantity) as total')
            ->when($month, function ($query, $month) {
                return $query->whereMonth('import_details.created_at', $month);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('import_details.created_at', $year);
            })
            ->groupBy('categories.name')
            ->get();
    }
}
