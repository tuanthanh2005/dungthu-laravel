@extends('layouts.app')

@section('title', __('VPN & Proxy Chuyên Nghiệp - DungThu.com'))
@section('meta_description', __('Dịch vụ VPN & Proxy tốc độ cao, ổn định, bảo mật. Đa dạng quốc gia, IP sạch, phù hợp cho cá nhân và doanh nghiệp.'))

@push('styles')
<style>
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

    .custom-tabs {
        border-bottom: 2px solid #e9ecef;
        gap: 15px;
    }

    .custom-tabs .nav-link {
        color: #495057;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 25px;
        transition: all 0.3s ease;
        background: transparent;
        border-radius: 0;
    }

    .custom-tabs .nav-link:hover {
        color: #0072ff;
        border-bottom-color: rgba(0, 114, 255, 0.3);
    }

    .custom-tabs .nav-link.active {
        color: #0072ff;
        border-bottom-color: #0072ff;
        background: transparent;
    }
</style>
@endpush

@section('content')


<div class="container pb-5">
    <!-- Tab Navigation -->
    <ul class="nav nav-pills custom-tabs mb-4 justify-content-center" id="vpnProxyTabs" data-aos="fade-up">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'vpn' ? 'active' : '' }}" href="{{ route('vpn.tab', ['tab' => 'vpn']) }}">
                <i class="fas fa-network-wired me-2"></i>VPN
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'proxy' ? 'active' : '' }}" href="{{ route('vpn.tab', ['tab' => 'proxy']) }}">
                <i class="fas fa-server me-2"></i>{{ __('Dịch Vụ Proxy') }}
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="vpnProxyTabsContent">
        
        @if($tab == 'vpn')
        <!-- Tab 1: VPN & Tool -->
        <div class="tab-pane fade show active" role="tabpanel">
            @if($vpnProducts->count() > 0)
            <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
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
                                        <i class="fas fa-shopping-cart me-2"></i>{{ __('Xem Chi Tiết') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5 bg-white rounded-3 shadow-sm border" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('Chưa có phần mềm VPN nào được thêm...') }}</h5>
            </div>
            @endif
        </div>
        @endif

        @if($tab == 'proxy')
        <!-- Tab 2: Proxy -->
        <div class="tab-pane fade show active" role="tabpanel">
            @if($proxies->count() > 0)
                <div class="table-responsive proxy-table" data-aos="fade-up" data-aos-delay="100">
                    <table class="table table-hover mb-0 text-center">
                        <thead>
                            <tr>
                                <th class="text-start">{{ __('Tên Gói Proxy') }}</th>
                                <th>{{ __('Quốc Gia') }}</th>
                                <th>{{ __('Giao Thức') }}</th>
                                <th>{{ __('Thời Hạn') }}</th>
                                <th>{{ __('Giá / Chu kỳ') }}</th>
                                <th>{{ __('Thao Tác') }}</th>
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
                                    {{ $proxy->duration ?? __('30 Ngày') }}
                                </td>
                                <td>
                                    @if(app()->getLocale() === 'en')
                                        @php
                                            $rate = (float) \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000);
                                            $priceUsd = (float) $proxy->price / $rate;
                                        @endphp
                                        <strong class="text-danger">${{ number_format($priceUsd, 2) }}</strong>
                                    @else
                                        <strong class="text-danger">{{ number_format($proxy->price, 0, ',', '.') }}đ</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="fas fa-comment-dots me-1"></i>{{ __('Mua ngay') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end text-muted small" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-info-circle me-1"></i> {{ __('Proxy sẽ được cấp phát ngay sau khi thanh toán qua bộ phận hỗ trợ Zalo.') }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-3 shadow-sm border" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-server fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('Hệ thống đang cập nhật danh sách Proxy...') }}</h5>
                    <p>{{ __('Vui lòng quay lại sau hoặc liên hệ bộ phận hỗ trợ qua Zalo để mua Proxy trực tiếp.') }}</p>
                    <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="btn btn-primary rounded-pill mt-2"><i class="fas fa-comment-dots me-2"></i>{{ __('Liên hệ Zalo') }}</a>
                </div>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection
