<?php

namespace App\Services;

use App\Helpers\PathHelper;
use App\Models\Blog;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class AutoBlogService
{
    /**
     * Danh sách 6 phong cách / góc tiếp cận bài viết đa dạng, tránh trùng lặp nội dung
     * và tối ưu tìm kiếm Google từ khóa sản phẩm giá rẻ.
     */
    public static function getAngles(): array
    {
        return [
            'deal_san_sale' => [
                'key' => 'deal_san_sale',
                'name' => 'Săn Deal Siêu Rẻ & Tiết Kiệm Tối Đa',
                'badge' => 'Săn Deal Giá Rẻ',
                'color' => '#dc2626',
                'focus' => 'Tập trung vào mức giá rẻ bất ngờ tại DungThu.com, so sánh với giá thị trường đắt đỏ. Nêu rõ lý do tại sao tại DungThu.com lại có mức giá tốt như vậy (nhập khẩu trực tiếp, tối ưu chi phí). Đánh mạnh vào tâm lý người tiêu dùng thông thái, kích thích chốt đơn nhận ưu đãi ngay hôm nay.',
            ],
            'review_thuc_te' => [
                'key' => 'review_thuc_te',
                'name' => 'Review Thực Tế & Đánh Giá Đáng Tiền',
                'badge' => 'Review Chi Tiết',
                'color' => '#2563eb',
                'focus' => 'Phong cách đánh giá, review chân thực từ người dùng thực tế. Phân tích các ưu điểm vượt trội, hiệu năng, sự tiện lợi và giải thích vì sao với mức giá ưu đãi hiện tại thì đây là sản phẩm "đáng đồng tiền bát gạo" nhất trong phân khúc.',
            ],
            'top_ly_do' => [
                'key' => 'top_ly_do',
                'name' => 'Top Lý Do Nên Sở Hữu Ngay',
                'badge' => 'Top Đề Xuất',
                'color' => '#d97706',
                'focus' => 'Phong cách danh sách Top 5 - Top 7 lý do bạn nhất định phải sở hữu sản phẩm này ngay hôm nay. Nhấn mạnh dịch vụ bàn giao tức thì trong 5 phút, bảo hành 1 đổi 1 uy tín và đội ngũ hỗ trợ 24/7 của DungThu.com.',
            ],
            'cam_nang_huong_dan' => [
                'key' => 'cam_nang_huong_dan',
                'name' => 'Cẩm Nang Hướng Dẫn & Mẹo Dùng Tối Ưu',
                'badge' => 'Cẩm Nang Mua Sắm',
                'color' => '#059669',
                'focus' => 'Phong cách cẩm nang hướng dẫn chi tiết dành cho người mua. Hướng dẫn cách tận dụng tối đa giá trị sản phẩm, các mẹo sử dụng thông minh, cách đặt mua online nhận hàng sau 5 phút và cách kiểm tra bảo hành.',
            ],
            'giai_dap_faq' => [
                'key' => 'giai_dap_faq',
                'name' => 'Giải Đáp Thắc Mắc & Hỏi Đáp Q&A',
                'badge' => 'Hỏi Đáp Chuyên Sâu',
                'color' => '#7c3aed',
                'focus' => 'Phong cách hỏi - đáp (FAQ) trực diện, giải quyết toàn bộ lo lắng, rào cản mua hàng của khách: Mua giá rẻ có an toàn không? Có được bảo hành không? Cách thức thanh toán ra sao? Bàn giao thế nào? Tạo niềm tin tuyệt đối cho khách hàng.',
            ],
            'so_sanh_kinh_te' => [
                'key' => 'so_sanh_kinh_te',
                'name' => 'So Sánh Giá & Bài Toán Tiết Kiệm',
                'badge' => 'So Sánh Chi Phí',
                'color' => '#0891b2',
                'focus' => 'Phong cách phân tích kinh tế & bài toán chi tiêu thông minh. Đặt lên bàn cân số tiền khách hàng phải trả khi mua giá niêm yết đắt đỏ vs khi mua giá rẻ tại DungThu.com, chứng minh khách tiết kiệm được hàng trăm nghìn hoặc hàng triệu đồng mà trải nghiệm vẫn đạt điểm 10.',
            ],
        ];
    }

    /**
     * Lấy sản phẩm tiếp theo theo thứ tự từ SP 1 đến SP cuối,
     * khi hết danh sách thì tự động xoay vòng lại SP 1.
     */
    public function getNextProduct(?int $forcedProductId = null): ?Product
    {
        if ($forcedProductId !== null) {
            return Product::find($forcedProductId);
        }

        $lastId = (int) SiteSetting::getValue('auto_blog_last_product_id', 0);

        // 1. Tìm sản phẩm tiếp theo có id > $lastId (sắp xếp id ASC)
        $nextProduct = Product::where('is_active', true)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->first();

        // 2. Nếu đã đi đến sản phẩm cuối cùng (không còn id > lastId),
        // tự động xoay vòng lại sản phẩm đầu tiên!
        if (!$nextProduct) {
            $nextProduct = Product::where('is_active', true)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextProduct) {
                $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0) + 1;
                SiteSetting::setValue('auto_blog_cycle_count', $cycle);
                Log::info("[AutoBlog] Đã hoàn thành một vòng tất cả sản phẩm. Bắt đầu vòng thứ {$cycle} từ sản phẩm đầu tiên ID #{$nextProduct->id} ({$nextProduct->name})");
            }
        }

        return $nextProduct;
    }

    /**
     * Xác định phong cách bài viết dựa trên tổng số lần chạy + số chu kỳ loop,
     * đảm bảo mỗi bài có góc nhìn khác nhau, không bị trùng lặp nội dung.
     */
    public function resolveAngle(int $index, ?string $forcedAngle = null): array
    {
        $angles = self::getAngles();

        if ($forcedAngle && isset($angles[$forcedAngle])) {
            return $angles[$forcedAngle];
        }

        $angleList = array_values($angles);
        return $angleList[$index % count($angleList)];
    }

    /**
     * Thực hiện toàn bộ quy trình viết bài tự động cho 1 sản phẩm:
     * Gọi Gemini -> Sinh bài chuẩn SEO -> Tạo ảnh AI -> Lưu Blog -> Gửi Google Indexing -> Cập nhật con trỏ.
     */
    public function writePostForProduct(
        Product $product,
        ?string $forcedAngle = null,
        ?string $forcedModel = null,
        ?string $apiKey = null
    ): array {
        $key = GeminiBlogService::getApiKey($apiKey);
        if (empty($key)) {
            throw new \Exception('Chưa cấu hình Gemini API Key! Vui lòng nhập API Key tại phần Cài đặt Auto Blog.');
        }

        $model = $forcedModel ?: SiteSetting::getValue('auto_blog_model', SiteSetting::getValue('gemini_default_model', 'gemini-3.5-flash'));
        $model = GeminiBlogService::formatModelName($model);

        $runCount = (int) SiteSetting::getValue('auto_blog_total_generated', 0);
        $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0);
        $angle = $this->resolveAngle($runCount + $cycle, $forcedAngle);

        // Chuẩn bị dữ liệu sản phẩm
        $priceFormatted = number_format($product->price, 0, ',', '.') . 'đ';
        $salePriceFormatted = $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . 'đ' : null;
        $productUrl = url('/product/' . $product->slug);

        // Map danh mục sản phẩm sang danh mục blog hợp lệ
        $categoryMap = [
            'tech'     => 'tech',
            'fashion'  => 'lifestyle',
            'doc'      => 'business',
            'acc-game' => 'tech',
        ];
        $suggestedCategory = $categoryMap[$product->category] ?? 'business';

        // Xây dựng prompt SEO chuyên sâu
        $prompt = <<<PROMPT
