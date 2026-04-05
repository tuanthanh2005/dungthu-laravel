@extends('layouts.app')

@section('title', 'Cửa Hàng Digital - DungThu.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/category-filter.css') }}?v={{ time() }}">
<style>
:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --dark: #1a1a2e;
    --muted: #6b7280;
    --bg: #f8faff;
    --card-radius: 16px;
    --card-shadow: 0 4px 20px rgba(102,126,234,0.09);
}
body { background: var(--bg); }

/* === SHOP PAGE HEADER (đồng bộ blog/home) === */
.shop-page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 28px 0 24px;
    border-bottom: 3px solid var(--primary);
}
.shop-page-header h1 {
    font-size: 2rem; font-weight: 900;
    background: linear-gradient(90deg, #fff, #a78bfa);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin-bottom: 6px;
}
.shop-page-header .sub { color: rgba(255,255,255,0.6); font-size: 0.9rem; }

/* === TOPIC BAR (đồng bộ) === */
.topic-bar {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky; top: 62px; z-index: 99;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.topic-bar .topics-scroll {
    display: flex; overflow-x: auto; scrollbar-width: none;
}
.topic-bar .topics-scroll::-webkit-scrollbar { display: none; }
.topic-bar a {
    white-space: nowrap; padding: 12px 18px;
    font-size: 0.85rem; font-weight: 600;
    color: var(--muted); text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: color .2s, border-color .2s;
    flex-shrink: 0;
}
.topic-bar a:hover, .topic-bar a.active {
    color: var(--primary); border-bottom-color: var(--primary);
}

/* === SHOP LAYOUT 2 CỘT === */
.shop-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 28px;
    max-width: 1180px;
    margin: 0 auto;
    padding: 24px 16px 48px;
}
@media (max-width: 991px) {
    .shop-layout { grid-template-columns: 1fr; }
    .shop-sidebar { display: none; }
}

/* === SEARCH & FILTER BAR === */
.search-bar-wrap {
    background: #fff;
    border-radius: var(--card-radius);
    padding: 16px 18px;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
    margin-bottom: 20px;
}
.search-bar-wrap form { display: flex; gap: 10px; }
.search-input {
    flex: 1;
    padding: 9px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 999px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color .2s;
}
.search-input:focus { border-color: var(--primary); }
.search-btn {
    padding: 9px 22px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; border: none; border-radius: 999px;
    font-weight: 700; font-size: 0.88rem; cursor: pointer;
    transition: opacity .15s;
    white-space: nowrap;
}
.search-btn:hover { opacity: 0.88; }

/* === CATEGORY TABS === */
.cat-tabs {
    display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px;
}
.cat-tab {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700;
    text-decoration: none;
    background: #f0f4ff; color: var(--muted);
    border: 1.5px solid transparent;
    transition: all .2s;
}
.cat-tab:hover { background: #e0e8ff; color: var(--primary); border-color: rgba(102,126,234,0.3); }
.cat-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
.cat-tab .ct-count {
    background: rgba(255,255,255,0.3);
    padding: 1px 6px; border-radius: 999px;
    font-size: 0.7rem; font-weight: 800;
}
.cat-tab.active .ct-count { background: rgba(255,255,255,0.25); }

/* === KẾT QUẢ LABEL === */
.results-label {
    font-size: 0.82rem; color: var(--muted);
    padding: 8px 14px; background: #fff;
    border-radius: 10px; border: 1px solid #e5e7eb;
    margin-bottom: 16px;
    display: inline-block;
}
.results-label strong { color: var(--primary); }

/* === SECTION LABEL === */
.section-label {
    display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
}
.section-label .bar {
    width: 4px; height: 22px; border-radius: 4px; flex-shrink: 0;
    background: linear-gradient(180deg, var(--primary), var(--secondary));
}
.section-label h2 { font-size: 1.05rem; font-weight: 800; color: var(--dark); margin: 0; }

/* === PRODUCT CARDS (đồng bộ home) === */
.prod-card {
    background: #fff; border-radius: 14px;
    overflow: hidden; box-shadow: 0 2px 14px rgba(0,0,0,0.07);
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s; height: 100%;
}
.prod-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(102,126,234,0.15); }
.prod-card .prod-img-wrap { position: relative; }
.prod-card .prod-img-wrap img { width: 100%; height: 150px; object-fit: cover; display: block; }
.prod-card .prod-badge {
    position: absolute; top: 8px; left: 8px;
    border-radius: 999px; font-size: 0.65rem; font-weight: 700;
    padding: 3px 8px; background: var(--primary); color: #fff;
    text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap; max-width: 80px;
    overflow: hidden; text-overflow: ellipsis;
}
.prod-card .prod-badge.sale { background: #ef4444; }
.prod-card .prod-badge.ebook { background: #8b5cf6; }
.prod-card .prod-body { padding: 12px 14px 14px; }
.prod-card .prod-name {
    font-size: 0.87rem; font-weight: 700;
    color: var(--dark); line-height: 1.3; margin-bottom: 6px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    min-height: 2.6em;
}
.prod-card .prod-desc {
    font-size: 0.75rem; color: var(--muted);
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 8px; min-height: 2.4em;
}
.prod-card .prod-footer {
    display: flex; align-items: center;
    justify-content: space-between; gap: 6px;
}
.prod-card .prod-price { font-size: 0.95rem; font-weight: 800; color: var(--primary); }
.prod-card .prod-price.sale-price { color: #ef4444; }
.prod-card .prod-old { font-size: 0.72rem; text-decoration: line-through; color: #9ca3af; }
.prod-card .prod-sale-badge { font-size: 0.65rem; background: #ef4444; color: #fff; padding: 1px 5px; border-radius: 4px; font-weight: 700; }
.prod-card .prod-btn {
    width: 32px; height: 32px; border-radius: 50%; border: none;
    background: #f0f4ff; color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px; flex-shrink: 0;
    transition: background .15s, color .15s;
}
.prod-card .prod-btn:hover { background: var(--primary); color: #fff; }

/* === ADSENSE === */
.ad-block {
    background: #fff; border: 1px dashed rgba(102,126,234,0.18);
    border-radius: 12px; overflow: hidden; margin: 20px 0;
}
.ad-label {
    font-size: 0.62rem; color: #bbb;
    text-align: center; padding: 3px 0;
    background: #fafafa; border-bottom: 1px solid #eee;
    letter-spacing: 0.1em; text-transform: uppercase;
}

/* === SIDEBAR === */
.sidebar-sticky { position: sticky; top: 92px; }
.sidebar-widget {
    background: #fff; border-radius: var(--card-radius);
    padding: 18px; box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08); margin-bottom: 20px;
}
.sidebar-widget-title {
    font-size: 0.88rem; font-weight: 800; color: var(--dark);
    margin-bottom: 14px; padding-bottom: 10px;
    border-bottom: 2px solid #f0f4ff;
    display: flex; align-items: center; gap: 7px;
}
.sidebar-widget-title i { color: var(--primary); }
.sidebar-cat-link {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 0; border-bottom: 1px solid #f3f4f6;
    text-decoration: none; color: var(--dark);
    font-size: 0.85rem; font-weight: 600;
    transition: color .15s;
}
.sidebar-cat-link:last-child { border-bottom: none; }
.sidebar-cat-link:hover { color: var(--primary); }
.sidebar-cat-link .sc-count {
    font-size: 0.72rem; background: #f0f4ff;
    color: var(--primary); padding: 2px 7px;
    border-radius: 999px; font-weight: 700;
}
.sidebar-blog-item {
    display: flex; gap: 10px; align-items: flex-start;
    padding: 8px 0; border-bottom: 1px solid #f3f4f6;
    text-decoration: none; color: inherit;
}
.sidebar-blog-item:last-child { border-bottom: none; }
.sidebar-blog-item:hover { opacity: 0.8; }
.sidebar-blog-item img { width: 56px; height: 44px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-blog-item .sbi-title {
    font-size: 0.8rem; font-weight: 600; color: var(--dark);
    line-height: 1.3; display: -webkit-box;
    -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.sidebar-blog-item .sbi-date { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
.tag-cloud { display: flex; flex-wrap: wrap; gap: 6px; }
.tag-pill {
    padding: 4px 11px; border-radius: 999px; font-size: 0.75rem;
    font-weight: 600; background: #f0f4ff; color: var(--primary);
    text-decoration: none; border: 1px solid rgba(102,126,234,0.18);
    transition: background .15s, color .15s;
}
.tag-pill:hover { background: var(--primary); color: #fff; }

/* PAGINATION */
.pagination .page-link { border-radius: 8px !important; margin: 0 2px; font-weight: 600; color: var(--primary); border-color: rgba(102,126,234,0.2); }
.pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

@media (max-width: 576px) {
    .shop-page-header h1 { font-size: 1.4rem; }
    .prod-card .prod-img-wrap img { height: 120px; }
    .shop-layout { padding: 16px 12px 32px; }
}
</style>
@endpush

@section('content')

{{-- === SHOP HEADER (đồng bộ blog) === --}}
<div class="shop-page-header">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <div>
                <h1>🛒 Cửa Hàng DungThu.com</h1>
                <div class="sub">Tài khoản số · Phần mềm · Ebook · Combo AI · Dịch vụ Digital</div>
            </div>
            {{-- AdSense header --}}
            <div class="d-none d-md-block">
                <div style="font-size:0.6rem;color:rgba(255,255,255,0.35);text-align:center;letter-spacing:0.08em;margin-bottom:2px;">QUẢNG CÁO</div>
                <ins class="adsbygoogle" style="display:inline-block;width:250px;height:80px;"
                    data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>
    </div>
</div>

{{-- === TOPIC BAR (đồng bộ) === --}}
<div class="topic-bar">
    <div class="container">
        <div class="topics-scroll">
            <a href="{{ route('home') }}">🏠 Trang chủ</a>
            <a href="{{ route('blog.index') }}">📝 Blog</a>
            <a href="{{ route('shop') }}" class="active">🛒 Shop</a>
            <a href="{{ route('shop', ['category_id' => '']) }}">⭐ Tất cả</a>
            @if(isset($categories))
            @foreach($categories->take(5) as $cat)
            <a href="{{ route('shop', ['category_id' => $cat->id]) }}"
               class="{{ $currentCategoryId == $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
            @endforeach
            @endif
            <a href="{{ route('buff.index') }}">⚡ Buff Service</a>
        </div>
    </div>
</div>

{{-- === MAIN SHOP LAYOUT === --}}
<div class="shop-layout">

    {{-- === MAIN CONTENT === --}}
    <div>

        {{-- Search bar --}}
        <div class="search-bar-wrap">
            <form action="{{ route('shop') }}" method="GET">
                <input type="hidden" name="category_id" value="{{ $currentCategoryId }}">
                <input type="text" class="search-input" name="search"
                    placeholder="🔍 Tìm sản phẩm..." value="{{ $searchTerm }}">
                <button type="submit" class="search-btn"><i class="fas fa-search me-1"></i> Tìm</button>
            </form>
        </div>

        {{-- Category tabs --}}
        @if(isset($categories) && $categories->count() > 0)
        <div class="cat-tabs">
            <a href="{{ route('shop') }}"
               class="cat-tab {{ $currentCategoryId == 'all' ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Tất cả
                <span class="ct-count">{{ $categories->sum('products_count') }}</span>
            </a>
            @foreach($categories as $category)
            <a href="{{ route('shop', ['category_id' => $category->id, 'search' => $searchTerm]) }}"
               class="cat-tab {{ $currentCategoryId == $category->id ? 'active' : '' }}">
                @switch($category->type)
                    @case('tech') 💻 @break
                    @case('ebooks') 📚 @break
                    @case('doc') 📄 @break
                    @default 📦 @break
                @endswitch
                {{ $category->name }}
                @if($category->products_count > 0)
                <span class="ct-count">{{ $category->products_count }}</span>
                @endif
            </a>
            @endforeach
        </div>
        @endif

        {{-- Results count --}}
        @if($searchTerm || $currentCategoryId != 'all')
        <div class="results-label mb-3">
            <i class="fas fa-filter me-1" style="color:var(--primary);"></i>
            Tìm thấy <strong>{{ $items->total() }}</strong> sản phẩm
            @if($searchTerm) khớp "<strong>{{ $searchTerm }}</strong>"@endif
            @if($currentCategoryId != 'all')
                @php $selCat = $categories->firstWhere('id', $currentCategoryId); @endphp
                @if($selCat) trong danh mục <strong>{{ $selCat->name }}</strong>@endif
            @endif
        </div>
        @endif

        {{-- Section label --}}
        <div class="section-label">
            <span class="bar"></span>
            <h2>{{ $searchTerm ? '🔍 Kết Quả Tìm Kiếm' : ($currentCategoryId != 'all' ? '📦 Sản Phẩm' : '⭐ Tất Cả Sản Phẩm') }}</h2>
        </div>

        @if($items->count() > 0)
        <div class="row row-cols-2 row-cols-md-3 g-3">
            @foreach($items as $i => $product)

            {{-- AdSense in-feed sau mỗi 8 sản phẩm --}}
            @if($i > 0 && $i % 8 === 0)
            </div>
            <div class="ad-block">
                <div class="ad-label">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:90px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-format="auto" data-full-width-responsive="true"
                    data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
            <div class="row row-cols-2 row-cols-md-3 g-3">
            @endif

            <div class="col" data-aos="fade-up" data-aos-delay="{{ ($i % 6) * 40 }}">
                <div class="prod-card {{ $product->isInStock() ? '' : 'opacity-50' }}">
                    <div class="prod-img-wrap">
                        @if($product->is_on_sale)
                            <span class="prod-badge sale">-{{ $product->discount_percent }}%</span>
                        @elseif($product->category === 'ebooks')
                            <span class="prod-badge ebook">📚 Ebook</span>
                        @else
                            <span class="prod-badge">{{ strtoupper(substr($product->category, 0, 8)) }}</span>
                        @endif
                        @if(!$product->isInStock())
                            <span class="prod-badge sale" style="left:auto;right:8px;">Hết hàng</span>
                        @endif
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">{{ $product->name }}</div>
                        @if($product->description)
                        <div class="prod-desc">{{ Str::limit($product->description, 60) }}</div>
                        @endif
                        @if($product->category === 'ebooks' && $product->hasFile())
                        <div class="mb-1">
                            <span class="badge bg-info" style="font-size:0.65rem;">
                                <i class="fas fa-file"></i> {{ strtoupper($product->file_type) }}
                            </span>
                            <span class="badge bg-secondary" style="font-size:0.65rem;">
                                <i class="fas fa-download"></i> {{ $product->formatted_file_size }}
                            </span>
                        </div>
                        @endif
                        <div class="prod-footer">
                            <div style="min-width:0;">
                                <div class="prod-price {{ $product->is_on_sale ? 'sale-price' : '' }}">{{ $product->formatted_price }}</div>
                                @if($product->is_on_sale)
                                <div class="prod-old">{{ $product->formatted_original_price }}</div>
                                @endif
                            </div>
                            @if($product->isInStock())
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline" onclick="event.stopPropagation()">
                                @csrf
                                <button type="submit" class="prod-btn"><i class="fas fa-cart-plus"></i></button>
                            </form>
                            @else
                            <button class="prod-btn" disabled style="opacity:0.35;"><i class="fas fa-ban"></i></button>
                            @endif
                        </div>
                    </div>
                    @if($product->isInStock())
                    <a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $items->links() }}
        </div>

        @else
        <div class="text-center py-5" style="background:#fff;border-radius:var(--card-radius);box-shadow:var(--card-shadow);">
            <i class="fas fa-box-open fa-3x mb-3" style="color:#e5e7eb;"></i>
            <h4 style="color:var(--muted);">Không tìm thấy sản phẩm nào</h4>
            <p style="color:var(--muted);font-size:0.9rem;">Thử từ khóa khác hoặc chọn danh mục khác</p>
            <a href="{{ route('shop') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 22px;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;font-weight:700;text-decoration:none;margin-top:8px;">
                <i class="fas fa-store"></i> Xem tất cả sản phẩm
            </a>
        </div>
        @endif

        {{-- AdSense cuối --}}
        <div class="ad-block mt-4">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

    </div>{{-- end main --}}

    {{-- === SIDEBAR === --}}
    <aside class="shop-sidebar">
        <div class="sidebar-sticky">

            {{-- AdSense sidebar --}}
            <div class="sidebar-widget p-0 mb-4">
                <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:280px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-slot="4989157975" data-ad-format="auto"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>

            {{-- Danh mục --}}
            @if(isset($categories) && $categories->count() > 0)
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-list"></i> Danh Mục</div>
                <a href="{{ route('shop') }}" class="sidebar-cat-link">
                    <span>🏷️ Tất cả sản phẩm</span>
                    <span class="sc-count">{{ $categories->sum('products_count') }}</span>
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="sidebar-cat-link">
                    <span>
                        @switch($cat->type)
                            @case('tech') 💻 @break
                            @case('ebooks') 📚 @break
                            @case('doc') 📄 @break
                            @default 📦 @break
                        @endswitch
                        {{ $cat->name }}
                    </span>
                    @if($cat->products_count > 0)
                    <span class="sc-count">{{ $cat->products_count }}</span>
                    @endif
                </a>
                @endforeach
            </div>
            @endif

            {{-- Tags --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-tags"></i> Khám Phá Thêm</div>
                <div class="tag-cloud">
                    <a href="{{ route('blog.index') }}" class="tag-pill">📝 Blog</a>
                    <a href="{{ route('blog.category', 'ai') }}" class="tag-pill">🤖 AI Tools</a>
                    <a href="{{ route('blog.category', 'kiem-tien') }}" class="tag-pill">💰 Kiếm Tiền</a>
                    <a href="{{ route('buff.index') }}" class="tag-pill">⚡ Buff</a>
                    <a href="{{ route('blog.category', 'thu-thuat') }}" class="tag-pill">🔧 Thủ Thuật</a>
                </div>
            </div>

            {{-- Blog mới --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-newspaper"></i> Bài Viết Mới</div>
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
                <div class="mt-3 text-center">
                    <a href="{{ route('blog.index') }}" style="display:inline-flex;align-items:center;gap:5px;padding:7px 18px;border-radius:999px;border:2px solid var(--primary);color:var(--primary);font-weight:700;font-size:0.78rem;text-decoration:none;">
                        Xem Blog →
                    </a>
                </div>
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

</div>{{-- end shop-layout --}}

@endsection

@push('scripts')
<script>
    AOS.init({ duration: 600, once: true });
</script>
@endpush
