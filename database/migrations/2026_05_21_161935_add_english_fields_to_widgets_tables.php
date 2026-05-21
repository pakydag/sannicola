<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('global_widgets', function (Blueprint $table) {
            $table->string('titolo_en')->nullable()->after('titolo');
        });

        Schema::table('widgets', function (Blueprint $table) {
            $table->string('titolo_en')->nullable()->after('titolo');
        });

        // Copy existing Italian data to English fields
        try {
            DB::table('global_widgets')->update([
                'titolo_en' => DB::raw('titolo'),
            ]);

            DB::table('widgets')->update([
                'titolo_en' => DB::raw('titolo'),
            ]);
        } catch (\Exception $e) {
            // Silence
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_widgets', function (Blueprint $table) {
            $table->dropColumn('titolo_en');
        });

        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('titolo_en');
        });
    }
};
