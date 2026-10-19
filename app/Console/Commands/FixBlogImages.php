<?php

namespace App\Console\Commands;

use App\Services\AutoBlogService;
use Illuminate\Console\Command;

class FixBlogImages extends Command
{
    /**
     * Tên và cú pháp của lệnh Artisan.
     */
    protected $signature = 'blog:fix-images';

    /**
     * Mô tả lệnh.
     */
    protected $description = 'Tạo lại toàn bộ ảnh banner logo chuẩn đẹp cho các bài viết blog tự động, thay thế các ảnh cũ';

    public function handle(AutoBlogService $autoBlogService): int
    {
        $this->info('──────────────────────────────────────────────────────');
        $this->info('🖼️  BẮT ĐẦU TẠO LẠI BANNER LOGO CHUẨN ĐẸP CHO BÀI VIẾT...');
        $this->info('──────────────────────────────────────────────────────');

        try {
            $count = $autoBlogService->refreshExistingBlogImages();

            $this->info("✅ Đã hoàn tất! Đã cập nhật lại banner logo cho {$count} bài viết blog.");
            $this->info('──────────────────────────────────────────────────────');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Lỗi khi cập nhật ảnh: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
