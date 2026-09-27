<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiBlogService
{
    /**
     * Lấy Gemini API Key theo thứ tự ưu tiên:
     * 1. Key truyền trực tiếp từ request
     * 2. Key lưu trong SiteSetting
     * 3. Key trong file .env (services.gemini.api_key)
     */
    public static function getApiKey(?string $providedKey = null): ?string
    {
        if (!empty($providedKey)) {
            return trim($providedKey);
        }

        $settingKey = SiteSetting::getValue('gemini_api_key');
        if (!empty($settingKey)) {
            return trim($settingKey);
        }

        $configKey = config('services.gemini.api_key');
        if (!empty($configKey)) {
            return trim($configKey);
        }

        return env('GEMINI_API_KEY');
    }

    /**
     * Chuẩn hóa tên model Gemini
     */
    public static function formatModelName(string $model): string
    {
        $model = strtolower(trim($model));
        
        $map = [
            'gemini 3.1 flash-lite' => 'gemini-3.1-flash-lite',
            'gemini 3.5 flash'      => 'gemini-3.5-flash',
            'gemini 2.0 flash'      => 'gemini-2.0-flash',
            'gemini 2.0 flash-lite' => 'gemini-2.0-flash-lite',
            'gemini 1.5 flash'      => 'gemini-1.5-flash',
            'gemini 1.5 pro'        => 'gemini-1.5-pro',
        ];

        return $map[$model] ?? $model;
    }

    /**
     * Tạo nội dung bài viết blog bán hàng bằng Gemini API
     */
    public function generateBlogPost(
        string $title,
        string $model = 'gemini-2.0-flash',
        ?string $apiKey = null,
        ?string $tone = 'sales'
    ): array {
        $key = self::getApiKey($apiKey);
        if (empty($key)) {
            throw new \Exception('Chưa cấu hình Gemini API Key! Vui lòng nhập API Key tại trang Cài Đặt hoặc trong khung trợ lý AI.');
        }

        $formattedModel = self::formatModelName($model);

        $toneInstructions = [
            'sales'      => 'Phong cách BÁN HÀNG CHỐT ĐƠN mạnh mẽ, thuyết phục, đánh trúng tâm lý mua hàng và thúc đẩy khách hàng hành động/đặt mua ngay.',
            'consulting' => 'Phong cách TƯ VẤN CHUYÊN NGHIỆP, phân tích sâu sắc, xây dựng niềm tin vững chắc cho khách hàng.',
            'sharing'    => 'Phong cách CHIA SẺ KINH NGHIỆM thực tế, gần gũi, vừa trao giá trị vừa lồng ghép khéo léo dịch vụ/sản phẩm.',
            'review'     => 'Phong cách đánh giá, REVIEW CHI TIẾT, so sánh ưu điểm vượt trội để người đọc thấy rõ lợi ích.',
        ];

        $selectedTone = $toneInstructions[$tone] ?? $toneInstructions['sales'];

        $prompt = <<<PROMPT
Bạn là một Chuyên gia Content Marketing & Copywriter hàng đầu. Nhiệm vụ của bạn là viết một bài blog chuẩn SEO và CHUẨN FORM BÁN HÀNG HẤP DẪN (kéo khách hàng, tăng tỷ lệ chuyển đổi chốt đơn).

THÔNG TIN ĐẦU VÀO:
- Tiêu đề bài viết: "{$title}"
- Giọng văn: {$selectedTone}

CẤU TRÚC BÀI VIẾT BẮT BUỘC:
1. Đặt vấn đề & Nỗi đau khách hàng: Nhấn mạnh vấn đề người đọc đang gặp phải và vì sao họ cần giải pháp ngay.
2. Giới thiệu Giải pháp & Lợi ích vượt trội: Giới thiệu dịch vụ/sản phẩm giúp giải quyết triệt để vấn đề đó. Liệt kê các lợi ích nổi bật nhất (dùng thẻ <ul> và <li>).
3. Lý do khách hàng chọn chúng tôi: Nêu 3-5 ưu điểm cạnh tranh (Giá rẻ/Uy tín/Hỗ trợ 24/7/Bảo hành/Tốc độ).
4. Bảng giá hoặc Gói ưu đãi thu hút: Gợi ý các gói dịch vụ/sản phẩm với mức giá hấp dẫn và ưu đãi đi kèm.
5. Kêu gọi hành động (Call To Action - CTA): Lời chốt sales cực kỳ mạnh mẽ, thúc đẩy khách hàng liên hệ hoặc đặt mua ngay lập tức.

YÊU CẦU ĐỊNH DẠNG ĐẦU RA:
Bạn PHẢI trả về ĐÚNG 1 ĐỐI TƯỢNG JSON thuần túy (không kèm thêm bất kỳ văn bản ngoài nào) với cấu trúc sau:
{
  "title": "Tiêu đề bài viết được tối ưu lại cho thu hút và chuẩn SEO (nếu cần)",
  "excerpt": "Mô tả ngắn hấp dẫn, tóm tắt bài viết trong 120 - 155 ký tự (TUYỆT ĐỐI KHÔNG vượt quá 160 ký tự) để hiển thị danh sách bài viết",
  "category": "Chọn 1 trong các giá trị sau phù hợp nhất: 'tech', 'lifestyle', 'business', 'other'",
  "content": "Toàn bộ nội dung bài viết dạng HTML phong phú (dùng <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>, <blockquote>, <div class=\"alert alert-primary p-3 rounded mb-3\">...</div> cho phần Lợi ích hoặc Kêu gọi mua hàng)"
}
PROMPT;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$formattedModel}:generateContent?key={$key}";

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? $response->body();
                Log::error("Gemini API Error: " . $errorMessage);

                // Nếu model truyền vào không tồn tại, thử fallback sang gemini-1.5-flash
                if ($formattedModel !== 'gemini-1.5-flash' && str_contains(strtolower($errorMessage), 'not found')) {
                    Log::info("Retrying Gemini API with fallback model gemini-1.5-flash");
                    return $this->generateBlogPost($title, 'gemini-1.5-flash', $key, $tone);
                }

                throw new \Exception("Lỗi từ Gemini API ({$response->status()}): " . $errorMessage);
            }

            $responseData = $response->json();
            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (empty($rawText)) {
                throw new \Exception("Gemini API không trả về nội dung hợp lệ.");
            }

            // Làm sạch nếu phản hồi bị bọc bởi markdown block ```json ... ```
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText));
            $parsed = json_decode($cleanJson, true);

            if (!is_array($parsed) || !isset($parsed['content'])) {
                Log::warning("Gemini API raw response non-JSON fallback", ['rawText' => $rawText]);
                return [
                    'title' => $title,
                    'excerpt' => mb_substr(strip_tags($rawText), 0, 150),
                    'category' => 'business',
                    'content' => nl2br(e($rawText)),
                ];
            }

            // Đảm bảo excerpt <= 162 ký tự
            $excerpt = trim($parsed['excerpt'] ?? '');
            if (mb_strlen($excerpt) > 160) {
                $excerpt = mb_substr($excerpt, 0, 157) . '...';
            }

            return [
                'title' => $parsed['title'] ?? $title,
                'excerpt' => $excerpt,
                'category' => in_array($parsed['category'] ?? '', ['tech', 'lifestyle', 'business', 'other']) ? $parsed['category'] : 'business',
                'content' => $parsed['content'],
            ];

        } catch (\Exception $e) {
            Log::error("GeminiBlogService Exception: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bóc tách các gói dịch vụ/biến thể sản phẩm bằng Gemini AI
     */
    public function parseProductVariants(
        string $rawInput,
        ?string $apiKey = null,
        ?string $productName = null
    ): array {
        $key = self::getApiKey($apiKey);
        if (empty($key)) {
            throw new \Exception('Chưa cấu hình Gemini API Key! Vui lòng nhập API Key tại trang Cài Đặt hoặc trong khung trợ lý AI.');
        }

        $formattedModel = 'gemini-2.0-flash';

        $prompt = <<<PROMPT
Bạn là chuyên gia phân tích và chuẩn hóa dữ liệu sản phẩm thương mại điện tử.
Người dùng (Admin/Nhân viên) cung cấp một văn bản ghi chép các gói dịch vụ, phiên bản hoặc biến thể sản phẩm, trong đó có kèm mô tả và yêu cầu của LEADER ở cuối các dòng hoặc cuối văn bản.

TÊN SẢN PHẨM (NẾU CÓ): "{$productName}"

VĂN BẢN ĐẦU VÀO CỦA LEADER:
"""
{$rawInput}
"""

QUY TẮC BÓC TÁCH VÀ CHUẨN HÓA BẮT BUỘC:
1. MỖI DÒNG thường là một gói sản phẩm. Đọc kỹ từng dòng để phân tách đầy đủ các gói.
2. ĐẶC BIỆT LƯU Ý MÔ TẢ CỦA LEADER Ở CUỐI CÁC DÒNG:
   - Leader thường ghi chú thêm ở cuối mỗi dòng hoặc cuối đoạn (ví dụ: '- 1 thiết bị', '- bảo hành 30 ngày', '- tài khoản cấp sẵn', '- kho 50 cái', '- gói 12 tháng tặng 1 tháng', '- profile riêng', v.v.).
   - Bạn PHẢI TUÂN THEO các mô tả này và ghép thông tin quan trọng vào "name" (Tên gói) một cách súc tích, chuyên nghiệp. Ví dụ: "Gói 1 Tháng (1 Profile, Cấp Sẵn)".
3. "price": Giá bán thực tế (BẮT BUỘC là số nguyên VNĐ, ví dụ: 35k -> 35000, 99.000đ -> 99000, 1tr2 -> 1200000). Không để trống.
4. "sale_price": Giá gốc trước khi giảm (số nguyên VNĐ). Nếu có giá gốc/giá gạch/giá niêm yết thì điền, nếu không có để null.
5. "stock": Tồn kho (số nguyên). Mặc định là 10 nếu không nói rõ, hoặc theo số lượng kho mà Leader chỉ định ở cuối dòng hoặc cuối văn bản.
6. "duration_value": Giá trị thời hạn dạng số nguyên (Ví dụ: 1, 3, 6, 12, 30). Nếu vĩnh viễn hoặc không thời hạn thì để null.
7. "duration_type": Đơn vị thời hạn: chỉ chọn 1 trong các giá trị ['days', 'months', 'years'] hoặc null nếu không có thời hạn.
   - Ví dụ: "30 ngày" -> duration_value: 30, duration_type: "days"
   - Ví dụ: "3 tháng" -> duration_value: 3, duration_type: "months"
   - Ví dụ: "1 năm" -> duration_value: 1, duration_type: "years"

ĐỊNH DẠNG ĐẦU RA BẮT BUỘC:
Trả về DUY NHẤT 1 mảng JSON thuần túy (không kèm giải thích markdown ngoài):
[
  {
    "name": "Tên gói kèm ghi chú của leader",
    "price": 35000,
    "sale_price": 50000,
    "stock": 10,
    "duration_value": 1,
    "duration_type": "months"
  }
]
PROMPT;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$formattedModel}:generateContent?key={$key}";

        try {
            $response = Http::timeout(45)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? $response->body();
                Log::error("Gemini parseProductVariants Error: " . $errorMessage);
                throw new \Exception("Lỗi Gemini API: " . $errorMessage);
            }

            $responseData = $response->json();
            $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText));
            $parsed = json_decode($cleanJson, true);

            if (!is_array($parsed)) {
                throw new \Exception("Không thể phân tích dữ liệu gói từ phản hồi của AI.");
            }

            if (isset($parsed['variants']) && is_array($parsed['variants'])) {
                $parsed = $parsed['variants'];
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::error("parseProductVariants Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
