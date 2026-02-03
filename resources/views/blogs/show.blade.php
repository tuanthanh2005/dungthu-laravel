@extends('layouts.app')

@section('title', $blog->title . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .blog-detail-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 48px 0 64px;
            min-height: 100vh;
        }
        
        .blog-detail-card {
            background: white;
            border-radius: 26px;
            padding: 60px 84px;
            box-shadow: 0 18px 60px rgba(0,0,0,0.12);
            margin-top: 64px;
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid rgba(102, 126, 234, 0.08);
        }

        @media (max-width: 992px) {
            .blog-detail-card { padding: 44px 40px; }
        }
        @media (max-width: 768px) {
            .blog-detail-card { padding: 28px 18px; border-radius: 18px; margin-top: 40px; }
            .blog-detail-wrapper { padding: 28px 0 48px; }
        }
        
        .blog-header-image {
            width: 100%;
            max-height: 560px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.14);
            margin: 8px 0 36px;
        }
        @media (max-width: 768px) {
            .blog-header-image { max-height: 280px; border-radius: 14px; margin-bottom: 26px; }
        }
        
        .blog-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            padding: 18px 0 22px;
            border-bottom: 1px solid #eef1f6;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        @media (max-width: 768px) {
            .blog-meta { gap: 14px; padding: 12px 0 16px; margin-bottom: 22px; }
        }
        
        .blog-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 15px;
        }
        
        .blog-meta-item i {
            color: #667eea;
            font-size: 18px;
        }
        
        .blog-title {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.25;
            color: #2d3748;
            margin-bottom: 26px;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 768px) {
            .blog-title {
                font-size: 28px;
                line-height: 1.25;
                margin-bottom: 18px;
            }
        }
        
        .blog-excerpt {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-left: 4px solid #667eea;
            padding: 26px 30px;
            border-radius: 14px;
            margin-bottom: 36px;
            font-size: 18px;
            line-height: 1.75;
            color: #4a5568;
            font-style: italic;
            text-align: center;
        }
        @media (max-width: 768px) {
            .blog-excerpt { padding: 18px 16px; font-size: 16px; margin-bottom: 24px; }
        }
        
        .blog-content-area {
            font-size: 18px;
            line-height: 1.95;
            color: #2d3748;
            max-width: 920px;
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .blog-content-area { font-size: 16.5px; line-height: 1.8; }
        }
        
        .blog-content-area h2 {
            margin-top: 3rem;
            margin-bottom: 1.2rem;
            font-weight: 700;
            font-size: 32px;
            color: #667eea;
            text-align: center;
        }
        @media (max-width: 768px) {
            .blog-content-area h2 { font-size: 24px; margin-top: 2.2rem; }
        }
        
        .blog-content-area h3 {
            margin-top: 2.2rem;
            margin-bottom: 1rem;
            font-weight: 600;
            font-size: 24px;
            color: #764ba2;
        }
        @media (max-width: 768px) {
            .blog-content-area h3 { font-size: 20px; margin-top: 1.8rem; }
        }
        
        .blog-content-area p {
            margin-bottom: 1.5rem;
            text-align: justify;
        }
        
        .blog-content-area ul, .blog-content-area ol {
            margin-bottom: 1.6rem;
            padding-left: 2rem;
        }
        
        .blog-content-area li {
            margin-bottom: 0.8rem;
            line-height: 1.8;
        }
        
        .blog-content-area img {
            max-width: 100%;
            height: auto;
            border-radius: 14px;
            margin: 2.2rem auto;
            display: block;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        @media (max-width: 768px) {
            .blog-content-area img { margin: 1.6rem auto; border-radius: 12px; }
        }
        
        .blog-content-area blockquote {
            border-left: 4px solid #667eea;
            padding: 20px 30px;
            margin: 2.5rem 0;
            font-style: italic;
            color: #666;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .blog-content-area code {
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 5px;
            color: #e11d48;
            font-family: 'Courier New', monospace;
        }

        .blog-content-area pre {
            background: #1e293b;
            color: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            margin: 2rem 0;
        }
        
        .share-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 20px;
            padding: 30px 28px;
            margin: 36px 0 44px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .share-section { padding: 22px 16px; border-radius: 16px; }
        }
        
        .share-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        @media (max-width: 768px) {
            .share-btn { width: 44px; height: 44px; margin: 0 6px; }
        }
        
        .share-btn:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
        }
        
        .share-btn.facebook {
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        }
        
        .share-btn.twitter {
            background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%);
        }
        
        .share-btn.pinterest {
            background: linear-gradient(135deg, #e60023 0%, #c9001f 100%);
        }
        
        .share-btn.linkedin {
            background: linear-gradient(135deg, #0077b5 0%, #00669c 100%);
        }
        
        .related-posts-section {
            margin-top: 70px;
            padding-top: 50px;
            border-top: 2px solid #f0f0f0;
        }
        @media (max-width: 768px) {
            .related-posts-section { margin-top: 48px; padding-top: 36px; }
        }

        .related-posts-section h3 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
        }
        
        .related-post-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
        }
        
        .related-post-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }
        
        .related-post-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .related-post-card:hover .related-post-img {
            transform: scale(1.1);
        }
        
        .related-post-content {
            padding: 25px;
        }
        
        .category-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            margin-top: 50px;
        }

        .author-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            font-weight: bold;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #667eea;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
        }

        .breadcrumb-item a:hover {
            color: #764ba2;
        }
    </style>
