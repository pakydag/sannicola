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
        Schema::create('booking_structure_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_structure_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_extra_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_structure_extras');
    }
};
