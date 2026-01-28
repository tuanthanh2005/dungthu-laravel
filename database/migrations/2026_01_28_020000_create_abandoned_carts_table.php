<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->json('cart_data');
            $table->unsignedInteger('items_count')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->unsignedTinyInteger('reminder_stage')->default(0);
            $table->timestamp('last_reminder_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
