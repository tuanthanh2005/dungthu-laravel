<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('cart:send-abandoned-reminders')->everyFifteenMinutes();

// Kiểm tra khách hàng sắp hết hạn dịch vụ - chạy mỗi ngày lúc 8h sáng
Schedule::command('durations:check-expiring')->dailyAt('08:00');

// Tự động dọn dẹp nhật ký IP nghi ngờ cũ hơn 14 ngày - chạy mỗi ngày lúc 0h đêm
Schedule::call(function () {
    \App\Models\SuspiciousIpLog::where('created_at', '<', now()->subDays(14))->delete();
})->dailyAt('00:00');
