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
        Schema::table('booking_structures', function (Blueprint $table) {
            $table->string('tipo_prezzo')->default('fisso')->after('costo_al_giorno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_structures', function (Blueprint $table) {
            //
        });
    }
};
