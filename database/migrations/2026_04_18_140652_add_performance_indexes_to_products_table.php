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
            $table->index('is_featured');
            $table->index('is_exclusive');
            $table->index('is_combo_ai');
            $table->index('is_flash_sale');
            $table->index('stock');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_exclusive']);
            $table->dropIndex(['is_combo_ai']);
            $table->dropIndex(['is_flash_sale']);
            $table->dropIndex(['stock']);
            $table->dropIndex(['created_at']);
        });
    }
};
