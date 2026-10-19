<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Services\AutoBlogService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoWriteBlogPosts extends Command
{
    /**
     * Tên và cú pháp của lệnh Artisan.
     */
    protected $signature = 'blog:auto-write 
                            {--force : Bỏ qua kiểm tra trạng thái bật/tắt và thời gian chờ để chạy ngay}
                            {--product-id= : Chỉ định ID sản phẩm cụ thể thay vì lấy SP tiếp theo}
                            {--model= : Chỉ định model Gemini (mặc định lấy theo cài đặt)}
                            {--tone= : Chỉ định phong cách bài viết (deal_san_sale, review_thuc_te, top_ly_do, ...)}';

    /**
     * Mô tả lệnh.
     */
    protected $description = 'Tự động viết bài blog SEO cho sản phẩm tiếp theo bằng AI Gemini, tự tạo ảnh và gửi Google Indexing (xoay vòng sản phẩm)';

    public function handle(AutoBlogService $autoBlogService): int
    {
        $this->info('──────────────────────────────────────────────────────');
        $this->info('🤖 BẮT ĐẦU QUY TRÌNH AUTO BLOG CHO SẢN PHẨM (GEMINI AI)');
        $this->info('📅 Thời gian: ' . now()->format('d/m/Y H:i:s'));
        $this->info('──────────────────────────────────────────────────────');

        $isForced = (bool) $this->option('force');
        $forcedProductId = $this->option('product-id') ? (int) $this->option('product-id') : null;
        $forcedModel = $this->option('model') ?: null;
        $forcedTone = $this->option('tone') ?: null;

        // 1. Kiểm tra tính năng có đang BẬT không
        if (!$isForced) {
            $enabled = (bool) SiteSetting::getValue('auto_blog_enabled', '1');
            if (!$enabled) {
                $this->warn('⚠️  Tính năng Auto Blog đang TẮT trong cài đặt hệ thống. Dùng --force nếu muốn ép chạy.');
                return Command::SUCCESS;
            }

            // Kiểm tra thời gian lần chạy cuối (giãn cách tối thiểu ~14 phút để tránh chạy trùng)
            $lastRun = SiteSetting::getValue('auto_blog_last_run_at');
            if ($lastRun) {
                $minutesSinceLastRun = Carbon::parse($lastRun)->diffInMinutes(now());
                if ($minutesSinceLastRun < 14) {
                    $this->comment("⏳ Lần chạy gần nhất cách đây {$minutesSinceLastRun} phút. Chu kỳ là 15 phút/lần. Bỏ qua lượt này (dùng --force để ép chạy ngay).");
                    return Command::SUCCESS;
                }
            }
        }

        // 2. Lấy sản phẩm tiếp theo trong chu kỳ
        $product = $autoBlogService->getNextProduct($forcedProductId);
        if (!$product) {
            $this->error('❌ Không tìm thấy sản phẩm nào (is_active = true) trong hệ thống.');
            return Command::FAILURE;
        }

        $priceDetails = AutoBlogService::resolveProductPriceDetails($product);
        $priceInfoText = $priceDetails['discount_formatted'];
        if ($priceDetails['has_sale'] && !empty($priceDetails['original_formatted'])) {
            $priceInfoText .= " (Giá gốc: {$priceDetails['original_formatted']} - Giảm {$priceDetails['save_percent']}%)";
        }
        $this->info("📦 Sản phẩm mục tiêu: #{$product->id} - {$product->name} [{$priceInfoText}]");

        // 3. Thực hiện tạo bài viết
        try {
            $this->info('🧠 Đang gọi Google Gemini AI viết nội dung chuẩn SEO & chốt đơn...');
            $result = $autoBlogService->writePostForProduct($product, $forcedTone, $forcedModel);

            $this->newLine();
            $this->info('✅ THÀNH CÔNG! ĐÃ XUẤT BẢN BÀI VIẾT MỚI:');
            $this->table(
                ['Trường', 'Chi tiết'],
                [
                    ['Blog ID', '#' . $result['blog']['id']],
                    ['Tiêu đề bài viết', $result['blog']['title']],
                    ['Slug', $result['blog']['slug']],
                    ['Góc tiếp cận / Style', $result['angle']['name'] . ' (' . $result['angle']['key'] . ')'],
                    ['Ảnh bài viết', $result['blog']['image'] ?? 'Không có'],
                    ['Google Indexing', $result['indexing_status']],
                    ['URL bài viết', $result['blog']['url']],
                ]
            );

            $next = $autoBlogService->getNextProduct();
            if ($next) {
                $this->comment("👉 Sản phẩm tiếp theo cho lần chạy sau (15p tới): #{$next->id} - {$next->name}");
            }

            $this->info('──────────────────────────────────────────────────────');
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('❌ Thất bại: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error("[AutoBlog Command Error] " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }
}
