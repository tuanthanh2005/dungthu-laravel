<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;
use App\Models\Order;

// Lấy đơn hàng đầu tiên
$order = Order::first();

if (!$order) {
    echo "❌ Không tìm thấy đơn hàng nào trong database!\n";
    exit(1);
}

echo "📦 Tìm thấy đơn hàng #" . $order->id . "\n";
echo "👤 Khách hàng: " . $order->customer_name . "\n";
echo "📧 Email: " . $order->customer_email . "\n";
echo "\n🚀 Đang gửi email test...\n\n";

try {
    $demoUsername = 'testuser_demo_' . $order->id;
    $demoPassword = 'Cudanmangorg_1';
    
    Mail::to($order->customer_email)->send(
        new OrderCompletedMail($order, $demoUsername, $demoPassword)
    );
    
    echo "✅ Email đã được gửi thành công!\n";
    echo "📬 Kiểm tra hộp thư: " . $order->customer_email . "\n";
    echo "🔐 Username demo: " . $demoUsername . "\n";
    echo "🔑 Password: " . $demoPassword . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi khi gửi email:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
