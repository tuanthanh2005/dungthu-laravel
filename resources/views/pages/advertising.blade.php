@extends('layouts.app')

@section('title', __('Liên Hệ Quảng Cáo'))

@push('styles')
    <style>
        .advertising-wrapper {
            padding: 100px 0 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .advertising-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .ad-section h3 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .ad-section p {
            font-size: 15px;
            line-height: 1.8;
            color: #4a5568;
            margin-bottom: 15px;
        }

        .ad-section ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .ad-section li {
            margin-bottom: 10px;
            color: #4a5568;
            line-height: 1.6;
        }

        .pricing-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }

        .pricing-box h4 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .pricing-box .price {
            font-size: 32px;
            font-weight: 700;
            margin: 15px 0;
        }

        .pricing-box .description {
            font-size: 14px;
            margin-top: 10px;
            opacity: 0.9;
        }

        .contact-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .contact-method {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .contact-method:hover {
            background: white;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
        }

        .contact-method i {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .contact-method h5 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #2d3748;
        }

        .contact-method a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .contact-method a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="advertising-wrapper">
        <div class="container">
            <div class="advertising-card" data-aos="fade-up">
                <h1 class="fw-bold mb-5">
                    <i class="fas fa-bullhorn text-primary me-2"></i>{{ __('Liên Hệ Quảng Cáo') }}
                </h1>

                <div class="ad-section">
                    <h3>📢 {{ __('Cơ Hội Quảng Cáo Với DungThu') }}</h3>
                    <p>{{ __('DungThu.com là một nền tảng bán hàng trực tuyến với hàng ngàn khách hàng tiềm năng hàng tháng. Chúng tôi cung cấp các giải pháp quảng cáo linh hoạt giúp thương hiệu của bạn tiếp cận khán giả chính xác.') }}</p>
                </div>

                <div class="ad-section">
                    <h3>🎯 {{ __('Các Gói Quảng Cáo') }}</h3>

                    <div class="pricing-box">
                        <h4>📦 {{ __('Gói Basic') }}</h4>
                        <div class="price">{{ app()->getLocale() === 'en' ? '$' . number_format(100000 / \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000), 2) . '/' . __('tháng') : '100.000đ/tháng' }}</div>
                        <div class="description">
                            • {{ __('Banner trên trang chủ (1 vị trí)') }}<br>
                            • {{ __('Hiển thị 30 ngày') }}<br>
                            • {{ __('Báo cáo lượt xem cơ bản') }}
                        </div>
                    </div>

                    <div class="pricing-box">
                        <h4>⭐ {{ __('Gói Premium') }}</h4>
                        <div class="price">{{ app()->getLocale() === 'en' ? '$' . number_format(300000 / \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000), 2) . '/' . __('tháng') : '300.000đ/tháng' }}</div>
                        <div class="description">
                            • {{ __('2 vị trí banner quảng cáo') }}<br>
                            • {{ __('Quảng cáo email được gửi') }}<br>
                            • {{ __('Báo cáo chi tiết CTR & conversion') }}<br>
                            • {{ __('Hỗ trợ ưu tiên') }}
                        </div>
                    </div>

                    <div class="pricing-box">
                        <h4>🚀 {{ __('Gói Enterprise') }}</h4>
                        <div class="price">{{ __('Liên hệ để tư vấn') }}</div>
                        <div class="description">
                            • {{ __('Giải pháp quảng cáo tùy chỉnh') }}<br>
                            • {{ __('Partnership kéo dài') }}<br>
                            • {{ __('Báo cáo phân tích chuyên sâu') }}<br>
                            • {{ __('Quản lý tài khoản chuyên dụng') }}
                        </div>
                    </div>
                </div>

                <div class="ad-section">
                    <h3>✨ {{ __('Tại Sao Chọn DungThu?') }}</h3>
                    <ul>
                        <li><strong>{{ __('Khán giả chất lượng') }}:</strong> {{ __('Hàng ngàn người dùng hoạt động hàng tuần') }}</li>
                        <li><strong>{{ __('Vị trí quảng cáo chiến lược') }}:</strong> {{ __('Hiển thị ở các vị trí có tỷ lệ click cao') }}</li>
                        <li><strong>{{ __('Báo cáo chi tiết') }}:</strong> {{ __('Theo dõi hiệu suất quảng cáo của bạn theo thời gian thực') }}</li>
                        <li><strong>{{ __('Giá cạnh tranh') }}:</strong> {{ __('Giải pháp quảng cáo phù hợp với mọi ngân sách') }}</li>
                        <li><strong>{{ __('Hỗ trợ 24/7') }}:</strong> {{ __('Đội ngũ chuyên gia sẵn sàng hỗ trợ') }}</li>
                    </ul>
                </div>

                <div class="ad-section">
                    <h3>📊 {{ __('Thống Kê DungThu') }}</h3>
                    <ul>
                        <li>👥 <strong>+5.000</strong> {{ __('người dùng hoạt động hàng tháng') }}</li>
                        <li>📱 <strong>+10.000</strong> {{ __('lượt truy cập hàng tháng') }}</li>
                        <li>🛒 <strong>+1.000</strong> {{ __('đơn hàng thành công hàng tháng') }}</li>
                        <li>⭐ <strong>4.8/5</strong> {{ __('đánh giá từ khách hàng') }}</li>
                    </ul>
                </div>

                <div class="ad-section">
                    <h3>🤝 {{ __('Các Loại Hình Hợp Tác') }}</h3>
                    <ul>
                        <li><strong>{{ __('Quảng cáo Banner') }}:</strong> {{ __('Hiển thị logo và link sản phẩm của bạn') }}</li>
                        <li><strong>{{ __('Sponsored Content') }}:</strong> {{ __('Bài viết giới thiệu sản phẩm trên blog') }}</li>
                        <li><strong>{{ __('Email Marketing') }}:</strong> {{ __('Gửi thông tin đến danh sách khách hàng') }}</li>
                        <li><strong>{{ __('Social Media') }}:</strong> {{ __('Chia sẻ sản phẩm trên kênh mạng xã hội') }}</li>
                        <li><strong>{{ __('Affiliate Program') }}:</strong> {{ __('Hợp tác doanh số, chi trả hoa hồng') }}</li>
                    </ul>
                </div>

                <div class="alert alert-info mt-5">
                    <strong>💡 {{ __('Lưu ý') }}:</strong> {{ __('Tất cả các gói quảng cáo tuân thủ chính sách quảng cáo của chúng tôi. Sản phẩm quảng cáo phải phù hợp với tiêu chí chất lượng và an toàn.') }}
                </div>
            </div>

            <div class="advertising-card" data-aos="fade-up" data-aos-delay="100">
                <h2 class="fw-bold mb-4"><i class="fas fa-envelope text-primary me-2"></i>{{ __('Liên Hệ Với Chúng Tôi') }}</h2>
                <p class="mb-4">{{ __('Bạn quan tâm đến quảng cáo? Hãy liên hệ với đội ngũ bán hàng của chúng tôi ngay hôm nay:') }}</p>

                <div class="contact-methods">
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <h5>{{ __('Email') }}</h5>
                        <a href="mailto:tranthanhtuanfix@gmail.com">tranthanhtuanfix@gmail.com</a>
                        <p style="font-size: 13px; margin-top: 10px; color: #718096;">{{ __('Phản hồi trong 24 giờ') }}</p>
                    </div>
                    <div class="contact-method">
                        <i class="fab fa-telegram"></i>
                        <h5>{{ __('Telegram') }}</h5>
                        <a href="https://t.me/specademy" target="_blank">@specademy</a>
                        <p style="font-size: 13px; margin-top: 10px; color: #718096;">{{ __('Chat trực tiếp và nhanh') }}</p>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-comments"></i>
                        <h5>{{ __('Zalo') }}</h5>
                        <a href="https://zalo.me/0708910952" target="_blank">0708910952</a>
                        <p style="font-size: 13px; margin-top: 10px; color: #718096;">{{ __('Liên hệ qua Zalo') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection