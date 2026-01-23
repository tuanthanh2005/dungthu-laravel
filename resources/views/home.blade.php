@extends('layouts.app')

@section('title', 'DungThu.com - Trải Nghiệm & Mua Sắm')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        .category-row {
            display: none;
        }
        .category-item {
            min-width: 0;
        }
        .category-row .cat-box {
            padding: 12px;
        }
        .category-fab {
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6c5ce7, #00cec9);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1050;
        }
        .category-fab i { font-size: 22px; }
        .category-fab-menu {
            position: fixed;
            right: 20px;
            bottom: 90px;
            width: 240px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            padding: 12px;
            display: none;
            z-index: 1050;
        }
        .category-fab-menu.show { display: block; }
        .category-fab-menu .cat-box {
            padding: 12px;
            border: 1px solid #f0f0f0;
            margin-bottom: 8px;
        }
        .category-fab-menu .cat-box:last-child { margin-bottom: 0; }
        
        /* Mobile: Giảm font size section titles */
        @media (max-width: 768px) {
            .section-title {
                font-size: calc(1.5rem - 3px) !important;
            }
        }
        
        /* Mobile: Giảm font size hero section */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: calc(1.5rem + 1vw) !important;
            }
            .hero-section .typing-text {
                font-size: calc(1.5rem + 1vw) !important;
            }
            .hero-section .lead {
                font-size: calc(1rem - 5px) !important;
            }
        }
    </style>
@endpush

