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

        /* Recent purchase toast (social proof) */
        .recent-purchase-toast {
            position: fixed;
            left: 18px;
            bottom: 18px;
            width: min(420px, calc(100vw - 36px));
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
            border: 1px solid rgba(0,0,0,0.06);
            padding: 14px 14px 12px;
            z-index: 2000;
            opacity: 0;
            transform: translateY(16px);
            pointer-events: none;
            transition: opacity .25s ease, transform .25s ease;
        }
        .recent-purchase-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .rpt-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rpt-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            line-height: 1;
        }
        .rpt-pill.buy { background: #12b76a; color: #fff; }
        .rpt-pill.verify { background: rgba(16,185,129,0.12); color: #0f766e; border: 1px solid rgba(16,185,129,0.25); }
        .rpt-close {
            margin-left: auto;
            border: none;
            background: transparent;
            font-size: 20px;
            line-height: 1;
            color: #6b7280;
            padding: 0 6px;
            cursor: pointer;
        }
        .rpt-body {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }
        .rpt-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #12b76a, #00cec9);
            color: #fff;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            position: relative;
        }
        .rpt-avatar .rpt-badge {
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }
        .rpt-name { font-weight: 800; font-size: 16px; }
        .rpt-sub { color: #6b7280; font-size: 13px; display: flex; align-items: center; gap: 6px; margin-top: 2px; }
        .rpt-product {
            display: block;
            margin-top: 10px;
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.20);
            border-radius: 12px;
            padding: 10px 12px;
            text-decoration: none;
            color: inherit;
        }
        .rpt-product-title {
            font-weight: 800;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .rpt-product-meta {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        @media (max-width: 576px) {
            .recent-purchase-toast { left: 10px; bottom: 10px; width: min(340px, calc(100vw - 20px)); }
            .recent-purchase-toast { padding: 8px 8px 7px; border-radius: 14px; }
            .rpt-pill { padding: 4px 7px; font-size: 11px; }
            .rpt-close { font-size: 18px; padding: 0 4px; }
            .rpt-body { gap: 8px; margin-top: 8px; }
            .rpt-avatar { width: 34px; height: 34px; font-size: 14px; }
            .rpt-name { font-size: 13px; }
            .rpt-sub { font-size: 11px; }
            .rpt-product { margin-top: 7px; padding: 7px 9px; border-radius: 11px; }
            .rpt-product-title { font-size: 12px; }
            .rpt-product-meta { font-size: 11px; gap: 6px; }
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

                {{--
                @if(!empty($recentPurchases) && count($recentPurchases) > 0)
                    <!-- KhÃ¡ch hÃ ng vá»«a mua (Social proof) -->
                    <div class="mb-5" data-aos="fade-up">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <div>
                                <span class="text-warning fw-bold text-uppercase ls-1">Uy tÃ­n</span>
                                <h3 class="fw-bold section-title">KhÃ¡ch HÃ ng Vá»«a Mua</h3>
                                <p class="text-muted mb-0">ThÃ´ng tin Ä‘Ã£ Ä‘Æ°á»£c áº©n Ä‘á»ƒ báº£o vá»‡ quyá»n riÃªng tÆ°</p>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-lg-2 g-3">
                            @foreach($recentPurchases as $purchase)
                                <div class="col">
                                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                                        <div class="card-body d-flex gap-3 align-items-start">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                 style="width: 44px; height: 44px; background: linear-gradient(135deg, rgba(108,92,231,0.12), rgba(0,206,201,0.12)); border: 1px solid rgba(0,0,0,0.06);">
                                                <i class="fas fa-shopping-bag text-primary"></i>
                                            </div>

                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div class="fw-bold">{{ $purchase['customer_name'] ?? 'KhÃ¡ch hÃ ng' }}</div>
                                                    <small class="text-muted">{{ $purchase['time_ago'] ?? '' }}</small>
                                                </div>

                                                <div class="text-muted" style="font-size: 0.95rem;">
                                                    Vá»«a {{ ($purchase['verb'] ?? '') === 'mua' ? 'mua' : 'Ä‘áº·t' }}
                                                    @php
                                                        $productText = ($purchase['product_name'] ?? 'Sáº£n pháº©m') . ((int)($purchase['extra_items'] ?? 0) > 0 ? (' +' . (int)$purchase['extra_items'] . ' SP') : '');
                                                    @endphp
                                                    @if(!empty($purchase['product_slug']))
                                                        <a href="{{ route('product.show', $purchase['product_slug']) }}" class="text-decoration-none fw-bold">{{ $productText }}</a>
                                                    @else
                                                        <span class="fw-bold">{{ $productText }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                --}}
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

                <!-- Sản Phẩm Đặc Biệt -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-right">
                        <div>
                            <span class="text-success fw-bold text-uppercase ls-1">⭐ Đặc biệt</span>
                            <h3 class="fw-bold section-title">Sản Phẩm Độc Quyền</h3>
                            <p class="text-muted mb-0">Những sản phẩm chất lượng cao được chọn lọc kỹ càng</p>
                        </div>
                        <a href="{{ route('shop') }}" class="text-decoration-none fw-bold">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="row row-cols-2 row-cols-md-4 g-4">
                        @foreach($highlightProducts as $product)
                        <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="product-card">
                                <div class="card-img-wrap">
                                    <span class="badge-custom bg-success">{{ strtoupper($product->category) }}</span>
                                    <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                                </div>
                                <div class="p-3">
                                    <h6 class="fw-bold product-title-2lines">{{ $product->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="text-success fw-bold">{{ $product->formatted_price }}</span>
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light rounded-circle text-success">
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
                                    <img src="{{ $deal->image ? asset('images/products/' . $deal->image) : 'https://via.placeholder.com/300' }}" 
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
                        <div class="col-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="blog-card w-100 text-center p-3" style="min-height:160px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    <img src="{{ $blog->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $blog->title }}" style="max-width:100%;max-height:80px;object-fit:cover;border-radius:10px;">
                                </a>
                                <a href="{{ route('blog.show', $blog->slug) }}" class="blog-title fw-bold mt-2 d-block" style="font-size:1.05rem;line-height:1.4;">{{ $blog->title }}</a>
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

    @if(!empty($recentPurchases) && count($recentPurchases) > 0)
        <script type="application/json" id="recentPurchaseData">@json($recentPurchases)</script>
        <div id="recentPurchaseToast" class="recent-purchase-toast" role="status" aria-live="polite" aria-atomic="true">
            <div class="rpt-header">
                <span class="rpt-pill buy">
                    <i class="fas fa-star"></i> Vừa mua
                </span>
                <span class="rpt-pill verify">
                    <i class="fas fa-check-circle"></i> Đã xác minh
                </span>
                <button type="button" class="rpt-close" id="recentPurchaseClose" aria-label="Đóng">&times;</button>
            </div>

            <div class="rpt-body">
                <div class="rpt-avatar">
                    <span id="rptAvatarLetter">N</span>
                    <span class="rpt-badge">
                        <i class="fas fa-check-circle" style="font-size: 12px; color: #12b76a;"></i>
                    </span>
                </div>

                <div style="min-width:0;flex:1;">
                    <div class="rpt-name" id="rptName">Khách hàng</div>
                    <div class="rpt-sub">
                        <i class="fas fa-check-circle"></i>
                        <span id="rptAction">vừa mua thành công</span>
                    </div>

                    <a href="#shop" class="rpt-product" id="rptProductLink">
                        <div class="rpt-product-title" id="rptProductTitle">Sản phẩm</div>
                        <div class="rpt-product-meta">
                            <span id="rptTime">vừa xong</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif

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
     <script>
         document.addEventListener('DOMContentLoaded', function () {
             const dataEl = document.getElementById('recentPurchaseData');
             const toastEl = document.getElementById('recentPurchaseToast');
             const closeEl = document.getElementById('recentPurchaseClose');

             if (!dataEl || !toastEl) return;

             const storageKey = 'recentPurchaseToastDismissedUntil';
             const dismissedUntil = parseInt(localStorage.getItem(storageKey) || '0', 10);
             if (dismissedUntil && Date.now() < dismissedUntil) return;

             let purchases = [];
             try {
                 purchases = JSON.parse(dataEl.textContent || '[]');
             } catch (e) {
                 return;
             }
             if (!Array.isArray(purchases) || purchases.length === 0) return;

             const els = {
                 avatarLetter: document.getElementById('rptAvatarLetter'),
                 name: document.getElementById('rptName'),
                 action: document.getElementById('rptAction'),
                 productLink: document.getElementById('rptProductLink'),
                 productTitle: document.getElementById('rptProductTitle'),
                 time: document.getElementById('rptTime'),
             };

             let index = 0;
             let stop = false;
             let hideTimer = null;
             let nextTimer = null;
             const SHOW_MS = 2500; // how long toast stays visible
             const INTERVAL_MS = 10000; // show a new one every 10s

             function getAvatarLetter(name) {
                 const s = String(name || '').trim();
                 const m = s.match(/[A-Za-zÀ-ỹĐđ]/u);
                 return (m ? m[0] : 'K').toUpperCase();
             }

             function getProductText(p) {
                 const base = p?.product_name ? String(p.product_name) : 'Sản phẩm';
                 const extra = Number(p?.extra_items || 0);
                 return extra > 0 ? `${base} +${extra} SP` : base;
             }

             function render(p) {
                 const customerName = p?.customer_name ? String(p.customer_name) : 'Khách hàng';
                 const verb = p?.verb === 'mua' ? 'mua' : 'đặt';
                 const timeAgo = p?.time_ago ? String(p.time_ago) : '';
                 const productText = getProductText(p);
                 const productUrl = p?.product_url ? String(p.product_url) : '#shop';

                 if (els.avatarLetter) els.avatarLetter.textContent = getAvatarLetter(customerName);
                 if (els.name) els.name.textContent = customerName;
                 if (els.action) els.action.textContent = `vừa ${verb} thành công`;
                 if (els.productTitle) els.productTitle.textContent = productText;
                 if (els.time) els.time.textContent = timeAgo;
                 if (els.productLink) els.productLink.setAttribute('href', productUrl);
             }

             function show() {
                 toastEl.classList.add('show');
             }

             function hide() {
                 toastEl.classList.remove('show');
             }

             function cycle() {
                 if (stop) return;
                 const p = purchases[index % purchases.length];
                 index += 1;

                 render(p);
                 show();

                 if (hideTimer) clearTimeout(hideTimer);
                 if (nextTimer) clearTimeout(nextTimer);

                 hideTimer = setTimeout(function () {
                     hide();
                 }, SHOW_MS);

                 nextTimer = setTimeout(cycle, INTERVAL_MS);
             }

             if (closeEl) {
                 closeEl.addEventListener('click', function () {
                     stop = true;
                     hide();
                     localStorage.setItem(storageKey, String(Date.now() + 60 * 1000));
                 });
             }

             toastEl.addEventListener('mouseenter', function () {
                 if (hideTimer) clearTimeout(hideTimer);
                 if (nextTimer) clearTimeout(nextTimer);
             });
             toastEl.addEventListener('mouseleave', function () {
                 if (stop) return;
                 if (hideTimer) clearTimeout(hideTimer);
                 hideTimer = setTimeout(function () {
                     hide();
                 }, 900);
             });

             setTimeout(cycle, 800);
         });
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
