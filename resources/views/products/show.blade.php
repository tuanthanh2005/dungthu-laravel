@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
:root {
    --primary: #667eea; --secondary: #764ba2;
    --dark: #1a1a2e; --muted: #6b7280;
    --bg: #f8faff; --card-radius: 16px;
    --card-shadow: 0 4px 20px rgba(102,126,234,0.09);
}
body { background: var(--bg); }

/* === BREADCRUMB === */
.breadcrumb-bar {
    background: #fff; border-bottom: 1px solid #e5e7eb; padding: 10px 0;
}
.breadcrumb { margin: 0; background: transparent; padding: 0; font-size: 0.82rem; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--primary); }
.breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 600; }

/* === LAYOUT === */
.detail-layout {
    display: grid; grid-template-columns: 1fr 300px;
    gap: 28px; max-width: 1180px;
    margin: 0 auto; padding: 24px 16px 48px;
}
@media (max-width: 991px) {
    .detail-layout { grid-template-columns: 1fr; }
    .detail-sidebar { display: none; }
}

/* === MAIN PRODUCT CARD === */
.product-main-card {
    background: #fff; border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
    overflow: hidden;
    margin-bottom: 20px;
}
.product-top { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
@media (max-width: 768px) { .product-top { grid-template-columns: 1fr; } }

.product-img-section {
    background: linear-gradient(135deg, #f8f9ff, #e8eeff);
    padding: 24px; display: flex; align-items: center; justify-content: center;
}
.product-img-section img {
    width: 100%; max-height: 340px; object-fit: cover;
    border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transition: transform .3s;
}
.product-img-section img:hover { transform: scale(1.03); }
@media (max-width: 768px) { .product-img-section { padding: 16px; } .product-img-section img { max-height: 220px; } }

.product-info-section { padding: 28px 28px 24px; }
@media (max-width: 768px) { .product-info-section { padding: 20px 16px; } }

.prod-cat-badge {
    display: inline-block; padding: 3px 12px; border-radius: 999px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; margin-bottom: 12px;
}
.product-name {
    font-size: 1.5rem; font-weight: 900; color: var(--dark);
    line-height: 1.25; margin-bottom: 10px;
}
@media (max-width: 768px) { .product-name { font-size: 1.2rem; } }
.product-desc { font-size: 0.9rem; color: var(--muted); line-height: 1.65; margin-bottom: 16px; }

.price-block { margin-bottom: 18px; }
.price-main { font-size: 2rem; font-weight: 900; color: var(--primary); line-height: 1; }
.price-main.sale { color: #ef4444; }
.price-old { font-size: 1rem; text-decoration: line-through; color: #9ca3af; margin-left: 8px; }
.price-badge-sale {
    display: inline-block; padding: 3px 8px; border-radius: 6px;
    background: #ef4444; color: #fff; font-size: 0.75rem; font-weight: 800;
    margin-left: 6px; vertical-align: middle;
}
.price-vat { font-size: 0.75rem; color: var(--muted); margin-top: 4px; }

.stock-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 999px; font-size: 0.82rem; font-weight: 700;
    margin-bottom: 18px;
}
.stock-badge.in { background: #dcfce7; color: #15803d; }
.stock-badge.out { background: #fee2e2; color: #b91c1c; }

.action-btns { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
.btn-cart {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 12px 28px; border-radius: 999px; font-weight: 800;
    font-size: 0.95rem; border: none; cursor: pointer;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; transition: opacity .15s, transform .15s; text-decoration: none;
}
.btn-cart:hover { opacity: 0.88; transform: translateY(-1px); color: #fff; }
.btn-buynow {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 12px 24px; border-radius: 999px; font-weight: 800;
    font-size: 0.95rem; border: none; cursor: pointer;
    background: linear-gradient(90deg, #f59e0b, #f97316);
    color: #fff; transition: opacity .15s; text-decoration: none;
}
.btn-buynow:hover { opacity: 0.88; color: #fff; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 12px 20px; border-radius: 999px; font-weight: 700;
    font-size: 0.88rem; border: 2px solid #e5e7eb; background: #fff;
    color: var(--muted); text-decoration: none; transition: all .15s;
}
.btn-back:hover { border-color: var(--primary); color: var(--primary); }

.trust-badges { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
@media (max-width: 480px) { .trust-badges { grid-template-columns: 1fr; } }
.trust-badge {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px; color: #fff;
    font-size: 0.82rem;
}
.trust-badge strong { font-size: 0.88rem; display: block; }
.trust-badge small { opacity: 0.85; font-size: 0.72rem; }

/* === AD BLOCK === */
.ad-block {
    background: #fff; border: 1px dashed rgba(102,126,234,0.18);
    border-radius: 12px; overflow: hidden; margin-bottom: 20px;
}
.ad-label {
    font-size: 0.62rem; color: #bbb; text-align: center; padding: 3px 0;
    background: #fafafa; border-bottom: 1px solid #eee;
    letter-spacing: 0.1em; text-transform: uppercase;
}

/* === TABS === */
.product-tabs-card {
    background: #fff; border-radius: var(--card-radius);
    box-shadow: var(--card-shadow); border: 1px solid rgba(102,126,234,0.08);
    overflow: hidden; margin-bottom: 20px;
}
.tabs-nav {
    display: flex; border-bottom: 2px solid #f0f4ff;
    overflow-x: auto; scrollbar-width: none;
}
.tabs-nav::-webkit-scrollbar { display: none; }
.tabs-nav button {
    padding: 14px 20px; border: none; background: transparent;
    font-size: 0.88rem; font-weight: 700; color: var(--muted);
    cursor: pointer; white-space: nowrap;
    border-bottom: 3px solid transparent; margin-bottom: -2px;
    transition: color .2s, border-color .2s;
}
.tabs-nav button.active { color: var(--primary); border-bottom-color: var(--primary); }
.tabs-nav button:hover { color: var(--primary); }
.tab-pane-custom { display: none; padding: 24px 28px; }
.tab-pane-custom.active { display: block; }
@media (max-width: 768px) { .tab-pane-custom { padding: 16px; } }

.spec-item {
    display: flex; gap: 10px; align-items: flex-start;
    padding: 10px 12px; background: #f8f9ff; border-radius: 10px;
    margin-bottom: 8px;
}
.spec-item i { color: var(--primary); margin-top: 2px; flex-shrink: 0; }
.spec-item strong { font-size: 0.82rem; color: var(--dark); }
.spec-item span { font-size: 0.82rem; color: var(--muted); }

/* Rating */
.rating-big { font-size: 3rem; font-weight: 900; color: var(--primary); line-height: 1; }
.stars-row i { font-size: 20px; }
.review-card { padding: 14px 0; border-bottom: 1px solid #f0f4ff; }
.review-card:last-child { border-bottom: none; }
.reviewer-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff; font-weight: 800; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
.rating-input input { display: none; }
.rating-input label { cursor: pointer; font-size: 26px; color: #ddd; transition: color .2s; }
.rating-input label:hover, .rating-input label:hover ~ label,
.rating-input input:checked ~ label { color: #ffc107; }

/* === RELATED === */
.section-label { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
.section-label .bar { width: 4px; height: 22px; border-radius: 4px; flex-shrink: 0; background: linear-gradient(180deg, var(--primary), var(--secondary)); }
.section-label h2 { font-size: 1.05rem; font-weight: 800; color: var(--dark); margin: 0; }

/* === SIDEBAR === */
.sidebar-sticky { position: sticky; top: 92px; }
.sidebar-widget { background: #fff; border-radius: var(--card-radius); padding: 18px; box-shadow: var(--card-shadow); border: 1px solid rgba(102,126,234,0.08); margin-bottom: 20px; }
.sidebar-widget-title { font-size: 0.88rem; font-weight: 800; color: var(--dark); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f4ff; display: flex; align-items: center; gap: 7px; }
.sidebar-widget-title i { color: var(--primary); }
.sidebar-blog-item { display: flex; gap: 10px; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; }
.sidebar-blog-item:last-child { border-bottom: none; }
.sidebar-blog-item:hover { opacity: 0.8; }
.sidebar-blog-item img { width: 56px; height: 44px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-blog-item .sbi-title { font-size: 0.8rem; font-weight: 600; color: var(--dark); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.sidebar-blog-item .sbi-date { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
.sidebar-prod { display: flex; gap: 10px; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; overflow: hidden; }
.sidebar-prod:last-child { border-bottom: none; }
.sidebar-prod:hover { opacity: 0.85; }
.sidebar-prod img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-prod > div { min-width: 0; flex: 1; overflow: hidden; }
.sidebar-prod .sp-name { font-size: 0.8rem; font-weight: 700; color: var(--dark); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word; }
.sidebar-prod .sp-price { font-size: 0.8rem; font-weight: 800; color: var(--primary); margin-top: 2px; }

@media (max-width: 576px) {
    .price-main { font-size: 1.5rem; }
    .btn-cart, .btn-buynow { padding: 10px 18px; font-size: 0.88rem; }
}
</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">🛒 Cửa hàng</a></li>
                <li class="breadcrumb-item active text-muted">{{ Str::limit($product->name, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="detail-layout">

    {{-- === MAIN CONTENT === --}}
    <div>

        {{-- AdSense TOP --}}
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- PRODUCT MAIN CARD --}}
        <div class="product-main-card">
            <div class="product-top">

                {{-- Ảnh sản phẩm --}}
                <div class="product-img-section">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}"
                         alt="{{ $product->name }}" loading="lazy">
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="product-info-section">
                    <span class="prod-cat-badge">{{ strtoupper($product->category) }}</span>
                    <h1 class="product-name">{{ $product->name }}</h1>
                    <p class="product-desc">{{ Str::limit($product->description, 180) }}</p>

                    {{-- Giá --}}
                    <div class="price-block">
                        <div>
                            <span class="price-main {{ $product->is_on_sale ? 'sale' : '' }}">{{ $product->formatted_price }}</span>
                            @if($product->is_on_sale)
                                <span class="price-old">{{ $product->formatted_original_price }}</span>
                                <span class="price-badge-sale">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        <div class="price-vat">💰 Giá đã bao gồm VAT | Hỗ trợ tất cả hình thức thanh toán</div>
                    </div>

                    {{-- Tình trạng --}}
                    @if($product->stock > 0)
                    <div class="stock-badge in"><i class="fas fa-check-circle"></i> Còn hàng ({{ $product->stock }} sp)</div>
                    @else
                    <div class="stock-badge out"><i class="fas fa-times-circle"></i> Hết hàng</div>
                    @endif

                    {{-- Nút mua --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="action-btns">
                            <button type="submit" class="btn-cart" {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                            </button>
                            @if($product->delivery_type === 'digital')
                            <button type="submit" formaction="{{ route('cart.buy-now', $product->id) }}" class="btn-buynow" {{ $product->stock > 0 ? '' : 'disabled' }}>
                                <i class="fas fa-bolt"></i> Mua ngay
                            </button>
                            @endif
                            <a href="{{ route('shop') }}" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Tiếp tục mua
                            </a>
                        </div>
                    </form>

                    {{-- Trust badges --}}
                    <div class="trust-badges">
                        <div class="trust-badge" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="fas fa-shield-alt fa-lg"></i>
                            <div><strong>Chính hãng 100%</strong><small>Cam kết hàng thật</small></div>
                        </div>
                        <div class="trust-badge" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
                            <i class="fas fa-tools fa-lg"></i>
                            <div><strong>Bảo hành 12 tháng</strong><small>Đổi trả miễn phí</small></div>
                        </div>
                        <div class="trust-badge" style="background:linear-gradient(135deg,#4facfe,#00f2fe);">
                            <i class="fas fa-headset fa-lg"></i>
                            <div><strong>Hỗ trợ 24/7</strong><small>Tư vấn miễn phí</small></div>
                        </div>
                        <div class="trust-badge" style="background:linear-gradient(135deg,#43e97b,#38f9d7);">
                            <i class="fas fa-bolt fa-lg"></i>
                            <div><strong>Giao hàng nhanh</strong><small>Toàn quốc 24h</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- AdSense giữa trang --}}
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-layout="in-article" data-ad-format="fluid"
                data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- TABS --}}
        <div class="product-tabs-card">
            <div class="tabs-nav">
                <button class="active" onclick="showTab('tab-features', this)"><i class="fas fa-star me-1" style="color:#f59e0b;"></i> Tính Năng Nổi Bật</button>
                <button onclick="showTab('tab-desc', this)"><i class="fas fa-align-left me-1" style="color:var(--primary);"></i> Mô Tả Chi Tiết</button>
                <button onclick="showTab('tab-reviews', this)"><i class="fas fa-comments me-1" style="color:#10b981;"></i> Đánh Giá ({{ $totalReviews }})</button>
            </div>

            {{-- Tab: Tính năng --}}
            <div id="tab-features" class="tab-pane-custom active">
                <div class="row g-3">
                    @foreach([
                        ['fas fa-check-circle','#10b981','Thiết kế hiện đại','Giao diện đẹp mắt, sang trọng'],
                        ['fas fa-star','#f59e0b','Chất lượng cao cấp','Kiểm định chất lượng nghiêm ngặt'],
                        ['fas fa-shield-alt','#667eea','An toàn tuyệt đối','Đạt chuẩn an toàn quốc tế'],
                        ['fas fa-bolt','#f97316','Hiệu suất cao','Tốc độ xử lý vượt trội'],
                        ['fas fa-headset','#06b6d4','Hỗ trợ 24/7','Tư vấn miễn phí mọi lúc'],
                        ['fas fa-undo','#8b5cf6','Đổi trả dễ dàng','7 ngày từ ngày mua'],
                    ] as [$icon, $color, $title, $desc])
                    <div class="col-md-6">
                        <div class="spec-item">
                            <i class="{{ $icon }}" style="color:{{ $color }};"></i>
                            <div><strong>{{ $title }}</strong><br><span>{{ $desc }}</span></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: Mô tả --}}
            <div id="tab-desc" class="tab-pane-custom">
                <div style="font-size:0.95rem;line-height:1.85;color:#374151;">{{ $product->description }}</div>

                {{-- AdSense in tab --}}
                <div class="ad-block mt-4">
                    <div class="ad-label">Quảng Cáo</div>
                    <ins class="adsbygoogle" style="display:block;min-height:90px;"
                        data-ad-client="ca-pub-3065867660863139"
                        data-ad-format="auto" data-full-width-responsive="true"
                        data-ad-slot="4989157975"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>

                <div class="row g-2 mt-3">
                    @foreach([
                        ['fas fa-list-ul','Danh mục', strtoupper($product->category)],
                        ['fas fa-barcode','SKU', '#'.$product->id],
                        ['fas fa-check-circle','Tình trạng', $product->stock > 0 ? 'Còn hàng' : 'Hết hàng'],
                        ['fas fa-shield-alt','Bảo hành','12 tháng'],
                        ['fas fa-credit-card','Thanh toán','Banking, COD, Transfer'],
                        ['fas fa-shipping-fast','Giao hàng','Toàn quốc 24-48h'],
                    ] as [$icon, $label, $val])
                    <div class="col-md-6">
                        <div class="spec-item">
                            <i class="{{ $icon }}"></i>
                            <div><strong>{{ $label }}:</strong><br><span>{{ $val }}</span></div>
                        </div>
                    </div>
                    @endforeach

                    @if($product->specs)
                    @foreach($product->specs as $key => $value)
                    <div class="col-md-6">
                        <div class="spec-item">
                            <i class="fas fa-info-circle"></i>
                            <div><strong>{{ ucfirst(str_replace('_',' ',$key)) }}:</strong><br><span>{{ $value }}</span></div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            {{-- Tab: Đánh giá --}}
            <div id="tab-reviews" class="tab-pane-custom">
                {{-- Rating tổng --}}
                <div class="text-center p-4 mb-4" style="background:#f8f9ff;border-radius:14px;">
                    <div class="rating-big">{{ number_format($averageRating, 1) }}</div>
                    <div class="stars-row my-2">
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
                    <div style="font-size:0.85rem;color:var(--muted);">Dựa trên <strong>{{ $totalReviews }}</strong> đánh giá</div>
                </div>

                {{-- AdSense trong reviews --}}
                <div class="ad-block mb-3">
                    <div class="ad-label">Quảng Cáo</div>
                    <ins class="adsbygoogle" style="display:block;min-height:90px;"
                        data-ad-client="ca-pub-3065867660863139"
                        data-ad-format="auto" data-full-width-responsive="true"
                        data-ad-slot="4989157975"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>

                {{-- Form đánh giá --}}
                @auth
                <div style="background:#f8f9ff;border-radius:14px;padding:18px;margin-bottom:18px;">
                    <h6 class="fw-bold mb-3"><i class="fas fa-edit me-1" style="color:var(--primary);"></i> Viết đánh giá của bạn</h6>
                    <form action="{{ route('product.comment', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size:0.85rem;">Đánh giá <span class="text-danger">*</span></label>
                            <div class="rating-input mb-1">
                                @for($s = 5; $s >= 1; $s--)
                                <input type="radio" name="rating" value="{{ $s }}" id="star{{ $s }}" {{ $s==5?'required':'' }}>
                                <label for="star{{ $s }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3"
                                placeholder="Chia sẻ trải nghiệm của bạn..." required
                                style="border-radius:10px;border:2px solid #e5e7eb;font-size:0.88rem;">{{ old('comment') }}</textarea>
                        </div>
                        <button type="submit" style="padding:8px 22px;border:none;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;font-weight:700;font-size:0.85rem;cursor:pointer;">
                            <i class="fas fa-paper-plane me-1"></i> Gửi đánh giá
                        </button>
                    </form>
                </div>
                @else
                <div style="background:#eff6ff;border-radius:10px;padding:14px;margin-bottom:16px;font-size:0.85rem;color:#1d4ed8;">
                    <i class="fas fa-info-circle me-1"></i>
                    Bạn cần <a href="{{ route('login') }}" style="font-weight:700;color:#1d4ed8;">đăng nhập</a> để viết đánh giá.
                </div>
                @endauth

                {{-- Danh sách đánh giá --}}
                @forelse($product->comments as $comment)
                <div class="review-card">
                    <div class="d-flex gap-2 align-items-start">
                        <div class="reviewer-avatar">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-weight:700;font-size:0.88rem;color:var(--dark);">{{ $comment->user->name }}</span>
                                <small style="color:var(--muted);font-size:0.72rem;">{{ $comment->created_at->format('d/m/Y') }}</small>
                            </div>
                            <div class="mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star text-warning" style="font-size:13px;"></i>
                                @endfor
                            </div>
                            <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $comment->comment }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-2x mb-2" style="color:#e5e7eb;"></i>
                    <p style="color:var(--muted);font-size:0.88rem;">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- AdSense sau tabs --}}
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

    </div>{{-- end main --}}

    {{-- === SIDEBAR === --}}
    <aside class="detail-sidebar">
        <div class="sidebar-sticky">

            {{-- AdSense sidebar 1 --}}
            <div class="sidebar-widget p-0 mb-4">
                <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:280px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-slot="4989157975" data-ad-format="auto"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>

            {{-- Tóm tắt mua nhanh --}}
            <div class="sidebar-widget" style="border:2px solid rgba(102,126,234,0.2);">
                <div class="sidebar-widget-title"><i class="fas fa-zap"></i>⚡ Mua Ngay</div>
                <div style="font-size:0.85rem;font-weight:700;color:var(--dark);margin-bottom:4px;">{{ $product->name }}</div>
                <div style="font-size:1.4rem;font-weight:900;color:{{ $product->is_on_sale ? '#ef4444' : 'var(--primary)' }};margin-bottom:12px;">{{ $product->formatted_price }}</div>
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="width:100%;padding:11px;border:none;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;font-weight:800;font-size:0.9rem;cursor:pointer;margin-bottom:8px;" {{ $product->stock > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                    </button>
                    @if($product->delivery_type === 'digital')
                    <button type="submit" formaction="{{ route('cart.buy-now', $product->id) }}" style="width:100%;padding:11px;border:none;border-radius:999px;background:linear-gradient(90deg,#f59e0b,#f97316);color:#fff;font-weight:800;font-size:0.9rem;cursor:pointer;" {{ $product->stock > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-bolt me-1"></i> Mua ngay
                    </button>
                    @endif
                </form>
            </div>

            {{-- Blog liên quan --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-newspaper"></i> Bài Viết Liên Quan</div>
                @php
                    $sidebarBlogs = \App\Models\Blog::published()->orderBy('published_at','desc')->take(3)->get();
                @endphp
                @foreach($sidebarBlogs as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="sidebar-blog-item">
                    <img src="{{ $blog->image ?? 'https://via.placeholder.com/56x44' }}" alt="{{ $blog->title }}">
                    <div>
                        <div class="sbi-title">{{ $blog->title }}</div>
                        <div class="sbi-date"><i class="far fa-calendar-alt"></i> {{ $blog->formatted_date }}</div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- AdSense sidebar 2 --}}
            <div class="sidebar-widget p-0">
                <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:200px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-slot="4989157975" data-ad-format="auto"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>

        </div>
    </aside>

</div>{{-- end detail-layout --}}

@endsection

@push('scripts')
<script>
AOS.init({ duration: 700, once: true });

function showTab(id, btn) {
    document.querySelectorAll('.tab-pane-custom').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tabs-nav button').forEach(b => b.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush
