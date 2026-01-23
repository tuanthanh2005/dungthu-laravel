<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng features - Tính năng nổi bật
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên tính năng
            $table->string('icon')->nullable(); // Icon class (VD: fas fa-bolt)
            $table->string('color')->default('#667eea'); // Màu gradient
            $table->text('description')->nullable(); // Mô tả ngắn
            $table->string('category')->default('tech'); // tech, fashion, doc
            $table->timestamps();
        });

        // Bảng trung gian product_features
        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('features');
    }
};
