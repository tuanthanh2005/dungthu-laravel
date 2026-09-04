<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons for sieusuperadmin.
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['user', 'order'])->latest();

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

        // Stats summary
        $totalCoupons = Coupon::count();
        $unusedCoupons = Coupon::where('is_used', false)->count();
        $usedCoupons = Coupon::where('is_used', true)->count();
        $totalValue = Coupon::sum('value');

        // Top customers filter / list (Users with most completed orders)
        $topCustomers = User::withCount(['orders' => function ($q) {
            $q->where('status', 'completed');
        }])
        ->withSum(['orders' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_amount')
        ->having('orders_count', '>', 0)
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

        for ($i = 0; $i < $quantity; $i++) {
            if ($quantity === 1 && $request->filled('code')) {
                $code = strtoupper(trim($request->code));
            } else {
                $code = 'VOUCHER-' . strtoupper(Str::random(6));
                // Ensure unique code
                while (Coupon::where('code', $code)->exists()) {
                    $code = 'VOUCHER-' . strtoupper(Str::random(6));
                }
            }

            Coupon::create([
                'code' => $code,
                'value' => $value,
                'user_id' => $userId,
                'is_used' => false,
            ]);

            $createdCount++;
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
     * Live search users for AJAX dropdown.
     */
    public function searchUsers(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (empty($q)) {
            $users = User::latest()->limit(15)->get(['id', 'name', 'email', 'phone', 'role']);
        } else {
            $users = User::where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
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
