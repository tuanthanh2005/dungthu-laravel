@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .doc-wrapper {
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
        }        .doc-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,151,167,0.2);
            border-left: 5px solid #00acc1;
        }
        .product-detail-image {
            border-radius: 15px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
            transition: transform 0.3s;
            border: 2px solid #00acc1;
        }
        .product-detail-image:hover {
            transform: scale(1.02) rotate(1deg);
        }
        .doc-badge {
            background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%);
            color: white;
            padding: 18px;
            border-radius: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,172,193,0.3);
            cursor: default;
            pointer-events: none;
        }
        .doc-badge i {
            font-size: 1.8rem;
            margin-right: 18px;
        }
        .doc-spec {
            background: #f1f8fb;
            border-left: 4px solid #00acc1;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .doc-spec strong {
            color: #00acc1;
        }
        .doc-tab.nav-link {
            border: none;
            background: white;
            margin: 0 5px;
            border-radius: 12px;
            padding: 15px 30px;
            color: #00695c;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0,172,193,0.2);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .doc-tab.nav-link:hover {
            background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,172,193,0.4);
            border-color: #00acc1;
        }
        .doc-tab.nav-link.active {
            background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(0,172,193,0.5);
            border-color: #00acc1;
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
<div class="doc-wrapper">
    <div class="container py-5" style="margin-top: 50px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00695c;">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: #00695c;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" style="color: #00acc1;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="doc-card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                         class="img-fluid product-detail-image w-100" 
                         alt="{{ $product->name }}">
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="doc-card">
                    <span class="badge mb-3" style="background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%); font-size: 14px;">
                        <i class="fas fa-book me-2"></i>{{ strtoupper($product->category) }}
                    </span>
                    <h1 class="fw-bold mb-3" style="color: #00695c;">{{ $product->name }}</h1>
                    <p class="lead text-muted mb-4">{{ $product->description }}</p>
                    
                    <div class="mb-4 p-4 rounded-4" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);">
                        <div class="d-flex align-items-end gap-3 flex-wrap">
                            <h2 class="fw-bold mb-0" style="color: #00acc1;">{{ $product->formatted_price }}</h2>
                            @if($product->is_on_sale)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-muted text-decoration-line-through">{{ $product->formatted_original_price }}</span>
                                    <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted"><i class="fas fa-tag me-1"></i>Giá ưu đãi cho học sinh, sinh viên</small>
                    </div>
                    
                    @if($product->stock > 0)
                        <div class="alert alert-success d-inline-block">
                            <i class="fas fa-check-circle"></i> Còn hàng ({{ $product->stock }} sản phẩm)
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
                                    style="background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%); color: white; border: none;"
                                    {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ
                            </button>
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua
                            </a>
                        </div>
                    </form>

                    <!-- Document Specs -->
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3" style="color: #00695c;">
                            <i class="fas fa-info-circle me-2"></i>Thông Tin Sản Phẩm
                        </h5>
                        <div class="doc-spec">
                            <i class="fas fa-file-alt me-2"></i>
                            <strong>Loại:</strong> Văn phòng phẩm cao cấp
                        </div>
                        <div class="doc-spec">
                            <i class="fas fa-palette me-2"></i>
                            <strong>Chất liệu:</strong> Giấy ivory 100gsm
                        </div>
                        <div class="doc-spec">
                            <i class="fas fa-ruler-combined me-2"></i>
                            <strong>Kích thước:</strong> A4 (210 x 297 mm)
                        </div>
                        <div class="doc-spec">
                            <i class="fas fa-box me-2"></i>
                            <strong>Đóng gói:</strong> 100 tờ/xấp
                        </div>
                        <div class="doc-spec">
                            <i class="fas fa-certificate me-2"></i>
                            <strong>Xuất xứ:</strong> Việt Nam
                        </div>
                    </div>

                    <!-- Document Features -->
                    <div class="mt-4">
                        <div class="doc-badge">
                            <i class="fas fa-award"></i>
                            <div>
                                <strong>Chất lượng cao</strong><br>
                                <small>Giấy trắng, bề mặt mịn</small>
                            </div>
                        </div>
                        <div class="doc-badge" style="background: linear-gradient(135deg, #26a69a 0%, #00897b 100%);">
                            <i class="fas fa-leaf"></i>
                            <div>
                                <strong>Thân thiện môi trường</strong><br>
                                <small>100% giấy tái chế</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill border-0 mb-4" id="productTabs" role="tablist" data-aos="fade-up">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link doc-tab active" id="features-tab" data-bs-toggle="tab" 
                                data-bs-target="#features" type="button" role="tab">
                            <i class="fas fa-clipboard-list me-2"></i>Đặc Tính
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link doc-tab" id="description-tab" data-bs-toggle="tab" 
                                data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-file-alt me-2"></i>Chi Tiết
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link doc-tab" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-comments me-2"></i>Đánh Giá
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Features Tab -->
                    <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                        <div class="doc-card">
                            <h4 class="fw-bold mb-4" style="color: #00695c;">
                                <i class="fas fa-star text-warning me-2"></i>Đặc Tính Nổi Bật
                            </h4>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Độ trắng cao</h6>
                                            <p class="text-muted mb-0">Giấy trắng tinh khiết, không gây mỏi mắt khi đọc lâu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Bề mặt mịn</h6>
                                            <p class="text-muted mb-0">Viết mượt mà, không thấm mực</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Độ bền cao</h6>
                                            <p class="text-muted mb-0">Giấy dày, không bị rách khi gấp hoặc viết nhiều</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Đa năng</h6>
                                            <p class="text-muted mb-0">Phù hợp cho in ấn, photocopy, viết tay</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">An toàn</h6>
                                            <p class="text-muted mb-0">Không chứa chất độc hại, an toàn cho sức khỏe</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Giá tốt</h6>
                                            <p class="text-muted mb-0">Ưu đãi cho mua số lượng lớn</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Tab -->
                    <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                        <div class="doc-card">
                            <h4 class="fw-bold mb-4" style="color: #00695c;">
                                <i class="fas fa-file-alt text-info me-2"></i>Mô Tả Chi Tiết
                            </h4>
                            <div class="text-muted mb-4 description-content" style="line-height: 1.8;">{!! nl2br(e($product->description)) !!}</div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold" style="color: #00acc1;">
                                        <i class="fas fa-list-check me-2"></i>Công dụng:
                                    </h6>
                                    <ul class="text-muted">
                                        <li>In tài liệu văn phòng</li>
                                        <li>Photocopy, scan</li>
                                        <li>Viết tay, ghi chép</li>
                                        <li>In ảnh, brochure</li>
                                        <li>Làm đồ án, báo cáo</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold" style="color: #00acc1;">
                                        <i class="fas fa-shield-alt me-2"></i>Cam kết:
                                    </h6>
                                    <ul class="text-muted">
                                        <li>Hàng chính hãng 100%</li>
                                        <li>Đổi trả trong 7 ngày nếu lỗi</li>
                                        <li>Giao hàng nhanh toàn quốc</li>
                                        <li>Hỗ trợ tư vấn 24/7</li>
                                        <li>Ưu đãi cho đơn hàng lớn</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="alert rounded-4 mt-4" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 50%); border: 2px solid #00acc1;">
                                <i class="fas fa-graduation-cap me-2" style="color: #00acc1;"></i>
                                <strong style="color: #00695c;">Ưu đãi đặc biệt:</strong> 
                                <span class="text-muted">Giảm 10% cho học sinh, sinh viên khi xuất trình thẻ. Mua từ 5 xấp tặng kèm bút bi cao cấp!</span>
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
