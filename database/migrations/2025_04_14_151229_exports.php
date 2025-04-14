<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->uuid('export_id')->primary(); // UUID làm khóa chính
            $table->uuid('customer_id');   // Khóa ngoại trỏ đến bảng customers
            $table->decimal('total_amount', 15, 2); // Tổng tiền, độ chính xác cao
            $table->date('export_date'); // Ngày xuất

            $table->foreign('customer_id')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade'); // Khi xóa khách hàng thì xóa luôn xuất khẩu liên quan

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};

