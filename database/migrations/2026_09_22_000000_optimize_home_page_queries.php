<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id', 'order_items_product_id_idx');
            $table->index('order_id', 'order_items_order_id_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured', 'created_at'], 'products_active_featured_created_idx');
            $table->index(['is_active', 'is_exclusive', 'created_at'], 'products_active_exclusive_created_idx');
            $table->index(['is_active', 'is_combo_ai', 'created_at'], 'products_active_combo_created_idx');
            $table->index(['is_active', 'is_flash_sale', 'created_at'], 'products_active_flash_created_idx');
            $table->index(['is_active', 'show_on_banner', 'created_at'], 'products_active_banner_created_idx');
        });

        Schema::table('card_exchanges', function (Blueprint $table) {
            $table->index(['status', 'processed_at'], 'card_exchanges_status_processed_idx');
        });
    }

    public function down(): void
    {
        Schema::table('card_exchanges', function (Blueprint $table) {
            $table->dropIndex('card_exchanges_status_processed_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_featured_created_idx');
            $table->dropIndex('products_active_exclusive_created_idx');
            $table->dropIndex('products_active_combo_created_idx');
            $table->dropIndex('products_active_flash_created_idx');
            $table->dropIndex('products_active_banner_created_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_id_idx');
            $table->dropIndex('order_items_order_id_idx');
        });
    }
};
