<?php
namespace App\Http\DTOs\Responses;
use App\Models\Import;
class ImportResponse
{
    public function __construct(public Import $import) {}

    public function toArray(): array
    {
        return [
            'import_id'    => $this->import->import_id,
            'supplier'  => [
                'supplier_id'   => $this->import->supplier->supplier_id,
                'name' => $this->import->supplier->name,
            ],
            'total_amount' => $this->import->total_amount,
            'note'         => $this->import->note,
            'created_at'   => $this->import->created_at,
            'updated_at'   => $this->import->updated_at,
            'account' => [
                'id'   => $this->import->account->id,
                'name' => $this->import->account->name,
            ],
        ];
    }
}
