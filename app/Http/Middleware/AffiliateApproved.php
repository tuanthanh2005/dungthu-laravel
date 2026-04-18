<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('affiliate')->check()) {
            return redirect()->route('affiliate.login')->with('error', 'Vui lòng đăng nhập cộng tác viên.');
        }

        $affiliate = Auth::guard('affiliate')->user();

        if ($affiliate->status === 'pending') {
            return redirect()->route('affiliate.pending');
        }

        if ($affiliate->status === 'rejected') {
            return redirect()->route('affiliate.rejected');
        }

        return $next($request);
    }
}
