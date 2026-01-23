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
            $table->string('file_path')->nullable()->after('image');
            $table->string('file_type')->nullable()->after('file_path'); // pdf, docx, txt, etc
            $table->integer('file_size')->nullable()->after('file_type'); // in KB
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_type', 'file_size']);
        });
    }
};
