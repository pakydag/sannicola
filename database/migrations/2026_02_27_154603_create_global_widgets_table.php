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
        Schema::create('global_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->string('tipo'); // gallery, video, mirror_blocks
            $table->json('data')->nullable(); // Stores specific widget data like URLs, limits, section_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_widgets');
    }
};
