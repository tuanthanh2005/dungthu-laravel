<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_message_id')->index();
            $table->string('telegram_chat_id')->nullable();
            $table->string('type')->index(); // 'order' or 'chat'
            $table->unsignedBigInteger('related_id')->index(); // order_id or user_id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_mappings');
    }
};
