@extends('layouts.app')

@section('title', __('Chính Sách Bảo Mật'))

@push('styles')
    <style>
        .policy-wrapper {
            padding: 100px 0 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .policy-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .policy-section {
            margin-bottom: 40px;
        }

        .policy-section h3 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .policy-section p {
            font-size: 15px;
            line-height: 1.8;
            color: #4a5568;
            margin-bottom: 15px;
        }

        .policy-section ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .policy-section li {
            margin-bottom: 10px;
            color: #4a5568;
            line-height: 1.6;
        }
    </style>
@endpush

@section('content')
    <div class="policy-wrapper">
        <div class="container">
            <div class="policy-card" data-aos="fade-up">
                <h1 class="fw-bold mb-5">
                    <i class="fas fa-shield-alt text-primary me-2"></i>{{ __('Chính Sách Bảo Mật') }}
                </h1>

                <div class="policy-section">
                    <h3>📋 {{ __('1. Thông Tin Chúng Tôi Thu Thập') }}</h3>
                    <p>{{ __('DungThu.com thu thập những thông tin sau để phục vụ bạn tốt hơn:') }}</p>
                    <ul>
                        <li>{{ __('Tên, email, số điện thoại khi bạn đăng ký tài khoản hoặc đặt hàng') }}</li>
                        <li>{{ __('Địa chỉ giao hàng để xử lý đơn hàng') }}</li>
                        <li>{{ __('Lịch sử mua hàng và sở thích sản phẩm') }}</li>
                        <li>{{ __('Dữ liệu kỹ thuật: IP address, trình duyệt, hành động trên trang web') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>🔒 {{ __('2. Cách Chúng Tôi Bảo Vệ Thông Tin Của Bạn') }}</h3>
                    <p>{{ __('Chúng tôi sử dụng các biện pháp bảo mật tiên tiến:') }}</p>
                    <ul>
                        <li>{{ __('Mã hóa SSL/TLS cho tất cả giao tiếp giữa bạn và website') }}</li>
                        <li>{{ __('Lưu trữ mật khẩu với mã hóa an toàn') }}</li>
                        <li>{{ __('Không chia sẻ thông tin cá nhân với bên thứ ba') }}</li>
                        <li>{{ __('Định kỳ kiểm tra bảo mật hệ thống') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>💾 {{ __('3. Cách Sử Dụng Thông Tin Của Bạn') }}</h3>
                    <p>{{ __('Thông tin bạn cung cấp được sử dụng cho:') }}</p>
                    <ul>
                        <li>{{ __('Xử lý và giao hàng đơn hàng') }}</li>
                        <li>{{ __('Gửi thông báo về tình trạng đơn hàng') }}</li>
                        <li>{{ __('Cải thiện dịch vụ và sản phẩm') }}</li>
                        <li>{{ __('Gửi tin tức và ưu đãi (nếu bạn đồng ý)') }}</li>
                        <li>{{ __('Tuân thủ pháp luật') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>🗑️ {{ __('4. Lưu Trữ Và Xóa Dữ Liệu') }}</h3>
                    <p>{{ __('Chúng tôi sẽ lưu trữ dữ liệu của bạn trong thời gian cần thiết để phục vụ bạn. Bạn có quyền yêu cầu xóa thông tin cá nhân bất kỳ lúc nào bằng cách liên hệ với chúng tôi.') }}</p>
                </div>

                <div class="policy-section">
                    <h3>🍪 {{ __('5. Cookie Và Theo Dõi') }}</h3>
                    <p>{{ __('Chúng tôi sử dụng cookie để:') }}</p>
                    <ul>
                        <li>{{ __('Duy trì phiên đăng nhập của bạn') }}</li>
                        <li>{{ __('Ghi nhớ sở thích của bạn') }}</li>
                        <li>{{ __('Phân tích cách bạn sử dụng website') }}</li>
                    </ul>
                    <p>{{ __('Bạn có thể tắt cookie trong cài đặt trình duyệt của mình.') }}</p>
                </div>

                <div class="policy-section">
                    <h3>👥 {{ __('6. Quyền Của Bạn') }}</h3>
                    <p>{{ __('Bạn có quyền:') }}</p>
                    <ul>
                        <li>{{ __('Truy cập và xem thông tin cá nhân của bạn') }}</li>
                        <li>{{ __('Chỉnh sửa hoặc cập nhật thông tin') }}</li>
                        <li>{{ __('Yêu cầu xóa tài khoản và dữ liệu') }}</li>
                        <li>{{ __('Thu hồi sự đồng ý gửi email marketing') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>📞 {{ __('7. Liên Hệ Về Chính Sách Bảo Mật') }}</h3>
                    <p>{{ __('Nếu bạn có bất kỳ câu hỏi nào về chính sách này, vui lòng liên hệ:') }}</p>
                    <ul>
                        <li>📧 Email: <a href="mailto:tranthanhtuanfix@gmail.com">tranthanhtuanfix@gmail.com</a></li>
                        <li>💬 Telegram: <a href="https://t.me/specademy" target="_blank">@specademy</a></li>
                    </ul>
                </div>

                <div class="alert alert-warning mt-5">
                    <strong>⚠️ Ghi chú:</strong> Chính sách bảo mật này có thể được cập nhật bất kỳ lúc nào. Chúng tôi sẽ
                    thông báo cho bạn về những thay đổi quan trọng.
                </div>
            </div>
        </div>
    </div>
@endsection