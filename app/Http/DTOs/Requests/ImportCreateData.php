<?php

namespace App\Http\DTOs\Requests;

class ImportCreateData
{
    /**
     * @param string $supplier_id
     * @param float $total_amount
     * @param string $import_date
     * @param ImportDetailData[] $details
     */
    public function __construct(
        public readonly string $supplier_id,
        public readonly float $total_amount,
        public readonly array $details,
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
            details: $details,
        );
    }

    public function toArray(): array
    {
        return [
            'import' => [
                'supplier_id'  => $this->supplier_id,
                'total_amount' => $this->total_amount,
            ],
            'details' => array_map(fn($d) => $d->toArray(), $this->details),
        ];
    }
}
