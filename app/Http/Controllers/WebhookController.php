<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Mail\OrderApprovedMail;
use App\Helpers\TelegramHelper;

class WebhookController extends Controller
{
    /**
     * Handle incoming SePay Webhook
     */
    public function handleSepayWebhook(Request $request)
    {
        Log::info('SePay Webhook received: ', $request->all());

        // Validate Authorization header if configured
        $authHeader = $request->header('Authorization');
        $apiKey = config('services.sepay.key');

        if ($authHeader && $apiKey) {
            // SePay sends Authorization header, check if it contains the API Key
            if (!str_contains($authHeader, $apiKey)) {
                Log::warning('SePay Webhook Unauthorized access attempt.');
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        $content = $request->input('content', '');
        $code = $request->input('code', '');
        $transferAmount = (float) $request->input('transferAmount', 0);
        $transactionId = $request->input('id');
        $gateway = $request->input('gateway', '');
        $referenceCode = $request->input('referenceCode', '');

        // Match order code pattern DT-[A-Z0-9]{8} or DT[A-Z0-9]{8}
        $orderCode = null;
        if (preg_match('/(DT-?[A-Z0-9]{8})/i', $content, $matches)) {
            $cleanCode = str_replace('-', '', strtoupper($matches[1]));
            $orderCode = 'DT-' . substr($cleanCode, 2);
        } elseif (preg_match('/(DT-?[A-Z0-9]{8})/i', $code, $matches)) {
            $cleanCode = str_replace('-', '', strtoupper($matches[1]));
            $orderCode = 'DT-' . substr($cleanCode, 2);
        }

        if (!$orderCode) {
            Log::warning('SePay Webhook: Could not parse order code from transaction description: ' . $content);
            return response()->json(['success' => false, 'message' => 'No order code found'], 200);
        }

        // Find the order
        $order = Order::where('order_code', $orderCode)->first();

        if ($order) {
            if ($order->status !== 'completed' && $order->status !== 'cancelled') {
                // Check if the order has expired (5 minutes)
                if ($order->created_at->diffInMinutes(now()) >= 5) {
                    $order->update(['status' => 'cancelled']);
                    Log::warning("SePay Webhook: Rejecting payment for expired order {$orderCode}.");
                    return response()->json(['success' => false, 'message' => 'Order payment has expired'], 200);
                }

                // Determine order expected amount. Note: currency conversion might apply
                $expectedAmount = (float) $order->total_amount;
                
                // If currency is USD, we convert it to VND to match SePay's local currency transfer amount
                if ($order->currency === 'USD') {
                    $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                    $expectedAmount = $expectedAmount * $rate;
                }

                // Check if paid amount is at least 95% of expected amount (accounting for small differences)
                if ($transferAmount >= ($expectedAmount * 0.95)) {
                    $order->update(['status' => 'completed']);
                    
                    // Trigger notifications & delivery
                    $this->sendOrderApprovedNotifications($order);
                    
                    Log::info("SePay Webhook: Order {$orderCode} updated to COMPLETED.");
                    return response()->json(['success' => true, 'message' => 'Order completed successfully']);
                } else {
                    Log::warning("SePay Webhook: Order {$orderCode} transfer amount {$transferAmount} is lower than expected {$expectedAmount}.");
                    return response()->json(['success' => false, 'message' => 'Insufficient amount paid'], 200);
                }
            }
            return response()->json(['success' => true, 'message' => 'Order already completed or processed']);
        } else {
            // Order doesn't exist yet (user paid first, then submits the order later)
            // Store transaction in cache for 5 minutes (300 seconds)
            Cache::put('sepay_payment_' . $orderCode, [
                'amount' => $transferAmount,
                'transaction_id' => $transactionId,
                'gateway' => $gateway,
                'reference_code' => $referenceCode,
                'matched_at' => now()->toIso8601String()
            ], 300);

            Log::info("SePay Webhook: Cached transaction for non-existent order code {$orderCode}. Amount: {$transferAmount}");
            return response()->json(['success' => true, 'message' => 'Payment cached. Waiting for checkout submit.']);
        }
    }

    /**
     * Check payment status (Polling endpoint)
     */
    public function checkStatus($orderCode)
    {
        $orderCode = strtoupper($orderCode);

        // 1. Check if order exists in DB
        $order = Order::where('order_code', $orderCode)->first();
        if ($order) {
            if ($order->status === 'completed') {
                return response()->json(['status' => 'success']);
            }
            // Check 5-minute expiry for pending order
            if ($order->status === 'pending' && $order->created_at->diffInMinutes(now()) >= 5) {
                $order->update(['status' => 'cancelled']);
                return response()->json(['status' => 'expired']);
            }
            if ($order->status === 'cancelled') {
                return response()->json(['status' => 'expired']);
            }
        }

        // 2. Check if transaction is cached
        if (Cache::has('sepay_payment_' . $orderCode)) {
            return response()->json(['status' => 'success']);
        }

        // 3. Check session expiration if order is not in DB yet
        if (session('checkout_order_code') === $orderCode) {
            $checkoutTime = session('checkout_order_time');
            if ($checkoutTime && (now()->timestamp - $checkoutTime) >= 300) {
                return response()->json(['status' => 'expired']);
            }
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Query SePay API directly to verify transaction (Manual fallback)
     */
    public function checkWebhook(Request $request, $orderCode)
    {
        $orderCode = strtoupper($orderCode);
        Log::info("Manual payment verification requested for: {$orderCode}");

        // 1. Check DB first
        $order = Order::where('order_code', $orderCode)->first();
        if ($order) {
            if ($order->status === 'completed') {
                return response()->json(['status' => 'success', 'message' => 'Đơn hàng đã được thanh toán thành công!']);
            }
            if ($order->status === 'cancelled') {
                return response()->json(['status' => 'expired', 'message' => 'Đơn hàng đã hết hạn thanh toán và bị hủy.']);
            }
            if ($order->status === 'pending' && $order->created_at->diffInMinutes(now()) >= 5) {
                $order->update(['status' => 'cancelled']);
                return response()->json(['status' => 'expired', 'message' => 'Đơn hàng đã hết hạn thanh toán và bị hủy.']);
            }
        } else {
            // Check session expiration if order is not in DB yet
            if (session('checkout_order_code') === $orderCode) {
                $checkoutTime = session('checkout_order_time');
                if ($checkoutTime && (now()->timestamp - $checkoutTime) >= 300) {
                    return response()->json(['status' => 'expired', 'message' => 'Đơn hàng đã hết hạn thanh toán. Vui lòng làm mới trang.']);
                }
            }
        }

        // 2. Check cache next
        if (Cache::has('sepay_payment_' . $orderCode)) {
            // If order exists in DB but is pending, let's complete it now
            if ($order && $order->status !== 'completed' && $order->status !== 'cancelled') {
                $cached = Cache::get('sepay_payment_' . $orderCode);
                $expectedAmount = (float) $order->total_amount;
                if ($order->currency === 'USD') {
                    $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                    $expectedAmount = $expectedAmount * $rate;
                }
                
                if ($cached['amount'] >= ($expectedAmount * 0.95)) {
                    $order->update(['status' => 'completed']);
                    $this->sendOrderApprovedNotifications($order);
                    Cache::forget('sepay_payment_' . $orderCode);
                    Log::info("Manual Check: Resolved order {$orderCode} from Cache.");
                    return response()->json(['status' => 'success', 'message' => 'Thanh toán thành công!']);
                }
            } else {
                return response()->json(['status' => 'success', 'message' => 'Đã phát hiện giao dịch thành công. Vui lòng nhấn Xác nhận Đặt hàng để hoàn tất.']);
            }
        }

        // 3. Check SePay API directly
        $apiKey = config('services.sepay.key');
        $accountNumber = config('services.vietqr.account_number', 'SEPTT20721');

        if (!$apiKey) {
            Log::error('SePay API Key is not configured.');
            return response()->json(['status' => 'pending', 'message' => 'Lỗi cấu hình hệ thống thanh toán. Vui lòng liên hệ Admin.']);
        }

        try {
            // Call User API (legacy list endpoint, matches user access token)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->timeout(10)->get('https://my.sepay.vn/userapi/transactions/list', [
                'account_number' => $accountNumber,
                'limit' => 20,
            ]);

            if ($response->failed()) {
                Log::error('Failed to fetch transactions from SePay API: ' . $response->body());
                return response()->json(['status' => 'pending', 'message' => 'Không thể kết nối đến máy chủ SePay. Vui lòng thử lại sau.']);
            }

            $data = $response->json();
            $transactions = $data['transactions'] ?? [];

            foreach ($transactions as $tx) {
                $content = $tx['transaction_content'] ?? '';
                $amount = (float) ($tx['amount_in'] ?? 0);

                // Standardize comparison by removing hyphens (banks often strip special characters)
                $normalizedContent = str_replace('-', '', $content);
                $normalizedOrderCode = str_replace('-', '', $orderCode);

                // Check if description has the order code
                if (stripos($normalizedContent, $normalizedOrderCode) !== false) {
                    Log::info("Manual Check: Found matching transaction on SePay API. TxID: " . ($tx['id'] ?? 'N/A') . ", Amount: {$amount}");

                    // Determine amount correctness
                    $expectedAmount = 0;
                    if ($order) {
                        $expectedAmount = (float) $order->total_amount;
                        if ($order->currency === 'USD') {
                            $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                            $expectedAmount = $expectedAmount * $rate;
                        }
                    } else {
                        // Fallback checking for session-based totals
                        $cart = session()->get('cart', []);
                        foreach ($cart as $details) {
                            $expectedAmount += $details['price'] * $details['quantity'];
                        }
                        if (session()->has('applied_coupon')) {
                            $couponId = session('applied_coupon');
                            $coupon = \App\Models\Coupon::find($couponId);
                            if ($coupon) {
                                $expectedAmount = max(0, $expectedAmount - (float)$coupon->value);
                            }
                        }
                    }

                    // Standardize amount checking
                    if ($amount >= ($expectedAmount * 0.95)) {
                        if ($order && $order->status !== 'completed' && $order->status !== 'cancelled') {
                            $order->update(['status' => 'completed']);
                            $this->sendOrderApprovedNotifications($order);
                        } else {
                            // Order not placed yet, cache it
                            Cache::put('sepay_payment_' . $orderCode, [
                                'amount' => $amount,
                                'transaction_id' => $tx['id'] ?? null,
                                'gateway' => $tx['bank_brand_name'] ?? '',
                                'reference_code' => $tx['reference_number'] ?? '',
                                'matched_at' => now()->toIso8601String()
                            ], 1800);
                        }

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Thanh toán thành công! Hệ thống đã ghi nhận giao dịch của bạn.'
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment directly from SePay: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Hệ thống chưa tìm thấy giao dịch. Vui lòng kiểm tra lại số tài khoản, nội dung và số tiền đã chuyển.'
        ]);
    }

    /**
     * Send approved order notifications
     */
    private function sendOrderApprovedNotifications(Order $order)
    {
        try {
            $email = $order->customer_email;
            if (!$email && $order->user_id) {
                $email = optional($order->user)->email;
            }

            if ($email) {
                $order->load('orderItems.product');
                Mail::to($email)->send(new OrderApprovedMail($order));
                Log::info('Order approved email sent to ' . $email . ' for order #' . $order->id);
            }
        } catch (\Exception $e) {
            Log::error('Error sending order approved email for order #' . $order->id . ': ' . $e->getMessage());
        }

        try {
            $order->load('orderItems.product');
            $message = "✅ <b>ĐƠN HÀNG ĐÃ ĐƯỢC DUYỆT TỰ ĐỘNG (SEPAY)</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "📦 <b>THÔNG TIN ĐƠN HÀNG</b>\n";
            $message .= "• Mã đơn: <b>#" . $order->id . "</b>\n";
            $message .= "• Mã code: <b>" . $order->order_code . "</b>\n";
            if (isset($order->discount_amount) && $order->discount_amount > 0) {
                $message .= "• Giảm giá: <b>-" . number_format($order->discount_amount, 0, ',', '.') . "đ</b> (" . $order->coupon_code . ")\n";
            }
            $message .= "• Tổng tiền: <b>" . number_format((float)$order->total_amount, 0, ',', '.') . "đ</b>\n";
            $message .= "• Thời gian: <b>" . $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";

            $message .= "👤 <b>KHÁCH HÀNG</b>\n";
            $message .= "• Họ tên: <b>" . $order->customer_name . "</b>\n";
            $message .= "• Email: <b>" . $order->customer_email . "</b>\n";
            $message .= "• SĐT: <b>" . $order->customer_phone . "</b>\n\n";

            $message .= "🛒 <b>SẢN PHẨM</b>\n";
            foreach ($order->orderItems as $index => $item) {
                $message .= ($index + 1) . ". " . ($item->product->name ?? 'N/A') . "\n";
                $message .= "   • SL: " . $item->quantity . " | Giá: " . number_format($item->price, 0, ',', '.') . "đ\n";
            }

            $message .= "\n📧 Email xác nhận đã được gửi tới khách hàng!";

            TelegramHelper::sendMessage($message);
            Log::info('Telegram notification sent for order #' . $order->id);
        } catch (\Exception $e) {
            Log::error('Error sending Telegram message for order #' . $order->id . ': ' . $e->getMessage());
        }
    }
}
