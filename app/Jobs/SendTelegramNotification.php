<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;

    /**
     * Create a new job instance.
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            Log::warning('Telegram credentials not configured.');
            return;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $this->text,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('Telegram queue notification sent successfully.');
            } else {
                Log::error('Telegram queue notification failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Telegram queue notification error: ' . $e->getMessage());
        }
    }
}
