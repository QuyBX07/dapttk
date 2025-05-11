<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    $this->call([
        AccountsTableSeeder::class,
        CategoriesTableSeeder::class, // Gọi seeder cho bảng categories
        ProductsTableSeeder::class, // Gọi seeder cho bảng products
    ]);
}

}
