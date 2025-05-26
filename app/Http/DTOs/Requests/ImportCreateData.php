<?php

namespace App\Http\DTOs\Requests;

class ImportCreateData
{

    public function __construct(
        public readonly string $supplier_id,
        public readonly float $total_amount,
        public readonly ?string $note,
        public readonly string $account_id,
        public readonly array $details,
        public readonly int $is_delete,
    ) {}

    public static function fromArray(array $data): self
    {
        $details = array_map(
            fn($item) => ImportDetailData::fromArray($item),
            $data['details'] ?? []
        );

        return new self(
            supplier_id: $data['supplier_id'],
            total_amount: $data['total_amount'],
            note: $data['note'] ?? null,
            account_id: $data['account_id'],
            is_delete:  0,
            details: $details,
        );
    }

    public function toArray(): array
    {
        return [
            'import' => [
                'supplier_id'  => $this->supplier_id,
                'total_amount' => $this->total_amount,
                'note'         => $this->note,
                'account_id'   => $this->account_id,
                'is_delete'    => $this->is_delete,
            ],
            'details' => array_map(fn($d) => $d->toArray(), $this->details),
        ];
    }
}
