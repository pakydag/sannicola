<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add secure_token to contact_requests
        Schema::table('contact_requests', function (Blueprint $table) {
            $table->string('secure_token', 64)->nullable()->after('letto');
        });

        // Generate tokens for existing contact requests
        $requests = DB::table('contact_requests')->get();
        foreach ($requests as $req) {
            DB::table('contact_requests')
                ->where('id', $req->id)
                ->update(['secure_token' => Str::random(32)]);
        }

        // 2. Create contact_messages table
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_request_id')->constrained('contact_requests')->onDelete('cascade');
            $table->enum('sender', ['admin', 'client']);
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::table('contact_requests', function (Blueprint $table) {
            $table->dropColumn('secure_token');
        });
    }
};
