<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VoucherGiftedMail;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons for sieusuperadmin.
     */
    public function index(Request $request)
    {
        // Filter base query to exclude coupons assigned to admin roles
        $baseQuery = Coupon::where(function ($q) {
            $q->whereNull('user_id')
              ->orWhereHas('user', function ($uq) {
                  $uq->whereNotIn('role', ['sieusuperadmin', 'superadmin_1', 'admin']);
              });
        });

        $query = (clone $baseQuery)->with(['user', 'order'])->latest();

        // Search filter (code or assigned user name/email)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->where('is_used', true);
            } elseif ($request->status === 'unused') {
                $query->where('is_used', false);
            } elseif ($request->status === 'assigned') {
                $query->whereNotNull('user_id');
            } elseif ($request->status === 'unassigned') {
                $query->whereNull('user_id');
            }
        }

        $coupons = $query->paginate(15)->withQueryString();

        // Stats summary (filtered to exclude admin coupons)
        $totalCoupons = (clone $baseQuery)->count();
        $unusedCoupons = (clone $baseQuery)->where('is_used', false)->count();
        $usedCoupons = (clone $baseQuery)->where('is_used', true)->count();
        $totalValue = (clone $baseQuery)->sum('value');

        // Top customers filter / list (Users with most completed orders, excluding Admin roles)
        $topCustomers = User::whereNotIn('role', ['sieusuperadmin', 'superadmin_1', 'admin'])
        ->whereHas('orders', function ($q) {
            $q->where('status', 'completed');
        })
        ->withCount(['orders' => function ($q) {
            $q->where('status', 'completed');
        }])
        ->withSum(['orders' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_amount')
        ->orderByDesc('orders_count')
        ->orderByDesc('orders_sum_total_amount')
        ->take(15)
        ->get();

        return view('admin.coupons.index', compact(
            'coupons',
            'totalCoupons',
            'unusedCoupons',
            'usedCoupons',
            'totalValue',
            'topCustomers'
        ));
    }

    /**
     * Store a newly created coupon.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:50|unique:coupons,code',
            'value' => 'required|numeric|min:1000',
            'user_id' => 'nullable|exists:users,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $quantity = (int) ($request->quantity ?? 1);
        $value = (float) $request->value;
        $userId = $request->filled('user_id') ? (int) $request->user_id : null;

        $createdCount = 0;
        $createdCoupons = [];

        \Illuminate\Support\Facades\DB::transaction(function () use ($quantity, $value, $userId, $request, &$createdCount, &$createdCoupons) {
            for ($i = 0; $i < $quantity; $i++) {
                if ($quantity === 1 && $request->filled('code')) {
                    $code = strtoupper(trim($request->code));
                } else {
                    $code = 'VOUCHER-' . strtoupper(Str::random(6));
                    while (Coupon::where('code', $code)->exists()) {
                        $code = 'VOUCHER-' . strtoupper(Str::random(6));
                    }
                }

                $coupon = Coupon::create([
                    'code' => $code,
                    'value' => $value,
                    'user_id' => $userId,
                    'is_used' => false,
                ]);

                $createdCoupons[] = $coupon;
                $createdCount++;
            }
        });

        // Gửi email thông báo tặng Voucher cho người dùng
        if ($userId && !empty($createdCoupons)) {
            try {
                $user = User::find($userId);
                if ($user && $user->email) {
                    foreach ($createdCoupons as $c) {
                        Mail::to($user->email)->send(new VoucherGiftedMail($user, $c));
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Lỗi gửi email voucher cho {$user->email}: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', "Đã tạo thành công {$createdCount} mã voucher!");
    }

    /**
     * Assign or unassign user for a coupon.
     */
    public function assignUser(Request $request, Coupon $coupon)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $userId = $request->filled('user_id') ? (int) $request->user_id : null;

        $coupon->update([
            'user_id' => $userId,
        ]);

        // Gửi email thông báo nếu gán cho 1 người dùng cụ thể
        if ($userId) {
            try {
                $user = User::find($userId);
                if ($user && $user->email) {
                    Mail::to($user->email)->send(new VoucherGiftedMail($user, $coupon));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Lỗi gửi email gán voucher cho {$user->email}: " . $e->getMessage());
            }
        }

        $userName = $userId ? User::find($userId)?->name : 'Áp dụng tất cả người dùng';

        return redirect()->back()->with('success', "Đã gán mã {$coupon->code} cho: {$userName}");
    }

    /**
     * Delete a coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();

        return redirect()->back()->with('success', "Đã xóa mã voucher {$code}!");
    }

    /**
     * Live search users for AJAX dropdown (excludes admin accounts).
     */
    public function searchUsers(Request $request)
    {
        $q = trim($request->get('q', ''));

        $query = User::whereNotIn('role', ['sieusuperadmin', 'superadmin_1', 'admin']);

        if (empty($q)) {
            $users = $query->latest()->limit(15)->get(['id', 'name', 'email', 'phone', 'role']);
        } else {
            $users = $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone', 'role']);
        }

        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'role' => $user->role,
                'display' => "{$user->name} ({$user->email}" . ($user->phone ? " - {$user->phone}" : "") . ")",
            ];
        }));
    }
}
