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
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->boolean('is_super_admin')->default(false)->after('role');
            $table->boolean('can_manage_site')->default(false)->after('is_super_admin');
            $table->boolean('can_manage_shop')->default(false)->after('can_manage_site');
            $table->boolean('can_manage_booking')->default(false)->after('can_manage_shop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'is_super_admin',
                'can_manage_site',
                'can_manage_shop',
                'can_manage_booking'
            ]);
        });
    }
};
