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

        // Lấy các bản ghi sắp hết hạn trong 3 ngày tới (bỏ qua các bản ghi đã hủy hoặc đánh dấu hoàn thành)
        $expiringDurations = CustomerDuration::where(function ($q) {
            $q->whereDoesntHave('order')
              ->orWhereHas('order', function ($o) {
                  $o->where('status', '!=', 'cancelled');
              });
        })
        ->expiring()
        ->with(['order', 'user', 'product'])
        ->orderBy('expiry_date', 'asc')
        ->get();

        if ($expiringDurations->isEmpty()) {
            $this->info('Không có khách hàng nào sắp hết hạn.');
            return 0;
        }

        $this->info("Tìm thấy {$expiringDurations->count()} khách hàng sắp hết hạn.");

        // Gửi tin nhắn tổng quan
        $summaryMsg = "⚠️ <b>CẢNH BÁO KHÁCH HÀNG SẮP HẾT HẠN DỊCH VỤ</b>\n";
        $summaryMsg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $summaryMsg .= "📅 Kiểm tra lúc: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n";
        $summaryMsg .= "📊 Tổng số: <b>{$expiringDurations->count()}</b> khách hàng sắp hết hạn trong 3 ngày tới.\n";
        $summaryMsg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $summaryMsg .= "👇 Chi tiết đầy đủ thông tin từng khách hàng bên dưới:";

        $summaryMarkup = [
            'inline_keyboard' => [
                [
                    ['text' => '📋 Quản lý thời hạn trên Web', 'url' => url('/admin/customer-durations')],
                ]
            ]
        ];

        TelegramHelper::sendMessage($summaryMsg, $summaryMarkup);

        // Gửi thông tin đầy đủ cho từng khách hàng
        foreach ($expiringDurations as $index => $duration) {
            $nowDate = now()->startOfDay();
            $expiryDate = Carbon::parse($duration->expiry_date)->startOfDay();
            $daysLeft = $nowDate->diffInDays($expiryDate, false);
            
            if ($daysLeft < 0) {
                $urgency = '🔴';
                $statusText = "ĐÃ HẾT HẠN (" . abs($daysLeft) . " ngày trước)";
            } elseif ($daysLeft === 0) {
                $urgency = '🔴';
                $statusText = "HẾT HẠN HÔM NAY!";
            } elseif ($daysLeft === 1) {
                $urgency = '🔴';
                $statusText = "Còn 1 ngày (Hết hạn ngày mai)";
            } elseif ($daysLeft === 2) {
                $urgency = '🟡';
                $statusText = "Còn 2 ngày";
            } else {
                $urgency = '🟢';
                $statusText = "Còn {$daysLeft} ngày";
            }

            $msg = "{$urgency} <b>THÔNG TIN KHÁCH HÀNG (#" . ($index + 1) . "/{$expiringDurations->count()})</b>\n";
            $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

            // 1. Thông tin cá nhân khách hàng
            $msg .= "👤 <b>THÔNG TIN KHÁCH HÀNG</b>\n";
            $msg .= "• Họ tên: <b>" . htmlspecialchars($duration->customer_name ?? 'N/A') . "</b>\n";
            $msg .= "• Email: <b>" . htmlspecialchars($duration->customer_email ?? 'N/A') . "</b>\n";
            $msg .= "• Số điện thoại: <b>" . htmlspecialchars($duration->customer_phone ?? 'Chưa cung cấp') . "</b>\n";
            if ($duration->user_id && $duration->user) {
                $msg .= "• Tài khoản Web: <b>" . htmlspecialchars($duration->user->name) . " (ID: #" . $duration->user_id . ")</b>\n";
            }
            $msg .= "\n";

            // 2. Gói dịch vụ & Thời hạn
            $msg .= "📦 <b>THÔNG TIN GÓI DỊCH VỤ</b>\n";
            $msg .= "• Sản phẩm: <b>" . htmlspecialchars($duration->product_name ?? 'N/A') . "</b>\n";
            $msg .= "• Mã đơn hàng: <b>#" . htmlspecialchars($duration->order_code ?? $duration->order_id ?? 'N/A') . "</b>\n";
            $msg .= "• Thời hạn gói: <b>" . htmlspecialchars($duration->total_duration ?? 'N/A') . "</b>\n";
            $msg .= "• Ngày bắt đầu: <b>" . ($duration->start_date ? Carbon::parse($duration->start_date)->format('d/m/Y') : 'N/A') . "</b>\n";
            $msg .= "• Ngày hết hạn: <b>" . ($duration->expiry_date ? Carbon::parse($duration->expiry_date)->format('d/m/Y') : 'N/A') . "</b>\n";
            $msg .= "• Tình trạng: <b>{$statusText}</b>\n\n";

            // 3. Thông tin bàn giao / Ghi chú admin
            $deliveryAccount = $duration->order ? $duration->order->delivery_account : null;
            $deliveryKey = $duration->order ? $duration->order->delivery_key : null;
            $adminNote = $duration->admin_note;

            if ($deliveryAccount || $deliveryKey || $adminNote) {
                $msg .= "🔑 <b>THÔNG TIN BÀN GIAO & GHI CHÚ</b>\n";
                if ($deliveryAccount) {
                    $msg .= "• Tài khoản: <code>" . htmlspecialchars($deliveryAccount) . "</code>\n";
                }
                if ($deliveryKey) {
                    $msg .= "• Key / License: <code>" . htmlspecialchars($deliveryKey) . "</code>\n";
                }
                if ($adminNote) {
                    $msg .= "• Ghi chú Admin: <code>" . htmlspecialchars($adminNote) . "</code>\n";
                }
                $msg .= "\n";
            }

            $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n";
            $msg .= "💡 <i>Hãy liên hệ khách hàng để tư vấn gia hạn kịp thời!</i>";

            // Inline buttons
            $buttons = [];
            if ($duration->order_id) {
                $buttons[] = ['text' => '🌐 Xem Đơn Hàng #' . $duration->order_id, 'url' => url("/admin/orders/{$duration->order_id}")];
            }
            $buttons[] = ['text' => '✏️ Sửa Thời Hạn', 'url' => url("/admin/customer-durations/{$duration->id}/edit")];

            $replyMarkup = [
                'inline_keyboard' => [$buttons]
            ];

            TelegramHelper::sendMessage($msg, $replyMarkup);
            
            usleep(250000); // Tránh rate limit Telegram API
        }

        $this->info('Đã gửi thông báo Telegram chi tiết thành công!');
        return 0;
    }
}
