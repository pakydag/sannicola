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
        Schema::create('car_rental_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('email');
            $table->string('telefono');
            $table->date('data_ritiro');
            $table->string('orario_ritiro');
            $table->date('data_riconsegna');
            $table->string('orario_riconsegna');
            $table->integer('numero_posti');
            $table->text('messaggio')->nullable();
            $table->boolean('letto')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_rental_requests');
    }
};
