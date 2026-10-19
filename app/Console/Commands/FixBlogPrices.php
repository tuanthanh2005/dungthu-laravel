<?php

namespace App\Console\Commands;

use App\Services\AutoBlogService;
use Illuminate\Console\Command;

class FixBlogPrices extends Command
{
    /**
     * Tên và cú pháp của lệnh Artisan.
     */
    protected $signature = 'blog:fix-prices';

    /**
     * Mô tả lệnh.
     */
    protected $description = 'Đồng bộ và làm mới lại khung giá ưu đãi (giá giảm và giá gốc) cho toàn bộ các bài viết blog tự động đã tạo trong hệ thống';

    public function handle(AutoBlogService $autoBlogService): int
    {
        $this->info('──────────────────────────────────────────────────────');
        $this->info('🔄 BẮT ĐẦU ĐỒNG BỘ & LÀM MỚI MỨC GIÁ BÀI VIẾT BLOG...');
        $this->info('──────────────────────────────────────────────────────');

        try {
            $count = $autoBlogService->refreshExistingBlogPriceBoxes();

            $this->info("✅ Đã hoàn tất! Đã cập nhật lại khung giá ưu đãi cho {$count} bài viết blog.");
            $this->info('Toàn bộ bài viết hiện đã hiển thị đúng GIÁ BÁN GIẢM (ƯU ĐÃI) và gạch ngang giá gốc.');
            $this->info('──────────────────────────────────────────────────────');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Lỗi khi đồng bộ giá bài viết: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
