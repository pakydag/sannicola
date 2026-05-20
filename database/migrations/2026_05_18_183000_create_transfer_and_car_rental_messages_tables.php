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
        // 1. Add secure_token to transfer_requests
        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->string('secure_token', 64)->nullable()->after('letto');
        });

        // Generate tokens for existing transfer requests
        $transfers = DB::table('transfer_requests')->get();
        foreach ($transfers as $tr) {
            DB::table('transfer_requests')
                ->where('id', $tr->id)
                ->update(['secure_token' => Str::random(32)]);
        }

        // 2. Add secure_token to car_rental_requests
        Schema::table('car_rental_requests', function (Blueprint $table) {
            $table->string('secure_token', 64)->nullable()->after('letto');
        });

        // Generate tokens for existing car rental requests
        $carRentals = DB::table('car_rental_requests')->get();
        foreach ($carRentals as $cr) {
            DB::table('car_rental_requests')
                ->where('id', $cr->id)
                ->update(['secure_token' => Str::random(32)]);
        }

        // 3. Create transfer_messages table
        Schema::create('transfer_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_request_id')->constrained('transfer_requests')->onDelete('cascade');
            $table->enum('sender', ['admin', 'client']);
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestamps();
        });

        // 4. Create car_rental_messages table
        Schema::create('car_rental_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_rental_request_id')->constrained('car_rental_requests')->onDelete('cascade');
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
        Schema::dropIfExists('car_rental_messages');
        Schema::dropIfExists('transfer_messages');
        
        Schema::table('car_rental_requests', function (Blueprint $table) {
            $table->dropColumn('secure_token');
        });
        
        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->dropColumn('secure_token');
        });
    }
};
