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
        Schema::create('shop_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('ean')->nullable();
            $table->string('colore')->nullable();
            $table->string('taglia')->nullable();
            $table->decimal('prezzo', 10, 2)->default(0);
            $table->decimal('prezzo_scontato', 10, 2)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_variants');
    }
};
