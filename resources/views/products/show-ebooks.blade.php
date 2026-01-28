@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .ebook-wrapper {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2dfdb 100%);
            padding: 40px 0;
            min-height: 100vh;
        }
        .description-content {
            font-size: 1rem;
        }
        @media (max-width: 768px) {
            .description-content {
                font-size: calc(1rem - 5px);
            }
        }
        .ebook-card {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 50px rgba(0,188,212,0.3);
        }
        .product-detail-image {
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .product-detail-image:hover {
            transform: scale(1.03);
        }
        .ebook-badge {
            background: linear-gradient(135deg, #00bcd4 0%, #00acc1 100%);
            color: white;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,188,212,0.3);
        }
        .ebook-badge i {
            font-size: 2rem;
            margin-right: 20px;
        }
        .file-info-box {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2dfdb 100%);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .file-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0,188,212,0.2);
        }
        .file-info-item:last-child {
            border-bottom: none;
        }
        .download-btn {
            background: linear-gradient(135deg, #00bcd4 0%, #00acc1 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: bold;
            border: none;
            transition: all 0.3s;
        }
        .download-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,188,212,0.4);
            color: white;
        }
        .download-btn:disabled {
            background: linear-gradient(135deg, #ccc 0%, #999 100%);
            cursor: not-allowed;
        }
        .ebook-tab.nav-link {
            border: none;
            background: linear-gradient(135deg, #e0f7fa 0%, #b2dfdb 100%);
            margin: 0 5px;
            border-radius: 15px;
            padding: 15px 30px;
            color: #00695c;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0,188,212,0.3);
            transition: all 0.3s ease;
        }
        .ebook-tab.nav-link:hover {
            background: linear-gradient(135deg, #00bcd4 0%, #00acc1 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,188,212,0.4);
        }
        .ebook-tab.nav-link.active {
            background: linear-gradient(135deg, #00bcd4 0%, #00acc1 100%);
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
<div class="ebook-wrapper">
    <div class="container py-5" style="margin-top: 50px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00695c;">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: #00695c;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" style="color: #00bcd4;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="ebook-card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600x800/e0f7fa/00bcd4?text=Ebook' }}" 
                         class="img-fluid product-detail-image w-100" 
                         alt="{{ $product->name }}">
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="ebook-card">
                    <div class="ebook-badge">
                        <i class="fas fa-file-pdf"></i>
                        <div>
                            <strong>Tài liệu kiếm tiền</strong>
                            <div class="small">Digital Download</div>
                        </div>
                    </div>
                    
                    <h1 class="fw-bold mb-3" style="color: #00695c;">{{ $product->name }}</h1>
                    <p class="lead text-muted mb-4">{{ Str::limit($product->description, 150, '......') }}</p>
                    
                    <!-- File Information -->
                    @if($product->hasFile())
                    <div class="file-info-box">
                        <h5 class="fw-bold mb-3" style="color: #00695c;">
                            <i class="fas fa-info-circle me-2"></i>Thông tin file
                        </h5>
                        <div class="file-info-item">
                            <span><i class="fas fa-file me-2"></i>Định dạng:</span>
                            <strong>{{ strtoupper($product->file_type ?? 'PDF') }}</strong>
                        </div>
                        <div class="file-info-item">
                            <span><i class="fas fa-hdd me-2"></i>Dung lượng:</span>
                            <strong>{{ $product->formatted_file_size ?? 'N/A' }}</strong>
                        </div>
                        <div class="file-info-item">
                            <span><i class="fas fa-download me-2"></i>Tải về:</span>
                            <strong>Không giới hạn</strong>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-4 p-4 rounded-4" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2dfdb 50%);">
                        <div class="d-flex align-items-end gap-3 flex-wrap">
                            <h2 class="fw-bold mb-0" style="color: #00bcd4;">{{ $product->formatted_price }}</h2>
                            @if($product->is_on_sale)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-muted text-decoration-line-through">{{ $product->formatted_original_price }}</span>
                                    <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mua 1 lần - Sử dụng mãi mãi</small>
                    </div>
                    
                    @if($product->stock > 0)
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="alert alert-success d-inline-flex align-items-center mb-0">
                                <i class="fas fa-check-circle"></i> Sẵn sàng tải về
                            </div>
                            <small class="text-muted">Gia hạn theo tháng 3/6/12 tháng: liên hệ admin hoặc box chat</small>
                        </div>
                        @else
                        <div class="alert alert-danger d-inline-block">
                            <i class="fas fa-times-circle"></i> Tạm ngừng bán
                        </div>
                    @endif
                    
                    @if($hasPurchased && $product->hasFile())
                        <!-- Download Button for Purchased -->
                        <div class="mt-4">
                            <a href="{{ route('product.download', $product) }}" 
                               class="btn download-btn btn-lg w-100 shadow">
                                <i class="fas fa-download me-2"></i> Tải về ngay
                            </a>
                            <p class="text-muted small mt-2 text-center">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Bạn đã mua tài liệu này
                            </p>
                        </div>
                    @else
                        <!-- Purchase Button -->
                        <form action="{{ route('cart.buy-now', $product->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn download-btn btn-lg w-100 shadow"
                                    {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart me-2"></i> Mua ngay
                            </button>
                        </form>
                        <p class="text-muted small mt-2 text-center">
                            <i class="fas fa-lock me-1"></i>
                            Thanh toán an toàn - Tải về sau khi mua
                        </p>
                    @endif
                    
                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill border-0 mb-4" id="productTabs" role="tablist" data-aos="fade-up">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ebook-tab active" id="features-tab" data-bs-toggle="tab" 
                                data-bs-target="#features" type="button" role="tab">
                            <i class="fas fa-list me-2"></i>Nội Dung
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ebook-tab" id="description-tab" data-bs-toggle="tab" 
                                data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Chi Tiết
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ebook-tab" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-comments me-2"></i>Đánh Giá
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Features Tab -->
                    <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                        <div class="ebook-card">
                            <h4 class="fw-bold mb-4" style="color: #00695c;">
                                <i class="fas fa-book-open text-info me-2"></i>Nội dung tài liệu
                            </h4>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Kiến thức nền tảng</h6>
                                            <p class="text-muted mb-0">Các khái niệm cơ bản để bắt đầu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Chiến lược thực chiến</h6>
                                            <p class="text-muted mb-0">Áp dụng ngay vào công việc</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success fs-3 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold">Case study thực tế</h6>
                                            <p class="text-muted mb-0">Học từ những người thành công</p>
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
                            <div class="text-muted mb-4 description-content" style="line-height: 1.8;">{!! nl2br(e($product->description)) !!}</div>
                            
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
