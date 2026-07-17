<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerDuration;
use App\Helpers\TelegramHelper;
use Carbon\Carbon;

class CheckExpiringDurations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'durations:check-expiring';

    /**
     * The console command description.
     */
    protected $description = 'Kiểm tra và gửi thông báo Telegram cho các khách hàng sắp hết hạn dịch vụ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra thời hạn dịch vụ khách hàng...');

        // Lấy các bản ghi sắp hết hạn trong 3 ngày tới
        $expiringDurations = CustomerDuration::whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now()->startOfDay())
            ->where('expiry_date', '<=', now()->addDays(3)->endOfDay())
            ->orderBy('expiry_date', 'asc')
            ->get();

        if ($expiringDurations->isEmpty()) {
            $this->info('Không có khách hàng nào sắp hết hạn.');
            return 0;
        }

        $this->info("Tìm thấy {$expiringDurations->count()} khách hàng sắp hết hạn.");

        // Tạo tin nhắn Telegram
        $message = "⚠️ <b>CẢNH BÁO: KHÁCH HÀNG SẮP HẾT HẠN DỊCH VỤ</b>\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "📅 Ngày kiểm tra: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n";
        $message .= "📊 Tổng số: <b>{$expiringDurations->count()}</b> khách hàng\n\n";

        foreach ($expiringDurations as $index => $duration) {
            $daysLeft = now()->startOfDay()->diffInDays(Carbon::parse($duration->expiry_date)->startOfDay(), false);
            $urgency = $daysLeft <= 1 ? '🔴' : ($daysLeft <= 2 ? '🟡' : '🟢');

            $message .= $urgency . " <b>" . ($index + 1) . ". " . $duration->customer_name . "</b>\n";
            $message .= "   📧 " . $duration->customer_email . "\n";
            
            if ($duration->customer_phone) {
                $message .= "   📱 " . $duration->customer_phone . "\n";
            }
            
            $message .= "   📦 " . $duration->product_name . "\n";
            $message .= "   🔖 Mã đơn: " . $duration->order_code . "\n";
            $message .= "   ⏱ Thời hạn: " . ($duration->total_duration ?? 'N/A') . "\n";
            $message .= "   📅 Hết hạn: <b>" . Carbon::parse($duration->expiry_date)->format('d/m/Y') . "</b>\n";
            
            if ($daysLeft <= 0) {
                $message .= "   ⏰ <b>HẾT HẠN HÔM NAY!</b>\n";
            } else {
                $message .= "   ⏰ Còn <b>{$daysLeft} ngày</b>\n";
            }
            
            $message .= "\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 <i>Hãy liên hệ khách hàng để gia hạn dịch vụ!</i>";

        try {
            TelegramHelper::sendMessage($message);
            $this->info('Đã gửi thông báo Telegram thành công!');
        } catch (\Exception $e) {
            $this->error('Lỗi gửi Telegram: ' . $e->getMessage());
        }

        return 0;
    }
}
