@extends('layouts.app')

@section('title', isset($category) ? ucfirst($category) . ' - Blog DungThu.com' : 'Blog Công Nghệ & Kiếm Tiền Online - DungThu.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --dark: #1a1a2e;
    --muted: #6b7280;
    --card-radius: 16px;
    --card-shadow: 0 4px 20px rgba(102,126,234,0.09);
    --bg: #f8faff;
}
body { background: var(--bg); }

/* ===== BLOG PAGE HEADER ===== */
.blog-page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 36px 0 32px;
    margin-top: 64px; /* offset fixed navbar */
    border-bottom: 3px solid var(--primary);
}
.blog-page-header h1 {
    font-size: 2rem; font-weight: 900;
    background: linear-gradient(90deg, #fff, #a78bfa);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin-bottom: 6px;
}
.blog-page-header .sub { color: rgba(255,255,255,0.6); font-size: 0.92rem; }

/* ===== CATEGORY FILTER ===== */
.cat-filter {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0;
    position: sticky; top: 62px; z-index: 99;
}
.cat-filter .cats-scroll {
    display: flex; gap: 0;
    overflow-x: auto; scrollbar-width: none;
}
.cat-filter .cats-scroll::-webkit-scrollbar { display: none; }
.cat-filter a {
    white-space: nowrap; padding: 12px 20px;
    font-size: 0.85rem; font-weight: 700;
    color: var(--muted); text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: color .2s, border-color .2s;
}
.cat-filter a:hover, .cat-filter a.active {
    color: var(--primary); border-bottom-color: var(--primary);
}

/* ===== LAYOUT ===== */
.blog-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 28px;
    max-width: 1180px;
    margin: 0 auto;
    padding: 28px 16px 48px;
}
@media (max-width: 991px) {
    .blog-layout { grid-template-columns: 1fr; }
    .blog-sidebar { display: none; }
}

/* ===== SECTION LABEL ===== */
.section-label {
    display: flex; align-items: center; gap: 10px; margin-bottom: 18px;
}
.section-label .bar {
    width: 4px; height: 22px; border-radius: 4px;
    background: linear-gradient(180deg, var(--primary), var(--secondary));
    flex-shrink: 0;
}
.section-label h2 { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0; }

