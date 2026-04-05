@extends('layouts.app')

@section('title', $blog->title . ' - DungThu.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --dark: #1a1a2e;
    --text: #2d3748;
    --muted: #6b7280;
    --bg: #f8faff;
    --card-radius: 16px;
    --card-shadow: 0 4px 20px rgba(102,126,234,0.09);
}
body { background: var(--bg); font-family: 'Segoe UI', system-ui, sans-serif; }

/* ===== BREADCRUMB BAR ===== */
.breadcrumb-bar {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 10px 0;
    margin-top: 64px; /* offset fixed navbar */
}
.breadcrumb { margin: 0; background: transparent; padding: 0; font-size: 0.82rem; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--primary); }
.breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 600; }
.breadcrumb-item a:hover { text-decoration: underline; }

/* ===== LAYOUT ===== */
.article-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 28px;
    max-width: 1180px;
    margin: 0 auto;
    padding: 28px 16px 48px;
    align-items: start;
}
@media (max-width: 991px) {
    .article-layout { grid-template-columns: 1fr; }
    .article-sidebar { display: none; }
}

/* ===== ARTICLE CARD ===== */
.article-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
    overflow: hidden;
}
.article-header { padding: 32px 36px 0; }
@media (max-width: 768px) { .article-header { padding: 20px 18px 0; } }

.article-cat {
    display: inline-block; padding: 4px 14px; border-radius: 999px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.07em;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; margin-bottom: 14px;
}
.article-title {
    font-size: 2rem; font-weight: 900;
    line-height: 1.25; color: var(--dark);
    margin-bottom: 16px;
}
@media (max-width: 768px) { .article-title { font-size: 1.35rem; } }

.article-meta {
    display: flex; align-items: center; gap: 16px;
    padding: 14px 0 16px;
    border-top: 1px solid #f0f4ff;
    border-bottom: 1px solid #f0f4ff;
    margin-bottom: 0;
    flex-wrap: wrap;
    font-size: 0.8rem; color: var(--muted);
}
.article-meta i { color: var(--primary); margin-right: 3px; }

/* ===== FEATURED IMAGE ===== */
.article-feat-img {
    width: 100%; max-height: 480px; object-fit: cover;
    display: block; margin-top: 24px;
}
@media (max-width: 768px) { .article-feat-img { max-height: 220px; } }

/* ===== EXCERPT ===== */
.article-excerpt {
    margin: 0 36px 0;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(102,126,234,0.07), rgba(118,75,162,0.07));
    border-left: 4px solid var(--primary);
    border-radius: 0 12px 12px 0;
    font-size: 1rem; line-height: 1.75;
    color: #4a5568; font-style: italic;
    margin-top: 24px;
}
@media (max-width: 768px) { .article-excerpt { margin: 16px 16px 0; padding: 14px 16px; font-size: 0.9rem; } }

/* ===== ARTICLE CONTENT ===== */
.article-body {
    padding: 28px 36px 36px;
    font-size: 1.02rem; line-height: 1.9; color: var(--text);
}
@media (max-width: 768px) { .article-body { padding: 20px 18px 24px; font-size: 0.95rem; } }

