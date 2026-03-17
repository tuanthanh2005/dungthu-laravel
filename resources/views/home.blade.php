@extends('layouts.app')

@section('title', 'DungThu.com - Trải Nghiệm & Mua Sắm')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(\App\Helpers\PathHelper::publicRootPath('css/home.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/category-filter.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        body { background-color: #f5f5f5; }
        
        .shopee-banner-wrapper { margin-top: 1.5rem; margin-bottom: 20px; }
        #shopeeCarousel .carousel-item { border-radius: 4px; height: 260px; overflow: hidden; }
        
        .shopee-container { background: #fff; margin-bottom: 20px; box-shadow: 0 1px 1px 0 rgba(0,0,0,.05); border-radius: 3px; }
        .shopee-header { padding: 15px 20px; border-bottom: 1px solid rgba(0,0,0,.05); display: flex; justify-content: space-between; align-items: center; }
        .shopee-title { font-size: 1.1rem; color: var(--primary); font-weight: 500; text-transform: uppercase; margin: 0; }
        .shopee-link { color: var(--primary); font-size: 0.9rem; text-decoration: none; }
        .shopee-link:hover { opacity: 0.8; }
            
        /* Shopee Quick Links */
        .quick-links { display: flex; justify-content: space-between; text-center; background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 1px 0 rgba(0,0,0,.05); margin-bottom: 20px; flex-wrap: wrap; }
        .ql-item { flex: 1; text-align: center; color: #333; text-decoration: none; min-width: 80px; margin-bottom: 10px; }
        .ql-item:hover { color: var(--primary); transform: translateY(-2px); transition: 0.2s; }
        .ql-icon { width: 45px; height: 45px; margin: auto; background: rgba(108, 92, 231, 0.1); border-radius: 30%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem; }
        .ql-text { font-size: 13px; margin-top: 8px; font-weight: 500;}

        /* Shopee Categories */
        .shopee-cats { display: flex; flex-wrap: wrap; border-top: 1px solid rgba(0,0,0,.05); border-left: 1px solid rgba(0,0,0,.05); }
        .shopee-cat-item { width: calc(100% / 8); padding: 20px 10px; text-align: center; border-right: 1px solid rgba(0,0,0,.05); border-bottom: 1px solid rgba(0,0,0,.05); text-decoration: none; color: #333; transition: all 0.1s; background: #fff;}
        .shopee-cat-item:hover { transform: translateY(-1px); box-shadow: 0 1px 10px 0 rgba(0,0,0,.08); z-index: 1; border-color: rgba(0,0,0,.05); color: var(--primary);}
        .shopee-cat-icon { width: 60px; height: 60px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; color: var(--primary); font-size: 1.8rem; overflow: hidden; }
        .shopee-cat-icon img { width: 100%; height: 100%; object-fit: cover; }
        .shopee-cat-name { font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2; }
        @media (max-width: 992px) { .shopee-cat-item { width: calc(100% / 5); } }
        @media (max-width: 768px) { .shopee-cat-item { width: calc(100% / 4); } }
        @media (max-width: 576px) { .shopee-cat-item { width: calc(100% / 3); } }
        
        /* Shopee Product Card */
        .shopee-product { background: #fff; border-radius: 3px; transition: transform 0.1s, box-shadow 0.1s, border 0.1s; border: 1px solid rgba(0,0,0,.05); position: relative; display: flex; flex-direction: column; overflow: hidden; height: 100%; text-decoration: none;}
        .shopee-product:hover { border: 1px solid var(--primary); transform: translateY(-2px); box-shadow: 0 2px 10px 0 rgba(0,0,0,.05); z-index: 1; }
        .sp-img-wrap { width: 100%; padding-top: 100%; position: relative; background: #f8f9fa;}
        .sp-img-wrap img { position: absolute; top:0; left:0; width:100%; height:100%; object-fit: cover; }
        .sp-info { padding: 8px; display: flex; flex-direction: column; flex-grow: 1; }
        .sp-title { font-size: 0.85rem; color: rgba(0,0,0,.87); line-height: 1.1rem; height: 2.2rem; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; margin-bottom: 5px;}
        .sp-price-row { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 5px; }
        .sp-price { color: var(--primary); font-size: 1.05rem; font-weight: 500;}
        .sp-original-price { color: rgba(0,0,0,.54); text-decoration: line-through; font-size: 0.75rem; margin-right: 5px;}
        .sp-badge { position: absolute; top: 0; right: 0; background: rgba(255,212,36,.9); color: #ee4d2d; padding: 2px 4px; font-size: 0.75rem; font-weight: bold; }
        .sp-badge-category { position: absolute; top: 5px; left: -5px; background: var(--primary); color: #fff; padding: 2px 8px; font-size: 0.7rem; border-radius: 0 3px 3px 0; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2); }
        .sp-badge-category::after { content: ''; position: absolute; left: 0; bottom: -5px; border-top: 5px solid #2d3436; border-left: 5px solid transparent; }
        .out-of-stock-overlay { position: absolute; inset: 0; background: rgba(255,255,255,.6); display: flex; align-items: center; justify-content: center; z-index: 2; }
        .out-of-stock-text { background: rgba(0,0,0,.6); color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; }
        
        .fs-timer { display: flex; gap: 5px; }
        .fs-timer .t-box { background: #333; color: #fff; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 3px; font-weight: bold; font-size: 0.9rem; }
        .fs-timer span { font-weight: bold; font-size: 1.1rem; color: #333; }
        
        /* Mall banner section */
        .mall-left-banner { height: 100%; border-radius: 3px; background: linear-gradient(135deg, #d32f2f, #f44336); display:flex; align-items:center; justify-content:center; color:white; min-height: 250px;}
        .mall-left-banner h2 { font-weight: bold; letter-spacing: 1px;}
        
        .daily-discover-header { background: #fff; border-bottom: 4px solid var(--primary); padding: 15px; text-align: center; color: var(--primary); font-weight: bold; font-size: 1.2rem; margin-bottom: 20px; box-shadow: 0 1px 1px 0 rgba(0,0,0,.05); position: sticky; top: 60px; z-index: 100;}
        
        /* Sticky Cart CTA inside product */
        .cart-btn { background: transparent; border: 1px solid var(--primary); color: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;}
        .cart-btn:hover { background: var(--primary); color: #fff; }
        .cart-btn:disabled { border-color: #ccc; color: #ccc; cursor: not-allowed; }
        .cart-btn:disabled:hover { background: transparent; color: #ccc; }
        
        .blog-shopee-card { background: #fff; border-radius: 3px; overflow: hidden; box-shadow: 0 1px 1px 0 rgba(0,0,0,.05); margin-bottom: 15px; transition: 0.2s;}
        .blog-shopee-card:hover { transform: translateY(-2px); box-shadow: 0 2px 10px 0 rgba(0,0,0,.1); }
    </style>
@endpush

@section('content')
<div style="background-color: #f5f5f5; min-height: 100vh; padding-bottom: 40px; padding-top: 15px;">

    <!-- Banners -->
    <div class="container shopee-banner-wrapper" data-aos="fade-down">
        <div class="row g-2">
            <div class="col-md-8">
                <div id="shopeeCarousel" class="carousel slide shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div style="background: linear-gradient(135deg, var(--primary), var(--accent)); width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; color: white;">
                                 <div class="text-center">
                                     <h2 class="fw-bold mb-3 display-5" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Nơi Bạn Có Thể</h2>
                                     <p class="lead mb-0 text-white">Kho tài nguyên số miễn phí hàng đầu.</p>
                                 </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div style="background: linear-gradient(135deg, #2d3436, var(--primary)); width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; color: white;">
                                 <div class="text-center">
                                     <h2 class="fw-bold mb-3 display-5" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Giảm Giá Khủng</h2>
                                     <p class="lead mb-0 text-white">Các deal sốc dành riêng cho bạn mỗi ngày.</p>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#shopeeCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#shopeeCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-flex flex-column gap-2">
                <div style="background: linear-gradient(135deg, var(--accent), var(--secondary)); flex: 1; border-radius: 4px; display:flex; justify-content:center; align-items:center; color:white; font-weight:bold; font-size: 1.2rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <i class="fas fa-gift me-2"></i> QUÀ TẶNG THÀNH VIÊN
                </div>
                <div style="background: linear-gradient(135deg, #ff9ff3, #feca57); flex: 1; border-radius: 4px; display:flex; justify-content:center; align-items:center; color:white; font-weight:bold; font-size: 1.2rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                    <i class="fas fa-crown me-2"></i> DEAL ĐỘC QUYỀN
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="container quick-links" data-aos="fade-up">
        <a href="#" class="ql-item">
            <div class="ql-icon"><i class="fas fa-shipping-fast"></i></div>
            <div class="ql-text">Miễn Phí Vận Chuyển</div>
        </a>
        <a href="#" class="ql-item">
            <div class="ql-icon"><i class="fas fa-tags"></i></div>
            <div class="ql-text">Voucher Giảm Giá</div>
        </a>
        <a href="#" class="ql-item" data-bs-toggle="modal" data-bs-target="#guideModal">
            <div class="ql-icon"><i class="fas fa-info-circle"></i></div>
            <div class="ql-text">Hướng Dẫn Mua Hàng</div>
        </a>
        <a href="#" class="ql-item">
            <div class="ql-icon"><i class="fas fa-gift"></i></div>
            <div class="ql-text">Quà Tặng Hấp Dẫn</div>
        </a>
        <a href="#blog" class="ql-item">
            <div class="ql-icon"><i class="fas fa-book-open"></i></div>
            <div class="ql-text">Blog Kiến Thức</div>
        </a>
    </div>

    <!-- Modal Hướng Dẫn Mua Hàng -->
    <div class="modal fade" id="guideModal" tabindex="-1" aria-labelledby="guideModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style="background: var(--primary); color: white;">
            <h5 class="modal-title" id="guideModalLabel"><i class="fas fa-info-circle me-2"></i> Quy Trình Mua Hàng</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
                <h5><i class="fas fa-check-circle text-success me-2"></i>Bước 1: Chọn sản phẩm</h5>
                <p class="ms-4 mb-3 text-muted">Tìm kiếm và chọn sản phẩm/tài nguyên bạn muốn mua rồi nhấn Thêm vào giỏ hàng.</p>
            </div>
            <div class="mb-3">
                <h5><i class="fas fa-check-circle text-success me-2"></i>Bước 2: Thanh toán & Chuyển khoản</h5>
                <p class="ms-4 mb-3 text-muted">Thực hiện đặt hàng và <strong>chuyển khoản</strong> theo thông tin hiển thị ở trang thanh toán.</p>
            </div>
            <div class="mb-3">
                <h5><i class="fas fa-check-circle text-success me-2"></i>Bước 3: Nhận hàng (10-15p)</h5>
                <p class="ms-4 mb-0 text-muted">Sau khi chuyển khoản thành công, vui lòng chờ khoảng 10-15 phút để Admin kích hoạt sản phẩm/hoặc gửi tài khoản cho bạn.</p>
            </div>
            
            <div class="alert alert-info mt-4 mb-0">
                <i class="fas fa-headset me-2"></i> <strong>Hỗ trợ nhanh:</strong><br>
                Nếu cần gấp hoặc bị lỗi, bạn có thể nhắn tin cho Admin qua biểu tượng chat ở góc phải màn hình hoặc mục <a href="{{ route('contact.index') }}" class="fw-bold text-decoration-none">Liên hệ</a>.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đã hiểu</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories / Danh Mục -->
    @if(isset($categories) && $categories->count() > 0)
    <div class="container shopee-container" data-aos="fade-up">
        <div class="shopee-header">
            <h3 class="shopee-title">DANH MỤC</h3>
        </div>
        <div class="shopee-cats">
            <a href="{{ route('shop') }}" class="shopee-cat-item">
                <div class="shopee-cat-icon"><i class="fas fa-th-large"></i></div>
                <div class="shopee-cat-name">Tất Cả</div>
            </a>
            @foreach($categories as $category)
            <a href="{{ route('shop', ['category_id' => $category->id]) }}" class="shopee-cat-item">
                <div class="shopee-cat-icon">
                    @if($category->image)
                        <img src="{{ $category->image }}" alt="{{ $category->name }}">
                    @else
                        @switch($category->type)
                            @case('tech') <i class="fas fa-laptop"></i> @break
                            @case('ebooks') <i class="fas fa-book"></i> @break
                            @case('doc') <i class="fas fa-file-alt"></i> @break
                            @default <i class="fas fa-box"></i>
                        @endswitch
                    @endif
                </div>
                <div class="shopee-cat-name">{{ $category->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Flash Sale -->
    @if(isset($saleProducts) && $saleProducts->count() > 0)
    <div class="container shopee-container" id="flash-sale" data-countdown-end="{{ $saleEndsAt?->getTimestamp() * 1000 }}">
        <div class="shopee-header" style="border-bottom: 1px solid rgba(0,0,0,.05);">
            <div class="d-flex align-items-center">
                <h3 class="shopee-title me-3 mb-0" style="color: #ee4d2d; font-size: 1.25rem;">
                    <i class="fas fa-bolt"></i> FLASH SALE
                </h3>
                <div class="fs-timer" aria-live="polite">
                    <div class="t-box" data-unit="hours">00</div>
                    <span>:</span>
                    <div class="t-box" data-unit="minutes">00</div>
                    <span>:</span>
                    <div class="t-box" data-unit="seconds">00</div>
                </div>
            </div>
            <a href="{{ route('shop') }}" class="shopee-link d-none d-md-block">Xem tất cả ></a>
        </div>
        <div class="p-3">
            <div class="row g-2">
                @foreach($saleProducts as $product)
                <div class="col-6 col-md-3 col-lg-2" data-aos="fade-up">
                    <a href="{{ route('product.show', $product->slug) }}" class="shopee-product">
                        <div class="sp-img-wrap">
                            <span class="sp-badge">-{{ $product->discount_percent }}%</span>
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="sp-info">
                            <div class="sp-title">{{ $product->name }}</div>
                            <div class="sp-price-row flex-wrap">
                                <div>
                                    <div class="sp-original-price">{{ $product->formatted_original_price }}</div>
                                    <div class="sp-price">{{ $product->formatted_price }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- DungThu Mall (Featured/Exclusive Products) -->
    <div class="container shopee-container mb-4">
        <div class="shopee-header">
            <div class="d-flex align-items-center">
                <h3 class="shopee-title me-3 mb-0" style="color: #d32f2f;">
                    <i class="fas fa-shopping-bag"></i> DUNGTHU MALL
                </h3>
                <span class="text-muted d-none d-md-inline" style="font-size: 0.85rem;"><i class="fas fa-check-circle text-danger"></i> Cam kết chính hãng</span>
            </div>
            <a href="{{ route('shop') }}" class="shopee-link d-none d-md-block">Xem tất cả ></a>
        </div>
        <div class="p-3">
            <div class="row g-2">
                <div class="col-md-4 d-none d-lg-block">
                    <div class="mall-left-banner">
                        <div class="text-center p-3">
                            <h2 class="display-6">MUA LÀ CÓ QUÀ</h2>
                            <p class="mb-0 fs-5">Tiện Ích - Tài Nguyên Mới Nhất</p>
                            <div class="mt-4"><button class="btn btn-outline-light rounded-pill px-4">Khám Phá</button></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="row g-2">
                        @foreach($highlightProducts ?? [] as $product)
                        <div class="col-6 col-md-3" data-aos="fade-up">
                            <div class="shopee-product">
                                @if(!$product->isInStock())
                                    <div class="out-of-stock-overlay">
                                        <div class="out-of-stock-text">Hết hàng</div>
                                    </div>
                                @endif
                                <a href="{{ route('product.show', $product->slug) }}" style="display: block; position:relative; flex-grow: 1; text-decoration: none; color: inherit; display:flex; flex-direction:column;">
                                    <div class="sp-img-wrap">
                                        <span class="sp-badge-category" style="background: #d32f2f;">Mall</span>
                                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" loading="lazy">
                                    </div>
                                    <div class="sp-info">
                                        <div class="sp-title">{{ $product->name }}</div>
                                        <div class="sp-price-row">
                                            <div class="sp-price">{{ $product->formatted_price }}</div>
                                        </div>
                                    </div>
                                </a>
                                <div class="px-2 pb-2 mt-auto d-flex justify-content-end w-100" style="position: relative; z-index: 5;">
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="cart-btn" {{ $product->isInStock() ? '' : 'disabled' }} title="Thêm vào giỏ hàng">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Discover (Gợi ý hôm nay) - replaces Nổi Bật and Combo AI -->
    <div class="container pt-2" id="shop">
        <div class="daily-discover-header rounded-top">
            GỢI Ý HÔM NAY
        </div>
        
        <div class="row g-2">
            @php 
                // Merge array collections to show in grid
                $allProducts = collect();
                if(isset($featuredProducts)) $allProducts = $allProducts->concat($featuredProducts);
                if(isset($comboAiProducts)) $allProducts = $allProducts->concat($comboAiProducts);
                $allProducts = $allProducts->unique('id');
            @endphp
            
            @foreach($allProducts as $product)
            <div class="col-6 col-md-3 col-lg-2 mb-2" data-aos="fade-up">
                <div class="shopee-product">
                    @if(!$product->isInStock())
                        <div class="out-of-stock-overlay">
                            <div class="out-of-stock-text">Hết hàng</div>
                        </div>
                    @endif
                    <a href="{{ route('product.show', $product->slug) }}" style="display: block; position:relative; flex-grow: 1; text-decoration: none; color: inherit; display:flex; flex-direction:column;">
                        <div class="sp-img-wrap">
                            <span class="sp-badge-category">{{ strtoupper($product->category ?? 'HOT') }}</span>
                            @if($product->is_on_sale)
                                <span class="sp-badge">-{{ $product->discount_percent }}%</span>
                            @endif
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="sp-info">
                            <div class="sp-title">{{ $product->name }}</div>
                            <div class="sp-price-row flex-wrap">
                                <div>
                                    @if($product->is_on_sale)
                                        <div class="sp-original-price">{{ $product->formatted_original_price }}</div>
                                    @endif
                                    <div class="sp-price">{{ $product->formatted_price }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="px-2 pb-2 mt-auto d-flex justify-content-end w-100" style="position: relative; z-index: 5;">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="cart-btn" {{ $product->isInStock() ? '' : 'disabled' }}>
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($allProducts->count() > 0)
        <div class="text-center mt-4 mb-5">
            <a href="{{ route('shop') }}" class="btn btn-light border bg-white px-5 py-2 fw-bold" style="color: #666; transition: 0.2s; box-shadow: 0 1px 1px 0 rgba(0,0,0,.05);">Xem Thêm</a>
        </div>
        @endif
    </div>

    <!-- Blog Chia Sẻ -->
    @if(isset($latestBlogs) && $latestBlogs->count() > 0)
    <div class="container shopee-container p-0" id="blog">
        <div class="shopee-header">
            <h3 class="shopee-title mb-0" style="color: #333;"><i class="fas fa-book-reader text-primary me-2"></i> BÀI VIẾT MỚI</h3>
            <a href="{{ route('blog.index') }}" class="shopee-link d-none d-md-block">Xem tất cả ></a>
        </div>
        <div class="p-3">
            <div class="row g-3">
                @foreach($latestBlogs as $index => $blog)
                <div class="col-12 col-md-6" data-aos="fade-up">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none text-dark">
                        <div class="blog-shopee-card d-flex h-100">
                            <div style="width: 140px; flex-shrink: 0; background: #f8f9fa;">
                                <img src="{{ $blog->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="p-3 d-flex flex-column justify-content-center">
                                <div class="fw-bold mb-2" style="font-size: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $blog->title }}</div>
                                <small class="text-muted"><i class="far fa-clock"></i> Cập nhật mới</small>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@include('partials.recent-purchases')
@include('partials.tet-decorations')

@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}?v={{ filemtime(\App\Helpers\PathHelper::publicRootPath('js/home.js')) }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrap = document.getElementById('flash-sale');
            if (!wrap) return;
            const endMs = parseInt(wrap.dataset.countdownEnd || '0', 10);
            const hoursEl = wrap.querySelector('[data-unit="hours"]');
            const minutesEl = wrap.querySelector('[data-unit="minutes"]');
            const secondsEl = wrap.querySelector('[data-unit="seconds"]');

            function pad(num) {
                return String(num).padStart(2, '0');
            }

            function tick() {
                const now = Date.now();
                let diff = Math.max(0, endMs - now);
                if (endMs > 0 && diff <= 0) {
                    wrap.style.display = 'none';
                    return;
                }
                const hours = Math.floor(diff / 3600000);
                diff = diff % 3600000;
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                if (hoursEl) hoursEl.textContent = pad(hours);
                if (minutesEl) minutesEl.textContent = pad(minutes);
                if (secondsEl) secondsEl.textContent = pad(seconds);
            }

            tick();
            setInterval(tick, 1000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush
