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
        if (!Schema::hasTable('shop_delivery_slots')) {
            Schema::create('shop_delivery_slots', function (Blueprint $table) {
                $table->id();
                $table->string('orario_consegna'); // Es. "12:00"
                $table->string('orario_limite');   // Es. "11:00"
                $table->boolean('attivo')->default(true);
                $table->integer('ordine')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'data_consegna')) {
                $table->date('data_consegna')->nullable()->after('metodo_pagamento');
            }
            if (!Schema::hasColumn('shop_orders', 'orario_consegna')) {
                $table->string('orario_consegna')->nullable()->after('data_consegna');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_delivery_slots');

        Schema::table('shop_orders', function (Blueprint $table) {
            if (Schema::hasColumn('shop_orders', 'orario_consegna')) {
                $table->dropColumn('orario_consegna');
            }
            if (Schema::hasColumn('shop_orders', 'data_consegna')) {
                $table->dropColumn('data_consegna');
            }
        });
    }
};
