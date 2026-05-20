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
        // 1. SECTIONS TABLE
        Schema::table('sections', function (Blueprint $table) {
            $table->string('nome_en')->nullable()->after('nome');
            $table->string('sottotitolo_en')->nullable()->after('sottotitolo');
            $table->text('contenuto_en')->nullable()->after('contenuto');
            $table->string('seo_title_en')->nullable()->after('seo_title');
            $table->text('seo_description_en')->nullable()->after('seo_description');
        });

        // 2. ARTICLES TABLE
        Schema::table('articles', function (Blueprint $table) {
            $table->string('titolo_en')->nullable()->after('titolo');
            $table->string('sottotitolo_en')->nullable()->after('sottotitolo');
            $table->text('descrizione_en')->nullable()->after('descrizione');
            $table->string('seo_title_en')->nullable()->after('seo_title');
            $table->text('seo_description_en')->nullable()->after('seo_description');
        });

        // 3. BOOKING STRUCTURES TABLE
        Schema::table('booking_structures', function (Blueprint $table) {
            $table->string('nome_en')->nullable()->after('nome');
            $table->text('descrizione_en')->nullable()->after('descrizione');
        });

        // 4. BOOKING SERVICES TABLE
        Schema::table('booking_services', function (Blueprint $table) {
            $table->string('nome_en')->nullable()->after('nome');
        });

        // 5. BOOKING EXTRAS TABLE
        Schema::table('booking_extras', function (Blueprint $table) {
            $table->string('nome_en')->nullable()->after('nome');
        });

        // Copy existing Italian data to English fields
        try {
            DB::table('sections')->update([
                'nome_en' => DB::raw('nome'),
                'sottotitolo_en' => DB::raw('sottotitolo'),
                'contenuto_en' => DB::raw('contenuto'),
                'seo_title_en' => DB::raw('seo_title'),
                'seo_description_en' => DB::raw('seo_description'),
            ]);

            DB::table('articles')->update([
                'titolo_en' => DB::raw('titolo'),
                'sottotitolo_en' => DB::raw('sottotitolo'),
                'descrizione_en' => DB::raw('descrizione'),
                'seo_title_en' => DB::raw('seo_title'),
                'seo_description_en' => DB::raw('seo_description'),
            ]);

            DB::table('booking_structures')->update([
                'nome_en' => DB::raw('nome'),
                'descrizione_en' => DB::raw('descrizione'),
            ]);

            if (Schema::hasColumn('booking_services', 'nome_en')) {
                DB::table('booking_services')->update([
                    'nome_en' => DB::raw('nome'),
                ]);
            }

            if (Schema::hasColumn('booking_extras', 'nome_en')) {
                DB::table('booking_extras')->update([
                    'nome_en' => DB::raw('nome'),
                ]);
            }
        } catch (\Exception $e) {
            // Silence copy errors in case tables are empty or lock
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['nome_en', 'sottotitolo_en', 'contenuto_en', 'seo_title_en', 'seo_description_en']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['titolo_en', 'sottotitolo_en', 'descrizione_en', 'seo_title_en', 'seo_description_en']);
        });

        Schema::table('booking_structures', function (Blueprint $table) {
            $table->dropColumn(['nome_en', 'descrizione_en']);
        });

        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropColumn(['nome_en']);
        });

        Schema::table('booking_extras', function (Blueprint $table) {
            $table->dropColumn(['nome_en']);
        });
    }
};
