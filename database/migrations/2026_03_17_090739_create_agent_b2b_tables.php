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
        Schema::create('b2b_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('b2b_payment_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('b2b_customers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name'); // Ragione sociale
            $table->string('vat_number')->nullable(); // P.IVA
            $table->string('contact_name')->nullable();
            $table->string('contact_surname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('payment_condition_id')->nullable()->constrained('b2b_payment_conditions')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('agent_brand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('b2b_brand_id')->constrained('b2b_brands')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_brand');
        Schema::dropIfExists('b2b_customers');
        Schema::dropIfExists('b2b_payment_conditions');
        Schema::dropIfExists('b2b_brands');
    }
};
