<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;
use App\Models\Order;

class TestEmail extends Command
{
    protected $signature = 'email:test {recipient}';
    protected $description = 'Send test email';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        $order = Order::first();
        if (!$order) {
            $this->error('Không tìm thấy đơn hàng nào!');
            return 1;
        }

        $this->info("📦 Đơn hàng: #{$order->id}");
        $this->info("📧 Gửi đến: {$recipient}");
        $this->info("🚀 Đang gửi...");

        try {
            $demoUsername = 'testuser_demo_' . $order->id;
            $demoPassword = 'Cudanmangorg_1';
            
            Mail::to($recipient)->send(
                new OrderCompletedMail($order, $demoUsername, $demoPassword)
            );
            
            $this->info("✅ Email đã gửi thành công!");
            $this->info("🔐 Username: {$demoUsername}");
            $this->info("🔑 Password: {$demoPassword}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Lỗi: " . $e->getMessage());
            return 1;
        }
    }
}
