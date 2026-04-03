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
        Schema::create('vapi_sms', function (Blueprint $table) {
            $table->id();
            $table->string('vapi_id')->nullable()->index();
            $table->string('phone_number')->index();
            $table->text('content');
            $table->timestamp('received_at')->nullable();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vapi_sms');
    }
};
