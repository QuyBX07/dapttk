<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            'category_id' => (string) Str::uuid(), // UUID cho category_id
            'name' => 'Electronics', // Tên danh mục
        ]);

        DB::table('categories')->insert([
            'category_id' => (string) Str::uuid(),
            'name' => 'Furniture',
        ]);

        DB::table('categories')->insert([
            'category_id' => (string) Str::uuid(),
            'name' => 'Clothing',
        ]);
    }
    
}
