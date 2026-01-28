@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .tech-wrapper {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 50%, #80deea 100%);
            padding: 40px 0;
            min-height: 100vh;
        }        .description-content {
            font-size: 1rem;
        }
        @media (max-width: 768px) {
            .description-content {
                font-size: calc(1rem - 5px);
            }
        }        .tech-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        }
        .product-detail-image {
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            transition: transform 0.3s;
            border: 3px solid #00d4ff;
        }
        .product-detail-image:hover {
            transform: scale(1.05) rotateY(5deg);
        }
        .tech-badge {
            background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,212,255,0.3);
            cursor: default;
            pointer-events: none;
        }
        .tech-badge i {
            font-size: 2rem;
            margin-right: 20px;
        }
        .spec-table {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }
        .spec-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .spec-row:last-child {
            border-bottom: none;
        }
        .tech-tab.nav-link {
            border: none;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            margin: 0 5px;
            border-radius: 15px;
            padding: 18px 35px;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .tech-tab.nav-link i {
            font-size: 28px;
            margin: 0 !important;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.3));
        }
        .tech-tab.nav-link span {
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        
        /* Tab Tính Năng - Xanh cyan sáng */
        #features-tab {
            background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #features-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(6,214,160,0.5), 0 0 20px rgba(6,214,160,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #features-tab.active {
            box-shadow: 0 12px 35px rgba(6,214,160,0.6), 0 0 25px rgba(6,214,160,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        /* Tab Mô Tả - Vàng cam sáng */
        #description-tab {
            background: linear-gradient(135deg, #ffa502 0%, #ff6348 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #description-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(255,165,2,0.5), 0 0 20px rgba(255,165,2,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #description-tab.active {
            box-shadow: 0 12px 35px rgba(255,165,2,0.6), 0 0 25px rgba(255,165,2,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        /* Tab Đánh Giá - Hồng tím sáng */
        #reviews-tab {
            background: linear-gradient(135deg, #ee5a6f 0%, #c44569 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #reviews-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(238,90,111,0.5), 0 0 20px rgba(238,90,111,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #reviews-tab.active {
            box-shadow: 0 12px 35px rgba(238,90,111,0.6), 0 0 25px rgba(238,90,111,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 28px;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffc107;
        }
    </style>
@endpush

@section('content')
<div class="tech-wrapper">
    <div class="container py-5" style="margin-top: 50px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #06d6a0; font-weight: 600;">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: #06d6a0; font-weight: 600;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" style="color: #ff6348; font-weight: 700;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="tech-card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                         class="img-fluid product-detail-image w-100" 
                         alt="{{ $product->name }}">
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="tech-card">
                    <span class="badge bg-info mb-3" style="font-size: 14px;">
                        <i class="fas fa-microchip me-2"></i>{{ strtoupper($product->category) }}
                    </span>
                    <h1 class="fw-bold mb-3" style="color: #0f2027;">{{ $product->name }}</h1>
                    <p class="lead text-muted mb-4">{{ Str::limit($product->description, 150, '......') }}</p>
                    
                    <div class="mb-4 p-4 bg-light rounded-4">
                        <div class="d-flex align-items-end gap-3 flex-wrap">
                            <h2 class="text-info fw-bold mb-0">{{ $product->formatted_price }}</h2>
                            @if($product->is_on_sale)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-muted text-decoration-line-through">{{ $product->formatted_original_price }}</span>
                                    <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Giá đã bao gồm VAT</small>
                    </div>
                    
                    @if($product->stock > 0)
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="alert alert-success d-inline-flex align-items-center mb-0">
                                <i class="fas fa-check-circle"></i> Còn hàng ({{ $product->stock }} sản phẩm)
                            </div>
                            <small class="text-muted">Gia hạn theo tháng 3/6/12 tháng: liên hệ admin hoặc box chat</small>
                        </div>
                        @else
                        <div class="alert alert-danger d-inline-block">
                            <i class="fas fa-times-circle"></i> Hết hàng
                        </div>
                    @endif
                    
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="d-flex gap-3 mb-4">
                            <button type="submit" class="btn btn-lg rounded-pill px-5 shadow" 
                                    style="background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%); color: white; border: none;"
                                    {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ
                            </button>
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua
                            </a>
                        </div>
                    </form>

                    <!-- Tech Specs -->
                    <div class="spec-table mt-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-cogs me-2 text-info"></i>Thông Số Kỹ Thuật</h5>
                        @if($product->specs && count(array_filter($product->specs)) > 0)
                            @foreach($product->specs as $key => $value)
                                @if(!empty($value))
                                <div class="spec-row">
                                    <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <strong>{{ is_array($value) ? implode(', ', $value) : $value }}</strong>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                Chưa có thông tin thông số kỹ thuật cho sản phẩm này.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill border-0 mb-4" id="productTabs" role="tablist" data-aos="fade-up">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab active" id="features-tab" data-bs-toggle="tab" 
                                data-bs-target="#features" type="button" role="tab">
                            <i class="fas fa-microchip"></i>
                            <span>Tính Năng</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab" id="description-tab" data-bs-toggle="tab" 
                                data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-list-alt"></i>
                            <span>Mô Tả</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-comments"></i>
                            <span>Đánh Giá</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Features Tab -->
                    <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                        <div class="tech-card">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-star text-warning me-2"></i>Tính Năng Nổi Bật
                            </h4>
                            @if($product->features && $product->features->count() > 0)
                            <div class="row g-3">
                                @php
                                $defaultColors = [
                                    '#667eea', // Tím xanh
                                    '#f093fb', // Hồng
                                    '#4facfe', // Xanh dương nhạt
                                    '#43e97b', // Xanh lá
                                    '#fa709a', // Đỏ cam
                                    '#764ba2', // Tím đậm
                                ];
                                @endphp
                                @foreach($product->features as $index => $feature)
                                @php
                                $color = $feature->color ?? $defaultColors[$index % count($defaultColors)];
                                @endphp
                                <div class="col-md-6">
                                    <div class="tech-badge" style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}dd 100%);">
                                        <i class="{{ $feature->icon }}"></i>
                                        <div>
                                            <strong>{{ $feature->name }}</strong><br>
                                            @if($feature->description)
                                            <small>{{ $feature->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Chưa có thông tin tính năng nổi bật cho sản phẩm này.
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description Tab -->
                    <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                        <div class="tech-card">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-align-left text-info me-2"></i>Mô Tả Chi Tiết
                            </h4>
                            <div class="text-muted description-content" style="line-height: 1.8;">{!! nl2br(e($product->description)) !!}</div>
                            <hr class="my-4">
                            <div class="alert alert-info rounded-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Lưu ý:</strong> Sản phẩm công nghệ được kiểm tra kỹ lưỡng trước khi giao hàng. 
                                Bảo hành chính hãng 24 tháng tại các trung tâm bảo hành toàn quốc.
                            </div>
                        </div>
                    </div>

                    @include('products.partials.reviews', ['product' => $product, 'averageRating' => $averageRating, 'totalReviews' => $totalReviews])
                </div>
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
