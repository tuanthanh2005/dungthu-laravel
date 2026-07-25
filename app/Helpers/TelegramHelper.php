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
        try {
            \App\Jobs\SendTelegramNotification::dispatch($text);
            return true;
        } catch (\Exception $e) {
            Log::error('Telegram dispatch error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi thông báo đơn hàng mới qua Telegram
     */
    public static function sendNewOrderNotification($order)
    {
        // Tạo nội dung thông báo
        $message = self::formatOrderMessage($order);

        try {
            \App\Jobs\SendTelegramNotification::dispatch($message);
            Log::info('Telegram notification queued successfully for order #' . $order->id);
            return true;
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

        $isUsd = ($order->currency === 'USD');

        if ($isUsd) {
            $message = "🔔 <b>NEW ORDER - CONFIRMED PAYMENT</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

            // Thông tin đơn hàng
            $message .= "📦 <b>ORDER DETAILS</b>\n";
            $message .= "• Order ID: <b>#" . $order->id . "</b>\n";
            $message .= "• Order Type: <b>" . self::getOrderTypeLabel($order->order_type) . "</b>\n";
            $message .= "• Time: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') . "</b>\n";
            $message .= "• Status: <b>" . $order->status_label . "</b>\n\n";

            // Thông tin khách hàng
            $message .= "👤 <b>CUSTOMER INFORMATION</b>\n";
            $message .= "• Full Name: <b>" . $order->customer_name . "</b>\n";
            $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
            $message .= "• Phone: <b>" . $order->customer_phone . "</b>\n";
            
            if ($order->customer_address && $order->customer_address !== 'Digital product - no shipping required') {
                $message .= "• Address: <b>" . $order->customer_address . "</b>\n";
            }
            $message .= "\n";

            // Chi tiết sản phẩm
            $message .= "🛒 <b>PRODUCT DETAILS</b>\n";
            foreach ($order->orderItems as $item) {
                $productName = $item->product ? ($item->product->name_en ?? $item->product->name) : 'Product does not exist';
                $message .= "• " . $productName . "\n";
                $message .= "  ├ Quantity: <b>" . $item->quantity . "</b>\n";
                $message .= "  ├ Unit Price: <b>$" . number_format($item->price, 2) . "</b>\n";
                $message .= "  └ Subtotal: <b>$" . number_format($item->price * $item->quantity, 2) . "</b>\n\n";
            }

            // Tổng tiền
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
            if (isset($order->discount_amount) && $order->discount_amount > 0) {
                $message .= "• Discount: <b>-$" . number_format($order->discount_amount, 2) . "</b> (" . $order->coupon_code . ")\n";
            }
            $message .= "💰 <b>TOTAL AMOUNT: $" . number_format($order->total_amount, 2) . "</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

            if ($order->status === 'completed') {
                $message .= "✅ <i>Instant digital order processed and completed automatically!</i>";
            } else {
                $message .= "⚠️ <i>Order requires manual processing. Please check and process!</i>";
            }
        } else {
            $message = "🔔 <b>ĐƠN HÀNG MỚI - XÁC NHẬN ĐÃ THANH TOÁN</b>\n";
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
        }

        return $message;
    }

    /**
     * Gửi thông báo thanh toán Buff qua Telegram
     */
    public static function sendBuffPaymentNotification($buffOrder)
    {
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

            \App\Jobs\SendTelegramNotification::dispatch($message);
            Log::info('Telegram buff payment notification queued for order: ' . $buffOrder->order_code);
            return true;
        } catch (\Exception $e) {
            Log::error('Telegram buff notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi thông báo có khách hàng mới đăng ký tài khoản
     */
    public static function sendNewUserNotification($user)
    {
        $text = "👤 <b>KHÁCH HÀNG MỚI ĐĂNG KÝ</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "• Họ tên: <b>" . $user->name . "</b>\n";
        $text .= "• Email: <b>" . $user->email . "</b>\n";
        $text .= "• Thời gian: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') . "</b>\n\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "🚀 <i>Chào mừng thành viên mới gia nhập hệ thống!</i>";

        try {
            \App\Jobs\SendTelegramNotification::dispatch($text);
            Log::info('Telegram notification queued for new user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Telegram User Notification Error: ' . $e->getMessage());
        }
    }

    /**
     * Gửi thông báo có tin nhắn chat mới từ khách hàng
     */
    public static function sendNewChatMessageNotification($message)
    {
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
            \App\Jobs\SendTelegramNotification::dispatch($text);
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
