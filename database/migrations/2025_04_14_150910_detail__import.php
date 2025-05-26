<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_details', function (Blueprint $table) {
            $table->uuid('importdetail_id')->primary(); 
            $table->uuid('product_id');   
            $table->uuid('import_id');    
            $table->integer('quantity');   
            $table->decimal('price', 15, 2); 

           
            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade'); 

            $table->foreign('import_id')
                  ->references('import_id')
                  ->on('imports')
                  ->onDelete('cascade'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_details');
    }
};

