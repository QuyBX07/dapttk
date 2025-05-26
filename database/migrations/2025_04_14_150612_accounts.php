<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID làm khóa chính
            $table->string('name');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('email')->unique(); // email không trùng
            $table->string('role')->default('admin'); // ví dụ: admin, user

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

