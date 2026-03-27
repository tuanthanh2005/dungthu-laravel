<?php

namespace App\Services;

use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqChatService
{
    private string $apiKey;
    private string $model;
    private string $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private int $timeout = 30;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key') ?? env('GROQ_API_KEY');
        $this->model = config('services.groq.model') ?? env('GROQ_MODEL', 'llama-3.1-8b-instant');
    }

    /**
     * Get AI response for a customer message
     */
    public function chat(string $message, string $sessionId, ?int $userId = null): array
    {
        try {
            // Get conversation context
            $conversationHistory = ChatbotMessage::getSessionContext($sessionId, config('chatbot.max_context_messages', 10));

            // Build messages array for API
            $messages = $this->buildMessages($message, $conversationHistory);

            // Call Groq API
            $startTime = microtime(true);
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout($this->timeout)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500,
                'top_p' => 1,
            ]);

            $responseTime = microtime(true) - $startTime;

            // Handle response
            if (!$response->successful()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Failed to get response from AI');
            }

            $data = $response->json();
            $aiResponse = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

            // Save message to database
            ChatbotMessage::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'message' => $message,
                'response' => $aiResponse,
                'message_type' => 'text',
                'response_time' => round($responseTime, 2),
                'status' => 'completed',
                'metadata' => [
                    'model' => $this->model,
                    'tokens_used' => $data['usage']['total_tokens'] ?? null,
                ],
            ]);

            return [
                'success' => true,
                'message' => $aiResponse,
                'response_time' => round($responseTime, 2),
                'session_id' => $sessionId,
            ];
        } catch (\Exception $e) {
            Log::error('Chatbot Error', [
                'message' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);

            // Save failed message
            ChatbotMessage::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'message' => $message,
                'response' => 'Xin lỗi, tôi gặp lỗi kỹ thuật. Vui lòng thử lại! 😢',
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return [
                'success' => false,
                'message' => 'Xin lỗi, tôi gặp lỗi kỹ thuật. Vui lòng liên hệ support: support@dungthu.com',
                'session_id' => $sessionId,
            ];
        }
    }

    /**
     * Build messages array for Groq API with system context
     */
    private function buildMessages(string $currentMessage, array $conversationHistory): array
    {
        $systemPrompt = $this->getSystemPrompt();

        return array_merge(
            [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ]
            ],
            $conversationHistory,
            [
                [
                    'role' => 'user',
                    'content' => $currentMessage,
                ]
            ]
        );
    }

    /**
     * Get system prompt for chatbot with formatting instructions
     */
    private function getSystemPrompt(): string
    {
        return <<<'PROMPT'
Bạn là customer support chatbot của dungthu.store, một nền tảng bán:
1. Digital Products (documents, tools, plugins, courses)
2. Social Media Services (TikTok, Facebook, Instagram followers/likes/views)
3. TikTok Deals (special offers)
4. Card Exchange (đổi thẻ lấy credit)

RESPONSE FORMATTING RULES (VERY IMPORTANT):
- Use **bold** cho từ khóa quan trọng
- Use • bullet points cho danh sách
- Use 1. 2. 3. cho numbered lists
- Use emoji freely (😊 👍 🎯 💰 ⚠️ ✅ ❌ 📧 etc.)
- Use --- để tách sections nếu cần
- Use backticks `code` cho field names hoặc commands
- Format giá tiền: XXk hoặc XXX,XXXk
- Format time: X-Y ngày hoặc X giờ
- Use paragraph breaks để dễ đọc
- NEVER write plain text without emoji/formatting

RESPONSE STRUCTURE TEMPLATE:
[Opening emoji + Greeting]
[Main answer with bullets/formatting]
[Important warnings with ⚠️]
[Contact info nếu cần escalate]
[Closing emoji + CTA]

EXAMPLE GOOD RESPONSE:
"👋 Chào bạn!

Dưới đây là sản phẩm WordPress bạn tìm:

**Tier 1 - Beginner:**
• WordPress Themes Bundle - **299k** ⭐ Hot
  - 50+ themes, instant delivery
  - Hỗ trợ free 3 tháng
  - Best for beginners

**Tier 2 - Professional:**
• WordPress Plugins Pro - **599k** 🚀
  - 100+ plugins, lifetime update
  - 24/7 support
  - Best for agencies

⚠️ **Lưu ý:** Hãy check requirements trước khi buy!

📧 Có gì thắc mắc? Contact: support@dungthu.com | ☎️ 0772698113

Bạn chọn cái nào? 😊"

KEY PRODUCTS:
- Digital: 1-500k VND, instant ⚡
- Buff Services: 45k+, 2-7 ngày 📱
- Card Exchange: 95-98% giá, 30-60 phút 🔄
- TikTok Deals: Giảm tới 50% 🎁

IMPORTANT WARNINGS:
- Buff Services: ⚠️ "Tăng từ từ (max 10k/tuần), tránh TikTok detect bot"
- Card Exchange: ⚠️ "Thẻ phải chưa dùng, tỷ giá 95-98%, không refund"
- Luôn end với: "📧 support@dungthu.com | ☎️ 0772698113 | 💬 Zalo: 0708910952"

RULES:
- Trả lời bằng Tiếng Việt, thân thiện, chuyên nghiệp
- ALWAYS use emoji & formatting
- Ask clarifying questions
- Suggest related products
- Show empathy khi customer frustrated
- Never make up specs
- Never promise refund without admin approval
- Hỗ trợ 24/7, tone: casual, friendly, helpful

FORMATTING PRIORITY:
1. emoji at start/end
2. **bold** for important
3. • bullets for lists
4. Paragraph breaks
5. Contact info at end
6. --- separator nếu many sections

TONE EXAMPLES:
❌ "Bạn muốn mua sản phẩm nào?"
✅ "Khoảng bao nhiêu budget bạn có? 💰 Mình có từ 1k tới 500k+ lựa chọn!"

❌ "Tiktok followers 10k giá 349k"
✅ "✅ TikTok followers 10k:
• Server 1: **349k** → 2-3 ngày ⭐ Recommended
• Server 2: 299k → 3-5 ngày
• Server 3: 279k → 5-7 ngày"
PROMPT;
    }

    /**
     * Get conversation summary (for analytics)
     */
    public function generateSummary(string $sessionId): ?string
    {
        try {
            $messages = ChatbotMessage::session($sessionId)->get();

            if ($messages->isEmpty()) {
                return null;
            }

            $conversationText = $messages->map(fn($msg) => "User: {$msg->message}\nBot: {$msg->response}")->join("\n---\n");

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout($this->timeout)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tóm tắt cuộc trò chuyện này trong 2-3 câu, tiếng Việt, không quá dài.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Tóm tắt cuộc trò chuyện:\n\n{$conversationText}",
                    ]
                ],
                'max_tokens' => 150,
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error generating summary', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
