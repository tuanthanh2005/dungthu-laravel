@extends('layouts.app')

@section('title', 'VPN & Proxy Chuyên Nghiệp - DungThu.com')
@section('meta_description', 'Dịch vụ VPN & Proxy tốc độ cao, ổn định, bảo mật. Đa dạng quốc gia, IP sạch, phù hợp cho cá nhân và doanh nghiệp.')

@push('styles')
<style>
    .vpn-hero {
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        padding: 60px 0;
        color: white;
        text-align: center;
        margin-bottom: 40px;
    }

    .vpn-title {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .vpn-desc {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .product-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .product-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3436;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        color: #e11d48;
        font-weight: 800;
        font-size: 1.2rem;
    }

    .product-original-price {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 0.9rem;
        margin-left: 10px;
    }

    .proxy-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .proxy-table th {
        background: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #6c757d;
        padding: 15px;
    }

    .proxy-table td {
        padding: 15px;
        vertical-align: middle;
    }

    .section-title {
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, #00c6ff, #0072ff);
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div class="vpn-hero">
    <div class="container" data-aos="fade-up">
        <h1 class="vpn-title"><i class="fas fa-shield-alt me-2"></i>VPN & PROXY CAO CẤP</h1>
        <p class="vpn-desc">Dịch vụ IP Private chất lượng cao, tốc độ đường truyền ổn định, hỗ trợ đa dạng nhu cầu nuôi tài khoản, làm MMO, lướt web ẩn danh.</p>
    </div>
</div>

<div class="container pb-5">
    <!-- Phần Sản phẩm VPN (Từ danh mục sản phẩm có is_vpn = 1) -->
    @if($vpnProducts->count() > 0)
    <div class="mb-5" data-aos="fade-up">
        <h2 class="section-title"><i class="fas fa-network-wired me-2 text-primary"></i>Phần Mềm VPN & Tool Đổi IP</h2>
        <div class="row g-4">
            @foreach($vpnProducts as $product)
                <div class="col-md-4 col-lg-3">
                    <div class="card product-card">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="product-img" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                <h3 class="product-title">{{ $product->name }}</h3>
                            </a>
                            <div class="mt-auto pt-3 border-top">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    @if($product->is_on_sale)
                                        <span class="product-original-price">{{ $product->formatted_original_price }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="fas fa-shopping-cart me-2"></i>Xem Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Phần Danh Sách Proxy -->
    <div data-aos="fade-up" data-aos-delay="100">
        <h2 class="section-title"><i class="fas fa-server me-2 text-info"></i>Dịch Vụ Proxy Riêng Cấp Phát (Private)</h2>
        
        @if($proxies->count() > 0)
            <div class="table-responsive proxy-table">
                <table class="table table-hover mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="text-start">Tên Gói Proxy</th>
                            <th>Quốc Gia</th>
                            <th>Giao Thức</th>
                            <th>Thời Hạn</th>
                            <th>Giá / Chu kỳ</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proxies as $proxy)
                        <tr>
                            <td class="text-start fw-bold text-dark">
                                <i class="fas fa-globe-americas text-primary me-2"></i>{{ $proxy->name }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $proxy->location ?? 'US' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $proxy->protocol ?? 'HTTP/SOCKS5' }}</span>
                            </td>
                            <td>
                                {{ $proxy->duration ?? '30 Ngày' }}
                            </td>
                            <td>
                                <strong class="text-danger">{{ number_format($proxy->price, 0, ',', '.') }}đ</strong>
                            </td>
                            <td>
                                <a href="https://zalo.me/0772698113" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                    <i class="fas fa-comment-dots me-1"></i>Mua ngay
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-end text-muted small">
                <i class="fas fa-info-circle me-1"></i> Proxy sẽ được cấp phát ngay sau khi thanh toán qua bộ phận hỗ trợ Zalo.
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                <i class="fas fa-server fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Hệ thống đang cập nhật danh sách Proxy...</h5>
                <p>Vui lòng quay lại sau hoặc liên hệ bộ phận hỗ trợ qua Zalo để mua Proxy trực tiếp.</p>
                <a href="https://zalo.me/0772698113" target="_blank" class="btn btn-primary rounded-pill mt-2"><i class="fas fa-comment-dots me-2"></i>Liên hệ Zalo</a>
            </div>
        @endif
    </div>
</div>
@endsection
