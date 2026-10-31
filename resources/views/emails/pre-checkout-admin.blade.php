<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khách hàng điền thông tin thanh toán – DungThu.com</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6fb;
            padding: 30px 16px;
            line-height: 1.6;
            color: #2d3748;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1b9aaa 0%, #06d6a0 100%);
            color: #ffffff;
            padding: 30px 25px;
            text-align: center;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .badge-code {
            display: inline-block;
            background: rgba(255, 255, 255, 0.25);
            padding: 4px 12px;
            border-radius: 20px;
            font-family: monospace;
            font-size: 15px;
            font-weight: bold;
            margin-top: 5px;
        }
        .content {
            padding: 25px;
        }
        .alert-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13.5px;
            color: #92400e;
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #f1f5f9;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 8px 10px;
            font-size: 14px;
            vertical-align: top;
            border-bottom: 1px solid #f8fafc;
        }
        .info-table td.label {
            width: 38%;
            color: #64748b;
            font-weight: 600;
        }
        .info-table td.val {
            color: #0f172a;
            font-weight: 600;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13.5px;
        }
        .item-table th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 10px;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .item-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .total-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px 20px;
            margin-top: 10px;
            margin-bottom: 25px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .total-final {
            display: flex;
            justify-content: space-between;
            padding-top: 8px;
            border-top: 2px dashed #cbd5e1;
            font-size: 17px;
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            background: #f8fafc;
            text-align: center;
            padding: 15px;
            font-size: 12.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>⚡ KHÁCH HÀNG VỪA ĐIỀN FORM BƯỚC 1</h1>
            <p>Khách hàng vừa bấm "Tiến hành thanh toán" &amp; chuyển sang quét mã QR</p>
            <div class="badge-code">{{ $data['order_code'] ?? 'DT-ORDER' }}</div>
        </div>

        <div class="content">
            <div class="alert-box">
                ⚠️ <strong>Lưu ý Admin:</strong> Đây là thông tin khách hàng điền trước khi quét QR thanh toán. Dữ liệu này được lưu trước để tránh việc khách tắt trình duyệt hoặc bị mất liên hệ!
            </div>

            <div class="section-title">👤 Thông tin khách hàng</div>
            <table class="info-table">
                <tr>
                    <td class="label">Họ và tên:</td>
                    <td class="val">{{ $data['customer_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Số điện thoại:</td>
                    <td class="val"><a href="tel:{{ $data['customer_phone'] ?? '' }}" style="color: #2563eb; text-decoration: none;">{{ $data['customer_phone'] ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <td class="label">Email nhận mã:</td>
                    <td class="val"><a href="mailto:{{ $data['customer_email'] ?? '' }}" style="color: #2563eb; text-decoration: none;">{{ $data['customer_email'] ?? 'N/A' }}</a></td>
                </tr>
                @if(!empty($data['upgrade_email_or_note']))
                <tr>
                    <td class="label">Email nâng cấp / Ghi chú:</td>
                    <td class="val" style="color: #d97706;">{{ $data['upgrade_email_or_note'] }}</td>
                </tr>
                @endif
                @if(!empty($data['customer_address']))
                <tr>
                    <td class="label">Địa chỉ nhận hàng:</td>
                    <td class="val">{{ $data['customer_address'] }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Kênh liên hệ:</td>
                    <td class="val">
                        @if(!empty($data['use_boxchat']))
                            <span style="color: #0284c7; font-weight: bold;">💬 Tự liên hệ qua Boxchat</span>
                        @endif
                        @if(!empty($data['customer_zalo']))
                            <div>Zalo: <strong>{{ $data['customer_zalo'] }}</strong></div>
                        @endif
                        @if(!empty($data['customer_facebook']))
                            <div>Facebook: <a href="{{ $data['customer_facebook'] }}" target="_blank" style="color: #2563eb;">{{ $data['customer_facebook'] }}</a></div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Thời gian:</td>
                    <td class="val">{{ $data['time'] ?? now()->toDateTimeString() }}</td>
                </tr>
                <tr>
                    <td class="label">Địa chỉ IP:</td>
                    <td class="val" style="font-family: monospace;">{{ $data['ip'] ?? 'N/A' }}</td>
                </tr>
            </table>

            <div class="section-title">🛒 Sản phẩm đã chọn</div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['items'] ?? [] as $item)
                    <tr>
                        <td><strong>{{ $item['name'] ?? '' }}</strong></td>
                        <td style="text-align: center;">x{{ $item['quantity'] ?? 1 }}</td>
                        <td style="text-align: right;">{{ $item['price_formatted'] ?? '' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ $item['total_formatted'] ?? '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8;">Không có sản phẩm nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="total-box">
                @if(!empty($data['discount_formatted']))
                <div class="total-row" style="color: #16a34a;">
                    <span>Giảm giá ({{ $data['coupon_code'] ?? 'Mã coupon' }}):</span>
                    <strong>-{{ $data['discount_formatted'] }}</strong>
                </div>
                @endif
                <div class="total-final">
                    <span>Tổng tiền cần thanh toán:</span>
                    <span>{{ $data['total_formatted'] ?? '0đ' }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            Email tự động từ hệ thống thanh toán DungThu.com • Quản trị viên: tranthanhtuanfix@gmail.com
        </div>
    </div>
</body>
</html>
