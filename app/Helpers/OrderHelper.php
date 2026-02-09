<?php

namespace App\Helpers;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;

class OrderHelper
{
    public static function sendOrderCompletedNotifications(Order $order): void
    {
        try {
            $demoUsername = self::generateDemoUsername($order);
            $demoPassword = self::generateRandomPassword();

            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(
                    new OrderCompletedMail($order, $demoUsername, $demoPassword)
                );
            }

            $telegramMessage = self::formatCompletedOrderTelegramMessage($order, $demoUsername, $demoPassword);
            TelegramHelper::sendMessage($telegramMessage);
        } catch (\Exception $e) {
            \Log::error('Error sending order completed notifications: ' . $e->getMessage());
        }
    }

    public static function generateDemoUsername(Order $order): string
    {
        if ($order->customer_email) {
            $emailParts = explode('@', $order->customer_email);
            $username = strtolower($emailParts[0]);
            return $username . '_demo_' . $order->id;
        }

        $name = strtolower(str_replace(' ', '', $order->customer_name));
        return $name . '_demo_' . $order->id;
    }

    public static function generateRandomPassword(int $length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*';

        $allChars = $uppercase . $lowercase . $numbers . $special;
        $password = '';

        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];

        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        $passwordArray = str_split($password);
        shuffle($passwordArray);

        return implode('', $passwordArray);
    }

    private static function formatCompletedOrderTelegramMessage(Order $order, string $demoUsername, string $demoPassword): string
    {
        $order->load('orderItems.product');

        $message = "✅ <b>ĐƠN HÀNG ĐÃ HOÀN THÀNH - ĐÃ CẤP TÀI KHOẢN</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
        $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
        $message .= "• Tổng tiền: <b>" . number_format((float)$order->total_amount, 0, ',', '.') . "đ</b>\n";
        $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";

        $message .= "👤 <b>KHÁCH HÀNG</b>\n";
        $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
        $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
        $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n\n";

        $message .= "🔐 <b>TÀI KHOẢN DEMO ĐÃ CẤP</b>\n";
        $message .= "• Username: <code>" . $demoUsername . "</code>\n";
        $message .= "• Password: <code>" . $demoPassword . "</code>\n\n";

        $message .= "🛒 <b>SẢN PHẨM</b>\n";
        foreach ($order->orderItems as $index => $item) {
            $message .= ($index + 1) . ". " . ($item->product->name ?? 'N/A') . "\n";
            $message .= "   • SL: " . $item->quantity . " | Giá: " . number_format($item->price, 0, ',', '.') . "đ\n";
        }

        $message .= "\n📧 Email thông báo đã được gửi tự động!";

        return $message;
    }
}
