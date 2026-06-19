@extends('layouts.app')

@section('title', $seoTitle ?? 'Blog - DungThu.com')
@section('meta_description', $seoDescription ?? __('Cập nhật xu hướng công nghệ, AI, tài khoản số, mẹo dùng tool và hướng dẫn sử dụng sản phẩm tại DungThu.com.'))
@section('canonical', $canonical ?? route('blog.index'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .blog-topic-section {
            background: #fff;
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 35px rgba(0,0,0,0.03);
            border: 1px solid rgba(108, 92, 231, 0.08);
        }
        .blog-topic-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 12px;
        }
        .blog-topic-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            max-height: 84px; /* Show about 2 rows */
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .blog-topic-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(108, 92, 231, 0.06);
            color: #5f27cd;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid rgba(108, 92, 231, 0.08);
            transition: all 0.2s ease;
        }
        .blog-topic-chip:hover,
        .blog-topic-chip.active {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(108, 92, 231, 0.18);
        }
        .blog-topic-toggle {
            display: flex;
            border: none;
            background: rgba(108, 92, 231, 0.08);
            color: #5f27cd;
            width: 38px;
            height: 38px;
            border-radius: 999px;
            align-items: center;
            justify-content: center;
            margin: 12px auto 0;
            transition: all 0.2s ease;
        }
        .blog-topic-toggle:hover {
            background: rgba(108, 92, 231, 0.15);
        }
        .blog-topic-toggle i {
            transition: transform 0.2s ease;
        }
        .blog-topic-section.expanded .blog-topic-links {
            max-height: 800px;
        }
        .blog-topic-section.expanded .blog-topic-toggle i {
            transform: rotate(180deg);
        }
        @media (max-width: 768px) {
            .blog-topic-section {
                padding: 14px 16px;
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="fw-bold">{{ $pageHeading ?? __('Blog Chia Sẻ') }}</h1>
            <p class="text-muted">{{ $seoDescription ?? __('Cập nhật xu hướng công nghệ, AI, mẹo dùng tool và hướng dẫn sử dụng sản phẩm.') }}</p>
        </div>
    </div>

    @if(!empty($topicLinks))
    <div class="blog-topic-section" data-aos="fade-up" data-aos-delay="100">
        <div class="blog-topic-title">
            <i class="fas fa-bolt text-warning me-2"></i>{{ __('Chủ đề nổi bật') }}
        </div>
        <div class="blog-topic-links">
            @foreach($topicLinks as $topicSlug => $topic)
                <a href="{{ route('blog.topic', $topicSlug) }}"
                   class="blog-topic-chip {{ request()->routeIs('blog.topic') && request()->route('topic') === $topicSlug ? 'active' : '' }}">
                    {{ $topic['label'] }}
                </a>
            @endforeach
        </div>
        <button type="button" class="blog-topic-toggle" aria-label="{{ __('Xem thêm chủ đề') }}" aria-expanded="false">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    @endif

    <div class="row">
        @forelse($blogs as $blog)
        <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
            <div class="product-card">
                <div class="card-img-wrap" style="height: 200px;">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="d-block">
                        <img src="{{ $blog->image ?? 'https://via.placeholder.com/400' }}" alt="{{ $blog->title }}">
                    </a>
                    <span class="badge-custom">{{ strtoupper($blog->category) }}</span>
                </div>
                <div class="p-3">
                    <div class="small text-muted mb-2">
                        <i class="far fa-clock"></i> {{ $blog->formatted_date }} &bull;
                        <i class="far fa-eye"></i> {{ $blog->views }} {{ __('lượt xem') }}
                    </div>
                    <h5 class="fw-bold mb-2">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none text-dark">
                            {{ $blog->title }}
                        </a>
                    </h5>
                    <p class="text-muted small mb-3">{{ Str::limit($blog->excerpt, 100) }}</p>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary">{{ __('Đọc thêm') }}</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="bg-white rounded-4 p-5 text-center shadow-sm">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="fw-bold">{{ __('Chưa có bài viết phù hợp') }}</h4>
                <p class="text-muted mb-0">{{ __('Chủ đề này sẽ được cập nhật thêm nội dung mới.') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });

        document.addEventListener('DOMContentLoaded', function() {
            const topicSection = document.querySelector('.blog-topic-section');
            const topicToggle = document.querySelector('.blog-topic-toggle');
            const topicLinks = document.querySelector('.blog-topic-links');

            if (topicSection && topicToggle && topicLinks) {
                // Hide toggle if content fits within 84px
                if (topicLinks.scrollHeight <= 90) {
                    topicToggle.style.display = 'none';
                }

                topicToggle.addEventListener('click', function() {
                    const expanded = topicSection.classList.toggle('expanded');
                    topicToggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    topicToggle.setAttribute('aria-label', expanded ? '{{ __('Thu gọn chủ đề') }}' : '{{ __('Xem thêm chủ đề') }}');
                });
            }
        });
    </script>
@endpush
