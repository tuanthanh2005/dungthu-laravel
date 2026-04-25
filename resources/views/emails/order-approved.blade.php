<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng đã được duyệt – DungThu.com</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4ff;
            padding: 30px 16px;
            line-height: 1.7;
            color: #2d3748;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
        }

        /* ── HEADER ── */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px 32px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            display: block;
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 28px;
            background: #ffffff;
            border-radius: 100% 100% 0 0;
        }
        .header .brand {
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .header .check-icon {
            font-size: 64px;
            margin-bottom: 14px;
            display: block;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        }
        .header h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .header .subtitle {
            font-size: 15px;
            opacity: 0.88;
        }

        /* ── CONTENT ── */
        .content {
            padding: 32px 30px 28px;
        }

        /* Thank-you banner */
        .thank-you-banner {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border-radius: 18px;
            padding: 22px 20px;
            text-align: center;
            margin-bottom: 26px;
            box-shadow: 0 8px 24px rgba(253, 160, 133, 0.35);
        }
        .thank-you-banner .site-name {
            font-size: 13px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.85);
            font-weight: 700;
            margin-bottom: 10px;
        }
        .thank-you-banner .message {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            line-height: 1.35;
        }
        .thank-you-banner .emoji-row {
            font-size: 26px;
            margin-top: 10px;
        }

        /* Greeting */
        .greeting {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 22px;
        }
        .greeting strong {
            color: #667eea;
        }

        /* Order info card */
        .order-card {
            background: #f7f8ff;
            border: 1.5px solid #e8ebff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 22px;
        }
        .order-card h3 {
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e8ebff;
            font-size: 14.5px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #718096; font-weight: 500; }
        .info-value { color: #2d3748; font-weight: 700; text-align: right; }
        .info-value.amount {
            font-size: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .badge-completed {
            display: inline-block;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 50px;
        }

        /* Products list */
        .products-section {
            margin-bottom: 22px;
        }
        .products-section h3 {
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 14px;
            background: #fdf8ff;
            border-radius: 12px;
            margin-bottom: 8px;
            border-left: 4px solid #764ba2;
            font-size: 14px;
        }
        .product-name { color: #2d3748; font-weight: 600; }
        .product-meta { color: #718096; font-size: 12.5px; margin-top: 2px; }
        .product-price { color: #667eea; font-weight: 700; font-size: 14.5px; }

        /* CTA button */
        .cta-wrapper {
            text-align: center;
            margin: 24px 0 20px;
        }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 50px;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.45);
        }

        /* Support */
        .support-box {
            background: #f0f4ff;
            border-radius: 14px;
            padding: 16px 18px;
            font-size: 13.5px;
            color: #4a5568;
            text-align: center;
            margin-bottom: 6px;
        }
        .support-box a { color: #667eea; font-weight: 700; text-decoration: none; }

        /* Footer */
        .footer {
            background: #1a202c;
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 22px 20px;
            font-size: 12.5px;
        }
        .footer .logo {
            font-size: 16px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .footer p { margin: 3px 0; }

        @media only screen and (max-width: 600px) {
            .email-container { border-radius: 0; }
            .header, .content { padding-left: 18px; padding-right: 18px; }
            .header h1 { font-size: 21px; }
            .thank-you-banner .message { font-size: 18px; }
        }
    </style>
</head>
<body>
<div class="email-container">

    <!-- HEADER -->
    <div class="header">
        <div class="brand">✨ dungthu.com</div>
        <span class="check-icon">🎉</span>
        <h1>Đơn hàng đã được duyệt!</h1>
        <p class="subtitle">Đơn #{{ $order->id }} – {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- Thank-you banner -->
        <div class="thank-you-banner">
            <div class="site-name">🛍 DUNGTHU.COM</div>
            <div class="message">Cảm ơn bạn đã đặt hàng bên mình nhé !!!</div>
            <div class="emoji-row">💜 🙏 💜</div>
        </div>

        <!-- Greeting -->
        <p class="greeting">
            Xin chào <strong>{{ $order->customer_name ?? 'bạn' }}</strong>,<br>
            Đơn hàng của bạn đã được admin <strong>xác nhận &amp; duyệt thành công</strong>.
            Cảm ơn bạn đã tin tưởng và ủng hộ <strong>DungThu.com</strong> 💜
        </p>

        <!-- Order info -->
        <div class="order-card">
            <h3>📦 Thông tin đơn hàng</h3>
            <div class="info-row">
                <span class="info-label">Mã đơn hàng</span>
                <span class="info-value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày đặt</span>
                <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái</span>
                <span class="info-value">
                    <span class="badge-completed">✅ Đã duyệt</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Tổng tiền</span>
                <span class="info-value amount">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <!-- Products -->
        @if($order->orderItems && $order->orderItems->count() > 0)
        <div class="products-section">
            <h3>🛒 Sản phẩm đã đặt</h3>
            @foreach($order->orderItems as $item)
            <div class="product-item">
                <div>
                    <div class="product-name">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                    <div class="product-meta">Số lượng: {{ $item->quantity }}</div>
                </div>
                <div class="product-price">{{ number_format($item->price, 0, ',', '.') }}đ</div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- CTA -->
        <div class="cta-wrapper">
            <a href="{{ config('app.url') }}/user/orders" class="cta-btn">
                📋 Xem chi tiết đơn hàng
            </a>
        </div>

        <!-- Support -->
        <div class="support-box">
            Cần hỗ trợ? Liên hệ ngay:
            <a href="mailto:{{ env('SUPPORT_EMAIL', 'support@dungthu.com') }}">{{ env('SUPPORT_EMAIL', 'support@dungthu.com') }}</a>
            hoặc Zalo: <a href="https://zalo.me/{{ env('SUPPORT_ZALO', '0708910952') }}">{{ env('SUPPORT_ZALO', '0708910952') }}</a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="logo">🛍 DungThu.com</div>
        <p>Email tự động – vui lòng không reply.</p>
        <p>© {{ date('Y') }} DungThu.com – Mua sắm thông minh, tiết kiệm tối đa.</p>
    </div>

</div>
</body>
</html>
