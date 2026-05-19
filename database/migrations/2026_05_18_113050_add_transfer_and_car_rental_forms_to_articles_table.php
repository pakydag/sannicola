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
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('has_transfer_form')->default(false)->after('has_contact_form');
            $table->boolean('has_car_rental_form')->default(false)->after('has_transfer_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('has_transfer_form');
            $table->dropColumn('has_car_rental_form');
        });
    }
};
