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
            $table->string('vapi_call_id')->nullable()->after('id')->index();
            $table->decimal('cost', 10, 4)->nullable()->after('description');
            $table->integer('duration')->nullable()->after('cost'); // in seconds
            $table->string('recording_url')->nullable()->after('duration');
        });

        // Update appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('vapi_call_id')->nullable()->after('id')->index();
            $table->decimal('cost', 10, 4)->nullable()->after('reason');
            $table->integer('duration')->nullable()->after('cost'); // in seconds
            $table->string('recording_url')->nullable()->after('duration');
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
