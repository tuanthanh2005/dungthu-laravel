<?php

namespace App\Http\Controllers;

use App\Models\BuffOrder;
use App\Models\BuffService;
use App\Models\BuffServer;
use App\Helpers\SupportHelper;
use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuffOrderController extends Controller
{
    public function create(BuffService $buffService)
    {
        $servers = BuffServer::where('is_active', true)
            ->where('is_maintenance', false)
            ->get();

        return view('buff.create-order', compact('buffService', 'servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:buff_services,id',
            'server_id' => 'required|exists:buff_servers,id',
            'social_link' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'emotion_type' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $service = BuffService::find($validated['service_id']);
        $server = BuffServer::find($validated['server_id']);

        // Kiểm tra server còn hoạt động không
        if (!$server->is_active || $server->is_maintenance) {
            return back()->withErrors(['server_id' => 'Server này hiện không khả dụng']);
        }

        // Kiểm tra số lượng hợp lệ
        if ($validated['quantity'] < $service->min_amount || $validated['quantity'] > $service->max_amount) {
            return back()->withErrors(['quantity' => "Số lượng phải từ {$service->min_amount} đến {$service->max_amount}"]);
        }

        $unitPrice = $service->getPriceForServer($server->id);
        $totalPrice = ($unitPrice * $validated['quantity']);

        $order = new BuffOrder();
        $order->user_id = $request->user()->id;
        $order->buff_service_id = $service->id;
        $order->buff_server_id = $server->id;
        $order->order_code = 'BUFF-' . strtoupper(Str::random(8));
        $order->social_link = $validated['social_link'];
        $order->quantity = $validated['quantity'];
        $order->notes = $validated['notes'] ?? null;
        $order->emotion_type = $validated['emotion_type'] ?? null;
        $order->base_price = 0;
        $order->unit_price = $unitPrice;
        $order->total_price = $totalPrice;
        $order->status = 'pending';
        $order->save();

        return redirect()->route('buff.payment', $order)->with('success', 'Tạo đơn thành công. Vui lòng thanh toán!');
    }

    public function payment(BuffOrder $buffOrder)
    {
        // Kiểm tra quyền
        if ($buffOrder->user_id !== auth()->id()) {
            abort(403);
        }

        if ($buffOrder->status !== 'pending') {
            return redirect()->route('buff.history')->with('info', 'Đơn hàng này không cần thanh toán');
        }

        // Generate VietQR code
        $bankCode = '970422'; // MB Bank
        $accountNumber = '0783704196';
        $amount = (int) $buffOrder->total_price;
        $description = 'DungThu Buff - ' . $buffOrder->order_code;
        $beneficiary = 'TRAN THANH TUAN';
        
        // VietQR API URL
        $qrUrl = "https://api.vietqr.io/image/{$bankCode}-{$accountNumber}-{$amount}-{$description}-{$beneficiary}.png";

        return view('buff.payment', compact('buffOrder', 'qrUrl'));
    }

    public function confirmPayment(BuffOrder $buffOrder, Request $request)
    {
        if ($buffOrder->user_id !== auth()->id()) {
            abort(403);
        }

        if ($buffOrder->status !== 'pending') {
            return response()->json(['error' => 'Đơn hàng không hợp lệ'], 400);
        }

        $buffOrder->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method ?? 'qr_code',
            'transaction_id' => $request->transaction_id ?? null,
            'paid_at' => now(),
        ]);

        // Send Telegram notification
        TelegramHelper::sendBuffPaymentNotification($buffOrder);

        return response()->json(['success' => true, 'redirect' => route('buff.history')]);
    }

    public function history()
    {
        $orders = BuffOrder::where('user_id', auth()->id())
            ->with(['buffService', 'buffServer'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('buff.history', compact('orders'));
    }

    public function detail(BuffOrder $buffOrder)
    {
        if ($buffOrder->user_id !== auth()->id()) {
            abort(403);
        }

        $supportInfo = SupportHelper::getContactInfo();
        
        return view('buff.order-detail', compact('buffOrder', 'supportInfo'));
    }
}
