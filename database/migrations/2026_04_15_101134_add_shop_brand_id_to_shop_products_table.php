<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->foreignId('shop_brand_id')->nullable()->after('shop_collection_id')->constrained('shop_brands')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropForeign(['shop_brand_id']);
            $table->dropColumn('shop_brand_id');
        });
    }
};
