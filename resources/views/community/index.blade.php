@extends('layouts.app')

@section('title', 'Cộng Đồng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .community-page {
            text-align: center;
            background: linear-gradient(180deg, #e7f2ef 0%, #f7fbfa 40%, #ffffff 100%);
            border-radius: 24px;
            padding: 10px 12px 30px;
            color: #1f2a2e;
        }
        .community-post-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbfa 100%);
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            padding: 28px;
            border: 1px solid rgba(0,0,0,0.04);
            text-align: left;
            color: #1f2a2e;
            position: relative;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
            cursor: pointer;
        }
        .community-post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0,0,0,0.12);
            border-color: rgba(31, 42, 46, 0.12);
        }
        .post-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .post-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #d9ece7;
            color: #1f2a2e;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }
        .post-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .post-title {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }
        .post-meta {
            font-size: 13px;
            color: #4f5f67;
        }
        .post-badge {
            background: #e6f4f1;
            color: #1f2a2e;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .comment-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(0,0,0,0.08);
            background: #f4fbf9;
            color: #1f2a2e;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            white-space: nowrap;
        }
        .post-excerpt {
            color: #43545d;
            margin-top: 6px;
        }
        .community-post-card .text-muted,
        .comment-card .text-muted {
            color: #4f5f67 !important;
        }
        .community-post-card h4,
        .community-post-card .fw-bold {
            color: #192126;
        }
        .comment-card {
            background: #f2f8f7;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.04);
            padding: 14px 16px;
            text-align: left;
            color: #1f2a2e;
        }
        .comment-form textarea {
            resize: vertical;
            min-height: 90px;
        }
        .comment-form textarea,
        .comment-form button {
            margin-left: auto;
            margin-right: auto;
        }
        @media (max-width: 768px) {
            .community-post-card { padding: 20px; }
            .post-title-row { flex-direction: column; align-items: flex-start; }
        }
    </style>
@endpush

@section('content')
<div class="container py-5 community-page" style="margin-top: 80px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Cộng Đồng Chia Sẻ Miễn Phí</h1>
            <p class="text-muted mb-0">Cộng Đồng Free Các Bạn Cứ Đăng Chia Sẻ Ở Đây Nhé !!!!</p>
        </div>
        @auth
            <a href="{{ route('community.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm mt-3 mt-md-0">
                <i class="fas fa-pen me-2"></i>Đăng bài
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 mt-3 mt-md-0">
                Đăng nhập để đăng bài
            </a>
        @endauth
    </div>

    <div class="row">
        @forelse($posts as $post)
            <div class="col-12 mb-4" data-aos="fade-up">
                <div class="community-post-card">
                    <a href="{{ route('community.show', $post) }}" class="stretched-link" aria-label="Xem chi tiết: {{ $post->title }}"></a>
                    @php
                        $firstImage = null;
                        if (!empty($post->content)) {
                            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $post->content, $matches)) {
                                $firstImage = $matches[1];
                            }
                        }
                    @endphp
                    <div class="post-header">
                        <div class="post-avatar">
                            {{ mb_substr($post->user->name ?? 'K', 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="post-title-row">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h4 class="post-title">{{ $post->title }}</h4>
                                    <span class="post-badge">Góc chia sẻ</span>
                                </div>
                                <div class="comment-pill">
                                    <i class="far fa-comment"></i> {{ $post->comments_count }}
                                </div>
                            </div>
                            <div class="post-meta mt-1">
                                <i class="far fa-user"></i> {{ $post->user->name ?? 'Thành viên' }}
                                <span class="mx-2">•</span>
                                <i class="far fa-clock"></i> {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($firstImage)
                                <div class="mt-2">
                                    <img src="{{ $firstImage }}" alt="{{ $post->title }}" style="max-width: 180px; height: auto; border-radius: 10px; border: 1px solid rgba(0,0,0,0.06);">
                                </div>
                            @endif
                            <div class="post-excerpt">
                                {{ \Str::limit(strip_tags(html_entity_decode($post->content, ENT_QUOTES, 'UTF-8')), 200) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center">Chưa có bài viết nào.</div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
@endpush
