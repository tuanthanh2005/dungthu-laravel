<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('affiliate')->check()) {
            return redirect()->route('affiliate.login')->with('error', 'Vui lòng đăng nhập cộng tác viên.');
        }

        $affiliate = Auth::guard('affiliate')->user();

        // Cho phép xem trang "chờ duyệt" nếu pending
        if ($affiliate->status === 'pending' && !in_array($request->route()->getName(), ['affiliate.pending', 'affiliate.logout'])) {
            return redirect()->route('affiliate.pending');
        }

        if ($affiliate->status === 'rejected' && !in_array($request->route()->getName(), ['affiliate.rejected', 'affiliate.logout'])) {
            return redirect()->route('affiliate.rejected');
        }

        return $next($request);
    }
}
