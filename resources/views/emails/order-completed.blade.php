<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
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
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .header .success-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px 30px;
        }
        .alert-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
        }
        .order-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .order-info h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
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
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
        }
        .credentials-box h2 {
            font-size: 24px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .credential-item {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .credential-value {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .products {
            margin: 25px 0;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f7fafc;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .product-name {
            font-weight: 600;
            color: #2d3748;
        }
        .product-price {
            color: #667eea;
            font-weight: 700;
        }
        .warning-box {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin: 25px 0;
            text-align: center;
        }
        .warning-box strong {
            font-size: 20px;
            display: block;
            margin-bottom: 15px;
        }
        .contact-section {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-top: 30px;
        }
        .contact-section h3 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .contact-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .contact-btn {
            display: inline-block;
            background: white;
            color: #4facfe;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        .footer {
            background: #2d3748;
            color: white;
            text-align: center;
            padding: 30px;
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
                padding: 25px 20px;
            }
            .credential-value {
                font-size: 24px;
            }
            .contact-buttons {
                flex-direction: column;
            }
            .contact-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>ĐƠN HÀNG ĐÃ ĐƯỢC XÁC NHẬN!</h1>
            <p>Đơn hàng #{{ $order->id }} đã được admin xác nhận thành công</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Alert -->
            <div class="alert-success">
                ✅ Đơn hàng của bạn đã được admin xác nhận thành công! Vui lòng liên hệ admin để nhận tài khoản demo.
            </div>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <h2>🔐 THÔNG TIN TÀI KHOẢN DEMO</h2>
                <div class="credential-item">
                    <div class="credential-label">TÊN ĐĂNG NHẬP</div>
                    <div class="credential-value">{{ $demoUsername }}</div>
                </div>
                <div class="credential-item">
                    <div class="credential-label">MẬT KHẨU</div>
                    <div class="credential-value">{{ $demoPassword }}</div>
                </div>
                <p style="margin-top: 20px; text-align: center; font-size: 14px; opacity: 0.9;">
                    ⚠️ Thông tin này đã được gửi riêng cho bạn. Vui lòng giữ bí mật mật khẩu.
                </p>
            </div>

            <!-- Order Info -->
            <div class="order-info">
                <h3>📦 Thông Tin Đơn Hàng</h3>
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value">#{{ $order->id }}</span>
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

            <!-- Products -->
            @if($order->orderItems->count() > 0)
            <div class="products">
                <h3 style="color: #2d3748; margin-bottom: 15px;">🛒 Sản phẩm đã mua:</h3>
                @foreach($order->orderItems as $item)
                <div class="product-item">
                    <div>
                        <div class="product-name">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                        <small style="color: #718096;">Số lượng: {{ $item->quantity }}</small>
                    </div>
                    <div class="product-price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Warning Box -->
            <div class="warning-box">
                <strong>⏱️ ADMIN ĐÃ XÁC NHẬN ĐƠN HÀNG!</strong>
                <p style="margin-top: 15px; font-size: 16px; line-height: 1.8;">
                    Cảm ơn bạn đã đặt hàng! Đơn hàng của bạn đã được admin xác nhận.
                    <br><br>
                    <strong>Bước tiếp theo:</strong> Vui lòng liên hệ ngay với admin để nhận tài khoản demo và bắt đầu sử dụng dịch vụ.
                </p>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h3>💬 LIÊN HỆ ADMIN ĐỂ NHẬN THÔNG TIN CHI TIẾT</h3>
                <p style="font-size: 16px; line-height: 1.8; margin-bottom: 10px;">
                    Vui lòng liên hệ với Admin để nhận thông tin đầy đủ về tài khoản, 
                    hướng dẫn sử dụng và các tính năng của sản phẩm.
                </p>
                <p style="font-size: 14px; opacity: 0.9;">
                    Admin sẽ gửi cho bạn link đăng nhập, tài liệu hướng dẫn và hỗ trợ kỹ thuật.
                </p>
                <div class="contact-buttons">
                    <a href="mailto:tranthanhtuanfix@gmail.com?subject=Đơn hàng #{{ $order->id }} - Yêu cầu thông tin tài khoản" class="contact-btn">
                        📧 Email Admin
                    </a>
                    <a href="https://t.me/tuanthanh0952" target="_blank" class="contact-btn">
                        💬 Telegram
                    </a>
                    <a href="https://zalo.me/0708910952" target="_blank" class="contact-btn">
                        📱 Zalo
                    </a>
                </div>
            </div>

            <!-- Thank You -->
            <div style="text-align: center; margin-top: 30px; padding: 20px; background: #f7fafc; border-radius: 15px;">
                <p style="font-size: 18px; color: #2d3748; font-weight: 600; margin-bottom: 10px;">
                    🙏 Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!
                </p>
                <p style="color: #718096;">
                    Chúng tôi cam kết mang đến trải nghiệm tốt nhất cho khách hàng.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-size: 16px; margin-bottom: 15px;"><strong>DungThu.com</strong></p>
            <p>📧 Email: tranthanhtuanfix@gmail.com</p>
            <p>🌐 Website: https://dungthu.com</p>
            <p style="margin-top: 20px; font-size: 12px;">
                Email này được gửi tự động, vui lòng không reply trực tiếp.
            </p>
            <p style="font-size: 12px;">
                Nếu cần hỗ trợ, vui lòng liên hệ qua các kênh trên.
            </p>
        </div>
    </div>
</body>
</html>
