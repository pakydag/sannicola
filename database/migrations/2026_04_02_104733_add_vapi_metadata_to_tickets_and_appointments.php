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
        // Update ai_tickets
        Schema::table('ai_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_tickets', 'vapi_call_id')) {
                $table->string('vapi_call_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('ai_tickets', 'cost')) {
                $table->decimal('cost', 10, 4)->nullable()->after('description');
            }
            if (!Schema::hasColumn('ai_tickets', 'duration')) {
                $table->integer('duration')->nullable()->after('cost');
            }
            if (!Schema::hasColumn('ai_tickets', 'recording_url')) {
                $table->string('recording_url')->nullable()->after('duration');
            }
        });

        // Update appointments
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'vapi_call_id')) {
                $table->string('vapi_call_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('appointments', 'cost')) {
                $table->decimal('cost', 10, 4)->nullable()->after('description');
            }
            if (!Schema::hasColumn('appointments', 'duration')) {
                $table->integer('duration')->nullable()->after('cost');
            }
            if (!Schema::hasColumn('appointments', 'recording_url')) {
                $table->string('recording_url')->nullable()->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_tickets', function (Blueprint $table) {
            $table->dropColumn(['vapi_call_id', 'cost', 'duration', 'recording_url']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['vapi_call_id', 'cost', 'duration', 'recording_url']);
        });
    }
};
