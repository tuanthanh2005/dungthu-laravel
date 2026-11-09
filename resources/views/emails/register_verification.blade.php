<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác thực đăng ký tài khoản - DungThu.com</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6fa;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-container {
            max-width: 560px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #eef2f6;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            opacity: 0.95;
        }
        .email-body {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .message-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }
        .otp-card {
            background: #f8faff;
            border: 2px dashed #667eea;
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 25px;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 34px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', Courier, monospace;
            background: #ffffff;
            display: inline-block;
            padding: 10px 24px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        }
        .otp-expiry {
            font-size: 13px;
            color: #dc2626;
            font-weight: 600;
            margin-top: 10px;
        }
        .instruction-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 25px;
            font-size: 13px;
            color: #334155;
            line-height: 1.5;
        }
        .instruction-box ol {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }
        .instruction-box li {
            margin-bottom: 4px;
        }
        .warning-text {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }
        .email-footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #edf2f7;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>DungThu.com</h1>
            <p>Xác thực địa chỉ Email đăng ký tài khoản</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Xin chào bạn,</div>
            <p class="message-text">
                Cảm ơn bạn đã lựa chọn đăng ký tài khoản tại <strong>DungThu.com</strong>. Để hoàn tất quy trình đăng ký và xác minh bạn là chủ sở hữu hộp thư Gmail này, vui lòng sử dụng mã xác thực bên dưới:
            </p>

            <!-- OTP Card -->
            <div class="otp-card">
                <div class="otp-label">MÃ XÁC THỰC CỦA BẠN</div>
                <div class="otp-code">{{ $code }}</div>
                <div class="otp-expiry">⏳ Mã có hiệu lực trong vòng 10 phút</div>
            </div>

            <!-- Instruction -->
            <div class="instruction-box">
                <strong>Hướng dẫn:</strong>
                <ol>
                    <li>Sao chép (copy) mã 6 chữ số ở trên: <strong>{{ $code }}</strong></li>
                    <li>Quay lại màn hình Đăng Ký và dán mã vào ô <em>"Mã xác thực Email"</em>.</li>
                    <li>Nhập mã bảo vệ Captcha và bấm <strong>"Tạo Tài Khoản"</strong> để hoàn tất.</li>
                </ol>
            </div>

            <!-- Warning -->
            <div class="warning-text">
                ⚠️ <strong>Lưu ý bảo mật:</strong> Không chia sẻ mã xác thực này cho bất kỳ ai, kể cả nhân viên hỗ trợ của chúng tôi. Nếu bạn không yêu cầu đăng ký tài khoản này, vui lòng bỏ qua email.
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            © {{ date('Y') }} DungThu.com - Nền tảng chia sẻ tài khoản & công nghệ số hàng đầu.<br>
            Email được gửi tự động từ hệ thống bảo mật chống gian lận.
        </div>
    </div>
</body>
</html>
