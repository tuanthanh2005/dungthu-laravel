<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\ProductCategory;

class GuestChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $apiKey = config('services.groq.key');
        Log::info('Guest chat config check', [
            'has_key' => (bool) $apiKey,
            'key_len' => $apiKey ? strlen($apiKey) : 0,
            'model' => config('services.groq.model'),
            'app_env' => config('app.env'),
        ]);
        if (!$apiKey) {
            return response()->json(['error' => 'AI chưa được cấu hình.'], 503);
        }

        $model = config('services.groq.model', 'llama-3.1-8b-instant');
        $catalogContext = $this->buildCatalogContext();
        $shopContext = $this->buildShopContext();
        $systemPrompt = implode("\n", [
            'Bạn là trợ lý tư vấn cho DungThu.com.',
            'Trả lời ngắn gọn, rõ ràng, thân thiện bằng tiếng Việt.',
            'Ưu tiên gợi ý sản phẩm phù hợp theo nhu cầu; nêu 2-4 gợi ý.',
            'Nếu khách hỏi về giá/đơn hàng cụ thể, hướng dẫn đăng nhập hoặc liên hệ admin.',
            'Thông tin shop & quy trình:',
            $shopContext,
            'Dữ liệu danh mục và sản phẩm (tóm tắt):',
            $catalogContext,
        ]);

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 300,
                ]);

            if (!$response->successful()) {
                Log::warning('Groq chat error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json(['error' => 'Không thể trả lời lúc này.'], 502);
            }

            $reply = data_get($response->json(), 'choices.0.message.content');
            if (!$reply) {
                return response()->json(['error' => 'Không thể trả lời lúc này.'], 502);
            }

            return response()->json(['reply' => $reply]);
        } catch (\Throwable $e) {
            Log::error('Groq chat exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Không thể trả lời lúc này.'], 502);
        }
    }

    private function buildCatalogContext(): string
    {
        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        $products = Product::query()
            ->where('stock', '>', 0)
            ->orderByDesc('created_at')
            ->take(40)
            ->get(['name', 'price', 'sale_price', 'category_id', 'category', 'description']);

        $categoriesById = $categories->keyBy('id');
        $lines = [];

        foreach ($categories as $cat) {
            $lines[] = "- Danh mục: {$cat->name} (type: {$cat->type})";
            $catProducts = $products->where('category_id', $cat->id)->take(6);

            foreach ($catProducts as $p) {
                $price = number_format((float) ($p->sale_price ?? $p->price), 0, ',', '.') . 'đ';
                $desc = trim(strip_tags((string) $p->description));
                $desc = mb_substr($desc, 0, 120);
                $lines[] = "  • {$p->name} – {$price} – {$desc}";
            }
        }

        if (empty($lines)) {
            return 'Chưa có dữ liệu sản phẩm.';
        }

        return implode("\n", $lines);
    }

    private function buildShopContext(): string
    {
        $lines = [];

        $lines[] = 'DungThu.com là nền tảng cung cấp giải pháp công nghệ, thời trang và công cụ Marketing cho cộng đồng Việt Nam.';
        $lines[] = 'Cam kết: sản phẩm chất lượng, giá cạnh tranh, hỗ trợ 24/7, bảo mật thông tin.';
        $lines[] = 'Quy trình thanh toán:';
        $lines[] = '1) Quét QR/chuyển khoản theo thông tin hiển thị.';
        $lines[] = '2) Nhập đầy đủ thông tin và địa chỉ.';
        $lines[] = '3) Nhấn xác nhận đặt hàng.';
        $lines[] = '4) Sản phẩm số sẽ gửi qua email sau khi xác nhận thanh toán.';
        $lines[] = '5) Sản phẩm vật lý giao trong 3-5 ngày.';
        $lines[] = 'Liên hệ: Telegram @dungthucom, Zalo 0708910952, Email tranthanhtuanfix@gmail.com.';

        return implode("\n", $lines);
    }
}
