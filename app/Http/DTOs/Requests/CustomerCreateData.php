<?php


namespace App\Http\DTOs\Requests;

class CustomerCreateData
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $email,
        public readonly string $address,
 
    ) {}

    /**
     * Tạo DTO từ dữ liệu đã được validate (ví dụ từ FormRequest)
     */
    public static function fromArray(array $data): self
    { 
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            email : $data['email'],
            address: $data['address']
          
        );
    }

    /**
     * Chuyển DTO thành array để dùng cho Repository / Model
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
           
        ];
    }
}
