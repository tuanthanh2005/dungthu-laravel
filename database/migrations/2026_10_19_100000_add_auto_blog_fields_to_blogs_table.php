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
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('category')->constrained('products')->nullOnDelete();
            }
            if (!Schema::hasColumn('blogs', 'is_auto_generated')) {
                $table->boolean('is_auto_generated')->default(false)->after('product_id')->index();
            }
            if (!Schema::hasColumn('blogs', 'auto_tone')) {
                $table->string('auto_tone', 50)->nullable()->after('is_auto_generated');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('blogs', 'is_auto_generated')) {
                $table->dropColumn('is_auto_generated');
            }
            if (Schema::hasColumn('blogs', 'auto_tone')) {
                $table->dropColumn('auto_tone');
            }
        });
    }
};
