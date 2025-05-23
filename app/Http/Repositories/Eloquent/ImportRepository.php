<?php

namespace App\Http\Repositories\Eloquent;


use App\Models\Import;
use App\Http\Repositories\Interfaces\ImportRepoInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\Interfaces\OnlyDeleteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Repositories\Interfaces\StatisticsRepositoryInterface;
use Illuminate\Support\Collection;

class ImportRepository implements ImportRepoInterface, 
                                    OnlyDeleteRepositoryInterface,
                                    StatisticsRepositoryInterface  
{
    public function findAll()
    {
        return Import::with('account:id,name')
            ->with('supplier:supplier_id,name')
            ->orWhere('is_delete', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(2);;
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
            // dd($importData->toArray()['import']);
            $import = Import::create($importData['import']);  // Dữ liệu nhập khẩu

            foreach ($importData['details'] as $detail) {
                $import->importDetails()->create($detail);  // Tạo chi tiết nhập khẩu
            }

            return $import;
        });
    }


    public function search(string $query) {}

    public function getDeleted()
    {
        return Import::with('account:id,name')
            ->with('supplier:supplier_id,name')
            ->where('is_delete', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(2);
    }



    function getTotalRevenueByYear($year)
{
    return DB::table('export_details')
        ->join('exports', 'export_details.export_id', '=', 'exports.export_id')
        ->whereYear('exports.created_at', $year)
        ->selectRaw('SUM(export_details.quantity * export_details.price) as total_revenue')
        ->value('total_revenue'); // Trả về một giá trị duy nhất
}
}