Bạn là Chuyên gia SEO Content Marketing và Copywriter hàng đầu trong lĩnh vực thương mại điện tử.
Nhiệm vụ của bạn là viết một bài blog CHUẨN SEO GOOGLE ĐỈNH CAO nhằm QUẢNG BÁ SẢN PHẨM GIÁ RẺ để khách hàng tìm kiếm trên Google (Google Search) dễ dàng ra bài viết và chốt đơn ngay lập tức.

THÔNG TIN SẢN PHẨM:
- Tên sản phẩm: "{$product->name}"
- Giá bán ưu đãi: {$priceFormatted}
- Giá gốc (nếu có): {$salePriceFormatted}
- Danh mục sản phẩm: {$product->category}
- Mô tả / Thông số tóm tắt: "{$product->description}"
- Đường dẫn xem chi tiết & mua hàng trực tiếp: {$productUrl}
- Website: DungThu.com | Hotline / Zalo hỗ trợ: 0772698113

PHONG CÁCH & GÓC TIẾP CẬN BÀI VIẾT NÀY:
- Góc tiếp cận: {$angle['name']}
- Định hướng: {$angle['focus']}

CÁC YÊU CẦU SEO & NỘI DUNG BẮT BUỘC:
1. TIÊU ĐỀ (Title): Cực kỳ hấp dẫn, kích thích tỷ lệ click (CTR), chứa các từ khóa tìm kiếm vàng như "Giá Rẻ", "Uy Tín", "Chính Hãng", "Đánh Giá", "Bảng Giá", "Mua Ở Đâu", "{name}". Tiêu đề PHẢI phù hợp với góc tiếp cận "{$angle['name']}".
2. ĐOẠN TRÍCH (Excerpt): Mô tả ngắn cuốn hút trong khoảng 120 - 155 ký tự (TUYỆT ĐỐI KHÔNG vượt quá 160 ký tự) chứa từ khóa chính và lời kích thích mua hàng.
3. NỘI DUNG (Content) DẠNG HTML PHONG PHÚ:
   - Dùng các thẻ cấu trúc chuẩn SEO: <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>, <blockquote>.
   - Thân bài phải phân tích sâu sắc, lôi cuốn, cung cấp giá trị thực tế cho người đọc, nêu bật bài toán kinh tế (tiết kiệm chi phí khi mua tại DungThu.com).
   - BẮT BUỘC chèn 1 KHUNG SẢN PHẨM NỔI BẬT với liên kết trực tiếp mua hàng (dùng đúng URL: {$productUrl}):
