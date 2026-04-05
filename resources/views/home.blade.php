@extends('layouts.app')

@section('title', 'DungThu.com - Blog Công Nghệ & Mua Sắm Online')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(\App\Helpers\PathHelper::publicRootPath('css/home.css')) }}">
<link rel="stylesheet" href="{{ asset('css/category-filter.css') }}?v={{ time() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
/* ===== RESET & BASE ===== */
:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --accent: #f093fb;
    --dark: #1a1a2e;
    --text: #2d3748;
    --muted: #6b7280;
    --bg-light: #f8faff;
    --card-shadow: 0 4px 24px rgba(102,126,234,0.10);
    --card-radius: 16px;
}

html, body {
    overflow-x: hidden;
    max-width: 100%;
}
body { background: var(--bg-light); }

/* Fix AdSense không tràn màn hình mobile */
.adsbygoogle { max-width: 100% !important; }

/* ===== HERO - CỰC GỌN, BLOG-STYLE ===== */
.hero-blog {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 28px 0 24px;
    margin-top: 64px; /* offset fixed navbar */
    border-bottom: 3px solid var(--primary);
}
.hero-blog .site-tagline {
    font-size: 0.8rem;
    letter-spacing: 0.12em;
    color: #a78bfa;
    text-transform: uppercase;
    font-weight: 700;
}
.hero-blog .site-name {
    font-size: 2.2rem;
    font-weight: 900;
    background: linear-gradient(90deg, #fff 0%, #a78bfa 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}
.hero-blog .site-desc {
    color: rgba(255,255,255,0.65);
    font-size: 0.92rem;
    margin-top: 6px;
}
.hero-socials a {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    color: #fff; text-decoration: none;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    transition: transform .15s, background .15s;
    font-size: 13px;
}
.hero-socials a:hover { transform: translateY(-2px); background: rgba(167,139,250,0.3); }

/* ===== TOP NAV CATEGORIES ===== */
.topic-bar {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0;
    position: sticky;
    top: 62px;
    z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.topic-bar .topics-scroll {
    display: flex;
    gap: 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.topic-bar .topics-scroll::-webkit-scrollbar { display: none; }
.topic-bar a {
    white-space: nowrap;
    padding: 12px 18px;
    font-size: 0.87rem;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: color .2s, border-color .2s;
}
.topic-bar a:hover, .topic-bar a.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

/* ===== LAYOUT ===== */
.page-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 16px 40px;
}
@media (max-width: 991px) {
    .page-layout { grid-template-columns: 1fr; }
    .sidebar { display: none; }
}

/* ===== SECTION LABELS ===== */
.section-label {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 18px;
}
.section-label .label-line {
    display: inline-block;
    width: 4px; height: 22px;
    border-radius: 4px;
    background: linear-gradient(180deg, var(--primary), var(--secondary));
}
.section-label h2 {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
}

/* ===== FEATURED BLOG (BIG CARD) ===== */
.featured-blog-card {
    background: #fff;
    border-radius: var(--card-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(102,126,234,0.08);
}
.featured-blog-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(102,126,234,0.18);
    color: inherit;
}
.featured-blog-card .feat-img {
    width: 100%; height: 240px;
    object-fit: cover;
    display: block;
}
.featured-blog-card .feat-body { padding: 20px 22px 22px; }
.feat-cat-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff;
    margin-bottom: 10px;
}
.featured-blog-card h3 {
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.35;
    color: var(--dark);
    margin-bottom: 8px;
}
.featured-blog-card p {
    font-size: 0.9rem;
    color: var(--muted);
    line-height: 1.6;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-meta-row {
    display: flex; align-items: center; gap: 14px;
    font-size: 0.78rem; color: var(--muted);
}
.blog-meta-row i { color: var(--primary); margin-right: 3px; }

/* ===== BLOG GRID (nhỏ) ===== */
.blog-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 576px) { .blog-grid { grid-template-columns: 1fr; } }

.blog-mini-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    text-decoration: none;
    color: inherit;
    display: flex; flex-direction: column;
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s;
}
.blog-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(102,126,234,0.14);
    color: inherit;
}
.blog-mini-card img {
    width: 100%; height: 130px; object-fit: cover;
}
.blog-mini-card .mini-body { padding: 12px 14px 14px; flex: 1; }
.blog-mini-card h4 {
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--dark);
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-mini-card .mini-meta {
    font-size: 0.72rem; color: var(--muted);
}

