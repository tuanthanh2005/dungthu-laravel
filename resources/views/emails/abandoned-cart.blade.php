<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial, sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:12px;padding:24px;border:1px solid #eee;">
            <h2 style="margin:0 0 8px 0;font-size:20px;color:#111;">Giỏ hàng của bạn đang chờ bạn</h2>
            <p style="margin:0 0 16px 0;color:#555;font-size:14px;">
                Bạn vẫn còn sản phẩm trong giỏ hàng. Nếu cần hỗ trợ, hãy nhắn tin cho admin.
            </p>

            <div style="margin:16px 0;">
                <strong style="font-size:14px;color:#111;">Sản phẩm đã thêm:</strong>
                <ul style="padding-left:18px;color:#333;font-size:14px;margin:8px 0;">
                    @foreach($cart->cart_data as $item)
                        <li>{{ $item['name'] ?? 'Sản phẩm' }} x {{ $item['quantity'] ?? 1 }}</li>
                    @endforeach
                </ul>
                <div style="font-size:14px;color:#111;">
                    Tổng: <strong>{{ number_format((float) $cart->total_amount, 0, ',', '.') }}đ</strong>
                </div>
            </div>

            <a href="{{ $cartUrl }}" style="display:inline-block;background:#4f46e5;color:#fff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:bold;font-size:14px;">
                Xem giỏ hàng
            </a>
        </div>
        <p style="margin:16px 0 0 0;font-size:12px;color:#888;">
            Nếu bạn đã mua hàng, vui lòng bỏ qua email này.
        </p>
    </div>
</body>
</html>
