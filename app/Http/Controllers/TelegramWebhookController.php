<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\TelegramHelper;
use App\Helpers\OrderHelper;
use App\Models\Order;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, $secret)
    {
        $expected = (string) config('services.telegram.webhook_secret', env('TELEGRAM_WEBHOOK_SECRET', ''));
        if (!$expected || !hash_equals($expected, (string) $secret)) {
            return response()->json(['ok' => false], 403);
        }

        $update = $request->all();

        if (isset($update['callback_query'])) {
            return $this->handleCallback($update['callback_query']);
        }

        return response()->json(['ok' => true]);
    }

    private function handleCallback(array $callback)
    {
        $data = $callback['data'] ?? '';
        $payload = TelegramHelper::verifyCallbackData($data);
        $callbackId = $callback['id'] ?? null;

        if (!$payload) {
            $this->answerCallback($callbackId, 'Dữ liệu không hợp lệ.');
            return response()->json(['ok' => true]);
        }

        $order = Order::find($payload['order_id']);
        if (!$order) {
            $this->answerCallback($callbackId, 'Không tìm thấy đơn hàng.');
            return response()->json(['ok' => true]);
        }

        if ($payload['action'] === 'approve') {
            if ($order->status === 'completed') {
                $this->answerCallback($callbackId, "Đơn #{$order->id} đã hoàn thành.");
                return response()->json(['ok' => true]);
            }

            $order->status = 'completed';
            $order->save();

            OrderHelper::sendOrderCompletedNotifications($order);
            $this->answerCallback($callbackId, "Đã duyệt đơn #{$order->id}.");
            return response()->json(['ok' => true]);
        }

        if ($payload['action'] === 'reject') {
            if ($order->status === 'cancelled') {
                $this->answerCallback($callbackId, "Đơn #{$order->id} đã bị hủy.");
                return response()->json(['ok' => true]);
            }

            $order->status = 'cancelled';
            $order->save();

            $this->answerCallback($callbackId, "Đã từ chối đơn #{$order->id}.");
            return response()->json(['ok' => true]);
        }

        $this->answerCallback($callbackId, 'Không hỗ trợ thao tác.');
        return response()->json(['ok' => true]);
    }

    private function answerCallback(?string $callbackId, string $text): void
    {
        if (!$callbackId) {
            return;
        }

        $botToken = config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
        if (!$botToken) {
            return;
        }

        Http::post("https://api.telegram.org/bot{$botToken}/answerCallbackQuery", [
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => false,
        ]);
    }
}
