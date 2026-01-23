@extends('layouts.app')

@section('title', 'Liên Hệ Quảng Cáo')

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
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
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
                <i class="fas fa-bullhorn text-primary me-2"></i>Liên Hệ Quảng Cáo
            </h1>

            <div class="ad-section">
                <h3>📢 Cơ Hội Quảng Cáo Với DungThu</h3>
                <p>DungThu.com là một nền tảng bán hàng trực tuyến với hàng ngàn khách hàng tiềm năng hàng tháng. Chúng tôi cung cấp các giải pháp quảng cáo linh hoạt giúp thương hiệu của bạn tiếp cận khán giả chính xác.</p>
            </div>

            <div class="ad-section">
                <h3>🎯 Các Gói Quảng Cáo</h3>
                
                <div class="pricing-box">
                    <h4>📦 Gói Basic</h4>
                    <div class="price">100.000đ/tháng</div>
                    <div class="description">
                        • Banner trên trang chủ (1 vị trí)<br>
                        • Hiển thị 30 ngày<br>
                        • Báo cáo lượt xem cơ bản
                    </div>
                </div>

                <div class="pricing-box">
                    <h4>⭐ Gói Premium</h4>
                    <div class="price">300.000đ/tháng</div>
                    <div class="description">
                        • 2 vị trí banner quảng cáo<br>
                        • Quảng cáo email được gửi<br>
                        • Báo cáo chi tiết CTR & conversion<br>
                        • Hỗ trợ ưu tiên
                    </div>
                </div>

                <div class="pricing-box">
                    <h4>🚀 Gói Enterprise</h4>
                    <div class="price">Liên hệ để tư vấn</div>
                    <div class="description">
                        • Giải pháp quảng cáo tùy chỉnh<br>
                        • Partnership kéo dài<br>
                        • Báo cáo phân tích chuyên sâu<br>
                        • Quản lý tài khoản chuyên dụng
                    </div>
                </div>
            </div>

            <div class="ad-section">
                <h3>✨ Tại Sao Chọn DungThu?</h3>
                <ul>
                    <li><strong>Khán giả chất lượng:</strong> Hàng ngàn người dùng hoạt động hàng tuần</li>
                    <li><strong>Vị trí quảng cáo chiến lược:</strong> Hiển thị ở các vị trí có tỷ lệ click cao</li>
                    <li><strong>Báo cáo chi tiết:</strong> Theo dõi hiệu suất quảng cáo của bạn theo thời gian thực</li>
                    <li><strong>Giá cạnh tranh:</strong> Giải pháp quảng cáo phù hợp với mọi ngân sách</li>
                    <li><strong>Hỗ trợ 24/7:</strong> Đội ngũ chuyên gia sẵn sàng hỗ trợ</li>
                </ul>
            </div>

            <div class="ad-section">
                <h3>📊 Thống Kê DungThu</h3>
                <ul>
                    <li>👥 <strong>+5.000</strong> người dùng hoạt động hàng tháng</li>
                    <li>📱 <strong>+10.000</strong> lượt truy cập hàng tháng</li>
                    <li>🛒 <strong>+1.000</strong> đơn hàng thành công hàng tháng</li>
                    <li>⭐ <strong>4.8/5</strong> đánh giá từ khách hàng</li>
                </ul>
            </div>

            <div class="ad-section">
                <h3>🤝 Các Loại Hình Hợp Tác</h3>
                <ul>
                    <li><strong>Quảng cáo Banner:</strong> Hiển thị logo và link sản phẩm của bạn</li>
                    <li><strong>Sponsored Content:</strong> Bài viết giới thiệu sản phẩm trên blog</li>
                    <li><strong>Email Marketing:</strong> Gửi thông tin đến danh sách khách hàng</li>
                    <li><strong>Social Media:</strong> Chia sẻ sản phẩm trên kênh mạng xã hội</li>
                    <li><strong>Affiliate Program:</strong> Hợp tác doanh số, chi trả hoa hồng</li>
                </ul>
            </div>

            <div class="alert alert-info mt-5">
                <strong>💡 Lưu ý:</strong> Tất cả các gói quảng cáo tuân thủ chính sách quảng cáo của chúng tôi. Sản phẩm quảng cáo phải phù hợp với tiêu chí chất lượng và an toàn.
            </div>
        </div>

        <div class="advertising-card" data-aos="fade-up" data-aos-delay="100">
            <h2 class="fw-bold mb-4">
                <i class="fas fa-envelope text-primary me-2"></i>Liên Hệ Với Chúng Tôi
            </h2>
            <p class="mb-4">Bạn quan tâm đến quảng cáo? Hãy liên hệ với đội ngũ bán hàng của chúng tôi ngay hôm nay:</p>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <h5>Email</h5>
                    <a href="mailto:tranthanhtuanfix@gmail.com">tranthanhtuanfix@gmail.com</a>
                    <p style="font-size: 13px; margin-top: 10px; color: #718096;">Phản hồi trong 24 giờ</p>
                </div>
                <div class="contact-method">
                    <i class="fab fa-telegram"></i>
                    <h5>Telegram</h5>
                    <a href="https://t.me/tuanthanh0952" target="_blank">@tuanthanh0952</a>
                    <p style="font-size: 13px; margin-top: 10px; color: #718096;">Chat trực tiếp và nhanh</p>
                </div>
                <div class="contact-method">
                    <i class="fas fa-comments"></i>
                    <h5>Zalo</h5>
                    <a href="https://zalo.me/0708910952" target="_blank">0708910952</a>
                    <p style="font-size: 13px; margin-top: 10px; color: #718096;">Liên hệ qua Zalo</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
