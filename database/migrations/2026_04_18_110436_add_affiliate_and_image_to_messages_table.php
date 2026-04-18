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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('affiliate_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
            $table->string('image')->nullable()->after('message');
            $table->text('message')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropForeign(['affiliate_id']);
            $table->dropColumn(['affiliate_id', 'image']);
            $table->text('message')->nullable(false)->change();
        });
    }
};
