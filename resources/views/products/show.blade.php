@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .product-detail-image {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .description-content {
            font-size: 1rem;
        }
        @media (max-width: 768px) {
            .description-content {
                font-size: calc(1rem - 5px);
            }
        }
        .product-detail-image:hover {
            transform: scale(1.02);
        }
        .info-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .info-badge i {
            font-size: 1.5rem;
            margin-right: 15px;
        }
        .nav-tabs .nav-link {
            border: none;
            background: white;
            margin: 0 5px;
            border-radius: 12px;
            padding: 15px 30px;
            color: #6c757d;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102,126,234,0.3);
        }
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(102,126,234,0.4);
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
<div class="container py-5" style="margin-top: 80px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Cửa hàng</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4" data-aos="fade-right">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                 class="img-fluid product-detail-image w-100" 
                 alt="{{ $product->name }}">
        </div>
        
        <div class="col-lg-6" data-aos="fade-left">
            <span class="badge bg-primary mb-2">{{ strtoupper($product->category) }}</span>
            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
            <p class="lead text-muted mb-4">{{ Str::limit($product->description, 150, '......') }}</p>
            
            <div class="mb-4">
                <h2 class="text-primary fw-bold mb-0">{{ $product->formatted_price }}</h2>
                <small class="text-muted">Giá đã bao gồm VAT</small>
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
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow" 
                            {{ $product->stock > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ
                    </button>
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua
                    </a>
                </div>
            </form>

            <div class="mt-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-star text-warning"></i> Ưu điểm nổi bật</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="info-badge">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Chính hãng 100%</strong><br>
                                <small>Cam kết hàng thật</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-tools"></i>
                            <div>
                                <strong>Bảo hành 12 tháng</strong><br>
                                <small>Đổi trả miễn phí</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-shipping-fast"></i>
                            <div>
                                <strong>Giao hàng nhanh</strong><br>
                                <small>Toàn quốc 24h</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>Hỗ trợ 24/7</strong><br>
                                <small>Tư vấn miễn phí</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill border-0" id="productTabs" role="tablist" data-aos="fade-up">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="features-tab" data-bs-toggle="tab" 
                            data-bs-target="#features" type="button" role="tab">
                        <i class="fas fa-star me-2"></i>Tính Năng Nổi Bật
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-align-left me-2"></i>Mô Tả Chi Tiết
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button" role="tab">
                        <i class="fas fa-comments me-2"></i>Đánh Giá Sản Phẩm
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="productTabsContent">
                <!-- Features Tab -->
                <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-star text-warning me-2"></i>Tính Năng Nổi Bật
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Thiết kế hiện đại</h6>
                                            <p class="text-muted mb-0">Giao diện đẹp mắt, sang trọng phù hợp mọi không gian</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Chất lượng cao cấp</h6>
                                            <p class="text-muted mb-0">Sản phẩm được kiểm định chất lượng nghiêm ngặt</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Tiết kiệm năng lượng</h6>
                                            <p class="text-muted mb-0">Công nghệ tiên tiến giúp tiết kiệm điện năng hiệu quả</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Dễ dàng sử dụng</h6>
                                            <p class="text-muted mb-0">Hướng dẫn chi tiết, thao tác đơn giản</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">An toàn tuyệt đối</h6>
                                            <p class="text-muted mb-0">Đạt chuẩn an toàn quốc tế, bảo vệ người dùng</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Bền bỉ lâu dài</h6>
                                            <p class="text-muted mb-0">Tuổi thọ cao, bảo hành chính hãng 12 tháng</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Tab -->
                <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-align-left text-primary me-2"></i>Mô Tả Chi Tiết
                            </h4>
                            <div class="text-muted mb-4 description-content" style="line-height: 1.8;" >{{ $product->description }}</div>
                            
                            <div class="border-top pt-4 mt-4">
                                <h5 class="fw-bold text-primary mb-4">
                                    <i class="fas fa-cube me-2"></i>Thông Số Kỹ Thuật
                                </h5>
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-list-ul text-primary me-2"></i><strong>Danh mục:</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ strtoupper($product->category) }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-barcode text-primary me-2"></i><strong>SKU:</strong>
                                            <p class="ms-4 mb-0 text-muted">#{{ $product->id }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-check-circle text-success me-2"></i><strong>Tình trạng:</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-globe text-primary me-2"></i><strong>Xuất xứ:</strong>
                                            <p class="ms-4 mb-0 text-muted">Chính hãng</p>
                                        </div>
                                    </div>
                                </div>

                                @if($product->specs)
                                    <div class="row g-4 mt-2">
                                        @foreach($product->specs as $key => $value)
                                            <div class="col-lg-6">
                                                <div class="p-3 bg-light rounded-3 mb-3">
                                                    <i class="fas fa-info-circle text-primary me-2"></i><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    <p class="ms-4 mb-0 text-muted">{{ $value }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="border-top pt-4 mt-4">
                                <h5 class="fw-bold text-primary mb-4">
                                    <i class="fas fa-box-open me-2"></i>Thông Tin Thêm
                                </h5>
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-shield-alt text-primary me-2"></i><strong>Bảo hành:</strong>
                                            <p class="ms-4 mb-0 text-muted">12 tháng</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-credit-card text-primary me-2"></i><strong>Thanh toán:</strong>
                                            <p class="ms-4 mb-0 text-muted">COD, Banking, Transfer</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-shipping-fast text-primary me-2"></i><strong>Giao hàng:</strong>
                                            <p class="ms-4 mb-0 text-muted">Toàn quốc (24-48h)</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-undo text-primary me-2"></i><strong>Đổi trả:</strong>
                                            <p class="ms-4 mb-0 text-muted">7 ngày từ ngày mua</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-4 rounded-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Lưu ý:</strong> Sản phẩm được đóng gói cẩn thận, kiểm tra kỹ càng trước khi giao hàng. 
                                Quý khách vui lòng kiểm tra sản phẩm trước khi thanh toán.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-comments text-warning me-2"></i>Đánh Giá Sản Phẩm
                            </h4>
                            
                            <!-- Overall Rating -->
                            <div class="text-center mb-5 p-4 bg-light rounded-4">
                                <div class="display-4 fw-bold text-primary mb-2">{{ number_format($averageRating, 1) }}</div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - $averageRating < 1)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted mb-0">Dựa trên <strong>{{ $totalReviews }} đánh giá</strong></p>
                            </div>

                            <!-- Comment Form (Only for logged in users) -->
                            @auth
                            <div class="card bg-light border-0 mb-4 rounded-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-edit text-primary me-2"></i>Viết đánh giá của bạn
                                    </h5>
                                    <form action="{{ route('product.comment', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Đánh giá của bạn <span class="text-danger">*</span></label>
                                            <div class="rating-input mb-2">
                                                <input type="radio" name="rating" value="5" id="star5" required>
                                                <label for="star5" title="5 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="4" id="star4">
                                                <label for="star4" title="4 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="3" id="star3">
                                                <label for="star3" title="3 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="2" id="star2">
                                                <label for="star2" title="2 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="1" id="star1">
                                                <label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                                            </div>
                                            @error('rating')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nhận xét <span class="text-danger">*</span></label>
                                            <textarea name="comment" class="form-control rounded-3" rows="4" 
                                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required>{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info rounded-4 mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Bạn cần <a href="{{ route('login') }}" class="alert-link fw-bold">đăng nhập</a> để viết đánh giá.
                            </div>
                            @endauth

                            <!-- Individual Reviews -->
                            @forelse($product->comments as $comment)
                            <div class="review-item mb-4 pb-4 border-bottom">
                                <div class="d-flex align-items-start">
                                    <div class="avatar me-3">
                                        <div class="bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0, 4)] }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-weight: bold;">
                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0">{{ $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $comment->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-muted mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
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
