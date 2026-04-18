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
            $table->index(['user_id', 'is_admin', 'is_read'], 'idx_messages_user_polling');
            $table->index(['affiliate_id', 'is_admin', 'is_read'], 'idx_messages_aff_polling');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('idx_messages_user_polling');
            $table->dropIndex('idx_messages_aff_polling');
            $table->dropIndex(['is_read']);
        });
    }
};
