<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    private static function botToken(): ?string
    {
        return config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
    }

    private static function chatId(): ?string
    {
        return config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID'));
    }

    private static function webhookSecret(): string
    {
        return (string) config('services.telegram.webhook_secret', env('TELEGRAM_WEBHOOK_SECRET', ''));
    }

    /**
     * Gửi tin nhắn tùy chỉnh qua Telegram
     */
    public static function sendMessage($text, $replyMarkup = null)
    {
        $botToken = self::botToken();
        $chatId = self::chatId();

        if (!$botToken || !$chatId) {
            Log::error('Telegram config missing (bot_token/chat_id).');
            return false;
        }

        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if ($replyMarkup) {
                $payload['reply_markup'] = $replyMarkup;
            }

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $payload);

            if ($response->successful()) {
                Log::info('Telegram message sent successfully');
                return true;
            }

            Log::error('Telegram send failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Telegram error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi thông báo đơn hàng mới qua Telegram
     */
    public static function sendNewOrderNotification($order)
    {
        $botToken = self::botToken();
        $chatId = self::chatId();

        if (!$botToken || !$chatId) {
            Log::error('Telegram config missing (bot_token/chat_id).');
            return false;
        }

        $message = self::formatOrderMessage($order);
        $replyMarkup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '✅ Duyệt đơn',
                        'callback_data' => self::buildCallbackData('approve', $order->id),
                    ],
                    [
                        'text' => '❌ Từ chối',
                        'callback_data' => self::buildCallbackData('reject', $order->id),
                    ],
                ],
            ],
        ];

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => $replyMarkup,
            ]);

            if ($response->successful()) {
                Log::info('Telegram notification sent successfully for order #' . $order->id);
                return true;
            }

            Log::error('Telegram notification failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Telegram notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format thông tin đơn hàng thành message Telegram
     */
    private static function formatOrderMessage($order)
    {
        $order->load('orderItems.product');

        $message = "🔔 <b>ĐƠN HÀNG MỚI - CHỜ ADMIN DUYỆT</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
        $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
        $message .= "• Loại đơn: <b>" . self::getOrderTypeLabel($order->order_type) . "</b>\n";
        $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') . "</b>\n";
        $message .= "• Trạng thái: <b>" . $order->status_label . "</b>\n\n";

        $message .= "👤 <b>THÔNG TIN KHÁCH HÀNG</b>\n";
        $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
        $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
        $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n";

        if ($order->customer_address && $order->customer_address !== 'Sản phẩm số - không cần giao hàng') {
            $message .= "• Địa chỉ: <b>" . $order->customer_address . "</b>\n";
        }
        $message .= "\n";

        $message .= "🛒 <b>CHI TIẾT SẢN PHẨM</b>\n";
        foreach ($order->orderItems as $item) {
            $productName = $item->product ? $item->product->name : 'Sản phẩm không tồn tại';
            $message .= "• " . $productName . "\n";
            $message .= "  ├ Số lượng: <b>" . $item->quantity . "</b>\n";
            $message .= "  ├ Đơn giá: <b>" . number_format($item->price, 0, ',', '.') . "đ</b>\n";
            $message .= "  └ Thành tiền: <b>" . number_format($item->price * $item->quantity, 0, ',', '.') . "đ</b>\n\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💰 <b>TỔNG TIỀN: " . number_format($order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $message .= "⚠️ <i>Khách hàng đã đặt hàng. Vui lòng duyệt để gửi email tải file.</i>";

        return $message;
    }

    /**
     * Get order type label
     */
    private static function getOrderTypeLabel($type)
    {
        $labels = [
            'qr' => '🎫 QR Deal',
            'document' => '📄 Tài liệu',
            'shipping' => '🚚 Giao hàng',
            'digital' => '💾 Digital',
        ];

        return $labels[$type] ?? 'Không xác định';
    }

    public static function buildCallbackData(string $action, int $orderId): string
    {
        $secret = self::webhookSecret();
        $base = $action . '|' . $orderId;
        $hash = $secret ? substr(hash_hmac('sha256', $base, $secret), 0, 12) : 'nosecret';
        return $base . '|' . $hash;
    }

    public static function verifyCallbackData(string $data): ?array
    {
        $parts = explode('|', $data);
        if (count($parts) !== 3) {
            return null;
        }

        [$action, $orderId, $hash] = $parts;
        if (!in_array($action, ['approve', 'reject'], true)) {
            return null;
        }

        $secret = self::webhookSecret();
        if ($secret) {
            $expected = substr(hash_hmac('sha256', $action . '|' . $orderId, $secret), 0, 12);
            if (!hash_equals($expected, $hash)) {
                return null;
            }
        }

        if (!ctype_digit($orderId)) {
            return null;
        }

        return ['action' => $action, 'order_id' => (int) $orderId];
    }
}
