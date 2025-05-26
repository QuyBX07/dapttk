<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('accounts')->insert([
            'id' => (string) Str::uuid(), // Tạo UUID cho account
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Mật khẩu đã mã hóa
            'phone' => '123456789',
            'role' => 'admin', // Vai trò là admin
        ]);

        DB::table('accounts')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'John Doe',
            'email' => '6s',
            'password' => bcrypt('password'),
            'phone' => '987654321',
            'role' => 'user', // Vai trò là user
        ]);
    }
}
