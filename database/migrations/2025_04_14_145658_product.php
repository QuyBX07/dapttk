<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('product_id')->primary(); // UUID làm khóa chính

            $table->string('name');
            $table->uuid('category_id'); // Sử dụng UUID làm khóa ngoại
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('cascade'); // FK tới categories.id

            $table->text('description')->nullable();
            $table->string('unit'); // đơn vị tính (cái, hộp, kg,...)
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable(); // ảnh sản phẩm (có thể là URL hoặc path)
            $table->decimal('price', 15, 2); // giá tiền, độ chính xác cao

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};


