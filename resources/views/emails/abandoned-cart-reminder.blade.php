<!DOCTYPE html>
<html>
<head>
    <title>Đừng quên giỏ hàng của bạn!</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #667eea; }
        .message-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px; border-left: 4px solid #667eea; }
        .cart-items { border: 1px solid #eee; border-radius: 10px; padding: 20px; margin-bottom: 30px; }
        .item { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .total { font-weight: bold; font-size: 18px; text-align: right; margin-top: 20px; color: #e53e3e; }
        .btn-wrapper { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 30px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">DungThu.com</div>
        <h2>Xin chào {{ $cart->user->name ?? 'bạn' }},</h2>
    </div>

    <div class="message-box">
        {!! nl2br(e($customMessage)) !!}
    </div>

    <div class="cart-items">
        <h3 style="margin-top: 0;">Chi tiết giỏ hàng của bạn:</h3>
        @foreach($cart->cart_data as $item)
            <div class="item">
                <div>
                    <strong>{{ $item['name'] ?? 'Sản phẩm' }}</strong><br>
                    <small>Số lượng: {{ $item['quantity'] ?? 1 }}</small>
                </div>
                <div>{{ number_format((float)($item['price'] ?? 0) * (float)($item['quantity'] ?? 1), 0, ',', '.') }}đ</div>
            </div>
        @endforeach
        
        <div class="total">
            Tổng cộng: {{ number_format((float)$cart->total_amount, 0, ',', '.') }}đ
        </div>
    </div>

    <div class="btn-wrapper">
        <a href="{{ url('/cart') }}" class="btn">Hoàn tất thanh toán ngay</a>
    </div>

    <div class="footer">
        <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ với chúng tôi qua thông tin trên website.</p>
        <p>&copy; {{ date('Y') }} DungThu.com. Tất cả các quyền được bảo lưu.</p>
    </div>
</body>
</html>
