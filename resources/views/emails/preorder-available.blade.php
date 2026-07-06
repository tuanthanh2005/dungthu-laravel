<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm bạn chờ đợi đã có hàng!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7fafc;
            padding: 20px;
            line-height: 1.6;
            color: #2d3748;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #edf2f7;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 24px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 15px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 24px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1a202c;
        }
        .intro-text {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 25px;
        }
        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            background: #f8fafc;
            display: table;
            width: 100%;
        }
        .product-image-cell {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            padding-right: 20px;
        }
        .product-image {
            width: 120px;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #edf2f7;
        }
        .product-info-cell {
            display: table-cell;
            vertical-align: middle;
        }
        .product-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .price-box {
            margin-bottom: 10px;
        }
        .sale-price {
            font-size: 20px;
            font-weight: 800;
            color: #e53e3e;
            margin-right: 8px;
        }
        .original-price {
            font-size: 14px;
            color: #a0aec0;
            text-decoration: line-through;
        }
        .normal-price {
            font-size: 20px;
            font-weight: 800;
            color: #2b6cb0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0 10px;
        }
        .btn {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        .footer {
            background: #2d3748;
            color: #a0aec0;
            text-align: center;
            padding: 24px;
            font-size: 13px;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer a {
            color: #cbd5e0;
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
                border: none;
            }
            .product-card {
                display: block;
            }
            .product-image-cell {
                display: block;
                width: 100%;
                text-align: center;
                padding-right: 0;
                padding-bottom: 15px;
            }
            .product-image {
                width: 150px;
            }
            .product-info-cell {
                display: block;
                width: 100%;
                text-align: center;
            }
            .btn {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>DungThu.com</h1>
            <p>Hàng đã về - Nhận ngay ưu đãi!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Xin chào,</div>
            <div class="intro-text">
                Chúng tôi xin thông báo sản phẩm bạn đã đăng ký theo dõi/giữ chỗ trước đây hiện tại <strong>đã chính thức có hàng</strong> trên hệ thống. Hãy nhanh tay click mua ngay để không bỏ lỡ cơ hội sở hữu sản phẩm này!
            </div>

            <!-- Product Card -->
            <div class="product-card">
                @if($product->image)
                    <div class="product-image-cell">
                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : url($product->image) }}" class="product-image" alt="{{ $product->name }}">
                    </div>
                @endif
                <div class="product-info-cell">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="price-box">
                        @if($product->is_on_sale)
                            <span class="sale-price">{{ $product->formatted_price }}</span>
                            <span class="original-price">{{ $product->formatted_original_price }}</span>
                        @else
                            <span class="normal-price">{{ $product->formatted_price }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="btn-wrapper">
                <a href="{{ route('product.show', $product->slug) }}" class="btn">Xem sản phẩm & Mua ngay</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn bạn đã tin tưởng và đồng hành cùng DungThu.com!</p>
            <p>Nếu có bất kỳ thắc mắc nào, vui lòng phản hồi email này hoặc liên hệ hotline chăm sóc khách hàng.</p>
            <p>&copy; {{ date('Y') }} DungThu.com. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