<div class="my-4 p-4 rounded-4 text-center border shadow-sm product-highlight-box" style="background: linear-gradient(135deg, #f0f7ff 0%, #e6f0fa 100%); border-color: #bee3f8 !important;">
          <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase mb-2"><i class="fas fa-fire me-1"></i> Ưu Đãi Giá Rẻ Hôm Nay</span>
          <h3 class="fw-bold text-dark mt-2 mb-2">{$product->name}</h3>
          <p class="text-muted mb-3">Mức giá ưu đãi chỉ: <strong class="text-danger fs-4">{$priceFormatted}</strong> (Tiết kiệm tối đa - Bảo hành 1 đổi 1 uy tín)</p>
          <a href="{$productUrl}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow-sm product-cta-btn" style="border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; color: #111;" target="_blank">
              <i class="fas fa-shopping-cart"></i> Xem Chi Tiết & Đặt Mua Ngay
          </a>
      </div>
    - BẮT BUỘC chèn 1 KHUNG LIÊN HỆ & BẢO HÀNH CHỐT ĐƠN ở cuối bài:
      <div class="alert alert-primary p-4 rounded-4 mt-4 shadow-sm border-0 blog-contact-box" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: white;">
          <h4 class="fw-bold text-white mb-2"><i class="fas fa-headset me-2"></i> Đặt Mua Nhanh & Hỗ Trợ Kỹ Thuật 24/7</h4>
          <p class="mb-3 text-white-50">Nhận sản phẩm / tài khoản chỉ sau 5 phút! Cam kết bảo hành 1 đổi 1 uy tín suốt thời gian sử dụng.</p>
          <div class="d-flex flex-wrap gap-2">
              <a href="https://zalo.me/0772698113" target="_blank" class="btn btn-warning px-4 py-2 fw-bold text-dark" style="border-radius: 10px;">
                  <i class="fas fa-comments me-1"></i> Nhắn Zalo: 0772698113
              </a>
              <a href="{$productUrl}" class="btn btn-light px-4 py-2 fw-bold text-primary" style="border-radius: 10px;">
                  <i class="fas fa-shopping-bag me-1"></i> Mua Ngay Trên Website
              </a>
          </div>
      </div>
   - TUYỆT ĐỐI KHÔNG dùng bất kỳ placeholder nào như '[Số điện thoại]', '[Hotline]', '[Zalo]', '[Link sản phẩm]'. Mọi số điện thoại PHẢI là 0772698113, mọi link PHẢI là {$productUrl}.

