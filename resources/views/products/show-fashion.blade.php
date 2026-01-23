@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .fashion-wrapper {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            padding: 40px 0;
            min-height: 100vh;
        }
        .fashion-card {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 50px rgba(252,182,159,0.3);
        }
        .product-detail-image {
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .product-detail-image:hover {
            transform: scale(1.03);
        }
        .fashion-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(255,107,107,0.3);
            cursor: default;
            pointer-events: none;
        }
        .fashion-badge i {
            font-size: 2rem;
            margin-right: 20px;
        }
        .size-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .size-btn {
            width: 50px;
            height: 50px;
            border: 2px solid #ff6b6b;
            background: white;
            border-radius: 10px;
            font-weight: bold;
            color: #ff6b6b;
            transition: all 0.3s;
        }
        .size-btn:hover, .size-btn.active {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.4);
        }
        .color-selector {
            display: flex;
            gap: 15px;
        }
        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        .color-option:hover, .color-option.active {
            border-color: #ff6b6b;
            transform: scale(1.2);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .fashion-tab.nav-link {
            border: none;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            margin: 0 5px;
            border-radius: 15px;
            padding: 15px 30px;
            color: #8b4513;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(252,182,159,0.3);
            transition: all 0.3s ease;
        }
        .fashion-tab.nav-link:hover {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,107,107,0.4);
        }
        .fashion-tab.nav-link.active {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(255,107,107,0.5);
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
<div class="fashion-wrapper">
    <div class="container py-5" style="margin-top: 50px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #8b4513;">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: #8b4513;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" style="color: #ff6b6b;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="fashion-card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                         class="img-fluid product-detail-image w-100" 
                         alt="{{ $product->name }}">
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="fashion-card">
                    <span class="badge mb-3" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); font-size: 14px;">
                        <i class="fas fa-tshirt me-2"></i>{{ strtoupper($product->category) }}
                    </span>
                    <h1 class="fw-bold mb-3" style="color: #8b4513;">{{ $product->name }}</h1>
                    <p class="lead text-muted mb-4">{{ $product->description }}</p>
                    
                    <div class="mb-4 p-4 rounded-4" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 50%);">
                        <h2 class="fw-bold mb-0" style="color: #ff6b6b;">{{ $product->formatted_price }}</h2>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Miễn phí vận chuyển</small>
                    </div>

                    <!-- Size Selector -->
                    <div class="mb-4">
                        <label class="fw-bold mb-2" style="color: #8b4513;">
                            <i class="fas fa-ruler me-2"></i>Chọn kích thước:
                        </label>
                        <div class="size-selector">
                            <button class="size-btn">S</button>
                            <button class="size-btn active">M</button>
                            <button class="size-btn">L</button>
                            <button class="size-btn">XL</button>
                            <button class="size-btn">XXL</button>
                        </div>
                    </div>

                    <!-- Color Selector -->
                    <div class="mb-4">
                        <label class="fw-bold mb-2" style="color: #8b4513;">
                            <i class="fas fa-palette me-2"></i>Chọn màu sắc:
                        </label>
                        <div class="color-selector">
                            <div class="color-option active" style="background: #000;" title="Đen"></div>
                            <div class="color-option" style="background: #fff; border: 1px solid #ddd;" title="Trắng"></div>
                            <div class="color-option" style="background: #ff6b6b;" title="Đỏ"></div>
                            <div class="color-option" style="background: #4dabf7;" title="Xanh dương"></div>
                            <div class="color-option" style="background: #ffd43b;" title="Vàng"></div>
                        </div>
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
                                    style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; border: none;"
                                    {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-bag me-2"></i> Thêm vào giỏ
                            </button>
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua
                            </a>
                        </div>
                    </form>

                    <!-- Fashion Features -->
                    <div class="mt-4">
                        <div class="fashion-badge">
                            <i class="fas fa-gem"></i>
                            <div>
                                <strong>Chất liệu cao cấp</strong><br>
                                <small>100% Cotton thoáng mát</small>
                            </div>
                        </div>
                        <div class="fashion-badge" style="background: linear-gradient(135deg, #ffa502 0%, #ff6348 100%);">
                            <i class="fas fa-star"></i>
                            <div>
                                <strong>Thiết kế độc quyền</strong><br>
                                <small>Xu hướng thời trang 2026</small>
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
                        <button class="nav-link fashion-tab active" id="features-tab" data-bs-toggle="tab" 
                                data-bs-target="#features" type="button" role="tab">
                            <i class="fas fa-heart me-2"></i>Đặc Điểm
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fashion-tab" id="description-tab" data-bs-toggle="tab" 
                                data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Hướng Dẫn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fashion-tab" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-comments me-2"></i>Đánh Giá
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Features Tab -->
                    <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                        <div class="fashion-card">
                            <h4 class="fw-bold mb-4" style="color: #8b4513;">
                                <i class="fas fa-star text-warning me-2"></i>Đặc Điểm Nổi Bật
                            </h4>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Vải cao cấp</h6>
                                            <p class="text-muted mb-0">100% cotton tự nhiên, thấm hút mồ hôi tốt</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Form dáng chuẩn</h6>
                                            <p class="text-muted mb-0">Ôm vừa vặn, tôn dáng người mặc</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Màu sắc bền đẹp</h6>
                                            <p class="text-muted mb-0">Công nghệ nhuộm hiện đại, không phai màu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Dễ phối đồ</h6>
                                            <p class="text-muted mb-0">Phù hợp mọi phong cách, mọi lứa tuổi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Tab -->
                    <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                        <div class="fashion-card">
                            <h4 class="fw-bold mb-4" style="color: #8b4513;">
                                <i class="fas fa-book text-danger me-2"></i>Hướng Dẫn Sử Dụng & Bảo Quản
                            </h4>
                            <p class="lead text-muted mb-4">{{ $product->description }}</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold" style="color: #ff6b6b;">
                                        <i class="fas fa-hand-sparkles me-2"></i>Hướng dẫn giặt:
                                    </h6>
                                    <ul class="text-muted">
                                        <li>Giặt máy ở nhiệt độ tối đa 30°C</li>
                                        <li>Không sử dụng chất tẩy mạnh</li>
                                        <li>Giặt với màu tương tự</li>
                                        <li>Lộn trái khi giặt</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold" style="color: #ff6b6b;">
                                        <i class="fas fa-wind me-2"></i>Hướng dẫn bảo quản:
                                    </h6>
                                    <ul class="text-muted">
                                        <li>Phơi nơi thoáng mát, tránh ánh nắng trực tiếp</li>
                                        <li>Ủi ở nhiệt độ trung bình</li>
                                        <li>Không vắt mạnh</li>
                                        <li>Bảo quản nơi khô ráo</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="alert rounded-4 mt-4" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 50%); border: none;">
                                <i class="fas fa-exchange-alt me-2"></i>
                                <strong>Chính sách đổi trả:</strong> Đổi size miễn phí trong vòng 7 ngày nếu sản phẩm chưa qua sử dụng.
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
        
        // Size selector
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Color selector
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endpush
