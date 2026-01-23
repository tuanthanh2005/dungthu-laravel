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
        Schema::create('card_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_type'); // Loại thẻ: Viettel, Mobifone, Vinaphone
            $table->string('card_serial'); // Seri thẻ
            $table->string('card_code'); // Mã thẻ
            $table->decimal('card_value', 10, 2); // Mệnh giá thẻ
            $table->string('bank_name'); // Tên ngân hàng
            $table->string('bank_account_number'); // Số tài khoản
            $table->string('bank_account_name'); // Tên chủ tài khoản
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->text('admin_note')->nullable(); // Ghi chú của admin
            $table->decimal('exchange_amount', 10, 2)->nullable(); // Số tiền thực nhận
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_exchanges');
    }
};
