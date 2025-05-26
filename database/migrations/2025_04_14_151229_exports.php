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
            $table->boolean('is_delete')->default(0); // Trạng thái xuất khẩu (1: đã xóa, 0: chưa xóa)
            $table->uuid('account_id'); // Người tạo phiếu xuất

            // Thêm khóa ngoại với bảng accounts
            $table->foreign('account_id')
                  ->references('id')  // Khóa chính trong bảng accounts
                  ->on('accounts')
                  ->onDelete('cascade'); // Khi xóa tài khoản thì xóa luôn xuất khẩu liên quan

            // Thêm khóa ngoại với bảng customers
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