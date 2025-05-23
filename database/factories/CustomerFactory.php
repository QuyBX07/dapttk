<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * Định nghĩa các thuộc tính mẫu cho mô hình Customer.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => Str::uuid(),  // Tạo UUID cho customer_id
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'is_deleted' => false,  
        ];
    }
}
