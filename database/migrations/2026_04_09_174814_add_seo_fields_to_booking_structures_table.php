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
            $table->string('seo_title')->nullable()->after('tipo_prezzo');
            $table->string('seo_description')->nullable()->after('seo_title');
            $table->string('seo_image')->nullable()->after('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_structures', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_image']);
        });
    }
};
