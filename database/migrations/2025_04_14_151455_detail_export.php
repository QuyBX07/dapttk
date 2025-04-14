<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('export_details', function (Blueprint $table) {
            $table->uuid('exportdetail_id')->primary(); // UUID làm khóa chính
            $table->uuid('product_id');   // Khóa ngoại trỏ đến bảng products
            $table->uuid('export_id');    // Khóa ngoại trỏ đến bảng exports
            $table->integer('quantity');  // Số lượng sản phẩm xuất
            $table->decimal('price', 15, 2); // Giá xuất sản phẩm

            // Thiết lập quan hệ khóa ngoại
            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade'); // Khi xóa sản phẩm sẽ xóa chi tiết xuất khẩu liên quan

            $table->foreign('export_id')
                  ->references('export_id')
                  ->on('exports')
                  ->onDelete('cascade'); // Khi xóa xuất khẩu sẽ xóa chi tiết xuất khẩu liên quan

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_details');
    }
};