@endpush

@section('content')
<div class="blog-detail-wrapper">
    <div class="container">
        <div class="blog-detail-card" data-aos="fade-up">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-5">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}"><i class="fas fa-blog me-1"></i>Blog</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($blog->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Blog Header -->
            <article>
                <div class="text-center mb-4">
                    <span class="category-badge">{{ ucfirst($blog->category) }}</span>
                </div>
                
                <h1 class="blog-title">{{ $blog->title }}</h1>
                
                <div class="blog-meta">
                    <div class="blog-meta-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $blog->formatted_date }}</span>
                    </div>
                    <div class="blog-meta-item">
                        <i class="far fa-eye"></i>
                        <span>{{ number_format($blog->views) }} lượt xem</span>
                    </div>
                    <div class="blog-meta-item">
                        <i class="far fa-clock"></i>
                        <span>5 phút đọc</span>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($blog->image)
                <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="blog-header-image" data-aos="zoom-in">
                @endif

                <!-- Excerpt -->
                <div class="blog-excerpt" data-aos="fade-up">
                    <i class="fas fa-quote-left text-primary me-2"></i>
                    {{ $blog->excerpt }}
                    <i class="fas fa-quote-right text-primary ms-2"></i>
                </div>

                <!-- AdSense: In-article -->
                <div class="my-4 d-flex justify-content-center" data-aos="fade-up">
                    <ins class="adsbygoogle"
                         style="display:block; text-align:center;"
                         data-ad-layout="in-article"
                         data-ad-format="fluid"
                         data-ad-client="ca-pub-3065867660863139"
                         data-ad-slot="4989157975"></ins>
                </div>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>

                <!-- Blog Content -->
                <div class="blog-content-area" data-aos="fade-up">
                    {!! $blog->content !!}
                </div>

                <!-- Author Info -->
                <div class="author-info" data-aos="fade-up">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="fw-bold mb-1" style="font-size: 18px;">DungThu.com</div>
                        <p class="text-muted mb-0">Chia sẻ kiến thức công nghệ và các công cụ hữu ích cho cộng đồng</p>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="share-section" data-aos="fade-up">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-share-alt text-primary me-2"></i>Chia sẻ bài viết này
                    </h5>
                    <div>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}" 
                           target="_blank" 
                           class="share-btn facebook"
                           title="Chia sẻ trên Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" 
                           target="_blank" 
                           class="share-btn twitter"
                           title="Chia sẻ trên Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(route('blog.show', $blog->slug)) }}&description={{ urlencode($blog->title) }}" 
                           target="_blank" 
                           class="share-btn pinterest"
                           title="Chia sẻ trên Pinterest">
                            <i class="fab fa-pinterest"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $blog->slug)) }}&title={{ urlencode($blog->title) }}" 
                           target="_blank" 
                           class="share-btn linkedin"
                           title="Chia sẻ trên LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedBlogs->count() > 0)
            <div class="related-posts-section" data-aos="fade-up">
                <h3 class="fw-bold">
                    <i class="fas fa-newspaper text-primary me-2"></i>Bài viết liên quan
                </h3>
                <div class="row g-4">
                    @foreach($relatedBlogs as $related)
                    <div class="col-md-4">
                        <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none">
                            <div class="related-post-card">
                                <div style="overflow: hidden;">
                                    <img src="{{ $related->image ?? 'https://via.placeholder.com/400x250' }}" 
                                         alt="{{ $related->title }}" 
                                         class="related-post-img">
                                </div>
                                <div class="related-post-content">
                                    <span class="badge bg-primary mb-2">{{ ucfirst($related->category) }}</span>
                                    <h6 class="fw-bold mb-2" style="color: #2d3748;">{{ Str::limit($related->title, 60) }}</h6>
                                    <p class="text-muted small mb-3">{{ Str::limit($related->excerpt, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-calendar me-1"></i>{{ $related->formatted_date }}
                                        </small>
                                        <small class="text-primary fw-bold">
                                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
@endpush
