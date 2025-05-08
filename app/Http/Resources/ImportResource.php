<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'import_id' => $this->import_id,
            'supplier_id' => $this->supplier_id,
            'total_amount' => $this->total_amount,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'account' => [
                'id' => $this->account->id,
                'name' => $this->account->name
            ]
        ];
    }
}
