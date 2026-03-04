<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buff_services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Tăng like Facebook", "Tăng follow TikTok", etc.
            $table->string('platform'); // facebook, tiktok, instagram
            $table->string('service_type'); // like, follow, comment, view
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // giá cơ bản
            $table->decimal('price_per_unit', 10, 2); // giá trên 1 đơn vị
            $table->integer('min_amount')->default(10);
            $table->integer('max_amount')->default(1000000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('buff_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Server 1310", "Server 1129", etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_maintenance')->default(false); // khi bảo trì
            $table->timestamps();
        });

        Schema::create('buff_server_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buff_service_id')->constrained('buff_services')->onDelete('cascade');
            $table->foreignId('buff_server_id')->constrained('buff_servers')->onDelete('cascade');
            $table->decimal('price', 10, 2); // giá riêng cho server này
            $table->timestamps();
            $table->unique(['buff_service_id', 'buff_server_id']);
        });

        Schema::create('buff_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buff_service_id')->constrained('buff_services')->onDelete('cascade');
            $table->foreignId('buff_server_id')->constrained('buff_servers')->onDelete('cascade');
            $table->string('order_code')->unique(); // mã đơn
            
            // Thông tin đơn
            $table->string('social_link'); // link Facebook, TikTok, Instagram
            $table->integer('quantity'); // số lượng (like, follow, comment, view)
            $table->text('notes')->nullable(); // ghi chú thêm
            $table->string('emotion_type')->nullable(); // loại cảm xúc cho comment (like, love, haha, sad, wow, angry)
            
            // Tính tiền
            $table->decimal('base_price', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('actual_price', 10, 2)->nullable(); // giá admin chỉnh sửa
            
            // Thanh toán
            $table->string('payment_method')->nullable(); // bank, momo, vietqr
            $table->string('transaction_id')->nullable(); // mã giao dịch
            $table->timestamp('paid_at')->nullable();
            
            // Trạng thái
            $table->enum('status', [
                'pending',          // chờ thanh toán
                'paid',             // đã thanh toán
                'processing',       // đang xử lý buff
                'completed',        // hoàn thành
                'cancelled',        // đã hủy
                'refunded'          // đã hoàn tiền
            ])->default('pending');
            
            $table->timestamp('started_at')->nullable(); // bắt đầu buff
            $table->timestamp('completed_at')->nullable(); // kết thúc buff
            $table->text('admin_notes')->nullable(); // ghi chú từ admin
            
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
            $table->index('order_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buff_server_prices');
        Schema::dropIfExists('buff_servers');
        Schema::dropIfExists('buff_orders');
        Schema::dropIfExists('buff_services');
    }
};
