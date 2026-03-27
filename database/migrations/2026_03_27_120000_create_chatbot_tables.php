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
        // Create chatbot_messages table if it doesn't exist
        if (!Schema::hasTable('chatbot_messages')) {
            Schema::create('chatbot_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('session_id')->index();
                $table->text('message');
                $table->text('response');
                $table->string('message_type')->default('text');
                $table->json('metadata')->nullable();
                $table->decimal('response_time', 5, 2)->nullable();
                $table->boolean('is_helpful')->nullable();
                $table->text('feedback_note')->nullable();
                $table->string('status')->default('completed');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Create chatbot_sessions table if it doesn't exist
        if (!Schema::hasTable('chatbot_sessions')) {
            Schema::create('chatbot_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('visitor_ip')->nullable();
                $table->string('device_info')->nullable();
                $table->integer('message_count')->default(0);
                $table->boolean('is_resolved')->default(false);
                $table->text('summary')->nullable();
                $table->datetime('ended_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_sessions');
    }
};