YÊU CẦU ĐỊNH DẠNG ĐẦU RA:
Bạn PHẢI trả về ĐÚNG 1 ĐỐI TƯỢNG JSON thuần túy (không kèm giải thích bên ngoài):
{
  "title": "Tiêu đề bài viết tối ưu SEO cho từ khóa giá rẻ",
  "excerpt": "Mô tả ngắn hấp dẫn từ 120 đến 155 ký tự",
  "category": "Chọn 1 trong: 'tech', 'lifestyle', 'business', 'other'",
  "content": "Toàn bộ nội dung bài viết dạng HTML phong phú"
}
PROMPT;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";

        try {
            $response = Http::timeout(75)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.75,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? $response->body();
                Log::error("[AutoBlog] Gemini API Error: " . $errorMessage);

                if ($model !== 'gemini-1.5-flash' && str_contains(strtolower($errorMessage), 'not found')) {
                    Log::info("[AutoBlog] Fallback sang gemini-1.5-flash");
                    return $this->writePostForProduct($product, $forcedAngle, 'gemini-1.5-flash', $key);
                }

                throw new \Exception("Lỗi Gemini API ({$response->status()}): " . $errorMessage);
            }

            $responseData = $response->json();
            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (empty($rawText)) {
                throw new \Exception("Gemini API không trả về nội dung bài viết.");
            }

            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText));
            $parsed = json_decode($cleanJson, true);

            if (!is_array($parsed) || empty($parsed['content'])) {
                Log::warning("[AutoBlog] Phản hồi không phải JSON hợp lệ, fallback", ['raw' => $rawText]);
                $cleanContent = nl2br(e($rawText));
                $parsed = [
                    'title' => "Mua {$product->name} Giá Rẻ Nhất - Bảo Hành Uy Tín Tại DungThu.com",
                    'excerpt' => mb_substr(strip_tags($rawText), 0, 150),
                    'category' => $suggestedCategory,
                    'content' => $cleanContent,
                ];
            }

            // Làm sạch nội dung & kiểm soát excerpt
            $finalTitle = trim($parsed['title'] ?? "Mua {$product->name} Giá Rẻ Tại DungThu.com");
            $finalExcerpt = trim($parsed['excerpt'] ?? '');
            if (mb_strlen($finalExcerpt) > 160) {
                $finalExcerpt = mb_substr($finalExcerpt, 0, 157) . '...';
            }

            $finalContent = $parsed['content'];
            $finalContent = str_replace(
                ['[Số điện thoại của bạn]', '[Số điện thoại]', '[Hotline]', '[Zalo]', '[SĐT của bạn]'],
                '0772698113',
                $finalContent
            );
            $finalContent = preg_replace(
                '/\[\s*(?:số\s*điện\s*thoại(?:\s*của\s*bạn)?|hotline(?:\s*của\s*bạn)?|zalo(?:\s*của\s*bạn)?|sđt(?:\s*của\s*bạn)?)\s*\]/iu',
                '0772698113',
                $finalContent
            );

            // Tự động tạo ảnh phù hợp cho bài viết
            $imageRelativePath = $this->generateAndSaveBlogImage($product, $parsed, $key);

            // KHÔNG chèn thẻ img vào $finalContent vì view blogs/show.blade.php đã tự động render $blog->image ở banner chính (tránh lỗi x2 hình)

            // Tạo slug duy nhất
            $baseSlug = Str::slug($finalTitle);
            $slug = $baseSlug;
            if (Blog::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . time();
            }

            $category = in_array($parsed['category'] ?? '', ['tech', 'lifestyle', 'business', 'other'])
                ? $parsed['category']
                : $suggestedCategory;

            // Lưu bài viết vào Database
            $blog = Blog::create([
                'title'             => $finalTitle,
                'slug'              => $slug,
                'excerpt'           => $finalExcerpt,
                'content'           => $finalContent,
                'image'             => $imageRelativePath,
                'category'          => $category,
                'product_id'        => $product->id,
                'is_auto_generated' => true,
                'auto_tone'         => $angle['key'],
                'is_featured'       => ($runCount % 3 === 0),
                'user_id'           => self::getAdminUserId(),
                'views'             => 0,
                'is_published'      => true,
                'published_at'      => now(),
            ]);

            // Tự động gửi URL bài viết lên Google Indexing API
            $indexingResult = GoogleIndexingService::submitBlogSafe($blog, 'auto_blog');

            // Cập nhật con trỏ sản phẩm & thống kê hệ thống
            SiteSetting::setValue('auto_blog_last_product_id', $product->id);
            SiteSetting::setValue('auto_blog_last_run_at', now()->toIso8601String());
            SiteSetting::setValue('auto_blog_total_generated', $runCount + 1);
            SiteSetting::setValue('auto_blog_last_blog_id', $blog->id);

            // Xóa cache blog để người dùng thấy bài mới ngay
            Cache::forget('blog_topics_list');

            Log::info("[AutoBlog] Tạo bài viết tự động thành công cho SP #{$product->id} - {$product->name} -> Blog #{$blog->id} ('{$blog->title}')");

            return [
                'success'         => true,
                'message'         => "Đã viết bài tự động thành công cho sản phẩm #{$product->id} - {$product->name}!",
                'blog'            => [
                    'id'           => $blog->id,
                    'title'        => $blog->title,
                    'slug'         => $blog->slug,
                    'excerpt'      => $blog->excerpt,
                    'image'        => $blog->image,
                    'category'     => $blog->category,
                    'published_at' => $blog->published_at?->format('d/m/Y H:i'),
                    'url'          => url('/blog/' . $blog->slug),
                    'edit_url'     => route('admin.blogs.edit', $blog->id),
                ],
                'product'         => [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => $priceFormatted,
                ],
                'angle'           => $angle,
                'indexing_status' => $indexingResult['status'] ?? 'unknown',
            ];

        } catch (\Exception $e) {
            Log::error("[AutoBlog] Lỗi khi tạo bài tự động: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tạo hình ảnh bài viết tự động:
     * - Cấp 1: Thử gọi Google Imagen 3 API bằng API Key Gemini có sẵn.
     * - Cấp 2: Nếu Imagen chưa kích hoạt trên key đó, ghép ảnh sản phẩm với nền ambient blur hiện đại bằng Intervention Image.
     * - Cấp 3: Vẽ banner gradient thương hiệu DungThu.com chất lượng cao (1200x800).
     */
    public function generateAndSaveBlogImage(Product $product, array $blogData, ?string $apiKey = null): ?string
    {
        $dir = 'images/blogs';
        $fileName = 'auto_' . $product->id . '_' . time() . '_' . Str::random(6) . '.jpg';
        $relativePath = '/' . $dir . '/' . $fileName;

        $rootPath = PathHelper::publicRootPath($dir);
        if (!is_dir($rootPath)) {
            @mkdir($rootPath, 0755, true);
        }
        $fullPath = $rootPath . DIRECTORY_SEPARATOR . $fileName;

        // CẤP 1: Thử tạo ảnh bằng Google Imagen 3 API với Gemini Key
        $key = GeminiBlogService::getApiKey($apiKey);
        if (!empty($key)) {
            try {
                $imageBytes = $this->callGoogleImagen($product, $blogData, $key);
                if (!empty($imageBytes)) {
                    $this->saveImageToBothLocations($imageBytes, $fileName, $dir);
                    Log::info("[AutoBlog] Tạo ảnh bài viết thành công qua Google Imagen 3 cho SP #{$product->id}");
                    return $relativePath;
                }
            } catch (\Throwable $e) {
                Log::info("[AutoBlog] Google Imagen không khả dụng ({$e->getMessage()}), chuyển sang cấp tạo ảnh sản phẩm...");
            }
        }

        // CẤP 2: Ghép ảnh sản phẩm có sẵn với hiệu ứng nền ambient blur hiện đại
        try {
            $imageBytes = $this->compositeProductBanner($product);
            if (!empty($imageBytes)) {
                $this->saveImageToBothLocations($imageBytes, $fileName, $dir);
                return $relativePath;
            }
        } catch (\Throwable $e) {
            Log::warning("[AutoBlog] Ghép ảnh sản phẩm lỗi: " . $e->getMessage() . ", chuyển sang vẽ banner gradient...");
        }

        // CẤP 3: Vẽ banner gradient thương hiệu DungThu.com 1200x800
        try {
            $imageBytes = $this->createBrandGradientBanner($product);
            if (!empty($imageBytes)) {
                $this->saveImageToBothLocations($imageBytes, $fileName, $dir);
                return $relativePath;
            }
        } catch (\Throwable $e) {
            Log::error("[AutoBlog] Vẽ banner gradient lỗi: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Gọi Google Imagen 3 API để sinh ảnh thương mại đẹp mắt cho sản phẩm
     */
    private function callGoogleImagen(Product $product, array $blogData, string $apiKey): ?string
    {
        $englishPrompt = "Professional commercial advertising banner for '{$product->name}', clean modern ecommerce aesthetic, dramatic studio lighting, 4k resolution, vibrant colors, premium product presentation, wide banner";

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$apiKey}";

        $response = Http::timeout(25)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, [
                'instances' => [
                    ['prompt' => $englishPrompt],
                ],
                'parameters' => [
                    'sampleCount' => 1,
                    'aspectRatio' => '16:9',
                ],
            ]);

        if ($response->successful()) {
            $json = $response->json();
            $base64 = $json['predictions'][0]['bytesBase64Encoded'] ?? null;
            if (!empty($base64)) {
                return base64_decode($base64);
            }
        }

        return null;
    }

    /**
     * Ghép ảnh sản phẩm với nền ambient blur 1200x800 bằng Intervention Image v3
     */
    private function compositeProductBanner(Product $product): ?string
    {
        if (empty($product->image)) {
            return null;
        }

        $rawSource = null;
        $imageUrl = $product->image;

        // Nếu là URL tuyệt đối
        if (preg_match('/^https?:\/\//i', $imageUrl)) {
            $parsedUrl = parse_url($imageUrl);
            $host = $parsedUrl['host'] ?? '';
            $path = ltrim($parsedUrl['path'] ?? '', '/');

            // Nếu trỏ về localhost / 127.0.0.1, đọc trực tiếp file cục bộ
            if (in_array($host, ['localhost', '127.0.0.1']) || empty($host)) {
                $localPath = PathHelper::publicRootPath($path);
                if (is_file($localPath)) {
                    $rawSource = @file_get_contents($localPath);
                } elseif (is_file(public_path($path))) {
                    $rawSource = @file_get_contents(public_path($path));
                }
            }

            if (empty($rawSource)) {
                try {
                    $fetch = Http::timeout(6)->get($imageUrl);
                    if ($fetch->successful()) {
                        $rawSource = $fetch->body();
                    }
                } catch (\Throwable $e) {
                    Log::warning("[AutoBlog] Không thể tải ảnh qua URL: {$imageUrl} ({$e->getMessage()})");
                }
            }
        } else {
            // Là file cục bộ
            $cleanPath = ltrim($imageUrl, '/');
            $localPath = PathHelper::publicRootPath($cleanPath);
            if (is_file($localPath)) {
                $rawSource = @file_get_contents($localPath);
            } elseif (is_file(public_path($cleanPath))) {
                $rawSource = @file_get_contents(public_path($cleanPath));
            }
        }

        if (empty($rawSource)) {
            return null;
        }

        $manager = new ImageManager(new GdDriver());
        $src = $manager->read($rawSource);

        $width = 1200;
        $height = 800;

        // Tạo canvas nền tối sang trọng
        $canvas = $manager->create($width, $height)->fill('#0f172a');

        // Tạo layer nền mờ ambient blur
        $bg = clone $src;
        $bg->cover($width, $height)->blur(28);
        $canvas->place($bg, 'top-left', 0, 0, 45); // 45% opacity

        // Tạo layer bóng mờ cho sản phẩm ở trung tâm
        $center = clone $src;
        $center->scaleDown(700, 600);
        $canvas->place($center, 'center');

        return (string) $canvas->toJpeg(90);
    }

    /**
     * Vẽ banner gradient thương hiệu DungThu.com 1200x800 bằng Intervention Image v3
     */
    private function createBrandGradientBanner(Product $product): string
    {
        $manager = new ImageManager(new GdDriver());
        $width = 1200;
        $height = 800;

        $canvas = $manager->create($width, $height)->fill('#0f172a');

        // Dùng GD vẽ gradient mịn và text nhận diện
        $img = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; $y++) {
            $r = (int) (15 + (30 - 15) * ($y / $height));
            $g = (int) (23 + (58 - 23) * ($y / $height));
            $b = (int) (42 + (138 - 42) * ($y / $height));
            $col = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $width, $y, $col);
        }

        $white = imagecolorallocate($img, 255, 255, 255);
        $amber = imagecolorallocate($img, 245, 158, 11);
        $blueLight = imagecolorallocate($img, 147, 197, 253);

        imagestring($img, 5, 80, 80, "DUNGTHU.COM - GIAI PHAP SO GIA RE", $amber);
        imagestring($img, 5, 80, 130, strtoupper(Str::ascii($product->name)), $white);

        $priceText = "GIA UU DAI: " . number_format($product->price, 0, ',', '.') . " VND";
        imagestring($img, 5, 80, 180, $priceText, $blueLight);
        imagestring($img, 5, 80, 230, "BAO HANH 1 DOI 1 - HOTLINE/ZALO: 0772698113", $amber);

        ob_start();
        imagejpeg($img, null, 90);
        $bytes = ob_get_clean();
        imagedestroy($img);

        return $bytes;
    }

    /**
     * Lưu file ảnh đồng thời vào cả PathHelper::publicRootPath() và public_path() (nếu khác nhau)
     * để tương thích cả localhost dev lẫn cPanel hosting.
     */
    private function saveImageToBothLocations(string $bytes, string $fileName, string $dir): void
    {
        $rootPath = PathHelper::publicRootPath($dir);
        if (!is_dir($rootPath)) {
            @mkdir($rootPath, 0755, true);
        }
        @file_put_contents($rootPath . DIRECTORY_SEPARATOR . $fileName, $bytes);

        $localPath = public_path($dir);
        if ($localPath !== $rootPath) {
            if (!is_dir($localPath)) {
                @mkdir($localPath, 0755, true);
            }
            @file_put_contents($localPath . DIRECTORY_SEPARATOR . $fileName, $bytes);
        }
    }

    /**
     * Lấy ID người dùng Admin làm tác giả bài viết
     */
    public static function getAdminUserId(): int
    {
        if (auth()->check()) {
            return auth()->id();
        }

        $admin = User::whereIn('role', ['sieusuperadmin', 'superadmin_1', 'admin'])->first()
            ?? User::first();

        return $admin ? $admin->id : 1;
    }

    /**
     * Lấy trạng thái hàng đợi và thông tin cho Dashboard Quản lý Auto Blog
     */
    public function getQueueStatus(): array
    {
        $enabled = (bool) SiteSetting::getValue('auto_blog_enabled', '1');
        $lastId = (int) SiteSetting::getValue('auto_blog_last_product_id', 0);
        $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0);
        $totalGenerated = (int) SiteSetting::getValue('auto_blog_total_generated', 0);
        $lastRunAt = SiteSetting::getValue('auto_blog_last_run_at');
        $model = SiteSetting::getValue('auto_blog_model', SiteSetting::getValue('gemini_default_model', 'gemini-3.5-flash'));

        $totalProducts = Product::where('is_active', true)->count();
        $lastProduct = $lastId > 0 ? Product::find($lastId) : null;
        $nextProduct = $this->getNextProduct();

        $recentBlogs = Blog::autoGenerated()
            ->with('product')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        $apiKey = GeminiBlogService::getApiKey();
        $hasKey = !empty($apiKey);
        $maskedKey = $hasKey ? (substr($apiKey, 0, 6) . '...' . substr($apiKey, -4)) : null;

        // Vị trí hiện tại trong chu kỳ
        $currentPosition = 1;
        if ($nextProduct) {
            $currentPosition = Product::where('is_active', true)
                ->where('id', '<=', $nextProduct->id)
                ->count();
        }

        return [
            'is_enabled'       => $enabled,
            'interval_minutes' => 15,
            'total_products'   => $totalProducts,
            'current_position' => $currentPosition,
            'last_product'     => $lastProduct,
            'next_product'     => $nextProduct,
            'cycle_count'      => $cycle,
            'total_generated'  => $totalGenerated,
            'last_run_at'      => $lastRunAt ? Carbon::parse($lastRunAt) : null,
            'has_api_key'      => $hasKey,
            'masked_key'       => $maskedKey,
            'model'            => $model,
            'recent_blogs'     => $recentBlogs,
        ];
    }
}
