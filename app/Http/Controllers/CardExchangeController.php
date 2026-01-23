<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardExchange;
use App\Helpers\TelegramHelper;
use Illuminate\Support\Facades\Auth;

class CardExchangeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đổi thẻ cào');
        }

        $user = Auth::user();
        $exchanges = CardExchange::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('card-exchange.index', compact('exchanges'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đổi thẻ cào');
        }

        $request->validate([
            'card_type' => 'required|in:Viettel,Mobifone,Vinaphone',
            'card_serial' => 'required|string|min:10|max:20',
            'card_code' => 'required|string|min:10|max:20',
            'card_value' => 'required|numeric|min:10000',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
        ], [
            'card_type.required' => 'Vui lòng chọn loại thẻ',
            'card_serial.required' => 'Vui lòng nhập seri thẻ',
            'card_code.required' => 'Vui lòng nhập mã thẻ',
            'card_value.required' => 'Vui lòng nhập mệnh giá thẻ',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng',
            'bank_account_number.required' => 'Vui lòng nhập số tài khoản',
            'bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
        ]);

        $exchange = CardExchange::create([
            'user_id' => Auth::id(),
            'card_type' => $request->card_type,
            'card_serial' => $request->card_serial,
            'card_code' => $request->card_code,
            'card_value' => $request->card_value,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'status' => 'pending',
        ]);

        // Gửi thông báo qua Telegram
        $this->sendTelegramNotification($exchange);

        return redirect()->route('card-exchange')->with('success', 'Gửi yêu cầu đổi thẻ thành công! Chúng tôi sẽ xử lý trong 5-10 phút.');
    }

    private function sendTelegramNotification($exchange)
    {
        $user = $exchange->user;
        
        $message = "🎴 <b>YÊU CẦU ĐỔI THẺ CÀO MỚI</b>\n\n";
        $message .= "👤 <b>Khách hàng:</b> {$user->name}\n";
        $message .= "📧 <b>Email:</b> {$user->email}\n";
        $message .= "📱 <b>SĐT:</b> " . ($user->phone ?? 'Chưa có') . "\n\n";
        $message .= "💳 <b>Thông tin thẻ:</b>\n";
        $message .= "   • Loại thẻ: {$exchange->card_type}\n";
        $message .= "   • Seri: {$exchange->card_serial}\n";
        $message .= "   • Mã thẻ: {$exchange->card_code}\n";
        $message .= "   • Mệnh giá: " . number_format($exchange->card_value, 0, ',', '.') . "đ\n\n";
        $message .= "🏦 <b>Thông tin ngân hàng:</b>\n";
        $message .= "   • Ngân hàng: {$exchange->bank_name}\n";
        $message .= "   • Số TK: {$exchange->bank_account_number}\n";
        $message .= "   • Chủ TK: {$exchange->bank_account_name}\n\n";
        $message .= "🆔 <b>Mã giao dịch:</b> #{$exchange->id}\n";
        $message .= "🕐 <b>Thời gian:</b> " . $exchange->created_at->format('d/m/Y H:i:s');

        TelegramHelper::sendMessage($message);
    }
}
