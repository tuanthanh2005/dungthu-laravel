<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;

class MiniGameController extends Controller
{
    /**
     * Show the lucky wheel page.
     */
    public function index()
    {
        $user = auth()->user();
        $coupons = $user->coupons()->latest()->get();

        return view('minigame.index', compact('user', 'coupons'));
    }

    /**
     * Spin the lucky wheel.
     */
    public function spin(Request $request)
    {
        $user = auth()->user();

        if ($user->spin_tickets < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có đủ vé quay!'
            ], 400);
        }

        // Decrement ticket count
        $user->decrement('spin_tickets');

        // Random logic: 5% for 50k, 19% for each of the other 5 options.
        $rand = mt_rand(1, 100);

        if ($rand <= 5) {
            $value = 50000;
            $label = 'Thẻ giảm giá 50k';
            $prizeIndex = 3;
        } else if ($rand <= 24) { // 5 + 19
            $value = 10000;
            $label = 'Thẻ giảm giá 10k';
            $prizeIndex = 0;
        } else if ($rand <= 43) { // 24 + 19
            $value = 5000;
            $label = 'Thẻ giảm giá 5k';
            $prizeIndex = 1;
        } else if ($rand <= 62) { // 43 + 19
            $value = 2000;
            $label = 'Thẻ giảm giá 2k';
            $prizeIndex = 2;
        } else if ($rand <= 81) { // 62 + 19
            $value = 25000;
            $label = 'Thẻ giảm giá 25k';
            $prizeIndex = 4;
        } else {
            $value = 15000;
            $label = 'Thẻ giảm giá 15k';
            $prizeIndex = 5;
        }

        // Generate a unique coupon code (e.g. LUCKY10K-ABCDEF)
        $valStr = ($value / 1000) . 'K';
        $code = 'LUCKY' . $valStr . '-' . strtoupper(Str::random(6));

        // Create coupon in DB
        $coupon = Coupon::create([
            'code' => $code,
            'value' => $value,
            'user_id' => $user->id,
            'is_used' => false,
        ]);

        return response()->json([
            'success' => true,
            'prize' => [
                'value' => $value,
                'label' => $label,
                'code' => $code,
                'index' => $prizeIndex,
            ],
            'spin_tickets' => $user->spin_tickets,
            'message' => 'Chúc mừng bạn đã trúng ' . $label . '!',
        ]);
    }
}
