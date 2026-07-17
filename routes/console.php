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
