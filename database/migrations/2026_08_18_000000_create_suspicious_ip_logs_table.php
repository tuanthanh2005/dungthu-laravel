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
        Schema::create('suspicious_ip_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('reason');
            $table->text('url')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 30)->default('auto_banned_24h');
            $table->timestamp('banned_until')->nullable();
            $table->timestamps();

            $table->index('ip_address');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspicious_ip_logs');
    }
};
