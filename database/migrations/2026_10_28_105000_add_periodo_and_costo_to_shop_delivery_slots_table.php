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
        Schema::table('shop_delivery_slots', function (Blueprint $table) {
            $table->string('periodo', 20)->default('mattina')->after('orario_limite');
            $table->decimal('costo', 8, 2)->default(0.00)->after('periodo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_delivery_slots', function (Blueprint $table) {
            $table->dropColumn(['periodo', 'costo']);
        });
    }
};
