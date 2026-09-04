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
}
