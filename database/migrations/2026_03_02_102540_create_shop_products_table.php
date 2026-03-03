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
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->foreignId('shop_category_id')->nullable()->constrained('shop_categories')->nullOnDelete();
            $table->foreignId('shop_collection_id')->nullable()->constrained('shop_collections')->nullOnDelete();
            $table->string('marca')->nullable();
            $table->text('descrizione')->nullable();
            $table->json('foto_aggiuntive')->nullable();
            $table->string('sku_padre')->nullable();
            $table->boolean('visibile')->default(true);
            $table->integer('ordine')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
