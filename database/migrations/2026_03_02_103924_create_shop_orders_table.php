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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('numero_ordine')->unique();
            $table->string('stato')->default('nuovo');
            $table->string('stato_pagamento')->default('attesa');
            $table->string('metodo_pagamento')->default('bonifico');
            $table->text('note_cliente')->nullable();
            $table->text('note_admin')->nullable();
            $table->decimal('totale_imponibile', 10, 2)->default(0);
            $table->decimal('totale_iva', 10, 2)->default(0);
            $table->decimal('totale_ordine', 10, 2)->default(0);
            $table->string('spedizione_nome')->nullable();
            $table->string('spedizione_indirizzo')->nullable();
            $table->string('spedizione_cap')->nullable();
            $table->string('spedizione_citta')->nullable();
            $table->string('spedizione_provincia')->nullable();
            $table->string('spedizione_nazione')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
