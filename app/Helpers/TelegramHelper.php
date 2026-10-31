<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    /**
     * Gửi tin nhắn tùy chỉnh qua Telegram
     */
    public static function sendMessage($text, $replyMarkup = null)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        try {
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if ($replyMarkup) {
                $data['reply_markup'] = is_array($replyMarkup) ? json_encode($replyMarkup) : $replyMarkup;
            }

            $response = Http::timeout(5)->post("https://api.telegram.org/bot{$botToken}/sendMessage", $data);

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
     * Gửi thông báo khi khách hàng vừa nhập form thông tin (Bước 1) trước khi chuyển qua QR
     */
    public static function sendPreCheckoutNotification(array $data)
    {
        $botToken = config('services.telegram.bot_token') ?: \App\Models\SiteSetting::getValue('telegram_bot_token');
        $chatId = config('services.telegram.chat_id') ?: \App\Models\SiteSetting::getValue('telegram_chat_id');

        if (!$botToken || !$chatId) {
            Log::warning('Telegram bot token or chat ID is not configured.');
            return false;
        }

        $orderCode = $data['order_code'] ?? 'N/A';
        $customerName = htmlspecialchars($data['customer_name'] ?? 'Khách hàng');
        $customerPhone = htmlspecialchars($data['customer_phone'] ?? 'N/A');
        $customerEmail = htmlspecialchars($data['customer_email'] ?? 'N/A');
        $upgradeNote = htmlspecialchars($data['upgrade_email_or_note'] ?? '');
        $customerAddress = htmlspecialchars($data['customer_address'] ?? '');
        $zalo = htmlspecialchars($data['customer_zalo'] ?? '');
        $facebook = htmlspecialchars($data['customer_facebook'] ?? '');
        $useBoxchat = !empty($data['use_boxchat']);
        $totalAmount = $data['total_formatted'] ?? '0đ';
        $ip = $data['ip'] ?? request()->ip();
        $time = $data['time'] ?? now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s');

        $contactInfo = [];
        if ($useBoxchat) {
            $contactInfo[] = '💬 Boxchat Website';
        }
        if ($zalo) {
            $contactInfo[] = "Zalo: <code>{$zalo}</code>";
        }
        if ($facebook) {
            $contactInfo[] = "FB: {$facebook}";
        }
        $contactStr = !empty($contactInfo) ? implode(' | ', $contactInfo) : 'Chưa có';

        $msg = "⚡ <b>KHÁCH HÀNG VỪA ĐIỀN FORM (BƯỚC 1 - CHỜ QUÉT QR)</b>\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "🆔 <b>Mã đơn hàng:</b> <code>{$orderCode}</code>\n";
        $msg .= "⏰ <b>Thời gian:</b> {$time}\n";
        $msg .= "🌐 <b>IP khách:</b> <code>{$ip}</code>\n\n";

        $msg .= "👤 <b>THÔNG TIN KHÁCH HÀNG:</b>\n";
        $msg .= "• <b>Họ tên:</b> <b>{$customerName}</b>\n";
        $msg .= "• <b>Số điện thoại:</b> <code>{$customerPhone}</code>\n";
        $msg .= "• <b>Email nhận mã:</b> <code>{$customerEmail}</code>\n";
        if ($upgradeNote) {
            $msg .= "• <b>Email nâng cấp / Ghi chú:</b> <i>{$upgradeNote}</i>\n";
        }
        if ($customerAddress) {
            $msg .= "• <b>Địa chỉ:</b> {$customerAddress}\n";
        }
        $msg .= "• <b>Liên hệ:</b> {$contactStr}\n\n";

        $msg .= "🛒 <b>SẢN PHẨM TRONG GIỎ HÀNG:</b>\n";
        $items = $data['items'] ?? [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemName = htmlspecialchars($item['name'] ?? 'Sản phẩm');
                $qty = $item['quantity'] ?? 1;
                $priceFormatted = $item['price_formatted'] ?? '';
                $msg .= "• {$itemName} (x{$qty}) - {$priceFormatted}\n";
            }
        } else {
            $msg .= "• (Không có thông tin chi tiết)\n";
        }

        if (!empty($data['discount_formatted'])) {
            $couponText = !empty($data['coupon_code']) ? " (Mã: <code>{$data['coupon_code']}</code>)" : "";
            $msg .= "🎟 <b>Giảm giá:</b> -{$data['discount_formatted']}{$couponText}\n";
        }

        $msg .= "💰 <b>TỔNG CẦN THANH TOÁN:</b> <b>{$totalAmount}</b>\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "ℹ️ <i>Khách hàng vừa bấm 'Tiến hành thanh toán' và đang ở màn hình quét QR. Admin lưu lại thông tin để tránh mất dữ liệu!</i>";

        try {
            $response = Http::timeout(4)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $msg,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Pre-checkout Telegram notification sent for ' . $orderCode);
                return true;
            } else {
                Log::error('Pre-checkout Telegram notification failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Telegram pre-checkout notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi thông báo đơn hàng mới qua Telegram kèm Nút bấm xử lý trực tiếp
     */
    public static function sendNewOrderNotification($order)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        // Tạo nội dung thông báo
        $message = self::formatOrderMessage($order);

        $replyMarkup = [
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

        try {
            $response = Http::timeout(3)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($replyMarkup),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $msgId = $data['result']['message_id'] ?? null;
                if ($msgId) {
                    \App\Models\TelegramMessageMapping::create([
                        'telegram_message_id' => (string) $msgId,
                        'telegram_chat_id' => (string) $chatId,
                        'type' => 'order',
                        'related_id' => $order->id,
                    ]);
                }
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
     * Gửi thông báo có khách hàng mới đăng ký tài khoản
     */
    public static function sendNewUserNotification($user)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        $text = "👤 <b>KHÁCH HÀNG MỚI ĐĂNG KÝ</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "• Họ tên: <b>" . $user->name . "</b>\n";
        $text .= "• Email: <b>" . $user->email . "</b>\n";
        $text .= "• Thời gian: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') . "</b>\n\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "🚀 <i>Chào mừng thành viên mới gia nhập hệ thống!</i>";

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
            Log::info('Telegram notification sent for new user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Telegram User Notification Error: ' . $e->getMessage());
        }
    }

    /**
     * Gửi thông báo có tin nhắn chat mới từ khách hàng kèm hỗ trợ trả lời 2 chiều
     */
    public static function sendNewChatMessageNotification($message)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        $user = $message->user;
        $userName = $user ? $user->name : 'Khách vãng lai';
        $userEmail = $user ? $user->email : 'N/A';
        $userId = $message->user_id ?: 0;

        $text = "💬 <b>TIN NHẮN CHAT MỚI</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "👤 <b>Người gửi:</b> " . htmlspecialchars($userName) . "\n";
        $text .= "📧 <b>Email:</b> " . htmlspecialchars($userEmail) . "\n\n";
        
        if ($message->message) {
            $text .= "📝 <b>Nội dung:</b>\n<i>" . htmlspecialchars($message->message) . "</i>\n\n";
        }
        
        if ($message->image) {
            $text .= "🖼 <b>Có đính kèm hình ảnh:</b> " . url($message->image) . "\n\n";
        }

        $text .= "💡 <i>Bấm <b>Reply (Trả lời)</b> tin nhắn này trên Telegram để phản hồi lại khách hàng!</i>\n\n";
        $text .= "⏰ <i>" . now()->format('H:i:s d/m/Y') . "</i>";

        $replyMarkup = [
            'inline_keyboard' => [
                [
                    ['text' => '🌐 Khung Chat Web', 'url' => url('/admin/chat')],
                ]
            ]
        ];

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($replyMarkup),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $msgId = $data['result']['message_id'] ?? null;
                if ($msgId) {
                    \App\Models\TelegramMessageMapping::create([
                        'telegram_message_id' => (string) $msgId,
                        'telegram_chat_id' => (string) $chatId,
                        'type' => 'chat',
                        'related_id' => $userId,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Telegram Chat Notification Error: ' . $e->getMessage());
        }
    }

    /**
     * Phản hồi callback query (tạo thông báo toast trên Telegram khi bấm nút)
     */
    public static function answerCallbackQuery($callbackQueryId, $text)
    {
        $botToken = config('services.telegram.bot_token');

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
                'text' => $text,
                'show_alert' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram answerCallbackQuery Error: ' . $e->getMessage());
        }
    }

    /**
     * Chỉnh sửa nội dung tin nhắn Telegram sau khi xử lý thành công
     */
    public static function editMessageText($chatId, $messageId, $text, $replyMarkup = null)
    {
        $botToken = config('services.telegram.bot_token');

        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/editMessageText", $params);
        } catch (\Exception $e) {
            Log::error('Telegram editMessageText Error: ' . $e->getMessage());
        }
    }

    /**
     * Gửi thông báo IP đáng nghi ngờ / bị khóa qua Telegram
     */
    public static function sendSuspiciousIpNotification($ip, $reason, $userAgent = null, $url = null)
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($botToken) || empty($chatId)) {
            return false;
        }

        $text = "🚨 <b>CẢNH BÁO IP ĐÁNG NGHI NGỜ / BỊ CHẶN</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "🌐 <b>Địa chỉ IP:</b> <code>" . $ip . "</code>\n";
        $text .= "⚠️ <b>Lý do:</b> " . $reason . "\n";

        if ($url) {
            $text .= "🔗 <b>URL truy cập:</b> <code>" . htmlspecialchars($url) . "</code>\n";
        }

        if ($userAgent) {
            $text .= "💻 <b>User-Agent:</b> <code>" . htmlspecialchars(substr($userAgent, 0, 150)) . "</code>\n";
        }

        $text .= "⏰ <b>Thời gian:</b> " . now()->timezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') . "\n\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "🔒 <i>Hệ thống tự động ngắt kết nối IP này để bảo vệ website!</i>";

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
            Log::info("Telegram alert sent for suspicious IP: {$ip}");
            return true;
        } catch (\Exception $e) {
            Log::error('Telegram Suspicious IP Alert Error: ' . $e->getMessage());
            return false;
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
