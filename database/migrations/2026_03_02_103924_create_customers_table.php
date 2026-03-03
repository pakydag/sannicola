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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('ragione_sociale')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('indirizzo')->nullable();
            $table->string('cap')->nullable();
            $table->string('citta')->nullable();
            $table->string('provincia')->nullable();
            $table->string('nazione')->default('Italia');
            $table->string('codice_fiscale')->nullable();
            $table->string('partita_iva')->nullable();
            $table->string('telefono')->nullable();
            $table->string('cellulare')->nullable();
            $table->string('sdi')->nullable();
            $table->string('pec')->nullable();
            $table->boolean('attivo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
