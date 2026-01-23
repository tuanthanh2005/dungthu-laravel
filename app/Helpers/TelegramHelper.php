<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    /**
     * Gửi thông báo đơn hàng mới qua Telegram
     */
    public static function sendNewOrderNotification($order)
    {
        $botToken = '8187679739:AAEbsH_miAXOOepBwsB9p7oraCqQdD4jIXI';
        $chatId = '8199725778';

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
        $message .= "💰 <b>TỔNG TIỀN: " . number_format($order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $message .= "⚠️ <i>Khách hàng đã xác nhận thanh toán. Vui lòng kiểm tra và xử lý đơn hàng!</i>";

        return $message;
    }

    /**
     * Get order type label
     */
    private static function getOrderTypeLabel($type)
    {
        $labels = [
            'qr' => '🎫 QR Deal',
            'document' => '📄 Tài liệu kiếm tiền',
            'shipping' => '🚚 Giao hàng',
            'digital' => '💾 Digital',
        ];

        return $labels[$type] ?? 'Không xác định';
    }
}
//////////////////////