.article-body h2 {
    font-size: 1.5rem; font-weight: 800;
    color: var(--dark); margin: 2.5rem 0 1rem;
    padding-left: 14px;
    border-left: 4px solid var(--primary);
}
.article-body h3 {
    font-size: 1.2rem; font-weight: 700;
    color: var(--secondary); margin: 2rem 0 0.8rem;
}
.article-body p { margin-bottom: 1.4rem; text-align: justify; }
.article-body ul, .article-body ol { margin-bottom: 1.4rem; padding-left: 1.8rem; }
.article-body li { margin-bottom: 0.6rem; }
.article-body img { max-width: 100%; height: auto; border-radius: 12px; margin: 1.8rem auto; display: block; box-shadow: 0 6px 20px rgba(0,0,0,0.10); }
.article-body blockquote { border-left: 4px solid var(--primary); padding: 16px 24px; margin: 2rem 0; font-style: italic; color: #555; background: #f8f9fa; border-radius: 0 10px 10px 0; }
.article-body code { background: #f1f5f9; padding: 2px 7px; border-radius: 5px; color: #e11d48; font-family: 'Courier New', monospace; font-size: 0.9em; }
.article-body pre { background: #1e293b; color: #f1f5f9; padding: 18px; border-radius: 10px; overflow-x: auto; margin: 1.8rem 0; }
.article-body a { color: var(--primary); text-decoration: underline; }
.article-body a:hover { color: var(--secondary); }

/* ===== ADSENSE IN-ARTICLE ===== */
.ad-in-article {
    background: #fafbff; border: 1px dashed rgba(102,126,234,0.15);
    border-radius: 12px; overflow: hidden;
    margin: 28px 0; text-align: center;
}
.ad-label { font-size: 0.62rem; color: #bbb; padding: 3px 0; background: #f5f5f5; border-bottom: 1px solid #eee; letter-spacing: 0.1em; text-transform: uppercase; }

/* ===== AUTHOR BOX ===== */
.author-box {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 24px; margin: 0 36px 28px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 14px;
}
@media (max-width: 768px) { .author-box { margin: 0 16px 20px; padding: 14px; gap: 12px; } }
.author-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: #fff; flex-shrink: 0;
}
.author-name { font-size: 1rem; font-weight: 800; color: var(--dark); margin-bottom: 3px; }
.author-desc { font-size: 0.82rem; color: var(--muted); line-height: 1.4; }

/* ===== SHARE SECTION ===== */
.share-box {
    display: flex; align-items: center; gap: 12px;
    padding: 18px 36px; border-top: 1px solid #f0f4ff;
    flex-wrap: wrap;
}
@media (max-width: 768px) { .share-box { padding: 14px 18px; } }
.share-box .share-label { font-size: 0.85rem; font-weight: 700; color: var(--dark); }
.share-btn {
    width: 38px; height: 38px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    color: #fff; text-decoration: none; font-size: 14px;
    transition: transform .2s, opacity .2s;
}
.share-btn:hover { transform: translateY(-3px); opacity: 0.88; color: #fff; }
.share-btn.fb { background: #1877f2; }
.share-btn.tw { background: #1da1f2; }
.share-btn.zalo { background: #0068ff; }
.share-btn.copy { background: linear-gradient(135deg, var(--primary), var(--secondary)); }

/* ===== TAGS ===== */
.article-tags { padding: 0 36px 28px; }
@media (max-width: 768px) { .article-tags { padding: 0 18px 20px; } }
.tag-pill { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: #f0f4ff; color: var(--primary); text-decoration: none; border: 1px solid rgba(102,126,234,0.18); margin: 3px; transition: background .15s, color .15s; }
.tag-pill:hover { background: var(--primary); color: #fff; }

/* ===== RELATED POSTS ===== */
.related-section { max-width: 1180px; margin: 0 auto; padding: 0 16px 48px; }
.section-label { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; }
.section-label .bar { width: 4px; height: 22px; border-radius: 4px; background: linear-gradient(180deg, var(--primary), var(--secondary)); }
.section-label h2 { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0; }
.related-card {
    background: #fff; border-radius: 14px; overflow: hidden;
    box-shadow: var(--card-shadow); text-decoration: none; color: inherit;
    border: 1px solid rgba(102,126,234,0.07);
    transition: transform .2s, box-shadow .2s;
    height: 100%;
}
.related-card:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(102,126,234,0.16); color: inherit; }
.related-card img { width: 100%; height: 150px; object-fit: cover; }
.related-card .rc-body { padding: 12px 14px 16px; }
.related-card h4 { font-size: 0.88rem; font-weight: 700; color: var(--dark); line-height: 1.35; margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.related-card .rc-meta { font-size: 0.72rem; color: var(--muted); }
.related-card .rc-meta i { color: var(--primary); }

/* ===== SIDEBAR ===== */
.sidebar-sticky { position: sticky; top: 92px; }
.sidebar-widget {
    background: #fff; border-radius: var(--card-radius);
    padding: 18px; box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08); margin-bottom: 20px;
}
.sidebar-widget-title { font-size: 0.88rem; font-weight: 800; color: var(--dark); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f4ff; display: flex; align-items: center; gap: 7px; }
.sidebar-widget-title i { color: var(--primary); }
.sidebar-blog-item { display: flex; gap: 10px; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; }
.sidebar-blog-item:last-child { border-bottom: none; }
.sidebar-blog-item:hover { opacity: 0.8; }
.sidebar-blog-item img { width: 60px; height: 46px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-blog-item .sbi-title { font-size: 0.8rem; font-weight: 600; color: var(--dark); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.sidebar-blog-item .sbi-date { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
.sidebar-prod { display: flex; gap: 10px; align-items: center; padding: 9px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; }
.sidebar-prod:last-child { border-bottom: none; }
.sidebar-prod:hover { opacity: 0.85; }
.sidebar-prod img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-prod .sp-name { font-size: 0.8rem; font-weight: 700; color: var(--dark); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.sidebar-prod .sp-price { font-size: 0.8rem; font-weight: 800; color: var(--primary); margin-top: 2px; }
.ad-sidebar { background: #fff; border-radius: var(--card-radius); overflow: hidden; box-shadow: var(--card-shadow); border: 1px solid rgba(102,126,234,0.08); margin-bottom: 20px; }

@media (max-width: 576px) {
    .article-title { font-size: 1.2rem; }
    .article-body { font-size: 0.92rem; }
    .article-body h2 { font-size: 1.2rem; }
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
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}"><i class="fas fa-blog me-1"></i>Blog</a></li>
                <li class="breadcrumb-item active text-muted">{{ Str::limit($blog->title, 45) }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="article-layout">

    {{-- === BÀI VIẾT CHÍNH === --}}
    <div>
        <article class="article-card" data-aos="fade-up">

            {{-- Header --}}
            <div class="article-header">
                <span class="article-cat">{{ ucfirst($blog->category) }}</span>
                <h1 class="article-title">{{ $blog->title }}</h1>
                <div class="article-meta">
                    <span><i class="far fa-calendar-alt"></i> {{ $blog->formatted_date }}</span>
                    <span><i class="far fa-eye"></i> {{ number_format($blog->views) }} lượt xem</span>
                    <span><i class="far fa-clock"></i> 5 phút đọc</span>
                    <span><i class="fas fa-user"></i> DungThu.com</span>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($blog->image)
            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="article-feat-img">
            @endif

            {{-- Excerpt --}}
            <div class="article-excerpt">
                <i class="fas fa-quote-left me-2" style="color:var(--primary);"></i>
                {{ $blog->excerpt }}
                <i class="fas fa-quote-right ms-2" style="color:var(--primary);"></i>
            </div>

            {{-- AdSense In-Article TOP --}}
            <div class="article-body">
                <div class="ad-in-article">
                    <div class="ad-label">Quảng Cáo</div>
                    <ins class="adsbygoogle" style="display:block;text-align:center;min-height:90px;"
                        data-ad-layout="in-article" data-ad-format="fluid"
                        data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>

                {{-- Content --}}
                {!! $blog->content !!}

                {{-- AdSense In-Article BOTTOM --}}
                <div class="ad-in-article mt-4">
                    <div class="ad-label">Quảng Cáo</div>
                    <ins class="adsbygoogle" style="display:block;text-align:center;min-height:90px;"
                        data-ad-layout="in-article" data-ad-format="fluid"
                        data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>
            </div>

            {{-- Tags --}}
            <div class="article-tags">
                <span class="tag-pill">{{ ucfirst($blog->category) }}</span>
                <a href="{{ route('blog.category', 'cong-nghe') }}" class="tag-pill">💻 Công Nghệ</a>
                <a href="{{ route('blog.category', 'ai') }}" class="tag-pill">🤖 AI</a>
                <a href="{{ route('blog.index') }}" class="tag-pill">📝 Blog</a>
                <a href="{{ route('shop') }}" class="tag-pill">🛒 Mua Sắm</a>
            </div>

            {{-- Author Box --}}
            <div class="author-box">
                <div class="author-avatar"><i class="fas fa-user"></i></div>
                <div>
                    <div class="author-name">DungThu.com</div>
                    <div class="author-desc">Chia sẻ kiến thức công nghệ, AI, kiếm tiền online và công cụ hữu ích cho cộng đồng. Website đã được Google AdSense duyệt kiếm tiền.</div>
                </div>
            </div>

            {{-- Share --}}
            <div class="share-box">
                <span class="share-label"><i class="fas fa-share-alt me-2" style="color:var(--primary);"></i>Chia sẻ:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}"
                   target="_blank" class="share-btn fb" title="Chia sẻ Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}"
                   target="_blank" class="share-btn tw" title="Chia sẻ Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://zalo.me/share?url={{ urlencode(route('blog.show', $blog->slug)) }}"
                   target="_blank" class="share-btn zalo" title="Chia sẻ Zalo"><span style="font-weight:900;font-size:13px;">Z</span></a>
                <button class="share-btn copy" id="copyLinkBtn" title="Sao chép link" onclick="copyLink()"><i class="fas fa-link"></i></button>
                <span id="copiedMsg" style="display:none;font-size:0.78rem;color:#10b981;font-weight:700;">✓ Đã sao chép!</span>
            </div>

        </article>

        {{-- AdSense after article --}}
        <div style="background:#fff;border:1px dashed rgba(102,126,234,0.15);border-radius:12px;overflow:hidden;margin:20px 0;">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

    </div>{{-- end article main --}}

    {{-- === SIDEBAR === --}}
    <aside class="article-sidebar">
        <div class="sidebar-sticky">

            {{-- AdSense sidebar sticky --}}
            <div class="ad-sidebar">
                <div class="ad-label">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:280px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-slot="4989157975" data-ad-format="auto"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>

            {{-- Bài viết liên quan (sidebar) --}}
            @if($relatedBlogs->count() > 0)
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-newspaper"></i> Bài Viết Liên Quan</div>
                @foreach($relatedBlogs as $related)
                <a href="{{ route('blog.show', $related->slug) }}" class="sidebar-blog-item">
                    <img src="{{ $related->image ?? 'https://via.placeholder.com/60x46' }}" alt="{{ $related->title }}">
                    <div>
                        <div class="sbi-title">{{ $related->title }}</div>
                        <div class="sbi-date"><i class="far fa-calendar-alt"></i> {{ $related->formatted_date }}</div>
                    </div>
                </a>
                @endforeach
                <div class="mt-3 text-center">
                    <a href="{{ route('blog.index') }}" style="display:inline-flex;align-items:center;gap:5px;padding:7px 18px;border-radius:999px;border:2px solid var(--primary);color:var(--primary);font-weight:700;font-size:0.78rem;text-decoration:none;">Xem tất cả Blog →</a>
                </div>
            </div>
            @endif

            {{-- Sản phẩm gợi ý --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-fire"></i> Mua Sắm Ngay</div>
                <a href="{{ route('shop') }}" class="sidebar-prod">
                    <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-store" style="color:#fff;font-size:18px;"></i></div>
                    <div>
                        <div class="sp-name">Tài khoản & Phần Mềm Số</div>
                        <div class="sp-price">→ Vào Shop ngay</div>
                    </div>
                </a>
                <a href="{{ route('buff.index') }}" class="sidebar-prod">
                    <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#f093fb,#f5576c);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-bolt" style="color:#fff;font-size:18px;"></i></div>
                    <div>
                        <div class="sp-name">Dịch Vụ Buff Sub/Like</div>
                        <div class="sp-price" style="color:#f5576c;">→ Giá tốt nhất</div>
                    </div>
                </a>
                <a href="{{ route('chatbot.index') }}" class="sidebar-prod">
                    <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-robot" style="color:#fff;font-size:18px;"></i></div>
                    <div>
                        <div class="sp-name">Chatbot AI Thông Minh</div>
                        <div class="sp-price" style="color:#0ea5e9;">→ Dùng miễn phí</div>
                    </div>
                </a>
            </div>

            {{-- Chủ đề --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title"><i class="fas fa-tags"></i> Khám Phá Thêm</div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <a href="{{ route('blog.category', 'cong-nghe') }}" style="padding:4px 11px;border-radius:999px;font-size:0.73rem;font-weight:600;background:#f0f4ff;color:var(--primary);text-decoration:none;border:1px solid rgba(102,126,234,0.18);">💻 Công Nghệ</a>
                    <a href="{{ route('blog.category', 'ai') }}" style="padding:4px 11px;border-radius:999px;font-size:0.73rem;font-weight:600;background:#f0f4ff;color:var(--primary);text-decoration:none;border:1px solid rgba(102,126,234,0.18);">🤖 AI</a>
                    <a href="{{ route('blog.category', 'kiem-tien') }}" style="padding:4px 11px;border-radius:999px;font-size:0.73rem;font-weight:600;background:#f0f4ff;color:var(--primary);text-decoration:none;border:1px solid rgba(102,126,234,0.18);">💰 Kiếm Tiền</a>
                    <a href="{{ route('blog.category', 'thu-thuat') }}" style="padding:4px 11px;border-radius:999px;font-size:0.73rem;font-weight:600;background:#f0f4ff;color:var(--primary);text-decoration:none;border:1px solid rgba(102,126,234,0.18);">🔧 Thủ Thuật</a>
                    <a href="{{ route('blog.category', 'review') }}" style="padding:4px 11px;border-radius:999px;font-size:0.73rem;font-weight:600;background:#f0f4ff;color:var(--primary);text-decoration:none;border:1px solid rgba(102,126,234,0.18);">⭐ Review</a>
                </div>
            </div>

            {{-- AdSense sidebar 2 --}}
            <div class="ad-sidebar">
                <div class="ad-label">Quảng Cáo</div>
                <ins class="adsbygoogle" style="display:block;min-height:200px;"
                    data-ad-client="ca-pub-3065867660863139"
                    data-ad-slot="4989157975" data-ad-format="auto"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>

        </div>
    </aside>

</div>{{-- end article-layout --}}

{{-- RELATED POSTS (full width, below) --}}
@if($relatedBlogs->count() > 0)
<div class="related-section">
    <div class="section-label">
        <span class="bar"></span>
        <h2>📚 Bài Viết Cùng Chủ Đề</h2>
    </div>
    <div class="row row-cols-2 row-cols-md-3 g-3">
        @foreach($relatedBlogs as $related)
        <div class="col">
            <a href="{{ route('blog.show', $related->slug) }}" class="related-card d-block">
                <img src="{{ $related->image ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $related->title }}">
                <div class="rc-body">
                    <span style="display:inline-block;padding:2px 9px;border-radius:999px;font-size:0.65rem;font-weight:700;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;margin-bottom:7px;">{{ ucfirst($related->category) }}</span>
                    <h4>{{ $related->title }}</h4>
                    <div class="rc-meta">
                        <i class="far fa-calendar-alt"></i> {{ $related->formatted_date }}
                        &nbsp;·&nbsp;
                        <i class="far fa-eye"></i> {{ number_format($related->views) }}
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- AdSense cuối trang --}}
    <div style="background:#fff;border:1px dashed rgba(102,126,234,0.15);border-radius:12px;overflow:hidden;margin-top:24px;">
        <div class="ad-label" style="font-size:0.62rem;color:#bbb;padding:3px 0;background:#fafafa;border-bottom:1px solid #eee;text-align:center;letter-spacing:0.1em;text-transform:uppercase;">Quảng Cáo</div>
        <ins class="adsbygoogle" style="display:block;min-height:90px;"
            data-ad-client="ca-pub-3065867660863139"
            data-ad-format="auto" data-full-width-responsive="true"
            data-ad-slot="4989157975"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>

</div>
@endif

@endsection

@push('scripts')
<script>
AOS.init({ duration: 700, once: true });

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        const msg = document.getElementById('copiedMsg');
        msg.style.display = 'inline';
        setTimeout(() => { msg.style.display = 'none'; }, 2000);
    });
}

// Reading progress bar
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.createElement('div');
    progressBar.style.cssText = 'position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#667eea,#764ba2);z-index:9999;transition:width .1s;width:0%;';
    document.body.prepend(progressBar);
    window.addEventListener('scroll', function() {
        const scrolled = window.scrollY;
        const total = document.documentElement.scrollHeight - window.innerHeight;
        progressBar.style.width = (scrolled / total * 100) + '%';
    });
});
</script>
@endpush
