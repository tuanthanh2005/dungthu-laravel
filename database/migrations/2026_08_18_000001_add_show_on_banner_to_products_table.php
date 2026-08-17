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
        if (!Schema::hasColumn('products', 'show_on_banner')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('show_on_banner')->default(false)->after('is_exclusive');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'show_on_banner')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('show_on_banner');
            });
        }
    }
};
