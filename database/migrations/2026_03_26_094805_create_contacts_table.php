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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            // Personal & Business info
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable(); // ragione_sociale
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password')->nullable(); // For unified login later

            // Tax & Billing info
            $table->string('tax_id')->nullable(); // codice_fiscale
            $table->string('vat_number')->nullable(); // partita_iva
            $table->string('sdi_code', 10)->nullable();
            $table->string('pec')->nullable();

            // Address info
            $table->string('address')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('city')->nullable();
            $table->string('province', 5)->nullable();
            $table->string('country')->default('Italia');

            // Module Flags
            $table->boolean('is_shop_customer')->default(false);
            $table->boolean('is_booking_customer')->default(false);
            $table->boolean('is_b2b_customer')->default(false);
            $table->boolean('is_lead')->default(false);
            $table->boolean('is_active')->default(true);

            // Audit & Extra
            $table->json('meta_data')->nullable(); // For module-specific data
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
