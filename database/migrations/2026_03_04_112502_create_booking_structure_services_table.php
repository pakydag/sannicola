<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_structure_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_structure_id')->constrained('booking_structures')->onDelete('cascade');
            $table->foreignId('booking_service_id')->constrained('booking_services')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['booking_structure_id', 'booking_service_id'], 'structure_service_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_structure_services');
    }
};
