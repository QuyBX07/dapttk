<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // Tạo sản phẩm cho danh mục Electronics
        $electronics = DB::table('categories')->where('name', 'Electronics')->first();
        if ($electronics) {
            DB::table('products')->insert([
                'product_id' => (string) Str::uuid(),
                'name' => 'Smartphone',
                'category_id' => $electronics->category_id,
                'description' => 'High-end smartphone with amazing features.',
                'unit' => 'piece',
                'quantity' => 100,
                'image' => 'smartphone.jpg',
                'price' => 699.99,
            ]);
        } else {
            echo "❌ Không tìm thấy danh mục 'Electronics'.\n";
        }

        // Tạo sản phẩm cho danh mục Furniture
        $furniture = DB::table('categories')->where('name', 'Furniture')->first();
        if ($furniture) {
            DB::table('products')->insert([
                'product_id' => (string) Str::uuid(),
                'name' => 'Sofa',
                'category_id' => $furniture->category_id,
                'description' => 'Comfortable leather sofa.',
                'unit' => 'set',
                'quantity' => 50,
                'image' => 'sofa.jpg',
                'price' => 499.99,
            ]);
        } else {
            echo "❌ Không tìm thấy danh mục 'Furniture'.\n";
        }
    }
}
