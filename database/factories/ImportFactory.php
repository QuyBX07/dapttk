<?php

namespace Database\Factories;

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
            'import_date'  => $this->faker->date(),
        ];
    }
}
