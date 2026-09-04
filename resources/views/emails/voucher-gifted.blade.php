<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Món quà Voucher dành riêng cho bạn</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-container {
            max-width: 580px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            padding: 35px 25px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .message-text {
            font-size: 14.5px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 25px;
        }
        .voucher-card {
            background: #fff1f2;
            border: 2px dashed #ff416c;
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .voucher-badge {
            display: inline-block;
            background: #ff416c;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        .voucher-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 26px;
            font-weight: 900;
            color: #e11d48;
            letter-spacing: 2px;
            margin: 8px 0;
        }
        .voucher-value {
            font-size: 18px;
            font-weight: 800;
            color: #9f1239;
            margin-top: 5px;
        }
        .voucher-note {
            font-size: 12px;
            color: #9f1239;
            opacity: 0.8;
            margin-top: 8px;
        }
        .cta-button {
            display: block;
            width: 220px;
            margin: 0 auto 25px auto;
            padding: 14px 20px;
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            text-align: center;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.35);
        }
        .email-footer {
            background: #f8fafc;
            padding: 20px 25px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12.5px;
            color: #64748b;
        }
        .email-footer a {
            color: #ff416c;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div style="font-size: 38px; margin-bottom: 5px;">🎁</div>
            <h1>QUÀ TẶNG VOUCHER ĐẶC BIỆT!</h1>
            <p>Tri ân khách hàng từ DungThu.com</p>
        </div>

        <div class="email-body">
            <div class="greeting">Xin chào {{ $user->name }}, 👋</div>
            <div class="message-text">
                DungThu.com xin gửi tặng bạn 1 mã Voucher ưu đãi giảm giá đặc biệt dành riêng cho tài khoản của bạn. Bạn có thể sử dụng mã này ngay khi mua sắm dịch vụ/sản phẩm trên website của chúng tôi!
            </div>

            <div class="voucher-card">
                <span class="voucher-badge">Mã Giảm Giá Của Bạn</span>
                <div class="voucher-code">{{ $coupon->code }}</div>
                <div class="voucher-value">Giảm {{ number_format($coupon->value, 0, ',', '.') }}đ</div>
                <div class="voucher-note">* Áp dụng 1 lần duy nhất cho tài khoản {{ $user->email }}</div>
            </div>

            <a href="{{ route('shop') }}" class="cta-button">SỬ DỤNG NGAY 🚀</a>

            <div class="message-text" style="font-size: 13px; text-align: center; color: #6b7280; margin-bottom: 0;">
                Mã giảm giá đã được lưu sẵn trong <strong>Hộp Quà (Kho Voucher)</strong> của bạn trên website.
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 0 0 6px 0;">Nếu cần trợ giúp, hãy truy cập <a href="{{ config('app.url') }}" target="_blank">DungThu.com</a> hoặc nhắn tin cho Admin 24/7.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} DungThu.com - Hệ thống dịch vụ tự động uy tín.</p>
        </div>
    </div>
</body>
</html>
