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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name'); // e.g. "API Codex 10M Token 1 ngày BHF", "Gói 1 tháng", "Gói 12 tháng"
            $table->string('name_en')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('price_usd', 10, 2)->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('sale_price_usd', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('duration_value')->nullable();
            $table->string('duration_type')->nullable(); // 'days', 'months', 'years'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['product_id', 'is_active', 'sort_order']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            $table->string('variant_name')->nullable()->after('variant_id');

            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn(['variant_id', 'variant_name']);
        });

        Schema::dropIfExists('product_variants');
    }
};
