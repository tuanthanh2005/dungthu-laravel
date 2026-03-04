<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;
use App\Models\Order;

class TestEmailSimple extends Command
{
    protected $signature = 'email:test-simple {recipient}';
    protected $description = 'Send test email with mock order';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        // Tạo mock order để test
        $mockOrder = new Order([
            'id' => 999,
            'customer_name' => 'Khách hàng test',
            'customer_email' => $recipient,
            'customer_phone' => '0772698113',
            'total_amount' => 500000,
            'status' => 'completed',
            'order_type' => 'qr',
        ]);
        
        // Set created_at
        $mockOrder->created_at = now();
        
        // Mock order items
        $mockOrder->setRelation('orderItems', collect([
            (object)[
                'product' => (object)[
                    'name' => 'Sản phẩm Test',
                ],
                'quantity' => 1,
                'price' => 500000,
            ]
        ]));

        $this->info("📧 Gửi đến: {$recipient}");
        $this->info("🚀 Đang gửi email test...");

        try {
            $demoUsername = 'testuser_demo_999';
            $demoPassword = $this->generateRandomPassword();
            
            Mail::to($recipient)->send(
                new OrderCompletedMail($mockOrder, $demoUsername, $demoPassword)
            );
            
            $this->info("✅ Email đã gửi thành công!");
            $this->info("🔐 Username: {$demoUsername}");
            $this->info("🔑 Password: {$demoPassword}");
            $this->info("📬 Kiểm tra hộp thư: {$recipient}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Lỗi: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Generate mật khẩu random mạnh
     */
    private function generateRandomPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0772698113';
        $special = '!@#$%^&*';
        
        $allChars = $uppercase . $lowercase . $numbers . $special;
        $password = '';
        
        // Đảm bảo có ít nhất 1 chữ hoa, 1 chữ thường, 1 số, 1 ký tự đặc biệt
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];
        
        // Tạo phần còn lại
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        
        // Shuffle password để ngẫu nhiên hơn
        $passwordArray = str_split($password);
        shuffle($passwordArray);
        
        return implode('', $passwordArray);
    }
}
