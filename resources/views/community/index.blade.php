@extends('layouts.app')

@section('title', 'Bot Telegram - DungThu.com')

@push('styles')
    <style>
        .tele-hero {
            background: radial-gradient(circle at top, rgba(106, 17, 203, 0.12), transparent 45%),
                        linear-gradient(180deg, #f7f2ff 0%, #ffffff 100%);
            border-radius: 26px;
            padding: 56px 24px;
        }
        .tele-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: #f1e8ff;
            color: #5a1fb2;
            font-weight: 700;
            font-size: 13px;
        }
        .tele-card {
            border-radius: 22px;
            border: 1px solid rgba(90, 31, 178, 0.12);
            box-shadow: 0 16px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            background: #fff;
        }
        .tele-card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            padding: 22px 24px;
        }
        .tele-card-body {
            padding: 22px 24px 26px;
        }
        .tele-feature {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-size: 14px;
            color: #334155;
        }
        .tele-feature span {
            color: #10b981;
            font-weight: 800;
        }
        .tele-cta {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 22px;
            font-weight: 700;
        }
        .tele-cta-outline {
            border: 2px solid #6a11cb;
            color: #6a11cb;
            border-radius: 999px;
            padding: 12px 22px;
            font-weight: 700;
            background: #fff;
        }
        .tele-highlight {
            background: #f8fafc;
            border-radius: 20px;
            padding: 22px;
            border: 1px dashed rgba(0,0,0,0.08);
        }
    </style>
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    @php
        $cryptoProduct = $botProducts['bot-crypto-alert'] ?? null;
        $goldProduct = $botProducts['bot-gia-vang'] ?? null;
        $stockProduct = $botProducts['bot-chung-khoan'] ?? null;
    @endphp
    <div class="tele-hero mb-5 text-center">
        <div class="tele-badge mb-3">BÁN TOOL TELEGRAM</div>
        <h1 class="fw-bold">Bộ Bot Telegram Kiếm Tiền Tự Động</h1>
        <p class="text-muted mb-4">
            Full source code Python, hướng dẫn chi tiết A-Z, chạy được ngay. Phù hợp cho người mới lẫn marketer có cộng đồng.
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="https://t.me/specademy" class="tele-cta text-decoration-none">Liên hệ Telegram</a>
            <a href="mailto:tranthanhtuanfix@gmail.com" class="tele-cta-outline text-decoration-none">Gửi Email</a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-4">
            <div class="tele-card h-100">
                <div class="tele-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">Bot Crypto Alert</div>
                        <div>🪙</div>
                    </div>
                </div>
                <div class="tele-card-body">
                    <p class="text-muted mb-3">Theo dõi giá coin real-time, gửi cảnh báo và gợi ý affiliate tự động.</p>
                    <div class="fw-bold mb-3" style="font-size: 20px; color: #6a11cb;">
                        {{ $cryptoProduct ? $cryptoProduct->formatted_price : '499.000đ' }}
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <div class="tele-feature"><span>✓</span> Full source code Python</div>
                        <div class="tele-feature"><span>✓</span> Setup dễ, có README & video</div>
                        <div class="tele-feature"><span>✓</span> Support qua Telegram</div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($cryptoProduct)
                            <form method="POST" action="{{ route('cart.buy-now', $cryptoProduct->id) }}">
                                @csrf
                                <button type="submit" class="tele-cta">Mua ngay</button>
                            </form>
                        @endif
                        <a href="https://t.me/specademy" class="tele-cta-outline text-decoration-none d-inline-block">Nhận tư vấn</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="tele-card h-100">
                <div class="tele-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">Bot Giá Vàng</div>
                        <div>💰</div>
                    </div>
                </div>
                <div class="tele-card-body">
                    <p class="text-muted mb-3">Cập nhật giá vàng SJC/PNJ/DOJI, push thông báo tức thì.</p>
                    <div class="fw-bold mb-3" style="font-size: 20px; color: #6a11cb;">
                        {{ $goldProduct ? $goldProduct->formatted_price : '399.000đ' }}
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <div class="tele-feature"><span>✓</span> Bot chạy ổn định 24/7</div>
                        <div class="tele-feature"><span>✓</span> Dễ dàng tùy biến nội dung</div>
                        <div class="tele-feature"><span>✓</span> Hướng dẫn kiếm tiền</div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($goldProduct)
                            <form method="POST" action="{{ route('cart.buy-now', $goldProduct->id) }}">
                                @csrf
                                <button type="submit" class="tele-cta">Mua ngay</button>
                            </form>
                        @endif
                        <a href="https://t.me/specademy" class="tele-cta-outline text-decoration-none d-inline-block">Nhận tư vấn</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="tele-card h-100">
                <div class="tele-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">Bot Chứng Khoán</div>
                        <div>📈</div>
                    </div>
                </div>
                <div class="tele-card-body">
                    <p class="text-muted mb-3">Theo dõi giá cổ phiếu VN, tạo kênh cảnh báo cho cộng đồng.</p>
                    <div class="fw-bold mb-3" style="font-size: 20px; color: #6a11cb;">
                        {{ $stockProduct ? $stockProduct->formatted_price : '449.000đ' }}
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <div class="tele-feature"><span>✓</span> Full Python source</div>
                        <div class="tele-feature"><span>✓</span> Tối ưu cho group Telegram</div>
                        <div class="tele-feature"><span>✓</span> Support nhanh</div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($stockProduct)
                            <form method="POST" action="{{ route('cart.buy-now', $stockProduct->id) }}">
                                @csrf
                                <button type="submit" class="tele-cta">Mua ngay</button>
                            </form>
                        @endif
                        <a href="https://t.me/specademy" class="tele-cta-outline text-decoration-none d-inline-block">Nhận tư vấn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tele-highlight text-center">
        <h2 class="fw-bold mb-3">Bạn nhận được gì?</h2>
        <div class="row g-3">
            <div class="col-12 col-md-4">✓ Full source code Python</div>
            <div class="col-12 col-md-4">✓ Hướng dẫn cài đặt từ A-Z</div>
            <div class="col-12 col-md-4">✓ Support qua Telegram</div>
        </div>
    </div>
</div>
@endsection
