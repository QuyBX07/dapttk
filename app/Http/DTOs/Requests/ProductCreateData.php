<?php


namespace App\Http\DTOs\Requests;

class ProductCreateData
{
    public function __construct(
        public readonly string $name,
        public readonly string $category_id,
        public readonly ?string $description,
        public readonly string $unit,
        public readonly int $quantity,
        public readonly ?string $image,
        public readonly float $price,
    ) {}

    /**
     * Tạo DTO từ dữ liệu đã được validate (ví dụ từ FormRequest)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            category_id: $data['category_id'],
            description: $data['description'] ?? null,
            unit: $data['unit'],
            quantity: $data['quantity'] ?? 0,
            image: $data['image'] ?? null,
            price: (float) $data['price'],
        );
    }

    /**
     * Chuyển DTO thành array để dùng cho Repository / Model
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'price' => $this->price,
        ];
    }
}
