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
     * Xác định chính xác mức giá ưu đãi (giá giảm) và giá gốc niêm yết của sản phẩm.
     * Luôn ưu tiên GIÁ BÁN GIẢM (sale_price hoặc giá thấp nhất của các biến thể/gói dịch vụ).
     *
     * @param Product $product
     * @return array [
     *     'discount_price'        => float (Giá giảm/Giá bán thực tế, ví dụ: 299000),
     *     'original_price'        => ?float (Giá gốc niêm yết, ví dụ: 550000),
     *     'discount_formatted'    => string (Ví dụ: "299.000đ"),
     *     'original_formatted'    => ?string (Ví dụ: "550.000đ"),
     *     'has_sale'              => bool,
     *     'save_percent'          => int (Ví dụ: 45),
     *     'save_amount'           => float (Ví dụ: 251000),
     *     'save_amount_formatted' => string (Ví dụ: "251.000đ"),
     *     'variant_name'          => ?string,
     *     'has_variants'          => bool,
     * ]
     */
    public static function resolveProductPriceDetails(Product $product): array
    {
        // 1. Kiểm tra các gói dịch vụ/biến thể đang hoạt động (activeVariants)
        $hasVariants = $product->relationLoaded('activeVariants')
            ? $product->activeVariants->isNotEmpty()
            : $product->activeVariants()->exists();

        $discountPrice = 0.0;
        $originalPrice = null;
        $hasSale = false;
        $variantName = null;

        if ($hasVariants) {
            $variants = $product->relationLoaded('activeVariants')
                ? $product->activeVariants
                : $product->activeVariants()->get();

            // Sắp xếp các biến thể theo giá bán thực tế thấp nhất (ưu tiên gói có giá rẻ nhất để quảng bá)
            $sortedVariants = $variants->sortBy(function ($v) {
                $vPrice = (float) ($v->price ?? 0);
                $vSale = (float) ($v->sale_price ?? 0);
                return ($vSale > 0 && $vSale < $vPrice) ? $vSale : ($vPrice > 0 ? $vPrice : 999999999);
            })->values();

            $firstVariant = $sortedVariants->firstWhere('stock', '>', 0) ?? $sortedVariants->first();

            if ($firstVariant) {
                $variantName = $firstVariant->name;
                $vPrice = (float) ($firstVariant->price ?? 0);
                $vSale = (float) ($firstVariant->sale_price ?? 0);

                if ($vSale > 0 && $vSale < $vPrice) {
                    $discountPrice = $vSale;
                    $originalPrice = $vPrice;
                    $hasSale = true;
                } else {
                    $discountPrice = $vPrice;
                    // Nếu gói dịch vụ này chưa có sale_price nhưng giá gốc sản phẩm cha cao hơn
                    $parentPrice = (float) ($product->price ?? 0);
                    if ($parentPrice > $discountPrice && $parentPrice > 0) {
                        $originalPrice = $parentPrice;
                        $hasSale = true;
                    }
                }
            }
        }

        // 2. Nếu sản phẩm không có biến thể hoặc biến thể chưa có giá hợp lệ
        if ($discountPrice <= 0) {
            $pPrice = (float) ($product->price ?? 0);
            $pSale = (float) ($product->sale_price ?? 0);

            if ($pSale > 0 && $pSale < $pPrice) {
                $discountPrice = $pSale;
                $originalPrice = $pPrice;
                $hasSale = true;
            } elseif ($pPrice > 0 && $pSale > $pPrice) {
                // Trường hợp người dùng nhập nhầm sale_price và price đảo ngược nhau trong admin
                $discountPrice = $pPrice;
                $originalPrice = $pSale;
                $hasSale = true;
            } else {
                $discountPrice = $pPrice > 0 ? $pPrice : $pSale;
                $originalPrice = null;
                $hasSale = false;
            }
        }

        // Đảm bảo không âm
        $discountPrice = max(0, $discountPrice);

        $saveAmount = ($hasSale && $originalPrice && $originalPrice > $discountPrice)
            ? ($originalPrice - $discountPrice)
            : 0.0;

        $savePercent = ($hasSale && $originalPrice && $originalPrice > 0 && $originalPrice > $discountPrice)
            ? (int) round(($saveAmount / $originalPrice) * 100)
            : 0;

        $isEffectiveSale = ($hasSale && $originalPrice !== null && $originalPrice > $discountPrice && $savePercent > 0);

        return [
            'discount_price'        => $discountPrice,
            'original_price'        => $isEffectiveSale ? $originalPrice : null,
            'discount_formatted'    => number_format($discountPrice, 0, ',', '.') . 'đ',
            'original_formatted'    => $isEffectiveSale ? number_format($originalPrice, 0, ',', '.') . 'đ' : null,
            'has_sale'              => $isEffectiveSale,
            'save_amount'           => $saveAmount,
            'save_amount_formatted' => number_format($saveAmount, 0, ',', '.') . 'đ',
            'save_percent'          => $savePercent,
            'variant_name'          => $variantName,
            'has_variants'          => $hasVariants,
        ];
    }

    /**
     * Tạo mã HTML chuẩn cho Khung Sản Phẩm Nổi Bật (Product Highlight Box)
     * Hiển thị GIÁ GIẢM ƯU ĐÃI NỔI BẬT (màu đỏ rực, font to), gạch ngang giá gốc và huy hiệu % tiết kiệm.
     */
    public static function renderProductHighlightBox(Product $product, array $priceDetails, string $productUrl): string
    {
        $discountFormatted = $priceDetails['discount_formatted'];
        $originalFormatted = $priceDetails['original_formatted'];
        $hasSale = $priceDetails['has_sale'];
        $savePercent = $priceDetails['save_percent'];

        if ($hasSale && !empty($originalFormatted)) {
            $priceLine = <<<HTML
<p class="text-muted mb-3 d-flex align-items-center justify-content-center flex-wrap gap-2">
    <span>Mức giá ưu đãi chỉ:</span>
    <strong class="text-danger fs-3" style="color: #dc2626 !important; font-size: 1.75rem !important; font-weight: 800;">{$discountFormatted}</strong>
    <del class="text-muted fs-6" style="text-decoration: line-through; opacity: 0.75;">{$originalFormatted}</del>
    <span class="badge bg-danger text-white rounded-pill px-2 py-1 small" style="background-color: #dc2626 !important;">Tiết kiệm {$savePercent}%</span>
</p>
HTML;
        } else {
            $priceLine = <<<HTML
<p class="text-muted mb-3 d-flex align-items-center justify-content-center flex-wrap gap-2">
    <span>Mức giá ưu đãi chỉ:</span>
    <strong class="text-danger fs-3" style="color: #dc2626 !important; font-size: 1.75rem !important; font-weight: 800;">{$discountFormatted}</strong>
    <span class="text-muted small">(Tiết kiệm tối đa - Bảo hành 1 đổi 1 uy tín)</span>
</p>
HTML;
        }

        return <<<HTML
<div class="my-4 p-4 rounded-4 text-center border shadow-sm product-highlight-box" style="background: linear-gradient(135deg, #f0f7ff 0%, #e6f0fa 100%); border-color: #bee3f8 !important;">
    <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase mb-2"><i class="fas fa-fire me-1"></i> Ưu Đãi Giá Rẻ Hôm Nay</span>
    <h3 class="fw-bold text-dark mt-2 mb-2">{$product->name}</h3>
    {$priceLine}
    <a href="{$productUrl}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow-sm product-cta-btn" style="border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; color: #111;" target="_blank">
        <i class="fas fa-shopping-cart"></i> Xem Chi Tiết & Đặt Mua Ngay
    </a>
</div>
HTML;
    }

    /**
     * Quét và cập nhật lại toàn bộ khung giá trong các bài viết blog tự động đã tạo,
     * đảm bảo hiển thị đúng giá giảm mới nhất của sản phẩm.
     */
    public function refreshExistingBlogPriceBoxes(): int
    {
        $blogs = Blog::whereNotNull('product_id')->get();
        $updatedCount = 0;

        foreach ($blogs as $blog) {
            $product = $blog->product;
            if (!$product) {
                continue;
            }

            $priceDetails = self::resolveProductPriceDetails($product);
            $productUrl = url('/product/' . $product->slug);
            $expectedBox = self::renderProductHighlightBox($product, $priceDetails, $productUrl);

            $oldContent = $blog->content;
            $newContent = $oldContent;

            if (preg_match('/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>\s*<\/div>/is', $newContent)) {
                $newContent = preg_replace(
                    '/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>\s*<\/div>/is',
                    $expectedBox,
                    $newContent
                );
            } elseif (preg_match('/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>/is', $newContent)) {
                $newContent = preg_replace(
                    '/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>/is',
                    $expectedBox,
                    $newContent
                );
            }

            if ($newContent !== $oldContent) {
                $blog->updateQuietly(['content' => $newContent]);
                $updatedCount++;
            }
        }

        return $updatedCount;
    }

    /**
     * Quét và tạo lại ảnh banner logo chuẩn đẹp cho toàn bộ bài viết blog tự động,
     * loại bỏ hoàn toàn các ảnh cũ bị lỗi hoặc dị dạng ma ám.
     */
    public function refreshExistingBlogImages(): int
    {
        $blogs = Blog::whereNotNull('product_id')->get();
        $updatedCount = 0;

        foreach ($blogs as $blog) {
            $product = $blog->product;
            if (!$product) {
                continue;
            }

            $angles = self::getAngles();
            $angle = $angles[$blog->auto_tone ?? 'deal_san_sale'] ?? array_values($angles)[0];
            $newImage = $this->generateAndSaveBlogImage($product, ['title' => $blog->title], null, $angle, $blog->id);

            if ($newImage) {
                $blog->updateQuietly(['image' => $newImage]);
                $updatedCount++;
            }
        }

        return $updatedCount;
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

        // Chuẩn bị dữ liệu giá bán & thông tin sản phẩm (Luôn ưu tiên GIÁ BÁN GIẢM để kích thích chốt đơn)
        $priceDetails = self::resolveProductPriceDetails($product);
        $discountPriceFormatted = $priceDetails['discount_formatted'];
        $originalPriceFormatted = $priceDetails['original_formatted'];
        $hasSale = $priceDetails['has_sale'];
        $savePercent = $priceDetails['save_percent'];
        $productUrl = url('/product/' . $product->slug);

        $boxHtmlTemplate = self::renderProductHighlightBox($product, $priceDetails, $productUrl);

        $originalPriceLine = $hasSale && !empty($originalPriceFormatted)
            ? "{$originalPriceFormatted} (Đang giảm giá {$savePercent}%)"
            : "Chưa áp dụng giảm thêm (Giá cạnh tranh nhất thị trường)";

        $savePromptGuide = $hasSale && !empty($originalPriceFormatted)
            ? "Nêu bật bài toán kinh tế: Giá gốc {$originalPriceFormatted} nay chỉ còn {$discountPriceFormatted} tại DungThu.com (Tiết kiệm tới {$savePercent}%)."
            : "Nêu bật ưu điểm mức giá hạt dẻ chỉ {$discountPriceFormatted}, rẻ hơn đáng kể so với thị trường.";

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

THÔNG TIN SẢN PHẨM & MỨC GIÁ CHÍNH XÁC:
- Tên sản phẩm: "{$product->name}"
- GIÁ BÁN ƯU ĐÃI (GIÁ GIẢM THỰC TẾ): {$discountPriceFormatted} (BẮT BUỘC DÙNG MỨC GIÁ NÀY LÀM GIÁ BÁN CHÍNH!)
- GIÁ GỐC NIÊM YẾT: {$originalPriceLine}
- Mức giảm giá: {$savePercent}%
- Danh mục sản phẩm: {$product->category}
- Mô tả / Thông số tóm tắt: "{$product->description}"
- Đường dẫn xem chi tiết & mua hàng trực tiếp: {$productUrl}
- Website: DungThu.com | Hotline / Zalo hỗ trợ: 0772698113

QUY TẮC BẮT BUỘC VỀ GIÁ TIỀN (TRÁNH KHÁCH HIỂU LẦM GIÁ ĐẮT):
1. BẮT BUỘC trong toàn bộ bài viết, khi nhắc đến giá bán bạn PHẢI dùng đúng GIÁ BÁN ƯU ĐÃI LÀ: {$discountPriceFormatted}.
2. TUYỆT ĐỐI KHÔNG lấy giá gốc làm giá bán chính, vì khách hàng sẽ thấy đắt tiền và thoát trang!
3. {$savePromptGuide}

PHONG CÁCH & GÓC TIẾP CẬN BÀI VIẾT NÀY:
- Góc tiếp cận: {$angle['name']}
- Định hướng: {$angle['focus']}

CÁC YÊU CẦU SEO & NỘI DUNG BẮT BUỘC:
1. TIÊU ĐỀ (Title): Cực kỳ hấp dẫn, kích thích tỷ lệ click (CTR), chứa các từ khóa tìm kiếm vàng như "Giá Rẻ", "Uy Tín", "Chính Hãng", "Đánh Giá", "Bảng Giá", "Mua Ở Đâu", "{name}". Tiêu đề PHẢI phù hợp với góc tiếp cận "{$angle['name']}".
2. ĐOẠN TRÍCH (Excerpt): Mô tả ngắn cuốn hút trong khoảng 120 - 155 ký tự (TUYỆT ĐỐI KHÔNG vượt quá 160 ký tự) chứa từ khóa chính và lời kích thích mua hàng.
3. NỘI DUNG (Content) DẠNG HTML PHONG PHÚ:
   - Dùng các thẻ cấu trúc chuẩn SEO: <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>, <blockquote>.
   - Thân bài phải phân tích sâu sắc, lôi cuốn, cung cấp giá trị thực tế cho người đọc, nêu bật bài toán kinh tế (tiết kiệm chi phí khi mua tại DungThu.com).
   - BẮT BUỘC chèn ĐÚNG KHUNG SẢN PHẨM NỔI BẬT sau vào vị trí trang trọng trong bài viết (dùng đúng URL: {$productUrl}):
{$boxHtmlTemplate}
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

        try {
            $callResult = GeminiBlogService::callWithFallback(
                $prompt,
                $model,
                $key,
                [
                    'temperature'      => 0.75,
                    'responseMimeType' => 'application/json',
                ],
                75
            );

            $rawText = $callResult['text'];
            $usedModel = $callResult['model_used'] ?? $model;

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

            // Chuẩn hóa và làm mới khung sản phẩm nổi bật (Đảm bảo giá giảm và giá gốc hiển thị chuẩn xác 100%, không bị Gemini sửa sai)
            if (preg_match('/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>\s*<\/div>/is', $finalContent)) {
                $finalContent = preg_replace(
                    '/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>\s*<\/div>/is',
                    $boxHtmlTemplate,
                    $finalContent
                );
            } elseif (preg_match('/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>/is', $finalContent)) {
                $finalContent = preg_replace(
                    '/<div class="[^"]*product-highlight-box[^"]*".*?<\/div>/is',
                    $boxHtmlTemplate,
                    $finalContent
                );
            } else {
                if (str_contains($finalContent, 'blog-contact-box')) {
                    $finalContent = str_replace('<div class="alert alert-primary', $boxHtmlTemplate . "\n\n" . '<div class="alert alert-primary', $finalContent);
                } else {
                    $finalContent .= "\n\n" . $boxHtmlTemplate;
                }
            }

            // Tự động tạo ảnh phù hợp cho bài viết
            $imageRelativePath = $this->generateAndSaveBlogImage($product, $parsed, $key, $angle, $runCount);

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

            $successMsg = "Đã viết bài tự động thành công cho sản phẩm #{$product->id} - {$product->name}!";
            if (!empty($usedModel) && $usedModel !== $model) {
                $successMsg .= " (Tự động chuyển sang model dự phòng: {$usedModel})";
            }

            return [
                'success'         => true,
                'message'         => $successMsg,
                'model_used'      => $usedModel ?? $model,
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
    public function generateAndSaveBlogImage(
        Product $product,
        array $blogData,
        ?string $apiKey = null,
        ?array $angle = null,
        int $runCount = 0
    ): ?string {
        $dir = 'images/blogs';
        $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0) + 1;
        $angleKey = $angle['key'] ?? 'general';

        // Tên file luôn luôn là DUY NHẤT kèm số chu kỳ, góc tiếp cận & timestamp để Google Index không bao giờ bị trùng
        $fileName = 'auto_p' . $product->id . '_c' . $cycle . '_' . $angleKey . '_' . time() . '_' . Str::random(8) . '.jpg';
        $relativePath = '/' . $dir . '/' . $fileName;

        $rootPath = PathHelper::publicRootPath($dir);
        if (!is_dir($rootPath)) {
            @mkdir($rootPath, 0755, true);
        }

        // CẤP 1 (ƯU TIÊN TUYỆT ĐỐI): Tự động thiết kế ảnh banner mới cho bài viết:
        // Lấy chính xác LOGO CỦA SẢN PHẨM đặt ở giữa, trang trí hiệu ứng quầng sáng (ambient glow), viền nền và huy hiệu
        // (Mỗi lần viết bài tự động tạo 1 ảnh mới toanh, đổi màu nền theo chu kỳ để không trùng Google Index, đẹp y như tấm Zoom Pro)
        if (!empty($product->image)) {
            try {
                $imageBytes = $this->compositeProductBanner($product, $angle, $runCount);
                if (!empty($imageBytes)) {
                    $this->saveImageToBothLocations($imageBytes, $fileName, $dir);
                    Log::info("[AutoBlog] Tự động thiết kế banner logo chuẩn đẹp thành công cho SP #{$product->id} (Cycle #{$cycle})");
                    return $relativePath;
                }
            } catch (\Throwable $e) {
                Log::warning("[AutoBlog] Ghép ảnh logo sản phẩm lỗi: " . $e->getMessage());
            }
        }

        // CẤP 2: Nếu sản phẩm chưa có ảnh, vẽ banner gradient thương hiệu DungThu.com 1200x800 sang xịn mịn
        try {
            $imageBytes = $this->createBrandGradientBanner($product, $angle, $runCount);
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
     * Tải dữ liệu nhị phân (bytes) của ảnh sản phẩm gốc
     */
    private function getProductImageRawBytes(Product $product): ?string
    {
        if (empty($product->image)) {
            return null;
        }

        $imageUrl = $product->image;
        $rawSource = null;

        if (preg_match('/^https?:\/\//i', $imageUrl)) {
            $parsedUrl = parse_url($imageUrl);
            $host = $parsedUrl['host'] ?? '';
            $path = ltrim($parsedUrl['path'] ?? '', '/');

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
            $cleanPath = ltrim($imageUrl, '/');
            $localPath = PathHelper::publicRootPath($cleanPath);
            if (is_file($localPath)) {
                $rawSource = @file_get_contents($localPath);
            } elseif (is_file(public_path($cleanPath))) {
                $rawSource = @file_get_contents(public_path($cleanPath));
            }
        }

        return $rawSource;
    }

    /**
     * Xây dựng Prompt tiếng Anh phong phú & đa phong cách cho AI Image Generators,
     * kết hợp góc nhìn bài viết + phong cách nghệ thuật ngẫu nhiên + seed để ảnh KHÔNG BAO GIỜ TRÙNG NHAU.
     */
    private function buildDynamicImagePrompt(Product $product, array $blogData, ?array $angle = null): array
    {
        $styles = [
            'Cinematic 3D commercial render, luxury dark studio lighting, glossy reflections, neon edge glow, octane render, 8k resolution, photorealistic',
            'Sleek modern minimalist tech workspace, laptop on premium wooden desk, soft natural morning sunlight, shallow depth of field, Apple aesthetic',
            'Futuristic digital hologram interface, glowing cyan and violet light rays, high-tech abstract floating data particles, cyber style',
            'Abstract vibrant 3D floating geometric crystal elements, smooth acrylic glass textures, Behance trending creative digital art',
            'Editorial tech magazine cover photography, dramatic high contrast lighting, elegant dark studio backdrop, premium brand aesthetic',
            'Warm cozy modern lifestyle setting with gadgets and notes, soft bokeh background, ambient room lighting, authentic premium feel',
            'Dynamic e-commerce promotional banner, floating 3D golden sparkles, glowing showcase pedestal, vibrant energetic sales banner',
            'Clean flat isometric tech illustration, bold vibrant gradients, modern iconography, sharp geometric aesthetics',
        ];

        $randomStyle = $styles[array_rand($styles)];
        $angleFocus = $angle['name'] ?? 'Giới thiệu sản phẩm';

        $cleanProductName = preg_replace('/[^\p{L}\p{N}\s\-\+\.]/u', '', $product->name);
        $cleanProductName = Str::limit(trim($cleanProductName), 60, '');

        $englishPrompt = "Professional commercial advertising banner for '{$cleanProductName}', concept related to {$angleFocus}, {$randomStyle}, vibrant colors, clean premium composition, 16:9 banner";
        $seed = rand(10000, 999999);

        return [
            'prompt' => $englishPrompt,
            'seed'   => $seed,
        ];
    }

    /**
     * Gọi Google Imagen 3 API để sinh ảnh thương mại đẹp mắt cho sản phẩm
     */
    private function callGoogleImagen(Product $product, array $blogData, string $apiKey, ?array $angle = null): ?string
    {
        $promptInfo = $this->buildDynamicImagePrompt($product, $blogData, $angle);
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$apiKey}";

        $response = Http::timeout(25)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, [
                'instances' => [
                    ['prompt' => $promptInfo['prompt']],
                ],
                'parameters' => [
                    'sampleCount' => 1,
                    'aspectRatio' => '16:9',
                    'seed'        => $promptInfo['seed'],
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
     * Tạo ảnh AI bằng Pollinations AI (Miễn phí 100%, không cần key, tạo ảnh AI bằng Flux/SD)
     */
    private function callPollinationsAi(Product $product, array $blogData, ?array $angle = null): ?string
    {
        $promptInfo = $this->buildDynamicImagePrompt($product, $blogData, $angle);
        $encodedPrompt = urlencode($promptInfo['prompt']);
        $seed = $promptInfo['seed'];

        $url = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width=1200&height=675&seed={$seed}&nologo=true";

        $response = Http::timeout(14)->get($url);
        if ($response->successful() && strlen($response->body()) > 5000) {
            return $response->body();
        }

        return null;
    }

    /**
     * Ghép ảnh sản phẩm với nền đa sắc thái + ambient glow + badge độc quyền (1200x800)
     * Đảm bảo KHÔNG BAO GIỜ bị trùng ảnh cũ khi lặp lại vòng xoay sản phẩm.
     */
    private function compositeProductBanner(Product $product, ?array $angle = null, int $runCount = 0): ?string
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

        // 8 Bộ màu nền sang trọng thay đổi theo chu kỳ & sản phẩm để không bao giờ trùng lặp
        $palettes = [
            [
                'bg'         => '#080d1a',
                'accent_rgb' => [99, 102, 241],   // Cyber Indigo
                'badge_bg'   => [67, 56, 202],
                'badge_tag'  => 'DEAL GIA RE',
            ],
            [
                'bg'         => '#150024',
                'accent_rgb' => [168, 85, 247],  // Royal Purple
                'badge_bg'   => [126, 34, 206],
                'badge_tag'  => 'REVIEW CHI TIET',
            ],
            [
                'bg'         => '#012017',
                'accent_rgb' => [16, 185, 129],  // Emerald Glow
                'badge_bg'   => [5, 150, 105],
                'badge_tag'  => 'TOP 1 UY TIN',
            ],
            [
                'bg'         => '#20050e',
                'accent_rgb' => [244, 63, 94],   // Crimson Sunset
                'badge_bg'   => [190, 18, 60],
                'badge_tag'  => 'UU DAI HOT 2026',
            ],
            [
                'bg'         => '#052338',
                'accent_rgb' => [14, 165, 233],  // Sapphire Ocean
                'badge_bg'   => [2, 132, 199],
                'badge_tag'  => 'TIET KIEM TOI DA',
            ],
            [
                'bg'         => '#18181b',
                'accent_rgb' => [245, 158, 11],  // Obsidian Gold
                'badge_bg'   => [217, 119, 6],
                'badge_tag'  => 'BAO HANH 1 DOI 1',
            ],
            [
                'bg'         => '#1c1917',
                'accent_rgb' => [249, 115, 22],  // Amber Bronze
                'badge_bg'   => [234, 88, 12],
                'badge_tag'  => 'CHINH HANG 100%',
            ],
            [
                'bg'         => '#032624',
                'accent_rgb' => [20, 184, 166],  // Teal Aurora
                'badge_bg'   => [13, 148, 136],
                'badge_tag'  => 'GIAI PHAP SO 1',
            ],
        ];

        $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0) + 1;
        $paletteIndex = ($runCount + $cycle + (int)$product->id) % count($palettes);
        $pal = $palettes[$paletteIndex];

        // 1. Tạo Canvas nền với màu sắc độc nhất của lượt này
        $canvas = $manager->create($width, $height)->fill($pal['bg']);

        // 2. Tạo layer nền ambient blur với độ mờ ngẫu nhiên nhẹ (22-34) và độ trong suốt (40-55%)
        $blurLevel = 24 + (($runCount * 3) % 10);
        $blurOpacity = 42 + (($runCount * 4) % 12);
        $bg = clone $src;
        $bg->cover($width, $height)->blur($blurLevel);
        $canvas->place($bg, 'top-left', 0, 0, $blurOpacity);

        // 3. Sử dụng GD native để vẽ thêm các quầng sáng (ambient glow orbs) và huy hiệu nhận diện
        $gd = $canvas->core()->native();

        // Quầng sáng góc trên bên trái
        $accentOrb1 = imagecolorallocatealpha($gd, $pal['accent_rgb'][0], $pal['accent_rgb'][1], $pal['accent_rgb'][2], 105);
        imagefilledellipse($gd, 180, 140, 480, 480, $accentOrb1);

        // Quầng sáng góc dưới bên phải
        $accentOrb2 = imagecolorallocatealpha($gd, $pal['accent_rgb'][0], $pal['accent_rgb'][1], $pal['accent_rgb'][2], 115);
        imagefilledellipse($gd, 1060, 680, 540, 540, $accentOrb2);

        // Huy hiệu góc trên bên trái (Badge tag)
        $badgeText = strtoupper(Str::ascii($angle['badge'] ?? $pal['badge_tag']));
        $badgeBgColor = imagecolorallocate($gd, $pal['badge_bg'][0], $pal['badge_bg'][1], $pal['badge_bg'][2]);
        $whiteColor = imagecolorallocate($gd, 255, 255, 255);
        $goldColor = imagecolorallocate($gd, 253, 224, 71);

        // Vẽ thẻ badge góc trái
        $badgeWidth = min(400, 30 + strlen($badgeText) * 11);
        imagefilledrectangle($gd, 45, 40, 45 + $badgeWidth, 84, $badgeBgColor);
        imagestring($gd, 5, 62, 54, $badgeText, $whiteColor);

        // Thanh footer branding nhận diện bản quyền chống trùng lặp
        $footerBg = imagecolorallocatealpha($gd, 0, 0, 0, 60);
        imagefilledrectangle($gd, 0, $height - 48, $width, $height, $footerBg);
        imagestring($gd, 4, 45, $height - 34, "DUNGTHU.COM  |  GIAI PHAP TAI KHOAN SO GIA RE  |  HOTLINE: 0772698113", $goldColor);

        // 4. Đặt logo sản phẩm ở vị trí trung tâm nổi bật
        $center = clone $src;
        $center->scaleDown(680, 520);
        $canvas->place($center, 'center');

        return (string) $canvas->toJpeg(90);
    }

    /**
     * Vẽ banner gradient thương hiệu DungThu.com 1200x800 đa dạng màu sắc
     */
    private function createBrandGradientBanner(Product $product, ?array $angle = null, int $runCount = 0): string
    {
        $manager = new ImageManager(new GdDriver());
        $width = 1200;
        $height = 800;

        $gradientThemes = [
            [[10, 15, 30], [30, 45, 90]],     // Navy Deep
            [[25, 5, 40], [70, 15, 95]],      // Violet Mystique
            [[5, 30, 20], [15, 75, 50]],      // Emerald Forest
            [[35, 10, 20], [90, 20, 45]],     // Crimson Velvet
            [[10, 25, 45], [20, 70, 110]],    // Oceanic Blue
            [[25, 25, 25], [60, 60, 60]],     // Charcoal Metallic
            [[30, 20, 10], [80, 50, 20]],     // Golden Bronze
            [[5, 30, 35], [15, 80, 90]],      // Teal Cyan
        ];

        $cycle = (int) SiteSetting::getValue('auto_blog_cycle_count', 0) + 1;
        $theme = $gradientThemes[($runCount + $cycle + (int)$product->id) % count($gradientThemes)];

        $img = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int) ($theme[0][0] + ($theme[1][0] - $theme[0][0]) * $ratio);
            $g = (int) ($theme[0][1] + ($theme[1][1] - $theme[0][1]) * $ratio);
            $b = (int) ($theme[0][2] + ($theme[1][2] - $theme[0][2]) * $ratio);
            $col = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $width, $y, $col);
        }

        $white = imagecolorallocate($img, 255, 255, 255);
        $amber = imagecolorallocate($img, 245, 158, 11);
        $blueLight = imagecolorallocate($img, 147, 197, 253);

        $badgeText = strtoupper(Str::ascii($angle['badge'] ?? 'DEAL GIA RE'));
        imagestring($img, 5, 80, 70, "[ {$badgeText} ] DUNGTHU.COM - GIAI PHAP SO GIA RE", $amber);
        imagestring($img, 5, 80, 130, strtoupper(Str::ascii(Str::limit($product->name, 45))), $white);

        $priceDetails = self::resolveProductPriceDetails($product);
        $priceText = "MUC GIA UU DAI: " . number_format($priceDetails['discount_price'], 0, ',', '.') . " VND";
        if ($priceDetails['has_sale'] && !empty($priceDetails['original_price'])) {
            $priceText .= " (TIET KIEM " . $priceDetails['save_percent'] . "%)";
        }
        imagestring($img, 5, 80, 190, $priceText, $blueLight);
        imagestring($img, 5, 80, 250, "CAM KET BAO HANH 1 DOI 1 - HOTLINE/ZALO: 0772698113", $amber);

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

        $nextPriceDetails = $nextProduct ? self::resolveProductPriceDetails($nextProduct) : null;

        return [
            'is_enabled'       => $enabled,
            'interval_minutes' => 15,
            'total_products'   => $totalProducts,
            'current_position' => $currentPosition,
            'last_product'     => $lastProduct,
            'next_product'     => $nextProduct,
            'next_product_price_formatted' => $nextPriceDetails['discount_formatted'] ?? null,
            'next_product_original_price_formatted' => $nextPriceDetails['original_formatted'] ?? null,
            'next_product_is_on_sale' => $nextPriceDetails['has_sale'] ?? false,
            'next_product_save_percent' => $nextPriceDetails['save_percent'] ?? 0,
            'next_product_price_details' => $nextPriceDetails,
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
