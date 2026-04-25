<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateInvoice;
use App\Models\AffiliateWithdrawal;
use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAffiliateController extends Controller
{
    // ─── Danh sách cộng tác viên ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Affiliate::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $affiliates      = $query->latest()->paginate(15);
        $pendingCount    = Affiliate::where('status', 'pending')->count();
        $approvedCount   = Affiliate::where('status', 'approved')->count();
        $rejectedCount   = Affiliate::where('status', 'rejected')->count();

        return view('admin.affiliates.index', compact(
            'affiliates', 'pendingCount', 'approvedCount', 'rejectedCount'
        ));
    }

    public function show(Affiliate $affiliate)
    {
        $affiliate->load(['invoices', 'withdrawals']);
        return view('admin.affiliates.show', compact('affiliate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:affiliates',
            'password'      => 'required|string|min:8',
            'phone'         => 'required|string|max:20',
            'cccd_number'   => 'required|string|max:20',
            'bank_name'     => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name'   => 'nullable|string|max:100',
        ]);

        Affiliate::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'cccd_number'   => $request->cccd_number,
            'bank_name'     => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $request->bank_account_name,
            'status'        => 'approved', // Auto approved when admin creates
            'approved_at'   => now(),
        ]);

        return back()->with('success', 'Đã tạo tài khoản Cộng tác viên thành công!');
    }

    // ─── Duyệt / Từ chối cộng tác viên ──────────────────────────────────────
    public function approve(Affiliate $affiliate)
    {
        $affiliate->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'reject_reason' => null,
        ]);

        // Notify via Telegram
        $msg  = "✅ <b>DUYỆT CỘNG TÁC VIÊN</b>\n";
        $msg .= "• Tên: <b>{$affiliate->name}</b>\n";
        $msg .= "• Email: <b>{$affiliate->email}</b>\n";
        $msg .= "• Thời gian duyệt: <b>" . now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "</b>";
        TelegramHelper::sendMessage($msg);

        return back()->with('success', "Đã duyệt cộng tác viên {$affiliate->name}!");
    }

    public function reject(Request $request, Affiliate $affiliate)
    {
        $request->validate(['reject_reason' => 'required|string|max:500']);

        $affiliate->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reject_reason,
        ]);

        return back()->with('success', "Đã từ chối hồ sơ của {$affiliate->name}!");
    }

    public function resetPassword(Affiliate $affiliate)
    {
        $affiliate->update([
            'password' => Hash::make('123456789'),
        ]);

        return back()->with('success', "Đã reset mật khẩu của CTV {$affiliate->name} về 123456789!");
    }

    // ─── Hóa đơn ─────────────────────────────────────────────────────────────
    public function invoices(Request $request)
    {
        $query = AffiliateInvoice::with('affiliate');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices      = $query->latest()->paginate(15);
        $pendingCount  = AffiliateInvoice::where('status', 'pending')->count();

        return view('admin.affiliates.invoices', compact('invoices', 'pendingCount'));
    }

    public function approveInvoice(Request $request, AffiliateInvoice $invoice)
    {
        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Hóa đơn này đã được xử lý rồi.');
        }

        $invoice->update([
            'status'       => 'approved',
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        // Add commission to affiliate balance
        $invoice->affiliate()->increment('balance', $invoice->commission);

        // Notify admin log
        $affiliate = $invoice->affiliate;
        $msg  = "✅ <b>DUYỆT HÓA ĐƠN CTV</b>\n";
        $msg .= "• CTV: <b>{$affiliate->name}</b>\n";
        $msg .= "• Sản phẩm: <b>{$invoice->product_name}</b>\n";
        $msg .= "• Hoa hồng: <b>" . number_format($invoice->commission, 0, ',', '.') . "đ</b>\n";
        $msg .= "• Số dư mới: <b>" . number_format($affiliate->fresh()->balance, 0, ',', '.') . "đ</b>";
        TelegramHelper::sendMessage($msg);

        return back()->with('success', 'Đã duyệt hóa đơn và cộng hoa hồng ' . number_format($invoice->commission, 0, ',', '.') . 'đ!');
    }

    public function rejectInvoice(Request $request, AffiliateInvoice $invoice)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        $invoice->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã từ chối hóa đơn!');
    }

    // ─── Rút tiền ─────────────────────────────────────────────────────────────
    public function withdrawals(Request $request)
    {
        $query = AffiliateWithdrawal::with('affiliate');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals   = $query->latest()->paginate(15);
        $pendingCount  = AffiliateWithdrawal::where('status', 'pending')->count();

        return view('admin.affiliates.withdrawals', compact('withdrawals', 'pendingCount'));
    }

    public function approveWithdrawal(Request $request, AffiliateWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý rồi.');
        }

        $affiliate = $withdrawal->affiliate;

        if ($withdrawal->amount > $affiliate->balance) {
            return back()->with('error', 'Số dư CTV không đủ để duyệt yêu cầu rút tiền này.');
        }

        $withdrawal->update([
            'status'       => 'approved',
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        // Deduct from balance
        $affiliate->decrement('balance', $withdrawal->amount);

        $msg  = "✅ <b>PHÊ DUYỆT RÚT TIỀN CTV</b>\n";
        $msg .= "• CTV: <b>{$affiliate->name}</b>\n";
        $msg .= "• Số tiền: <b>" . number_format($withdrawal->amount, 0, ',', '.') . "đ</b>\n";
        $msg .= "• NH: <b>{$withdrawal->bank_name}</b> - STK: <b>{$withdrawal->bank_account_number}</b>\n";
        $msg .= "• Số dư còn lại: <b>" . number_format($affiliate->fresh()->balance, 0, ',', '.') . "đ</b>";
        TelegramHelper::sendMessage($msg);

        return back()->with('success', 'Đã duyệt rút tiền và trừ số dư thành công!');
    }

    public function rejectWithdrawal(Request $request, AffiliateWithdrawal $withdrawal)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        $withdrawal->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã từ chối yêu cầu rút tiền!');
    }
}
