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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('booking_structure_id')->constrained('booking_structures')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('adulti')->default(1);
            $table->integer('bambini')->default(0);
            $table->decimal('totale_prezzo', 10, 2);
            $table->string('stato')->default('attesa'); // attesa, confermato, annullato
            $table->string('stato_pagamento')->default('attesa'); // attesa, pagato, rimborsato
            $table->string('metodo_pagamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
