@extends('layouts.app')

@section('title', $post->title . ' - Cộng Đồng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .community-post-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            padding: 32px;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .comment-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.06);
            padding: 16px;
        }
        @media (max-width: 768px) {
            .community-post-card { padding: 20px; }
        }
    </style>
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px; max-width: 980px;">
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
        <div class="text-muted" style="white-space: pre-line;">{!! nl2br(e($post->content)) !!}</div>

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

        @auth
            <form method="POST" action="{{ route('community.comments.store', $post) }}" class="mb-3">
                @csrf
                <textarea name="content" rows="3" class="form-control" placeholder="Viết bình luận..." required></textarea>
                @error('content')<small class="text-danger">{{ $message }}</small>@enderror
                <button type="submit" class="btn btn-primary mt-2">Gửi</button>
            </form>
        @else
            <div class="alert alert-light border">
                Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
            </div>
        @endauth

        <div class="d-flex flex-column gap-3">
            @forelse($post->comments as $comment)
                <div class="comment-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">{{ $comment->user->name ?? 'Thành viên' }}</div>
                            <div class="small text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @auth
                            <div class="d-flex gap-2">
                                @can('community-comment.update', $comment)
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->id }}">
                                        Sửa
                                    </button>
                                @endcan
                                @can('community-comment.delete', $comment)
                                    <form method="POST" action="{{ route('community.comments.delete', $comment) }}" onsubmit="return confirm('Xóa bình luận này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                    </form>
                                @endcan
                            </div>
                        @endauth
                    </div>

                    <div class="mt-2" style="white-space: pre-line;">{{ $comment->content }}</div>

                    @can('community-comment.update', $comment)
                        <div class="collapse mt-3" id="edit-comment-{{ $comment->id }}">
                            <form method="POST" action="{{ route('community.comments.update', $comment) }}">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="3" class="form-control" required>{{ $comment->content }}</textarea>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Lưu</button>
                            </form>
                        </div>
                    @endcan
                </div>
            @empty
                <div class="alert alert-light border">Chưa có bình luận nào.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
