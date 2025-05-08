<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->uuid('import_id')->primary(); // UUID làm khóa chính
            $table->uuid('supplier_id');   // Khóa ngoại trỏ đến bảng customers
            $table->decimal('total_amount', 15, 2); // Tổng tiền, độ chính xác cao
            $table->boolean('is_delete')->default(0); // Trạng thái nhập khẩu (1: đã xóaxóa, 0: chưa xóa)
            $table->string('note')->nullable(); // Ghi chú
            $table->uuid('account_id'); // Người tạo phiếu nhập
            $table->foreign('account_id')
                ->references('id')  // Khóa chính trong bảng accounts
                ->on('accounts')
                ->onDelete('cascade'); // Khi xóa tài khoản thì xóa luôn nhập khẩu liên quan
            $table->foreign('supplier_id')
                ->references('supplier_id')
                ->on('suppliers')
                ->onDelete('cascade'); // Khi xóa khách hàng thì xóa luôn nhập khẩu liên quan

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