@section('content')

    <header class="hero-section text-center">
        <div class="shape" style="top: 20%; left: 10%; width: 50px; height: 50px;"></div>
        <div class="shape" style="top: 60%; right: 15%; width: 80px; height: 80px; animation-delay: 2s;"></div>
        
        <div class="container mt-5" data-aos="zoom-in">
            <h1 class="display-4 fw-bold mb-3">Nơi Bạn Có Thể <span class="typing-text text-warning" id="typewriter"></span></h1>
            <p class="lead opacity-75 mb-4">Kho tài nguyên số, thời trang và công cụ tiện ích miễn phí hàng đầu.</p>
        </div>
    </header>

    <div class="container" style="margin-top: -80px; position: relative; z-index: 10;">
        <div class="category-row"></div>
    </div>

    <div class="category-fab" onclick="toggleCategoryMenu(event)">
        <i class="fas fa-th"></i>
    </div>
    <div class="category-fab-menu" id="categoryFabMenu">
        <div class="cat-box text-center" onclick="filterData('all'); toggleCategoryMenu(event);">
            <i class="fas fa-th-large cat-icon"></i>
            <div class="fw-bold">Tất Cả</div>
        </div>
        <div class="cat-box text-center" onclick="filterData('tech'); toggleCategoryMenu(event);">
            <i class="fas fa-laptop-code cat-icon"></i>
            <div class="fw-bold">Công Nghệ</div>
        </div>
        <div class="cat-box text-center" onclick="filterData('tiktok'); toggleCategoryMenu(event);">
            <i class="fab fa-tiktok cat-icon"></i>
            <div class="fw-bold">Săn Sale TikTok</div>
        </div>
        <div class="cat-box text-center" onclick="filterData('ebooks'); toggleCategoryMenu(event);">
            <i class="fas fa-file-invoice-dollar cat-icon"></i>
            <div class="fw-bold">Tài Liệu Kiếm Tiền</div>
        </div>
        <div class="cat-box text-center" onclick="handleCardExchangeClick(); toggleCategoryMenu(event);">
            <i class="fas fa-credit-card cat-icon"></i>
            <div class="fw-bold">Đổi Thẻ Cào</div>
        </div>
    </div>

    <div class="container" style="margin-top: 80px; padding-top: 20px;">
        <div class="row">
            
            <div class="col-12">
                <!-- Sản Phẩm Nổi Bật -->
                <div id="shop" class="mb-5">
                    <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-right">
                        <div>
                            <span class="text-primary fw-bold text-uppercase ls-1">Dành cho bạn</span>
                            <h3 class="fw-bold section-title">Sản Phẩm Nổi Bật</h3>
                        </div>
                        <a href="{{ route('shop') }}" class="text-decoration-none fw-bold">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="row row-cols-2 row-cols-md-4 g-4" id="product-grid">
                        @foreach($featuredProducts as $product)
                        <div class="col" data-aos="fade-up">
                            <div class="product-card">
                                <div class="card-img-wrap">
                                    <span class="badge-custom">{{ strtoupper($product->category) }}</span>
                                    <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                                </div>
                                <div class="p-3">
                                    <h6 class="fw-bold product-title-2lines">{{ $product->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light rounded-circle text-primary">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>             
                <!-- Săn Sale Tiktok Shop -->
                <div id="tiktok-deals" class="mb-5" data-tiktok-section>
                    <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-right">
                        <div>
                            <span class="text-danger fw-bold text-uppercase ls-1">
                                <i class="fab fa-tiktok"></i> HOT DEAL
                            </span>
                            <h3 class="fw-bold section-title">Săn Sale Tiktok Shop</h3>
                            <p class="text-muted mb-0">Giảm giá cực sốc, số lượng có hạn!</p>
                        </div>
                        <a href="{{ route('shop', ['category' => 'tiktok']) }}" class="text-decoration-none fw-bold">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>

                    @if($tiktokDeals->count() > 0)
                    <div class="row row-cols-2 row-cols-md-4 g-4" id="tiktok-grid">
                        @foreach($tiktokDeals as $deal)
                        <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="tiktok-deal-card">
                                @if($deal->discount_percent)
                                    <span class="tiktok-badge">-{{ $deal->discount_percent }}%</span>
                                @endif
                                <div class="tiktok-img-wrap">
                                    <img src="{{ $deal->image ? asset('storage/' . $deal->image) : 'https://via.placeholder.com/300' }}" 
                                         alt="{{ $deal->name }}">
                                </div>
                                <div class="p-2">
                                    <h6 class="fw-bold text-truncate small mb-1">{{ $deal->name }}</h6>
                                    @if($deal->original_price && $deal->sale_price)
                                        <div class="mb-1">
                                            <span class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">
                                                {{ $deal->formatted_original_price }}
                                            </span>
                                        </div>
                                        <div class="text-danger fw-bold">{{ $deal->formatted_sale_price }}</div>
                                    @endif
                                    <a href="{{ $deal->tiktok_link }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-danger w-100 mt-2">
                                        <i class="fab fa-tiktok"></i> Mua Ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Chưa có deal nào. Vui lòng quay lại sau!
                    </div>
                    @endif
                </div>



                <div id="blog" class="pt-4 border-top">
                    <div class="mb-4" data-aos="fade-right">
                        <span class="text-warning fw-bold text-uppercase ls-1">Kiến thức & Thủ thuật</span>
                        <h3 class="fw-bold section-title">Blog Chia Sẻ</h3>
                        <p class="text-muted">Cập nhật xu hướng công nghệ, mẹo phối đồ và hướng dẫn dùng tool.</p>
                    </div>

                    <div class="row">
                        @foreach($latestBlogs as $index => $blog)
                        <div class="col-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="blog-card">
                                <img src="{{ $blog->image ?? 'https://via.placeholder.com/300' }}" class="blog-thumb" alt="{{ $blog->title }}">
                                <div class="blog-content">
                                    <div class="blog-date">
                                        <i class="far fa-clock"></i> {{ $blog->formatted_date }} • {{ ucfirst($blog->category) }}
                                    </div>
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="blog-title">{{ $blog->title }}</a>
                                    <p class="blog-excerpt">{{ $blog->excerpt }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-primary rounded-pill px-4">Xem thêm bài viết</a>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
    <script>
        const categoryMenu = document.getElementById('categoryFabMenu');
        function toggleCategoryMenu(event) {
            event.stopPropagation();
            categoryMenu.classList.toggle('show');
        }
        document.addEventListener('click', function() {
            categoryMenu.classList.remove('show');
        });

        function handleCardExchangeClick() {
            @auth
                // Nếu đã đăng nhập, chuyển thẳng qua trang đổi thẻ cào
                window.location.href = "{{ route('card-exchange.index') }}";
            @else
                // Nếu chưa đăng nhập, yêu cầu đăng nhập
                alert('Vui lòng đăng nhập để sử dụng tính năng đổi thẻ cào!');
                window.location.href = "{{ route('login') }}";
            @endauth
        }
    </script>
    @if(session('scrollTo'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const section = document.querySelector('[data-tiktok-section]');
            if (section) {
                setTimeout(() => {
                    section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Thêm animation highlight
                    section.style.animation = 'pulse 1s ease-in-out 2';
                }, 500);
            }
        });
    </script>
    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); box-shadow: 0 0 30px rgba(255, 0, 80, 0.3); }
        }
    </style>
    @endif
@endpush
