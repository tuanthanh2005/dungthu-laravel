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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'duration_months')) {
                $table->dropColumn('duration_months');
            }
            $table->unsignedInteger('duration_value')->nullable()->after('is_vpn');
            $table->string('duration_type', 10)->nullable()->after('duration_value'); // 'days', 'months'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['duration_value', 'duration_type']);
            $table->unsignedTinyInteger('duration_months')->nullable()->after('is_vpn');
        });
    }
};