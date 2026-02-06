@extends('layouts.app')

@section('title', $post->title . ' - Cộng Đồng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .community-page {
            background: linear-gradient(180deg, #e7f2ef 0%, #f7fbfa 40%, #ffffff 100%);
            border-radius: 24px;
            padding: 10px 12px 30px;
            color: #1f2a2e;
        }
        .community-post-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbfa 100%);
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            padding: 32px;
            border: 1px solid rgba(0,0,0,0.04);
            color: #1f2a2e;
        }
        .community-post-card .text-muted,
        .comment-card .text-muted {
            color: #4f5f67 !important;
        }
        .community-post-card h2,
        .community-post-card .fw-bold {
            color: #192126;
        }
        .community-post-card h2 {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 0.2px;
        }
        .comment-card {
            background: #f2f8f7;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.06);
            padding: 16px;
            color: #1f2a2e;
        }
        .reply-list {
            margin-top: 10px;
            border-left: none;
            padding-left: 0;
        }
        .reply-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 12px 14px;
        }
        .reply-to {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e6f4f1;
            color: #1f2a2e;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .reply-btn {
            background: #1f6f64;
            color: #ffffff;
            border: none;
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 600;
            box-shadow: 0 6px 14px rgba(31, 111, 100, 0.22);
        }
        .reply-btn:hover {
            background: #18594f;
            color: #ffffff;
            transform: translateY(-1px);
        }
        @media (max-width: 768px) {
            .community-post-card { padding: 20px; }
        }
    </style>
@endpush

@section('content')
<div class="container py-5 community-page" style="margin-top: 80px; max-width: 980px;">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('community.index') }}" class="text-decoration-none">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="community-post-card mb-4">
        <div class="small text-muted mb-2">
            <i class="far fa-user"></i> {{ $post->user->name ?? 'Thành viên' }}
            <span class="mx-2">•</span>
            <i class="far fa-clock"></i> {{ $post->created_at->format('d/m/Y H:i') }}
        </div>
        <h2 class="fw-bold mb-3">{{ $post->title }}</h2>
        <div class="text-muted">{!! html_entity_decode($post->content, ENT_QUOTES, 'UTF-8') !!}</div>

        @auth
            @if(Gate::allows('community-post.update', $post) || Gate::allows('community-post.delete', $post))
                <div class="d-flex gap-2 mt-4">
                    @can('community-post.update', $post)
                        <a href="{{ route('community.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Sửa bài
                        </a>
                    @endcan
                    @can('community-post.delete', $post)
                        <form method="POST" action="{{ route('community.delete', $post) }}" onsubmit="return confirm('Xóa bài viết này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i>Xóa bài
                            </button>
                        </form>
                    @endcan
                </div>
            @endif
        @endauth
    </div>

    <div class="mb-4">
        <h5 class="fw-bold mb-3">Bình luận ({{ $post->comments->count() }})</h5>

        <form method="POST" action="{{ route('community.comments.store', $post) }}" class="mb-3">
            @csrf
            <textarea name="content" rows="3" class="form-control" placeholder="Viết bình luận..." required></textarea>
            @error('content')<small class="text-danger">{{ $message }}</small>@enderror
            <button type="submit" class="btn btn-primary mt-2">Gửi</button>
        </form>

        <div class="d-flex flex-column gap-3">
            @forelse($post->comments->whereNull('parent_id')->values() as $comment)
                @include('community.partials.comment', ['comment' => $comment, 'post' => $post, 'level' => 0])
            @empty
                <div class="alert alert-light border">Chưa có bình luận nào.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
