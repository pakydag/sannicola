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
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign key constraint that references the wrong table (customers)
            $table->dropForeign('bookings_customer_id_foreign');
            
            // Add correct foreign key constraint that references booking_customers
            $table->foreign('customer_id')
                ->references('id')
                ->on('booking_customers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the correct foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Re-add the wrong one referencing customers
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }
};
