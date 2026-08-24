<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Message;
use App\Models\TelegramMessageMapping;
use App\Helpers\TelegramHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Tiếp nhận Webhook từ Telegram Bot
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Telegram Webhook Payload:', $data);

        // 1. Xử lý Nút Bấm Inline Keyboard (Callback Query)
        if (isset($data['callback_query'])) {
            return $this->handleCallbackQuery($data['callback_query']);
        }

        // 2. Xử lý Trả Lời Tin Nhắn (Reply Message cho Live Chat)
        if (isset($data['message']['reply_to_message'])) {
            return $this->handleMessageReply($data['message']);
        }

        return response()->json(['status' => 'ignored']);
    }

    /**
     * Xử lý khi Admin bấm nút duyệt đơn trên Telegram
     */
    private function handleCallbackQuery(array $callbackQuery)
    {
        $callbackId = $callbackQuery['id'];
        $callbackData = $callbackQuery['data'] ?? '';
        $message = $callbackQuery['message'] ?? [];
        $chatId = $message['chat']['id'] ?? null;
        $messageId = $message['message_id'] ?? null;
        $adminTelegramId = $callbackQuery['from']['id'] ?? null;

        // Kiểm tra quyền Admin (Chat ID)
        $configuredChatId = config('services.telegram.chat_id');
        if ((string)$chatId !== (string)$configuredChatId && (string)$adminTelegramId !== (string)$configuredChatId) {
            TelegramHelper::answerCallbackQuery($callbackId, '⚠️ Bạn không có quyền thực hiện thao tác này!');
            return response()->json(['status' => 'unauthorized']);
        }

        // Parse Callback Data format: "order:{status}:{order_id}"
        $parts = explode(':', $callbackData);
        if (count($parts) === 3 && $parts[0] === 'order') {
            $statusKey = $parts[1];
            $orderId = $parts[2];

            $order = Order::find($orderId);
            if (!$order) {
                TelegramHelper::answerCallbackQuery($callbackId, '❌ Không tìm thấy đơn hàng #' . $orderId);
                return response()->json(['status' => 'order_not_found']);
            }

            $statusMap = [
                'completed' => 'completed',
                'processing' => 'processing',
                'cancelled' => 'cancelled',
                'pending' => 'pending',
            ];

            $statusLabels = [
                'completed' => 'HOÀN THÀNH',
                'processing' => 'ĐANG XỬ LÝ',
                'cancelled' => 'ĐÃ HỦY',
                'pending' => 'CHỜ XỬ LÝ',
            ];

            if (isset($statusMap[$statusKey])) {
                $order->update(['status' => $statusMap[$statusKey]]);
                $newLabel = $statusLabels[$statusKey] ?? strtoupper($statusKey);

                // Gửi thông báo toast trên Telegram
                TelegramHelper::answerCallbackQuery($callbackId, "✅ Đã đổi đơn hàng #{$order->id} sang trạng thái: {$newLabel}!");

                // Cập nhật lại giao diện tin nhắn Telegram
                $originalText = $message['text'] ?? '';
                $adminName = $callbackQuery['from']['first_name'] ?? 'Admin';
                $updatedTime = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');

                $updatedText = $originalText . "\n\n━━━━━━━━━━━━━━━━━━━━━━\n";
                $updatedText .= "⚡ <b>TRẠNG THÁI MỚI: {$newLabel}</b>\n";
                $updatedText .= "👤 <i>Cập nhật bởi {$adminName} lúc {$updatedTime}</i>";

                $newReplyMarkup = [
                    'inline_keyboard' => [
                        [
                            ['text' => '✅ Hoàn thành', 'callback_data' => "order:completed:{$order->id}"],
                            ['text' => '🚚 Đang xử lý', 'callback_data' => "order:processing:{$order->id}"],
                        ],
                        [
                            ['text' => '❌ Hủy đơn', 'callback_data' => "order:cancelled:{$order->id}"],
                            ['text' => '🌐 Xem trên Web', 'url' => url("/admin/orders/{$order->id}")],
                        ]
                    ]
                ];

                TelegramHelper::editMessageText($chatId, $messageId, $updatedText, $newReplyMarkup);

                return response()->json(['status' => 'success']);
            }
        }

        TelegramHelper::answerCallbackQuery($callbackId, 'Thao tác không hợp lệ.');
        return response()->json(['status' => 'invalid_callback']);
    }

    /**
     * Xử lý khi Admin bấm Reply tin nhắn trên Telegram để trả lời khách hàng
     */
    private function handleMessageReply(array $messageData)
    {
        $replyTo = $messageData['reply_to_message'] ?? [];
        $replyMsgId = $replyTo['message_id'] ?? null;
        $text = trim($messageData['text'] ?? '');
        $chatId = $messageData['chat']['id'] ?? null;

        if (!$replyMsgId || empty($text)) {
            return response()->json(['status' => 'empty']);
        }

        // Tìm bản ghi mapping từ message_id Telegram -> user_id
        $mapping = TelegramMessageMapping::where('telegram_message_id', (string)$replyMsgId)
            ->where('type', 'chat')
            ->first();

        if (!$mapping) {
            // Nếu không tìm thấy trong mapping, gửi câu trả lời mặc định nếu có thể
            TelegramHelper::sendMessage("⚠️ Không tìm thấy phiên chat tương ứng với tin nhắn này.");
            return response()->json(['status' => 'mapping_not_found']);
        }

        $userId = $mapping->related_id;

        // Lưu tin nhắn phản hồi của Admin vào Database
        $newMessage = Message::create([
            'user_id' => $userId > 0 ? $userId : null,
            'message' => $text,
            'is_admin' => true,
            'is_read' => false,
        ]);

        $recipientName = $userId > 0 ? "Khách hàng #{$userId}" : "Khách vãng lai";
        TelegramHelper::sendMessage("✅ <b>ĐÃ GỬI PHẢN HỒI THÀNH CÔNG!</b>\n━━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Tới:</b> {$recipientName}\n📝 <b>Nội dung:</b> <i>{$text}</i>");

        return response()->json(['status' => 'success', 'message_id' => $newMessage->id]);
    }

    /**
     * Route hỗ trợ đăng ký Webhook Telegram tự động
     */
    public function setWebhook()
    {
        $botToken = config('services.telegram.bot_token');
        $webhookUrl = url('/api/telegram/webhook');

        if (empty($botToken)) {
            return response()->json(['error' => 'Chưa cấu hình Telegram Bot Token trong file .env (TELEGRAM_BOT_TOKEN)'], 400);
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