/* ===== LIST BLOG (dọc) ===== */
.blog-list-item {
    display: flex; gap: 14px; align-items: flex-start;
    background: #fff; border-radius: 14px;
    padding: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    text-decoration: none; color: inherit;
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s;
}
.blog-list-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102,126,234,0.13);
    color: inherit;
}
.blog-list-item img {
    width: 90px; height: 70px;
    object-fit: cover; border-radius: 10px;
    flex-shrink: 0;
}
.blog-list-item h4 {
    font-size: 0.9rem; font-weight: 700;
    line-height: 1.35; color: var(--dark);
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-list-item .list-meta { font-size: 0.72rem; color: var(--muted); }

/* ===== ADSENSE BLOCKS ===== */
.adsense-block {
    background: #fff;
    border: 1px dashed rgba(102,126,234,0.2);
    border-radius: 12px;
    overflow: hidden;
    text-align: center;
}
.adsense-block-label {
    font-size: 0.65rem;
    color: #bbb;
    text-align: center;
    padding: 3px 0 2px;
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

/* ===== SIDEBAR ===== */
.sidebar-widget {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 18px;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
    margin-bottom: 20px;
}
.sidebar-widget-title {
    font-size: 0.88rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f4ff;
    display: flex; align-items: center; gap: 7px;
}
.sidebar-widget-title i { color: var(--primary); }

/* Sidebar product card */
.sidebar-product {
    display: flex; gap: 10px; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none; color: inherit;
    transition: opacity .15s;
    overflow: hidden;
}
.sidebar-product:last-child { border-bottom: none; padding-bottom: 0; }
.sidebar-product:hover { opacity: 0.82; }
.sidebar-product img {
    width: 54px; height: 54px;
    object-fit: cover; border-radius: 10px;
    flex-shrink: 0;
    border: 1px solid #e5e7eb;
}
.sidebar-product > div {
    min-width: 0; flex: 1; overflow: hidden;
}
.sidebar-product .sp-name {
    font-size: 0.82rem; font-weight: 700;
    color: var(--dark); line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: anywhere;
}
.sidebar-product .sp-price {
    font-size: 0.82rem;
    font-weight: 800;
    color: var(--primary);
    margin-top: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Sidebar blog popular */
.sidebar-blog-item {
    display: flex; gap: 10px; align-items: flex-start;
    padding: 9px 0;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none; color: inherit;
    transition: opacity .15s;
}
.sidebar-blog-item:last-child { border-bottom: none; }
.sidebar-blog-item:hover { opacity: 0.82; }
.sidebar-blog-item .sb-num {
    font-size: 1.1rem; font-weight: 900;
    color: #e5e7eb; line-height: 1; min-width: 22px;
}
.sidebar-blog-item .sb-title {
    font-size: 0.82rem; font-weight: 600;
    color: var(--dark); line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ===== TAGS CLOUD ===== */
.tag-cloud { display: flex; flex-wrap: wrap; gap: 7px; }
.tag-pill {
    padding: 4px 12px; border-radius: 999px;
    font-size: 0.75rem; font-weight: 600;
    background: #f0f4ff; color: var(--primary);
    text-decoration: none; border: 1px solid rgba(102,126,234,0.2);
    transition: background .15s, color .15s;
}
.tag-pill:hover { background: var(--primary); color: #fff; }

/* ===== SHOP SECTION ===== */
.shop-section { margin-top: 32px; }
.shop-tabs {
    display: flex; gap: 4px;
    background: #f0f4ff; border-radius: 12px;
    padding: 4px; margin-bottom: 18px;
}
.shop-tab-btn {
    flex: 1; padding: 8px 12px;
    border: none; border-radius: 10px;
    font-size: 0.82rem; font-weight: 700;
    cursor: pointer; background: transparent;
    color: var(--muted); transition: all .2s;
}
.shop-tab-btn.active { background: #fff; color: var(--primary); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }

/* Product card - redesigned */
.prod-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(0,0,0,0.07);
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s;
    height: 100%;
}
.prod-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(102,126,234,0.15);
}
.prod-card .prod-img-wrap { position: relative; }
.prod-card .prod-img-wrap img {
    width: 100%; height: 160px;
    object-fit: cover; display: block;
}
.prod-card .prod-badge {
    position: absolute; top: 8px; left: 8px;
    border-radius: 999px; font-size: 0.68rem;
    font-weight: 700; padding: 3px 8px;
    background: var(--primary); color: #fff;
}
.prod-card .prod-badge.sale { background: #ef4444; }
.prod-card .prod-body { padding: 12px 14px 14px; }
.prod-card .prod-name {
    font-size: 0.88rem; font-weight: 700;
    color: var(--dark); line-height: 1.3;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6em;
}
.prod-card .prod-footer {
    display: flex; align-items: center;
    justify-content: space-between; gap: 6px;
}
.prod-card .prod-price { font-size: 0.95rem; font-weight: 800; color: var(--primary); }
.prod-card .prod-price.sale-price { color: #ef4444; }
.prod-card .prod-old { font-size: 0.75rem; text-decoration: line-through; color: #9ca3af; }
.prod-card .prod-btn {
    width: 32px; height: 32px;
    border-radius: 50%; border: none;
    background: #f0f4ff; color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px;
    transition: background .15s, color .15s;
    flex-shrink: 0;
}
.prod-card .prod-btn:hover { background: var(--primary); color: #fff; }

/* Flash sale bar */
.flash-bar {
    background: linear-gradient(90deg, #ef4444, #f97316);
    border-radius: 12px; padding: 10px 16px;
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 16px;
}
.flash-bar .fb-label { color: #fff; font-weight: 800; font-size: 0.9rem; }
.flash-timer { display: flex; gap: 4px; align-items: center; margin-left: auto; }
.flash-pill {
    background: rgba(0,0,0,0.25); color: #fff;
    padding: 3px 7px; border-radius: 6px;
    font-weight: 800; font-size: 0.82rem;
    min-width: 26px; text-align: center;
}
.flash-sep { color: #fff; font-weight: 800; }

/* ===== "XEM THÊM" BUTTONS ===== */
.btn-see-more {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 22px; border-radius: 999px;
    border: 2px solid var(--primary); color: var(--primary);
    font-weight: 700; font-size: 0.88rem;
    text-decoration: none; transition: all .2s;
}
.btn-see-more:hover { background: var(--primary); color: #fff; }

/* ===== RECENT PURCHASE TOAST ===== */
.rpt-toast {
    position: fixed; left: 16px; bottom: 16px;
    width: min(380px, calc(100vw - 32px));
    background: #fff; border-radius: 16px;
    box-shadow: 0 16px 48px rgba(0,0,0,0.16);
    border: 1px solid rgba(0,0,0,0.06);
    padding: 12px; z-index: 2000;
    opacity: 0; transform: translateY(16px);
    pointer-events: none;
    transition: opacity .25s, transform .25s;
}
.rpt-toast.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
.rpt-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 9px; border-radius: 999px; font-weight: 700; font-size: 12px; }
.rpt-pill.buy { background: #12b76a; color: #fff; }
.rpt-pill.verify { background: rgba(16,185,129,0.1); color: #0f766e; border: 1px solid rgba(16,185,129,0.2); }
.rpt-close { margin-left: auto; border: none; background: transparent; font-size: 18px; color: #9ca3af; cursor: pointer; }
.rpt-body { display: flex; gap: 10px; margin-top: 10px; }
.rpt-avatar { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #12b76a, #00cec9); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.rpt-name { font-weight: 800; font-size: 14px; }
.rpt-sub { color: #6b7280; font-size: 12px; margin-top: 2px; }
.rpt-prod { display: block; margin-top: 8px; background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15); border-radius: 10px; padding: 8px 10px; text-decoration: none; color: inherit; }
.rpt-prod-title { font-weight: 700; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.rpt-prod-time { color: #9ca3af; font-size: 11px; margin-top: 2px; }

@media (max-width: 576px) {
    .hero-blog .site-name { font-size: 1.55rem; }
    .page-layout { padding: 16px 12px 32px; gap: 20px; }
    .featured-blog-card .feat-img { height: 180px; }
    .prod-card .prod-img-wrap img { height: 130px; }
}
</style>
@endpush

@section('content')

{{-- ===== HERO HEADER ===== --}}
<div class="hero-blog">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <div>
                <div class="site-tagline">Blog & Cửa Hàng Số</div>
                <div class="site-name">DungThu.com</div>
                <div class="site-desc">Chia sẻ kiến thức công nghệ · Công cụ AI · Kiếm tiền online · Mua sắm digital</div>
                <div class="hero-socials mt-2 d-flex gap-2">
                    <a href="https://www.tiktok.com/@spdungthu.com" target="_blank" rel="noopener" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.facebook.com/thanh.tuan.378686" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://zalo.me/0708910952" target="_blank" rel="noopener" title="Zalo" style="font-weight:800;font-size:12px;">Z</a>
                </div>
            </div>
            {{-- AdSense top banner --}}
            <div class="d-none d-lg-block" style="min-width:300px;max-width:400px;">
                <div class="adsense-block-label">Quảng cáo</div>
                <ins class="adsbygoogle" style="display:inline-block;width:300px;height:90px;"
                    data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>
    </div>
</div>

{{-- ===== TOPIC BAR ===== --}}
<div class="topic-bar">
    <div class="container">
        <div class="topics-scroll">
            <a href="{{ route('home') }}" class="active">🏠 Trang chủ</a>
            <a href="{{ route('blog.index') }}">📝 Blog</a>
            <a href="{{ route('shop') }}">🛒 Shop</a>
            <a href="{{ route('blog.category', 'cong-nghe') }}">💻 Công Nghệ</a>
            <a href="{{ route('blog.category', 'ai') }}">🤖 AI Tools</a>
            <a href="{{ route('blog.category', 'kiem-tien') }}">💰 Kiếm Tiền</a>
            <a href="{{ route('blog.category', 'thu-thuat') }}">🔧 Thủ Thuật</a>
            <a href="{{ route('buff.index') }}">⚡ Dịch Vụ Buff</a>
        </div>
    </div>
</div>

{{-- ===== MAIN LAYOUT ===== --}}
<div class="page-layout">

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-content">

        {{-- === FLASH SALE (nếu có) === --}}
        @if(isset($saleProducts) && $saleProducts->count() > 0)
        <div class="flash-bar mb-3" data-countdown-end="{{ $saleEndsAt?->getTimestamp() * 1000 }}">
            <span class="fb-label">⚡ Flash Sale</span>
            <div class="flash-timer">
                <span class="flash-pill" data-unit="hours">00</span>
                <span class="flash-sep">:</span>
                <span class="flash-pill" data-unit="minutes">00</span>
                <span class="flash-sep">:</span>
                <span class="flash-pill" data-unit="seconds">00</span>
            </div>
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
            @foreach($saleProducts as $product)
            <div class="col">
                <div class="prod-card position-relative">
                    <div class="prod-img-wrap">
                        <span class="prod-badge sale">-{{ $product->discount_percent }}%</span>
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">{{ $product->name }}</div>
                        <div class="prod-footer">
                            <div>
                                <div class="prod-price sale-price">{{ $product->formatted_price }}</div>
                                <div class="prod-old">{{ $product->formatted_original_price }}</div>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="prod-btn"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- === BLOG NỔI BẬT === --}}
        @if($latestBlogs->count() > 0)
        <div class="section-label">
            <span class="label-line"></span>
            <h2>📰 Bài Viết Mới Nhất</h2>
            <a href="{{ route('blog.index') }}" class="ms-auto text-decoration-none fw-bold" style="font-size:.82rem;color:var(--primary);">Xem tất cả →</a>
        </div>

        {{-- Featured blog (bài đầu tiên) --}}
        @php $featuredBlog = $latestBlogs->first(); @endphp
        <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="featured-blog-card mb-3 d-block">
            <img src="{{ $featuredBlog->image ?? 'https://via.placeholder.com/800x400' }}" alt="{{ $featuredBlog->title }}" class="feat-img">
            <div class="feat-body">
                <span class="feat-cat-badge">{{ ucfirst($featuredBlog->category) }}</span>
                <h3>{{ $featuredBlog->title }}</h3>
                <p>{{ $featuredBlog->excerpt }}</p>
                <div class="blog-meta-row">
                    <span><i class="far fa-calendar-alt"></i> {{ $featuredBlog->formatted_date }}</span>
                    <span><i class="far fa-eye"></i> {{ number_format($featuredBlog->views) }} lượt xem</span>
                    <span><i class="far fa-clock"></i> 5 phút đọc</span>
                </div>
            </div>
        </a>

        {{-- Blog grid (các bài còn lại) --}}
        @if($latestBlogs->count() > 1)
        <div class="blog-grid mb-4">
            @foreach($latestBlogs->skip(1) as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="blog-mini-card">
                <img src="{{ $blog->image ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $blog->title }}">
                <div class="mini-body">
                    <span class="feat-cat-badge" style="font-size:0.65rem;padding:2px 8px;">{{ ucfirst($blog->category) }}</span>
                    <h4>{{ $blog->title }}</h4>
                    <div class="mini-meta"><i class="far fa-calendar-alt"></i> {{ $blog->formatted_date }} · <i class="far fa-eye"></i> {{ number_format($blog->views) }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
        @endif

        {{-- === ADSENSE IN-FEED (giữa trang) === --}}
        <div class="adsense-block mb-4">
            <div class="adsense-block-label">Quảng cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-format="fluid"
                data-ad-layout-key="-fb+5w+4e-db+86"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- === SẢN PHẨM NỔI BẬT === --}}
        <div class="shop-section">
            <div class="section-label">
                <span class="label-line"></span>
                <h2>🛒 Sản Phẩm Nổi Bật</h2>
                <a href="{{ route('shop') }}" class="ms-auto text-decoration-none fw-bold" style="font-size:.82rem;color:var(--primary);">Xem tất cả →</a>
            </div>

            {{-- Category filter icons --}}
            @if(isset($categories) && $categories->count() > 0)
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($categories->take(6) as $cat)
                <a href="{{ route('shop', ['category_id' => $cat->id]) }}"
                   class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill text-decoration-none fw-600"
                   style="background:#f0f4ff;color:var(--primary);font-size:0.78rem;font-weight:600;border:1px solid rgba(102,126,234,0.2);">
                    {{ $cat->name }}
                    @if($cat->products_count > 0)<span class="ms-1 badge" style="background:var(--primary);font-size:0.6rem;">{{ $cat->products_count }}</span>@endif
                </a>
                @endforeach
            </div>
            @endif

            <div class="row row-cols-2 row-cols-md-3 g-3" id="product-grid">
                @foreach($featuredProducts->take(6) as $product)
                <div class="col">
                    <div class="prod-card {{ $product->isInStock() ? '' : 'opacity-50' }}">
                        <div class="prod-img-wrap">
                            <span class="prod-badge">{{ strtoupper(substr($product->category, 0, 6)) }}</span>
                            @if(!$product->isInStock())<span class="prod-badge sale" style="left:auto;right:8px;">Hết</span>@endif
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                        </div>
                        <div class="prod-body">
                            <div class="prod-name">{{ $product->name }}</div>
                            <div class="prod-footer">
                                <div>
                                    <div class="prod-price {{ $product->is_on_sale ? 'sale-price' : '' }}">{{ $product->formatted_price }}</div>
                                    @if($product->is_on_sale)<div class="prod-old">{{ $product->formatted_original_price }}</div>@endif
                                </div>
                                @if($product->isInStock())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="prod-btn"><i class="fas fa-cart-plus"></i></button>
                                </form>
                                @else
                                <button class="prod-btn" disabled style="opacity:0.4;"><i class="fas fa-ban"></i></button>
                                @endif
                            </div>
                        </div>
                        @if($product->isInStock())<a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>@endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop') }}" class="btn-see-more"><i class="fas fa-store me-1"></i> Xem toàn bộ sản phẩm</a>
            </div>
        </div>

        {{-- === ADSENSE BANNER 2 === --}}
        <div class="adsense-block my-4">
            <div class="adsense-block-label">Quảng cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- === SẢN PHẨM ĐỘC QUYỀN === --}}
        @if($highlightProducts->count() > 0)
        <div class="mt-2 mb-2">
            <div class="section-label">
                <span class="label-line" style="background:linear-gradient(180deg,#10b981,#059669)"></span>
                <h2>⭐ Sản Phẩm Độc Quyền</h2>
                <a href="{{ route('shop') }}" class="ms-auto text-decoration-none fw-bold" style="font-size:.82rem;color:#10b981;">Xem tất cả →</a>
            </div>
            <div class="row row-cols-2 row-cols-md-4 g-3">
                @foreach($highlightProducts->take(8) as $product)
                <div class="col">
                    <div class="prod-card {{ $product->isInStock() ? '' : 'opacity-50' }}">
                        <div class="prod-img-wrap">
                            <span class="prod-badge" style="background:#10b981;">{{ strtoupper(substr($product->category, 0, 6)) }}</span>
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                        </div>
                        <div class="prod-body">
                            <div class="prod-name">{{ $product->name }}</div>
                            <div class="prod-footer">
                                <div>
                                    <div class="prod-price" style="color:#10b981;">{{ $product->formatted_price }}</div>
                                    @if($product->is_on_sale)<div class="prod-old">{{ $product->formatted_original_price }}</div>@endif
                                </div>
                                @if($product->isInStock())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="prod-btn" style="color:#10b981;"><i class="fas fa-cart-plus"></i></button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @if($product->isInStock())<a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- === COMBO AI === --}}
        @if(isset($comboAiProducts) && $comboAiProducts->count() > 0)
        <div class="mt-4 mb-2">
            <div class="section-label">
                <span class="label-line" style="background:linear-gradient(180deg,#3b82f6,#1d4ed8)"></span>
                <h2>🤖 Combo AI Giá Rẻ</h2>
                <a href="{{ route('shop') }}" class="ms-auto text-decoration-none fw-bold" style="font-size:.82rem;color:#3b82f6;">Xem tất cả →</a>
            </div>
            <div class="row row-cols-2 row-cols-md-4 g-3">
                @foreach($comboAiProducts->take(8) as $product)
                <div class="col">
                    <div class="prod-card {{ $product->isInStock() ? '' : 'opacity-50' }}">
                        <div class="prod-img-wrap">
                            <span class="prod-badge" style="background:#3b82f6;">COMBO AI</span>
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                        </div>
                        <div class="prod-body">
                            <div class="prod-name">{{ $product->name }}</div>
                            <div class="prod-footer">
                                <div>
                                    <div class="prod-price" style="color:#3b82f6;">{{ $product->formatted_price }}</div>
                                    @if($product->is_on_sale)<div class="prod-old">{{ $product->formatted_original_price }}</div>@endif
                                </div>
                                @if($product->isInStock())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="prod-btn" style="color:#3b82f6;"><i class="fas fa-cart-plus"></i></button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @if($product->isInStock())<a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- end main-content --}}

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">

        {{-- AdSense sidebar sticky --}}
        <div class="sidebar-widget p-0" style="position:sticky;top:130px;">
            <div class="adsense-block-label" style="border-radius:16px 16px 0 0;">Quảng cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:250px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975"
                data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- Sản phẩm bán chạy --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-fire-alt"></i> Sản Phẩm Bán Chạy</div>
            @foreach($featuredProducts->take(5) as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="sidebar-product">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/54' }}" alt="{{ $product->name }}">
                <div>
                    <div class="sp-name">{{ $product->name }}</div>
                    <div class="sp-price">{{ $product->formatted_price }}</div>
                </div>
            </a>
            @endforeach
            <div class="mt-3 text-center">
                <a href="{{ route('shop') }}" class="btn-see-more" style="font-size:0.78rem;padding:7px 18px;">Xem Shop →</a>
            </div>
        </div>

        {{-- Bài viết nổi bật --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-chart-line"></i> Bài Viết Nổi Bật</div>
            @foreach($latestBlogs as $i => $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="sidebar-blog-item">
                <span class="sb-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="sb-title">{{ $blog->title }}</span>
            </a>
            @endforeach
            <div class="mt-3 text-center">
                <a href="{{ route('blog.index') }}" class="btn-see-more" style="font-size:0.78rem;padding:7px 18px;">Đọc Blog →</a>
            </div>
        </div>

        {{-- Tags / Topics --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-tags"></i> Chủ Đề</div>
            <div class="tag-cloud">
                <a href="{{ route('blog.category', 'cong-nghe') }}" class="tag-pill">💻 Công Nghệ</a>
                <a href="{{ route('blog.category', 'ai') }}" class="tag-pill">🤖 AI</a>
                <a href="{{ route('blog.category', 'kiem-tien') }}" class="tag-pill">💰 Kiếm Tiền</a>
                <a href="{{ route('blog.category', 'thu-thuat') }}" class="tag-pill">🔧 Thủ Thuật</a>
                <a href="{{ route('blog.category', 'review') }}" class="tag-pill">⭐ Review</a>
                <a href="{{ route('blog.category', 'huong-dan') }}" class="tag-pill">📖 Hướng Dẫn</a>
                <a href="{{ route('shop') }}" class="tag-pill">🛒 Mua Sắm</a>
            </div>
        </div>

        {{-- AdSense sidebar 2 --}}
        <div class="sidebar-widget p-0">
            <div class="adsense-block-label" style="border-radius:16px 16px 0 0;">Quảng cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:200px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975"
                data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

    </aside>

</div>{{-- end page-layout --}}

{{-- Recent Purchase Toast --}}
@if(!empty($recentPurchases) && count($recentPurchases) > 0)
<script type="application/json" id="recentPurchaseData">@json($recentPurchases)</script>
<div id="recentPurchaseToast" class="rpt-toast" role="status">
    <div class="d-flex align-items-center">
        <span class="rpt-pill buy"><i class="fas fa-star"></i> Vừa mua</span>
        <span class="rpt-pill verify ms-2"><i class="fas fa-check-circle"></i> Đã xác minh</span>
        <button class="rpt-close ms-auto" id="recentPurchaseClose">&times;</button>
    </div>
    <div class="rpt-body">
        <div class="rpt-avatar"><span id="rptAvatarLetter">N</span></div>
        <div style="min-width:0;flex:1;">
            <div class="rpt-name" id="rptName">Khách hàng</div>
            <div class="rpt-sub" id="rptAction">vừa mua thành công</div>
            <a href="#" class="rpt-prod" id="rptProductLink">
                <div class="rpt-prod-title" id="rptProductTitle">Sản phẩm</div>
                <div class="rpt-prod-time" id="rptTime">vừa xong</div>
            </a>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

{{-- Flash sale countdown --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bar = document.querySelector('[data-countdown-end]');
    if (!bar) return;
    const endMs = parseInt(bar.dataset.countdownEnd || '0', 10);
    function tick() {
        const diff = Math.max(0, endMs - Date.now());
        if (endMs > 0 && diff <= 0) { bar.closest('.flash-bar')?.remove(); return; }
        const h = Math.floor(diff/3600000), m = Math.floor((diff%3600000)/60000), s = Math.floor((diff%60000)/1000);
        const p = n => String(n).padStart(2,'0');
        bar.querySelector('[data-unit="hours"]').textContent = p(h);
        bar.querySelector('[data-unit="minutes"]').textContent = p(m);
        bar.querySelector('[data-unit="seconds"]').textContent = p(s);
    }
    tick(); setInterval(tick, 1000);
});
</script>

{{-- Recent Purchase Toast --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataEl = document.getElementById('recentPurchaseData');
    const toastEl = document.getElementById('recentPurchaseToast');
    const closeEl = document.getElementById('recentPurchaseClose');
    if (!dataEl || !toastEl) return;
    const DISMISS_KEY = 'rptDismissedUntil';
    if (Date.now() < parseInt(localStorage.getItem(DISMISS_KEY)||'0')) return;
    let purchases = [];
    try { purchases = JSON.parse(dataEl.textContent || '[]'); } catch(e) { return; }
    if (!purchases.length) return;
    let idx = 0, stop = false, hideT, nextT;
    const SHOW = 3000, INTERVAL = 10000;
    function render(p) {
        const name = p?.customer_name || 'Khách hàng';
        document.getElementById('rptAvatarLetter').textContent = (name.match(/[A-Za-zÀ-ỹĐđ]/u)||['K'])[0].toUpperCase();
        document.getElementById('rptName').textContent = name;
        document.getElementById('rptAction').textContent = 'vừa ' + (p?.verb||'mua') + ' thành công';
        document.getElementById('rptProductTitle').textContent = p?.product_name || 'Sản phẩm';
        document.getElementById('rptTime').textContent = p?.time_ago || '';
        document.getElementById('rptProductLink').href = p?.product_url || '#';
    }
    function cycle() {
        if (stop) return;
        render(purchases[idx++ % purchases.length]);
        toastEl.classList.add('show');
        hideT = setTimeout(() => toastEl.classList.remove('show'), SHOW);
        nextT = setTimeout(cycle, INTERVAL);
    }
    if (closeEl) closeEl.addEventListener('click', () => {
        stop = true; toastEl.classList.remove('show');
        localStorage.setItem(DISMISS_KEY, Date.now() + 60000);
    });
    setTimeout(cycle, 1200);
});
</script>
@endpush
