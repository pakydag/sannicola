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
        Schema::create('transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('email');
            $table->string('telefono');
            $table->string('luogo_arrivo');
            $table->date('data');
            $table->string('orario');
            $table->integer('numero_persone');
            $table->boolean('andata_ritorno')->default(false);
            $table->date('data_ritorno')->nullable();
            $table->string('orario_ritorno')->nullable();
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
        Schema::dropIfExists('transfer_requests');
    }
};
