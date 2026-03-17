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
        Schema::create('b2b_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('b2b_brand_id')->constrained('b2b_brands')->onDelete('cascade');
            $table->string('season')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('has_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2b_products');
    }
};
