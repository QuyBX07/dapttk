<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\BaseRepositoryInterface;
use App\Http\Repositories\Interfaces\ImportRepositoryInterface;
use App\Models\Import;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ImportRepository implements BaseRepositoryInterface, ImportRepositoryInterface, SearchRepositoryInterface
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
            'importDetails.product:product_id,name',  // lấy name của product
            'account:id,name',
            'supplier:supplier_id,name'
        ])->findOrFail($id);
    }

    public function create(array $data): Import
    {
        return Import::create($data);
    }

    public function update(string $id, array $data): bool
    {
        return Import::findOrFail($id)->update($data);
    }

    public function delete(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $import = Import::findOrFail($id);

            // Xoá các chi tiết
            // $import->importDetails()->delete();

            // Xoá import
            $import->is_delete = 1;
            return $import->save();
        });
    }


    public function createWithDetails(ImportCreateData $importData): Import
    {

        return DB::transaction(function () use ($importData) {
            // dd($importData->toArray()['import']);
            $import = Import::create($importData->toArray()['import']);  // Dữ liệu nhập khẩu

            foreach ($importData->details as $detail) {
                $import->importDetails()->create($detail->toArray());  // Tạo chi tiết nhập khẩu
            }

            return $import;
        });
    }


    public function search(string $query) {}
}
