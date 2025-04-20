<?php

namespace App\Http\DTOs\Requests;

class ImportDetailData
{
    public function __construct(
        public readonly string $product_id,
        public readonly int $quantity,
        public readonly float $price
    ) {}

    /**
     * Chuyển đổi mảng thành đối tượng ImportDetailData
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            price: $data['price']
        );
    }

    /**
     * Chuyển đối tượng ImportDetailData thành mảng
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity'    => $this->quantity,
            'price'      => $this->price,
        ];
    }
}
