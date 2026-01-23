@extends('layouts.app')

@section('title', 'Về Chúng Tôi')

@push('styles')
<style>
    .about-wrapper {
        padding: 100px 0 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .about-card {
        background: white;
        border-radius: 20px;
        padding: 50px 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .section-title {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 28px;
        border-bottom: 3px solid #667eea;
        padding-bottom: 15px;
    }
    .feature-item {
        padding: 20px;
        background: #f7fafc;
        border-radius: 15px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
    .feature-item i {
        color: #667eea;
        font-size: 24px;
        margin-right: 15px;
    }
    .feature-item h5 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .feature-item p {
        color: #718096;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="about-wrapper">
    <div class="container">
        <div class="about-card" data-aos="fade-up">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-info-circle text-primary me-2"></i>Về Chúng Tôi
            </h1>

            <h3 class="section-title">
                <i class="fas fa-rocket me-2"></i>Sứ Mệnh
            </h3>
            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 30px;">
                DungThu.com là nền tảng cung cấp giải pháp công nghệ, thời trang và công cụ Marketing miễn phí cho cộng đồng Việt Nam. 
                Chúng tôi cam kết mang đến những sản phẩm chất lượng với giá cạnh tranh nhất.
            </p>

            <h3 class="section-title">
                <i class="fas fa-star me-2"></i>Lợi Ích Khi Chọn Chúng Tôi
            </h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <h5>Sản Phẩm Chất Lượng</h5>
                        <p>Tất cả sản phẩm đều được kiểm duyệt kỹ lưỡng trước khi bán.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <h5>Giá Cạnh Tranh</h5>
                        <p>Mức giá tốt nhất trên thị trường với nhiều ưu đãi đặc biệt.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <h5>Hỗ Trợ 24/7</h5>
                        <p>Đội ngũ hỗ trợ khách hàng sẵn sàng giúp đỡ bạn mọi lúc.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <h5>An Toàn & Bảo Mật</h5>
                        <p>Thông tin cá nhân của bạn được bảo vệ tối đa.</p>
                    </div>
                </div>
            </div>

            <h3 class="section-title mt-5">
                <i class="fas fa-handshake me-2"></i>Tại Sao Tin Tưởng DungThu?
            </h3>
            <ul style="font-size: 16px; line-height: 2; margin-bottom: 30px;">
                <li><i class="fas fa-check text-success me-2"></i><strong>Kinh Nghiệm:</strong> Nhiều năm phục vụ khách hàng</li>
                <li><i class="fas fa-check text-success me-2"></i><strong>Uy Tín:</strong> Hàng ngàn khách hàng hài lòng</li>
                <li><i class="fas fa-check text-success me-2"></i><strong>Nhanh Chóng:</strong> Xử lý đơn hàng siêu tốc</li>
                <li><i class="fas fa-check text-success me-2"></i><strong>Đáng Tin:</strong> Cam kết bảo vệ quyền lợi khách hàng</li>
            </ul>

            <div class="alert alert-primary mt-5" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-envelope me-2"></i>Liên Hệ Chúng Tôi
                </h5>
                <p class="mb-1">
                    <strong>Email:</strong> 
                    <a href="mailto:tranthanhtuanfix@gmail.com">tranthanhtuanfix@gmail.com</a>
                </p>
                <p class="mb-1">
                    <strong>Telegram:</strong> 
                    <a href="https://t.me/tuanthanh0952" target="_blank">@tuanthanh0952</a>
                </p>
                <p class="mb-0">
                    <strong>Zalo:</strong> 
                    <a href="https://zalo.me/0708910952" target="_blank">0708910952</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
