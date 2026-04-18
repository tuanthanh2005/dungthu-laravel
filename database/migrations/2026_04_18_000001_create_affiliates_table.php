<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('cccd_front')->nullable(); // Ảnh CCCD mặt trước
            $table->string('cccd_back')->nullable();  // Ảnh CCCD mặt sau
            $table->string('cccd_number')->nullable(); // Số CCCD
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('balance', 15, 0)->default(0); // Số dư tích lũy (VNĐ)
            $table->text('reject_reason')->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
