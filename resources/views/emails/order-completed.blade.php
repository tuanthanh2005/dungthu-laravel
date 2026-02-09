<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng đã xác nhận</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 28px 24px;
            text-align: center;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .header .success-icon {
            font-size: 44px;
            margin-bottom: 12px;
        }
        .content {
            padding: 24px;
        }
        .alert-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 14px 16px;
            border-radius: 15px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
        }
        .order-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 18px;
            margin-bottom: 18px;
        }
        .order-info h3 {
            color: #667eea;
            margin-bottom: 14px;
            font-size: 18px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #718096;
            font-weight: 500;
        }
        .info-value {
            color: #2d3748;
            font-weight: 600;
            text-align: right;
        }
        .credentials-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin: 18px 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
        }
        .credentials-box h2 {
            font-size: 18px;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .credential-item {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        .credential-value {
            font-size: 22px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .contact {
            text-align: center;
            margin-top: 14px;
            color: #4a5568;
            font-size: 14px;
        }
        .contact a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }
        .footer {
            background: #2d3748;
            color: white;
            text-align: center;
            padding: 18px;
        }
        .footer p {
            margin: 5px 0;
            opacity: 0.8;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 20px 16px;
            }
            .credential-value {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>Đơn hàng đã được xác nhận</h1>
            <p>Đơn #{{ $publicOrderNumber ?? $order->id }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Alert -->
            <div class="alert-success">
                ✅ Đơn hàng đã hoàn tất. Thông tin tài khoản demo ở dưới.
            </div>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <h2>🔐 Tài khoản demo</h2>
                <div class="credential-item">
                    <div class="credential-label">TÊN ĐĂNG NHẬP</div>
                    <div class="credential-value">{{ $demoUsername }}</div>
                </div>
                <div class="credential-item">
                    <div class="credential-label">MẬT KHẨU</div>
                    <div class="credential-value">{{ $demoPassword }}</div>
                </div>
                <p style="margin-top: 12px; text-align: center; font-size: 13px; opacity: 0.9;">
                    Vui lòng giữ bí mật mật khẩu.
                </p>
            </div>

            <!-- Order Info -->
            <div class="order-info">
                <h3>📦 Thông Tin Đơn Hàng</h3>
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value">#{{ $publicOrderNumber ?? $order->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tổng tiền:</span>
                    <span class="info-value" style="color: #667eea; font-size: 20px;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                </div>
            </div>

            @if(!empty($downloadItems))
                <div class="order-info">
                    <h3>⬇️ Link Tải File</h3>
                    @foreach($downloadItems as $item)
                        <div class="info-row">
                            <span class="info-label">{{ $item['name'] }}</span>
                            <span class="info-value">
                                <a href="{{ $item['url'] }}" style="color: #667eea; font-weight: 700;">Tải file</a>
                            </span>
                        </div>
                    @endforeach
                    <p style="margin-top: 10px; font-size: 13px; color: #4a5568;">
                        Lưu ý: Bạn cần đăng nhập tài khoản đã mua để tải file.
                    </p>
                </div>
            @endif

            <div class="contact">
                Cần hỗ trợ? Liên hệ: <a href="mailto:tranthanhtuanfix@gmail.com">tranthanhtuanfix@gmail.com</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-size: 14px;"><strong>DungThu.com</strong></p>
            <p style="font-size: 12px;">Email tự động – vui lòng không reply.</p>
        </div>
    </div>
</body>
</html>
