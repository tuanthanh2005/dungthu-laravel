<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\AutoBlogService;
use App\Services\GeminiBlogService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutoBlogController extends Controller
{
    /**
     * Kích hoạt viết bài ngay lập tức cho sản phẩm tiếp theo (hoặc sản phẩm chỉ định)
     */
    public function runNow(Request $request, AutoBlogService $service): JsonResponse
    {
        $productId = $request->input('product_id') ? (int) $request->input('product_id') : null;
        $tone = $request->input('tone') ?: null;
        $model = $request->input('model') ?: null;
        $apiKey = $request->input('api_key') ?: null;

        // Nếu admin truyền API Key mới từ giao diện, cập nhật ngay
        if (!empty($apiKey)) {
            SiteSetting::setValue('gemini_api_key', trim($apiKey));
        }

        try {
            $product = $service->getNextProduct($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm nào đang hoạt động (is_active = true) để viết bài!',
                ], 422);
            }

            $result = $service->writePostForProduct($product, $tone, $model, $apiKey);

            // Lấy lại thông tin hàng đợi mới nhất sau khi viết
            $status = $service->getQueueStatus();

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data'    => $result,
                'queue'   => [
                    'current_position' => $status['current_position'],
                    'total_products'   => $status['total_products'],
                    'total_generated'  => $status['total_generated'],
                    'cycle_count'      => $status['cycle_count'],
                    'next_product'     => $status['next_product'] ? [
                        'id'             => $status['next_product']->id,
                        'name'           => $status['next_product']->name,
                        'price'          => $status['next_product_price_formatted'] ?? (number_format($status['next_product']->price, 0, ',', '.') . 'đ'),
                        'original_price' => $status['next_product_original_price_formatted'] ?? null,
                        'has_sale'       => $status['next_product_is_on_sale'] ?? false,
                        'save_percent'   => $status['next_product_save_percent'] ?? 0,
                    ] : null,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error("[AutoBlogController] Lỗi khi tạo bài tự động: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Cập nhật các thiết lập cho tính năng Auto Blog
     */
    public function updateSettings(Request $request, AutoBlogService $service): JsonResponse
    {
        $request->validate([
            'auto_blog_enabled' => 'nullable|in:0,1,true,false',
            'auto_blog_model'   => 'nullable|string',
            'api_key'           => 'nullable|string',
            'reset_product_id'  => 'nullable|integer',
        ]);

        if ($request->has('auto_blog_enabled')) {
            $val = in_array($request->input('auto_blog_enabled'), [1, '1', 'true', true], true) ? '1' : '0';
            SiteSetting::setValue('auto_blog_enabled', $val);
        }

        if ($request->filled('auto_blog_model')) {
            SiteSetting::setValue('auto_blog_model', trim($request->input('auto_blog_model')));
            SiteSetting::setValue('gemini_default_model', trim($request->input('auto_blog_model')));
        }

        if ($request->filled('api_key')) {
            SiteSetting::setValue('gemini_api_key', trim($request->input('api_key')));
        }

        if ($request->has('reset_product_id')) {
            $resetId = (int) $request->input('reset_product_id');
            SiteSetting::setValue('auto_blog_last_product_id', max(0, $resetId));
        }

        if ($request->has('reset_last_run') || $request->input('reset_last_run') == 1) {
            SiteSetting::setValue('auto_blog_last_run_at', now()->toIso8601String());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu cấu hình Auto Blog thành công!',
            'status'  => $service->getQueueStatus(),
        ]);
    }

    /**
     * Lấy trạng thái hàng đợi và thống kê Auto Blog
     */
    public function status(AutoBlogService $service): JsonResponse
    {
        $status = $service->getQueueStatus();

        $lastRun = $status['last_run_at'];
        $minutesSinceLast = $lastRun ? Carbon::parse($lastRun)->diffInMinutes(now()) : 9999;
        $interval = (int) SiteSetting::getValue('auto_blog_interval_minutes', '15');
        $isDue = ($status['is_enabled'] && ($lastRun === null || $minutesSinceLast >= $interval));

        return response()->json([
            'success' => true,
            'data'    => [
                'is_enabled'             => $status['is_enabled'],
                'interval_minutes'       => $status['interval_minutes'],
                'total_products'         => $status['total_products'],
                'current_position'       => $status['current_position'],
                'total_generated'        => $status['total_generated'],
                'cycle_count'            => $status['cycle_count'],
                'has_api_key'            => $status['has_api_key'],
                'masked_key'             => $status['masked_key'],
                'model'                  => $status['model'],
                'is_due'                 => $isDue,
                'minutes_since_last_run' => $minutesSinceLast,
                'last_run_human'         => $lastRun ? Carbon::parse($lastRun)->diffForHumans() : 'Chưa chạy',
                'last_product'           => $status['last_product'] ? [
                    'id'   => $status['last_product']->id,
                    'name' => $status['last_product']->name,
                ] : null,
                'next_product'           => $status['next_product'] ? [
                    'id'             => $status['next_product']->id,
                    'name'           => $status['next_product']->name,
                    'price'          => $status['next_product_price_formatted'] ?? (number_format($status['next_product']->price, 0, ',', '.') . 'đ'),
                    'original_price' => $status['next_product_original_price_formatted'] ?? null,
                    'has_sale'       => $status['next_product_is_on_sale'] ?? false,
                    'save_percent'   => $status['next_product_save_percent'] ?? 0,
                ] : null,
                'last_run_at'            => $status['last_run_at'] ? Carbon::parse($status['last_run_at'])->format('d/m/Y H:i:s') : null,
            ],
        ]);
    }

    /**
     * Webhook endpoint cho External Cron (UptimeRobot, Cron-job.org, EasyCron, cPanel Webhook...)
     * Cho phép máy chủ hosting chạy cron 15p thông qua HTTP GET request an toàn.
     */
    public function handleWebCron(Request $request, AutoBlogService $service): JsonResponse
    {
        $cronSecret = SiteSetting::getValue('auto_blog_cron_token');
        if (empty($cronSecret)) {
            $cronSecret = 'dungthu_auto_' . substr(md5(config('app.key')), 0, 12);
            SiteSetting::setValue('auto_blog_cron_token', $cronSecret);
        }

        $providedToken = (string) $request->input('token', '');
        if (!hash_equals($cronSecret, $providedToken) && !auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Token bảo mật không chính xác. Vui lòng kiểm tra lại URL Cron trong trang quản trị!',
            ], 403);
        }

        $enabled = (bool) SiteSetting::getValue('auto_blog_enabled', '1');
        if (!$enabled && !$request->has('force')) {
            return response()->json([
                'success' => false,
                'message' => 'Tính năng Auto Blog đang TẮT trong cài đặt.',
            ]);
        }

        // Kiểm tra giãn cách 14 phút
        $lastRun = SiteSetting::getValue('auto_blog_last_run_at');
        if ($lastRun && !$request->has('force')) {
            $minutes = Carbon::parse($lastRun)->diffInMinutes(now());
            if ($minutes < 14) {
                return response()->json([
                    'success' => false,
                    'message' => "Chưa đủ 15 phút giãn cách (lần chạy trước cách đây {$minutes} phút).",
                ]);
            }
        }

        $product = $service->getNextProduct();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm nào để viết bài.',
            ]);
        }

        try {
            $result = $service->writePostForProduct($product);
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data'    => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cập nhật làm mới lại khung giá ưu đãi chuẩn cho toàn bộ bài viết blog tự động đã tạo
     */
    public function refreshPrices(AutoBlogService $service): JsonResponse
    {
        try {
            $count = $service->refreshExistingBlogPriceBoxes();

            return response()->json([
                'success' => true,
                'message' => "Đã đồng bộ và làm mới mức giá ưu đãi thành công cho {$count} bài viết blog!",
                'count'   => $count,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi làm mới giá bài viết: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cập nhật làm mới lại toàn bộ ảnh banner logo chuẩn đẹp cho bài viết blog tự động
     */
    public function refreshImages(AutoBlogService $service): JsonResponse
    {
        try {
            $count = $service->refreshExistingBlogImages();

            return response()->json([
                'success' => true,
                'message' => "Đã tạo lại banner logo thương hiệu chuẩn đẹp thành công cho {$count} bài viết blog!",
                'count'   => $count,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi làm mới ảnh bài viết: ' . $e->getMessage(),
            ], 500);
        }
    }
}
