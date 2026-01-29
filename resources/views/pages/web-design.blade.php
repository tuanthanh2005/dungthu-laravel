@extends('layouts.app')

@section('title', 'Thiết kế website giá rẻ')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .web-design-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 16px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 10px 24px rgba(0,0,0,0.08);
    }
    .web-design-card .price {
        font-weight: 800;
        font-size: 1.2rem;
        color: #1d4ed8;
    }
    .web-design-card ul {
        padding-left: 18px;
        margin: 10px 0 0;
    }
    .web-design-card li {
        margin-bottom: 6px;
    }
</style>
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Thiết kế website giá rẻ</h1>
            <p class="text-muted">Trao đổi nhanh, chốt trong 1-2 tiếng. Thiết kế chuẩn SEO, tối ưu mobile, bàn giao nhanh.</p>
        </div>
    </div>

    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-end">
                        <div>
                            <span class="text-primary fw-bold text-uppercase ls-1">Dịch vụ</span>
                            <h3 class="fw-bold section-title mb-2">Thiết kế website giá rẻ</h3>
                            <p class="text-muted mb-0">Chỉ nhận: website bán hàng, website blog, website tin tức. Vui lòng liên hệ qua Zalo hoặc Facebook. Thời gian thiết kế 3-14 ngày tùy độ phức tạp. Tên domain và hosting shop sẽ đứng hộ để bảo trì nâng cấp.</p>
                        </div>
                        <a href="#" class="btn btn-primary rounded-pill px-4 mt-3 mt-md-0" data-bs-toggle="modal" data-bs-target="#contactModal">
                            Nhận tư vấn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3 g-md-4" data-aos="fade-up">
        <div class="col">
            <div class="web-design-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0">Gói Starter</h6>
                    <span class="badge bg-primary">Phổ biến</span>
                </div>
                <div class="price">3.000.000đ</div>
                <ul class="text-muted small">
                    <li>Website 1-3 trang (Trang chủ, Giới thiệu, Liên hệ)</li>
                    <li>Thêm 1 trang sản phẩm</li>
                    <li>Giao diện chuẩn mobile, hiển thị đẹp trên điện thoại</li>
                    <li>Bao gồm tên miền + hosting 1 năm</li>
                    <li>Phù hợp lưu lượng nhỏ; nếu vào đông sẽ tư vấn nâng cấp</li>
                    <li>Tốc độ tải nhanh, tối ưu hình ảnh cơ bản</li>
                    <li>Hỗ trợ chỉnh sửa nhỏ trong 7 ngày</li>
                    <li>Bàn giao là chạy ngay, khách chỉ cần chờ thời gian giao web</li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="web-design-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0">Gói Business</h6>
                    <span class="badge bg-success">Đề xuất</span>
                </div>
                <div class="price">4.800.000đ</div>
                <ul class="text-muted small">
                    <li>Website 5-7 trang (sản phẩm/dịch vụ, bảng giá, FAQ...)</li>
                    <li>Form liên hệ + bản đồ Google Maps</li>
                    <li>Bao gồm tên miền + hosting 1 năm</li>
                    <li>Phù hợp lưu lượng vừa; nếu vào đông sẽ tư vấn nâng cấp</li>
                    <li>Chuẩn SEO cơ bản (title, meta, sitemap)</li>
                    <li>Hỗ trợ chỉnh sửa nội dung trong 30 ngày</li>
                    <li>Bàn giao là chạy ngay, khách chỉ cần chờ thời gian giao web</li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="web-design-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0">Gói Pro</h6>
                    <span class="badge bg-warning text-dark">Nâng cao</span>
                </div>
                <div class="price">6.700.000đ</div>
                <ul class="text-muted small">
                    <li>Website 10+ trang, cấu trúc rõ ràng</li>
                    <li>Blog + quản trị nội dung (đăng bài, danh mục)</li>
                    <li>Bao gồm tên miền + hosting 1 năm</li>
                    <li>Phù hợp lưu lượng lớn; nếu vào đông sẽ tư vấn nâng cấp</li>
                    <li>Tracking & báo cáo (Google Analytics)</li>
                    <li>Hỗ trợ bảo trì cơ bản 60 ngày</li>
                    <li>Bàn giao là chạy ngay, khách chỉ cần chờ thời gian giao web</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
