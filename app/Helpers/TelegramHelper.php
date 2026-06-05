<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    /**
     * Gửi tin nhắn tùy chỉnh qua Telegram
     */
    public static function sendMessage($text)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Telegram message sent successfully');
                return true;
            } else {
                Log::error('Telegram send failed: ' . $response->body());
                return false;
            }
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
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        // Tạo nội dung thông báo
        $message = self::formatOrderMessage($order);

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Telegram notification sent successfully for order #' . $order->id);
                return true;
            } else {
                Log::error('Telegram notification failed: ' . $response->body());
                return false;
            }
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
        // Load order items với product
        $order->load('orderItems.product');

        $message = "🔔 <b>ĐỚN HÀNG MỚI - XÁC NHẬN ĐÃ THANH TOÁN</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Thông tin đơn hàng
        $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
        $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
        $message .= "• Loại đơn: <b>" . self::getOrderTypeLabel($order->order_type) . "</b>\n";
        $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') . "</b>\n";
        $message .= "• Trạng thái: <b>" . $order->status_label . "</b>\n\n";

        // Thông tin khách hàng
        $message .= "👤 <b>THÔNG TIN KHÁCH HÀNG</b>\n";
        $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
        $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
        $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n";
        
        if ($order->customer_address && $order->customer_address !== 'Sản phẩm số - không cần giao hàng') {
            $message .= "• Địa chỉ: <b>" . $order->customer_address . "</b>\n";
        }
        $message .= "\n";

        // Chi tiết sản phẩm
        $message .= "🛒 <b>CHI TIẾT SẢN PHẨM</b>\n";
        foreach ($order->orderItems as $item) {
            $productName = $item->product ? $item->product->name : 'Sản phẩm không tồn tại';
            $message .= "• " . $productName . "\n";
            $message .= "  ├ Số lượng: <b>" . $item->quantity . "</b>\n";
            $message .= "  ├ Đơn giá: <b>" . number_format($item->price, 0, ',', '.') . "đ</b>\n";
            $message .= "  └ Thành tiền: <b>" . number_format($item->price * $item->quantity, 0, ',', '.') . "đ</b>\n\n";
        }

        // Tổng tiền
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        if (isset($order->discount_amount) && $order->discount_amount > 0) {
            $message .= "• Giảm giá: <b>-" . number_format($order->discount_amount, 0, ',', '.') . "đ</b> (" . $order->coupon_code . ")\n";
        }
        $message .= "💰 <b>TỔNG TIỀN: " . number_format($order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        if ($order->status === 'completed') {
            $message .= "✅ <i>Đơn hàng có sẵn kho đã được xử lý và hoàn thành tự động!</i>";
        } else {
            $message .= "⚠️ <i>Đơn hàng chưa có sẵn kho. Vui lòng kiểm tra và xử lý đơn hàng!</i>";
        }

        return $message;
    }

    /**
     * Gửi thông báo thanh toán Buff qua Telegram
     */
    public static function sendBuffPaymentNotification($buffOrder)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        try {
            $service = $buffOrder->buffService;
            $server = $buffOrder->buffServer;
            $user = $buffOrder->user;

            $message = "🎯 <b>BUFF PAYMENT COMPLETED</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

            $message .= "📦 <b>Order Info:</b>\n";
            $message .= "• Code: <b>" . $buffOrder->order_code . "</b>\n";
            $message .= "• Platform: <b>" . ucfirst($service->platform) . "</b>\n";
            $message .= "• Service: <b>" . $service->name . "</b>\n";
            $message .= "• Server: <b>" . $server->name . "</b>\n\n";

            $message .= "👤 <b>User:</b>\n";
            $message .= "• Name: <b>" . $user->name . "</b>\n";
            $message .= "• Email: <b>" . $user->email . "</b>\n\n";

            $message .= "📊 <b>Details:</b>\n";
            $message .= "• Quantity: <b>" . number_format($buffOrder->quantity) . "</b>\n";
            $message .= "• Unit Price: <b>" . number_format($buffOrder->unit_price, 0, ',', '.') . "đ</b>\n";
            $message .= "• Base Price: <b>" . number_format($buffOrder->base_price, 0, ',', '.') . "đ</b>\n";
            $message .= "• Total: <b>" . number_format($buffOrder->total_price, 0, ',', '.') . "đ</b>\n\n";

            $message .= "🔗 Link: <b>" . substr($buffOrder->social_link, 0, 50) . "...</b>\n";
            $message .= "⏰ Time: <b>" . $buffOrder->updated_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') . "</b>\n\n";

            $message .= "✅ <i>Payment confirmed! Processing order...</i>";

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Telegram buff payment notification sent for order: ' . $buffOrder->order_code);
                return true;
            } else {
                Log::error('Telegram buff notification failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Telegram buff notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi thông báo có tin nhắn chat mới từ khách hàng
     */
    public static function sendNewChatMessageNotification($message)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        $user = $message->user;
        $userName = $user ? $user->name : 'Khách lạ';
        $userEmail = $user ? $user->email : 'N/A';

        $text = "💬 <b>TIN NHẮN CHAT MỚI</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "👤 <b>Người gửi:</b> " . $userName . "\n";
        $text .= "📧 <b>Email:</b> " . $userEmail . "\n\n";
        
        if ($message->message) {
            $text .= "📝 <b>Nội dung:</b>\n<i>" . $message->message . "</i>\n\n";
        }
        
        if ($message->image) {
            $text .= "🖼 <b>Có đính kèm hình ảnh</b>\n\n";
        }

        $text .= "🔗 <a href=\"" . url('/admin/chat') . "\">Trả lời ngay tại đây</a>\n";
        $text .= "⏰ <i>" . now()->format('H:i:s d/m/Y') . "</i>";

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram Chat Notification Error: ' . $e->getMessage());
        }
    }

    /**
     * Lấy nhãn cho loại đơn hàng
     */
    private static function getOrderTypeLabel($type)
    {
        $labels = [
            'qr' => 'TikTok QR',
            'document' => 'Tài liệu / Ebook',
            'shipping' => 'Giao hàng vật lý',
            'digital' => 'Sản phẩm số',
        ];

        return $labels[$type] ?? 'Mặc định';
    }
}
