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
        Schema::table('shop_variants', function (Blueprint $table) {
            $table->integer('quantita')->default(0)->after('prezzo_scontato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_variants', function (Blueprint $table) {
            $table->dropColumn('quantita');
        });
    }
};
