<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_details', function (Blueprint $table) {
            $table->uuid('importdetail_id')->primary(); // UUID làm khóa chính
            $table->uuid('product_id');   // Khóa ngoại trỏ đến bảng products
            $table->uuid('import_id');    // Khóa ngoại trỏ đến bảng imports
            $table->integer('quality');   // Số lượng sản phẩm nhập
            $table->decimal('price', 15, 2); // Giá nhập sản phẩm

            // Thiết lập quan hệ khóa ngoại
            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade'); // Khi xóa sản phẩm sẽ xóa chi tiết nhập khẩu liên quan

            $table->foreign('import_id')
                  ->references('import_id')
                  ->on('imports')
                  ->onDelete('cascade'); // Khi xóa nhập khẩu sẽ xóa chi tiết nhập khẩu liên quan

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_details');
    }
};

