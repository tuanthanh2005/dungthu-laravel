<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin bàn giao đơn hàng – DungThu.com</title>
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

        /* Greeting */
        .greeting {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 22px;
        }
        .greeting strong {
            color: #667eea;
        }

        /* Delivery Card */
        .delivery-card {
            background: #ffffff;
            border: 2px solid #667eea;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 26px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.12);
            position: relative;
            overflow: hidden;
        }
        .delivery-card-stripe {
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #667eea, #764ba2);
        }
        .delivery-title {
            margin-top: 0;
            margin-bottom: 18px;
            color: #667eea;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .delivery-item {
            background: #f7fafc;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 12px;
            border: 1px solid #edf2f7;
        }
        .delivery-label {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .delivery-value {
            font-family: 'Consolas', 'Courier New', Courier, monospace;
            font-size: 15px;
            color: #2d3748;
            font-weight: 700;
            word-break: break-all;
            white-space: pre-wrap;
        }
        .delivery-value.key-text {
            color: #d53f8c;
            letter-spacing: 0.5px;
        }
        .delivery-note-box {
            background: #fffaf0;
            border-radius: 10px;
            padding: 14px 16px;
            border: 1px solid #feebc8;
        }
        .delivery-note-label {
            font-size: 11px;
            color: #dd6b20;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .delivery-note-content {
            font-size: 14px;
            color: #4a5568;
            white-space: pre-wrap;
            line-height: 1.6;
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
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e8ebff;
            font-size: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #718096; font-weight: 500; }
        .info-value { color: #2d3748; font-weight: 700; text-align: right; }
        .info-value.amount {
            font-size: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #667eea;
        }
        .badge-completed {
            display: inline-block;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 50px;
        }

        /* Products list */
        .products-section {
            margin-bottom: 22px;
        }
        .products-section h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 12px;
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
            font-size: 13.5px;
        }
        .product-name { color: #2d3748; font-weight: 600; }
        .product-meta { color: #718096; font-size: 12px; margin-top: 2px; }
        .product-price { color: #667eea; font-weight: 700; font-size: 14px; }

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
            font-size: 13px;
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
            font-size: 12px;
        }
        .footer .logo {
            font-size: 15px;
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
        }
    </style>
</head>
<body>
<div class="email-container">

    <!-- HEADER -->
    <div class="header">
        <div class="brand">✨ dungthu.com</div>
        <span class="check-icon">🔑</span>
        <h1>Thông tin bàn giao đơn hàng</h1>
        <p class="subtitle">Đơn #{{ $order->id }} – {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- Greeting -->
        <p class="greeting">
            Xin chào <strong>{{ $order->customer_name ?? 'bạn' }}</strong>,<br>
            Cảm ơn bạn đã mua sắm tại <strong>DungThu.com</strong>! Dưới đây là thông tin chi tiết về tài khoản / KEY kích hoạt của bạn.
        </p>

        <!-- DELIVERY CREDENTIALS CARD -->
        <div class="delivery-card">
            <div class="delivery-card-stripe"></div>
            <h3 class="delivery-title">🔑 Thông tin bàn giao</h3>
            
            @if($order->delivery_account)
            <div class="delivery-item">
                <div class="delivery-label">Tài khoản / Account</div>
                <div class="delivery-value">{!! nl2br(e($order->delivery_account)) !!}</div>
            </div>
            @endif

            @if($order->delivery_key)
            <div class="delivery-item">
                <div class="delivery-label">KEY / Mã kích hoạt</div>
                <div class="delivery-value key-text">{{ $order->delivery_key }}</div>
            </div>
            @endif

            @if($order->delivery_note)
            <div class="delivery-note-box">
                <div class="delivery-note-label">Thông báo & Hướng dẫn</div>
                <div class="delivery-note-content">{!! nl2br(e($order->delivery_note)) !!}</div>
            </div>
            @endif
        </div>

        <!-- Order info -->
        <div class="order-card">
            <h3>📦 Thông tin hóa đơn</h3>
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
                    <span class="badge-completed">✅ Đã hoàn thành</span>
                </span>
            </div>
            @if(isset($order->discount_amount) && $order->discount_amount > 0)
            <div class="info-row">
                <span class="info-label">Giảm giá ({{ $order->coupon_code }})</span>
                <span class="info-value text-danger" style="color: #dc3545; font-weight: bold;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
            </div>
            @endif
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
            <a href="https://dungthu.com/user/orders" class="cta-btn">
                Xem Đơn Hàng Của Bạn
            </a>
        </div>

        <!-- Support -->
        <div class="support-box">
            Cần hỗ trợ thêm? Liên hệ qua:
            <br>
            Email: <a href="mailto:{{ env('SUPPORT_EMAIL', 'tranthanhtuanfix@gmail.com') }}">{{ env('SUPPORT_EMAIL', 'tranthanhtuanfix@gmail.com') }}</a>
            hoặc Zalo: <a href="https://zalo.me/{{ env('SUPPORT_ZALO', '0708910952') }}">{{ env('SUPPORT_ZALO', '0708910952') }}</a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="logo">🛍 DungThu.com</div>
        <p>Email tự động – vui lòng không reply thư này.</p>
        <p>© {{ date('Y') }} DungThu.com – Mua sắm thông minh, tiết kiệm tối đa.</p>
    </div>

</div>
</body>
</html>