/* ===== FEATURED BIG CARD ===== */
.blog-featured {
    background: #fff; border-radius: var(--card-radius);
    overflow: hidden; box-shadow: var(--card-shadow);
    text-decoration: none; color: inherit;
    display: block; transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(102,126,234,0.08);
    margin-bottom: 20px;
}
.blog-featured:hover { transform: translateY(-3px); box-shadow: 0 14px 44px rgba(102,126,234,0.18); color: inherit; }
.blog-featured img { width: 100%; height: 260px; object-fit: cover; }
.blog-featured .bf-body { padding: 20px 24px 24px; }
.cat-badge {
    display: inline-block; padding: 3px 11px; border-radius: 999px;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--primary), var(--secondary)); color: #fff;
    margin-bottom: 10px;
}
.blog-featured h2 { font-size: 1.4rem; font-weight: 800; color: var(--dark); line-height: 1.3; margin-bottom: 8px; }
.blog-featured .excerpt { font-size: 0.9rem; color: var(--muted); line-height: 1.65; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.meta-row { display: flex; align-items: center; gap: 14px; font-size: 0.77rem; color: var(--muted); flex-wrap: wrap; }
.meta-row i { color: var(--primary); margin-right: 3px; }
.read-more-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 999px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; font-weight: 700; font-size: 0.82rem;
    text-decoration: none; transition: opacity .15s, transform .15s;
}
.read-more-btn:hover { opacity: 0.88; transform: translateX(2px); color: #fff; }

/* ===== LIST VIEW ===== */
.blog-list-card {
    display: flex; gap: 16px; align-items: flex-start;
    background: #fff; border-radius: 14px;
    padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    text-decoration: none; color: inherit;
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s;
    margin-bottom: 14px;
}
.blog-list-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(102,126,234,0.13); color: inherit; }
.blog-list-card img { width: 110px; height: 82px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }
.blog-list-card .lc-body { flex: 1; min-width: 0; }
.blog-list-card h3 { font-size: 0.95rem; font-weight: 700; color: var(--dark); line-height: 1.35; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.blog-list-card p { font-size: 0.82rem; color: var(--muted); margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.blog-list-card .lc-meta { font-size: 0.72rem; color: var(--muted); display: flex; gap: 10px; flex-wrap: wrap; }
.blog-list-card .lc-meta i { color: var(--primary); }

/* ===== GRID VIEW ===== */
.blog-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
@media (max-width: 576px) { .blog-grid-2 { grid-template-columns: 1fr; } }
.blog-grid-card {
    background: #fff; border-radius: 14px;
    overflow: hidden; box-shadow: var(--card-shadow);
    text-decoration: none; color: inherit;
    border: 1px solid rgba(102,126,234,0.07);
    transition: transform .2s, box-shadow .2s;
}
.blog-grid-card:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(102,126,234,0.16); color: inherit; }
.blog-grid-card img { width: 100%; height: 140px; object-fit: cover; }
.blog-grid-card .gc-body { padding: 12px 14px 14px; }
.blog-grid-card h3 { font-size: 0.88rem; font-weight: 700; color: var(--dark); line-height: 1.35; margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.blog-grid-card .gc-meta { font-size: 0.72rem; color: var(--muted); }
.blog-grid-card .gc-meta i { color: var(--primary); }

/* ===== ADSENSE ===== */
.ad-block {
    background: #fff; border: 1px dashed rgba(102,126,234,0.18);
    border-radius: 12px; overflow: hidden; margin: 20px 0;
}
.ad-label { font-size: 0.62rem; color: #bbb; text-align: center; padding: 3px 0; background: #fafafa; border-bottom: 1px solid #f0f0f0; letter-spacing: 0.1em; text-transform: uppercase; }

/* ===== SIDEBAR ===== */
.sidebar-widget {
    background: #fff; border-radius: var(--card-radius);
    padding: 18px; box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
    margin-bottom: 20px;
}
.sidebar-widget-title { font-size: 0.88rem; font-weight: 800; color: var(--dark); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f4ff; display: flex; align-items: center; gap: 7px; }
.sidebar-widget-title i { color: var(--primary); }
.sidebar-blog-rank {
    display: flex; gap: 10px; align-items: flex-start;
    padding: 8px 0; border-bottom: 1px solid #f3f4f6;
    text-decoration: none; color: inherit;
}
.sidebar-blog-rank:last-child { border-bottom: none; }
.sidebar-blog-rank:hover { opacity: 0.8; }
.rank-num { font-size: 1.1rem; font-weight: 900; color: #e5e7eb; line-height: 1; min-width: 22px; }
.rank-title { font-size: 0.82rem; font-weight: 600; color: var(--dark); line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.rank-views { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
.tag-cloud { display: flex; flex-wrap: wrap; gap: 6px; }
.tag-pill { padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: #f0f4ff; color: var(--primary); text-decoration: none; border: 1px solid rgba(102,126,234,0.18); transition: background .15s, color .15s; }
.tag-pill:hover { background: var(--primary); color: #fff; }
.sidebar-prod { display: flex; gap: 10px; align-items: center; padding: 9px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; }
.sidebar-prod:last-child { border-bottom: none; }
.sidebar-prod:hover { opacity: 0.85; }
.sidebar-prod img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-prod .sp-name { font-size: 0.8rem; font-weight: 700; color: var(--dark); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.sidebar-prod .sp-price { font-size: 0.82rem; font-weight: 800; color: var(--primary); margin-top: 2px; }

/* ===== PAGINATION ===== */
.pagination .page-link { border-radius: 8px !important; margin: 0 2px; font-weight: 600; color: var(--primary); border-color: rgba(102,126,234,0.2); }
.pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

@media (max-width: 576px) {
    .blog-page-header h1 { font-size: 1.4rem; }
    .blog-featured img { height: 180px; }
    .blog-list-card img { width: 80px; height: 64px; }
}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="blog-page-header">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
            <div>
                <h1>📝 Blog DungThu.com</h1>
                <div class="sub">Chia sẻ kiến thức · Công nghệ · AI · Kiếm tiền online · Thủ thuật</div>
            </div>
            {{-- AdSense header --}}
            <div class="d-none d-md-block">
                <div style="font-size:0.6rem;color:rgba(255,255,255,0.35);letter-spacing:0.08em;text-align:center;margin-bottom:2px;">QUẢNG CÁO</div>
                <ins class="adsbygoogle" style="display:inline-block;width:250px;height:80px;"
                    data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORY FILTER --}}
<div class="cat-filter">
    <div class="container">
        <div class="cats-scroll">
            <a href="{{ route('blog.index') }}" class="{{ !isset($category) ? 'active' : '' }}">🏷️ Tất Cả</a>
            <a href="{{ route('blog.category', 'cong-nghe') }}" class="{{ (isset($category) && $category=='cong-nghe') ? 'active' : '' }}">💻 Công Nghệ</a>
            <a href="{{ route('blog.category', 'ai') }}" class="{{ (isset($category) && $category=='ai') ? 'active' : '' }}">🤖 AI Tools</a>
            <a href="{{ route('blog.category', 'kiem-tien') }}" class="{{ (isset($category) && $category=='kiem-tien') ? 'active' : '' }}">💰 Kiếm Tiền</a>
            <a href="{{ route('blog.category', 'thu-thuat') }}" class="{{ (isset($category) && $category=='thu-thuat') ? 'active' : '' }}">🔧 Thủ Thuật</a>
            <a href="{{ route('blog.category', 'review') }}" class="{{ (isset($category) && $category=='review') ? 'active' : '' }}">⭐ Review</a>
            <a href="{{ route('blog.category', 'huong-dan') }}" class="{{ (isset($category) && $category=='huong-dan') ? 'active' : '' }}">📖 Hướng Dẫn</a>
        </div>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="blog-layout">

    {{-- === MAIN CONTENT === --}}
    <div>

        @if($blogs->total() === 0)
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-3x mb-3" style="color:#e5e7eb;"></i>
            <h3 style="color:var(--muted);">Chưa có bài viết nào</h3>
            <a href="{{ route('blog.index') }}" class="read-more-btn mt-3 d-inline-flex">Xem tất cả bài viết</a>
        </div>
        @else

        @php $featured = $blogs->first(); $restBlogs = $blogs->skip(1); @endphp

        {{-- Featured post --}}
        <div class="section-label">
            <span class="bar"></span>
            <h2>{{ isset($category) ? '📂 ' . ucfirst($category) : '🔥 Bài Viết Nổi Bật' }}</h2>
        </div>

        <a href="{{ route('blog.show', $featured->slug) }}" class="blog-featured">
            <img src="{{ $featured->image ?? 'https://via.placeholder.com/800x350' }}" alt="{{ $featured->title }}">
            <div class="bf-body">
                <span class="cat-badge">{{ ucfirst($featured->category) }}</span>
                <h2>{{ $featured->title }}</h2>
                <p class="excerpt">{{ $featured->excerpt }}</p>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="meta-row">
                        <span><i class="far fa-calendar-alt"></i> {{ $featured->formatted_date }}</span>
                        <span><i class="far fa-eye"></i> {{ number_format($featured->views) }} lượt xem</span>
                        <span><i class="far fa-clock"></i> 5 phút đọc</span>
                    </div>
                    <span class="read-more-btn">Đọc bài <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </div>
        </a>

        {{-- AdSense in-feed sau bài đầu --}}
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-format="fluid" data-ad-layout-key="-fb+5w+4e-db+86"
                data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- Grid 2 cột (bài 2 và 3) --}}
        @php $gridBlogs = $restBlogs->take(2); $listBlogs = $restBlogs->skip(2); @endphp
        @if($gridBlogs->count() > 0)
        <div class="section-label mt-2">
            <span class="bar"></span>
            <h2>📰 Bài Viết Mới</h2>
        </div>
        <div class="blog-grid-2">
            @foreach($gridBlogs as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="blog-grid-card">
                <img src="{{ $blog->image ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $blog->title }}">
                <div class="gc-body">
                    <span class="cat-badge" style="font-size:0.65rem;padding:2px 8px;">{{ ucfirst($blog->category) }}</span>
                    <h3>{{ $blog->title }}</h3>
                    <div class="gc-meta">
                        <i class="far fa-calendar-alt"></i> {{ $blog->formatted_date }}
                        &nbsp;·&nbsp;
                        <i class="far fa-eye"></i> {{ number_format($blog->views) }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- List view (bài 4 trở đi) --}}
        @if($listBlogs->count() > 0)
        <div class="section-label mt-1">
            <span class="bar"></span>
            <h2>📄 Tất Cả Bài Viết</h2>
        </div>
        @foreach($listBlogs as $i => $blog)
            @if($i === 2)
            {{-- AdSense giữa danh sách --}}
            <div class="ad-block">
                <div class="ad-label">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:90px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-format="auto" data-full-width-responsive="true"
                    data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
            @endif
            <a href="{{ route('blog.show', $blog->slug) }}" class="blog-list-card">
                <img src="{{ $blog->image ?? 'https://via.placeholder.com/200x130' }}" alt="{{ $blog->title }}">
                <div class="lc-body">
                    <span class="cat-badge" style="font-size:0.65rem;padding:2px 8px;">{{ ucfirst($blog->category) }}</span>
                    <h3>{{ $blog->title }}</h3>
                    <p>{{ $blog->excerpt }}</p>
                    <div class="lc-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $blog->formatted_date }}</span>
                        <span><i class="far fa-eye"></i> {{ number_format($blog->views) }} lượt xem</span>
                        <span><i class="far fa-clock"></i> 5 phút</span>
                    </div>
                </div>
            </a>
        @endforeach
        @endif

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $blogs->links() }}
        </div>

        @endif

    </div>

    {{-- === SIDEBAR === --}}
    <aside class="blog-sidebar">

        {{-- AdSense sidebar --}}
        <div class="sidebar-widget p-0 mb-4">
            <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:280px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975" data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- Bài viết xem nhiều --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-chart-line"></i> Xem Nhiều Nhất</div>
            @foreach($blogs->take(5) as $i => $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="sidebar-blog-rank">
                <span class="rank-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <div class="rank-title">{{ $blog->title }}</div>
                    <div class="rank-views"><i class="far fa-eye"></i> {{ number_format($blog->views) }} lượt xem</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Chủ đề --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-tags"></i> Chủ Đề</div>
            <div class="tag-cloud">
                <a href="{{ route('blog.category', 'cong-nghe') }}" class="tag-pill">💻 Công Nghệ</a>
                <a href="{{ route('blog.category', 'ai') }}" class="tag-pill">🤖 AI</a>
                <a href="{{ route('blog.category', 'kiem-tien') }}" class="tag-pill">💰 Kiếm Tiền</a>
                <a href="{{ route('blog.category', 'thu-thuat') }}" class="tag-pill">🔧 Thủ Thuật</a>
                <a href="{{ route('blog.category', 'review') }}" class="tag-pill">⭐ Review</a>
                <a href="{{ route('blog.category', 'huong-dan') }}" class="tag-pill">📖 Hướng Dẫn</a>
            </div>
        </div>

        {{-- Sản phẩm hot --}}
        <div class="sidebar-widget">
            <div class="sidebar-widget-title"><i class="fas fa-fire"></i> Sản Phẩm Hot</div>
            <a href="{{ route('shop') }}" class="sidebar-prod">
                <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-store" style="color:#fff;font-size:20px;"></i></div>
                <div>
                    <div class="sp-name">Xem tất cả sản phẩm số</div>
                    <div class="sp-price">→ Vào Shop</div>
                </div>
            </a>
            <a href="{{ route('buff.index') }}" class="sidebar-prod">
                <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#f093fb,#f5576c);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-bolt" style="color:#fff;font-size:20px;"></i></div>
                <div>
                    <div class="sp-name">Dịch vụ buff Sub/Like/View</div>
                    <div class="sp-price" style="color:#f5576c;">→ Xem Giá</div>
                </div>
            </a>
        </div>

        {{-- AdSense sidebar 2 --}}
        <div class="sidebar-widget p-0">
            <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:200px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975" data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

    </aside>

</div>{{-- end blog-layout --}}

@endsection

@push('scripts')
<script>
    AOS.init({ duration: 700, once: true });
</script>
@endpush
