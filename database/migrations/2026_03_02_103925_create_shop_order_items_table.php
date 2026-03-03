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
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_order_id')->constrained('shop_orders')->cascadeOnDelete();
            $table->foreignId('shop_variant_id')->nullable()->constrained('shop_variants')->nullOnDelete();
            $table->string('nome_prodotto');
            $table->string('sku')->nullable();
            $table->string('colore')->nullable();
            $table->string('taglia')->nullable();
            $table->integer('quantita')->default(1);
            $table->decimal('prezzo_unitario', 10, 2)->default(0);
            $table->decimal('subtotale', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
    }
};
