<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->unique()->uuid(), // ID sản phẩm ngẫu nhiên
            'name' => $this->faker->word(), // Tên sản phẩm ngẫu nhiên
            'category_id' => \App\Models\Category::inRandomOrder()->first()->category_id ?? \App\Models\Category::factory()->create()->category_id,

            'description' => $this->faker->sentence(), // Mô tả ngẫu nhiên
            'unit' => $this->faker->randomElement(['piece', 'box', 'kg']), // Đơn vị ngẫu nhiên
            'quantity' => $this->faker->numberBetween(0, 100), // Số lượng ngẫu nhiên
            'image' => $this->faker->imageUrl(640, 480, 'products', true), // URL ảnh ngẫu nhiên
            'price' => $this->faker->randomFloat(2, 1, 1000), // Giá ngẫu nhiên
            'is_deleted' => false, // Không bị xóa
        ];
    }
}
