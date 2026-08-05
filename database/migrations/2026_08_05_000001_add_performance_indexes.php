<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_featured', 'created_at'], 'products_featured_created_idx');
            $table->index(['is_exclusive', 'created_at'], 'products_exclusive_created_idx');
            $table->index(['is_combo_ai', 'created_at'], 'products_combo_created_idx');
            $table->index(['is_flash_sale', 'created_at'], 'products_flash_created_idx');
            $table->index(['category_id', 'created_at'], 'products_category_created_idx');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'blogs_published_at_idx');
            $table->index(['category', 'published_at'], 'blogs_category_published_idx');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->index(['is_active', 'show_on_home', 'name'], 'categories_home_name_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', fn (Blueprint $table) => $table->dropIndex('orders_status_created_idx'));
        Schema::table('product_categories', fn (Blueprint $table) => $table->dropIndex('categories_home_name_idx'));
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('blogs_published_at_idx');
            $table->dropIndex('blogs_category_published_idx');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_featured_created_idx');
            $table->dropIndex('products_exclusive_created_idx');
            $table->dropIndex('products_combo_created_idx');
            $table->dropIndex('products_flash_created_idx');
            $table->dropIndex('products_category_created_idx');
        });
    }
};
