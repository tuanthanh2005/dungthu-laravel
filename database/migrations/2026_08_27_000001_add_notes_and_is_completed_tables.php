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
        Schema::table('customer_durations', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('expiry_date');
            $table->text('admin_note')->nullable()->after('is_completed');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('status_note')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_durations', function (Blueprint $table) {
            $table->dropColumn(['is_completed', 'admin_note']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_note');
        });
    }
};
