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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('delivery_account')->nullable()->after('status');
            $table->text('delivery_key')->nullable()->after('delivery_account');
            $table->text('delivery_note')->nullable()->after('delivery_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_account', 'delivery_key', 'delivery_note']);
        });
    }
};
