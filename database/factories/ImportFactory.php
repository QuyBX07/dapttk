<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Import>
 */
class ImportFactory extends Factory
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
            'import_id'    => (string) Str::uuid(),
            'supplier_id'  => Supplier::inRandomOrder()->first()?->supplier_id ?? Supplier::factory(),

            'total_amount' => $this->faker->randomFloat(2, 100000, 1000000),
            'is_delete'    => false,
            'note'         => $this->faker->sentence(),
            'account_id'   => Account::inRandomOrder()->first()?->id?? Account::factory(), // Tạo người dùng tương ứng
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}
