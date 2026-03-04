<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('icona')->default('✓');
            $table->integer('ordine')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_service_categories');
    }
};
