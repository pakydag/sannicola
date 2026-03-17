<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('b2b_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_order_id')->constrained('b2b_orders')->onDelete('cascade');
            $table->foreignId('b2b_product_id')->constrained('b2b_products')->onDelete('cascade');
            $table->foreignId('b2b_product_variant_id')->constrained('b2b_product_variants')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2b_order_items');
    }
};
