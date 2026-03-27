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
        Schema::table('ai_tickets', function (Blueprint $table) {
            $table->string('call_id')->nullable()->after('contact_id')->index();
            $table->text('transcription')->nullable()->after('description');
            $table->string('audio_url')->nullable()->after('transcription');
            $table->timestamp('closed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_tickets', function (Blueprint $table) {
            $table->dropColumn(['call_id', 'transcription', 'audio_url', 'closed_at']);
        });
    }
};
