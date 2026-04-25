<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\PathHelper;
use App\Http\Controllers\Controller;
use App\Models\AffiliateInvoice;
use App\Models\AffiliateWithdrawal;
use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateDashboardController extends Controller
{
    private function affiliate()
    {
        return Auth::guard('affiliate')->user();
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $affiliate = $this->affiliate();
        $totalEarned    = $affiliate->invoices()->where('status', 'approved')->sum('commission');
        $pendingEarned  = $affiliate->invoices()->where('status', 'pending')->sum('commission');
        $totalWithdrawn = $affiliate->withdrawals()->where('status', 'approved')->sum('amount');
        $balance        = $affiliate->balance;

        $recentInvoices   = $affiliate->invoices()->latest()->take(5)->get();
        $recentWithdrawals= $affiliate->withdrawals()->latest()->take(5)->get();

        return view('affiliate.dashboard', compact(
            'affiliate', 'totalEarned', 'pendingEarned',
            'totalWithdrawn', 'balance', 'recentInvoices', 'recentWithdrawals'
        ));
    }

    // ─── Pending / Rejected status pages ─────────────────────────────────────
    public function pending()
    {
        return view('affiliate.status.pending', ['affiliate' => $this->affiliate()]);
    }

    public function rejected()
    {
        return view('affiliate.status.rejected', ['affiliate' => $this->affiliate()]);
    }

    // ─── Invoices (gửi hóa đơn) ──────────────────────────────────────────────
    public function invoices()
    {
        $affiliate = $this->affiliate();
        $invoices  = $affiliate->invoices()->latest()->paginate(10);
        return view('affiliate.invoices.index', compact('affiliate', 'invoices'));
    }

    public function createInvoice()
    {
        return view('affiliate.invoices.create', ['affiliate' => $this->affiliate()]);
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required_without:customer_phone|nullable|string|max:255',
            'customer_phone' => 'required_without:customer_email|nullable|string|max:20',
            'amount'         => 'required|numeric|min:1000',
            'bill_image'     => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'note'           => 'nullable|string|max:1000',
        ], [
            'product_name.required'  => 'Tên sản phẩm không được để trống.',
            'customer_name.required' => 'Tên khách hàng là bắt buộc.',
            'customer_email.required_without' => 'Vui lòng nhập Email hoặc Số điện thoại khách hàng.',
            'customer_phone.required_without' => 'Vui lòng nhập Email hoặc Số điện thoại khách hàng.',
            'amount.required'        => 'Số tiền không được để trống.',
            'amount.min'             => 'Số tiền tối thiểu là 1.000đ.',
            'bill_image.required'    => 'Vui lòng upload ảnh hóa đơn.',
            'bill_image.image'       => 'File phải là ảnh.',
            'bill_image.max'         => 'Ảnh không được quá 5MB.',
        ]);

        $affiliate = $this->affiliate();
        $billPath  = null;

        if ($request->hasFile('bill_image')) {
            $file     = $request->file('bill_image');
            $filename = time() . '_bill_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $uploadPath = PathHelper::publicRootPath('uploads/affiliates');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $billPath = 'uploads/affiliates/' . $filename;
        }

        $amount     = (int) $request->amount;
        $commission = (int) round($amount * 0.05); // 5%

        $invoice = AffiliateInvoice::create([
            'affiliate_id'   => $affiliate->id,
            'product_name'   => $request->product_name,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'amount'         => $amount,
            'commission'     => $commission,
            'bill_image'     => $billPath,
            'note'           => $request->note,
            'status'         => 'pending',
        ]);

        // Notify admin
        $msg  = "🧾 <b>HÓA ĐƠN CỘNG TÁC VIÊN MỚI</b>\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $msg .= "• CTV: <b>{$affiliate->name}</b>\n";
        $msg .= "• Sản phẩm: <b>{$invoice->product_name}</b>\n";
        $msg .= "• Khách hàng: <b>{$invoice->customer_name}</b>\n";
        if ($invoice->customer_email || $invoice->customer_phone) {
            $msg .= "• Thông tin KH: " . implode(' | ', array_filter([$invoice->customer_email, $invoice->customer_phone])) . "\n";
        }
        $msg .= "• Số tiền KH trả: <b>" . number_format($amount, 0, ',', '.') . "đ</b>\n";
        $msg .= "• Hoa hồng 5%: <b>" . number_format($commission, 0, ',', '.') . "đ</b>\n";
        $msg .= "• Thời gian: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";
        $msg .= "⚠️ <i>Vui lòng kiểm tra và duyệt hóa đơn!</i>\n";
        $msg .= "🔗 " . url('/admin/affiliates/invoices');
        TelegramHelper::sendMessage($msg);

        return redirect()->route('affiliate.invoices')->with('success', 'Gửi hóa đơn thành công! Vui lòng chờ admin duyệt.');
    }

    // ─── Withdrawals (rút tiền) ───────────────────────────────────────────────
    public function withdrawals()
    {
        $affiliate   = $this->affiliate();
        $withdrawals = $affiliate->withdrawals()->latest()->paginate(10);
        return view('affiliate.withdrawals.index', compact('affiliate', 'withdrawals'));
    }

    public function createWithdrawal()
    {
        $affiliate = $this->affiliate();
        return view('affiliate.withdrawals.create', compact('affiliate'));
    }

    public function storeWithdrawal(Request $request)
    {
        $affiliate = $this->affiliate();

        $request->validate([
            'amount'              => 'required|numeric|min:50000',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
            'note'                => 'nullable|string|max:500',
        ], [
            'amount.required'              => 'Số tiền không được để trống.',
            'amount.min'                   => 'Số tiền rút tối thiểu là 50.000đ.',
            'bank_name.required'           => 'Tên ngân hàng không được để trống.',
            'bank_account_number.required' => 'Số tài khoản không được để trống.',
            'bank_account_name.required'   => 'Tên chủ tài khoản không được để trống.',
        ]);

        $amount = (int) $request->amount;

        if ($amount > $affiliate->balance) {
            return back()->withErrors(['amount' => 'Số dư không đủ để rút. Số dư hiện tại: ' . number_format($affiliate->balance, 0, ',', '.') . 'đ'])->withInput();
        }

        // Check no pending withdrawal
        if ($affiliate->withdrawals()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Bạn đang có yêu cầu rút tiền đang chờ duyệt. Vui lòng chờ admin xử lý xong trước khi tạo yêu cầu mới.');
        }

        $withdrawal = AffiliateWithdrawal::create([
            'affiliate_id'        => $affiliate->id,
            'amount'              => $amount,
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $request->bank_account_name,
            'note'                => $request->note,
            'status'              => 'pending',
        ]);

        // Notify admin via Telegram
        $msg  = "💸 <b>YÊU CẦU RÚT TIỀN - CTV</b>\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $msg .= "• CTV: <b>{$affiliate->name}</b> ({$affiliate->email})\n";
        $msg .= "• Số tiền: <b>" . number_format($amount, 0, ',', '.') . "đ</b>\n";
        $msg .= "• Ngân hàng: <b>{$withdrawal->bank_name}</b>\n";
        $msg .= "• STK: <b>{$withdrawal->bank_account_number}</b>\n";
        $msg .= "• Chủ TK: <b>{$withdrawal->bank_account_name}</b>\n";
        if ($withdrawal->note) {
            $msg .= "• Ghi chú: {$withdrawal->note}\n";
        }
        $msg .= "• Thời gian: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>\n\n";
        $msg .= "💰 <i>Số dư CTV: " . number_format($affiliate->balance, 0, ',', '.') . "đ</i>\n";
        $msg .= "⚠️ <i>Vui lòng chuyển tiền và phê duyệt trong Admin!</i>\n";
        $msg .= "🔗 " . url('/admin/affiliates/withdrawals');
        TelegramHelper::sendMessage($msg);

        return redirect()->route('affiliate.withdrawals')->with('success', 'Yêu cầu rút tiền đã gửi! Admin sẽ chuyển tiền và duyệt sớm nhất.');
    }

    // ─── Account Settings ─────────────────────────────────────────────────────
    public function account()
    {
        return view('affiliate.account', ['affiliate' => $this->affiliate()]);
    }

    public function updateAccount(Request $request)
    {
        $affiliate = $this->affiliate();

        $request->validate([
            'name'                => 'required|string|max:255',
            'phone'               => 'required|string|max:20',
            'address'             => 'required|string|max:500',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name'   => 'nullable|string|max:100',
        ]);

        $affiliate->update($request->only('name', 'phone', 'address', 'bank_name', 'bank_account_number', 'bank_account_name'));

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
    }

    public function updatePassword(Request $request)
    {
        $affiliate = $this->affiliate();

        $request->validate([
            'current_password'     => 'required',
            'new_password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.min'          => 'Mật khẩu mới phải ít nhất 8 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        if (!Hash::check($request->current_password, $affiliate->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $affiliate->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
