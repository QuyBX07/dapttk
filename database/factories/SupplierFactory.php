<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'supplier_id' => Str::uuid(), // Tạo UUID thủ công
            'name'        => $this->faker->company,
            'phone'       => $this->faker->phoneNumber,
            'email'       => $this->faker->unique()->safeEmail,
            'address'     => $this->faker->address,
            'is_deleted'  => false, // Không bị xóa
        ];
    }
}
