<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\AbandonedCart;
use App\Mail\AbandonedCartReminderMail;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'cart:send-abandoned-reminders';
    protected $description = 'Send abandoned cart reminder emails (4h, 16h, 24h) to logged-in users';

    public function handle(): int
    {
        $now = now();
        $thresholds = [
            0 => 4,
            1 => 16,
            2 => 24,
        ];

        $carts = AbandonedCart::query()
            ->where('reminder_stage', '<', 3)
            ->whereNotNull('last_activity_at')
            ->get();

        foreach ($carts as $cart) {
            $stage = (int) $cart->reminder_stage;
            if (!isset($thresholds[$stage])) {
                continue;
            }

            $hours = $thresholds[$stage];
            if ($cart->last_activity_at === null) {
                continue;
            }

            if ($now->diffInHours($cart->last_activity_at) < $hours) {
                continue;
            }

            if (!$cart->email) {
                $cart->reminder_stage = 3;
                $cart->save();
                continue;
            }

            try {
                Mail::to($cart->email)->send(new AbandonedCartReminderMail($cart, $stage));
                $cart->reminder_stage = $stage + 1;
                $cart->last_reminder_at = $now;
                $cart->save();
            } catch (\Exception $e) {
                \Log::error('Abandoned cart reminder failed: ' . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
