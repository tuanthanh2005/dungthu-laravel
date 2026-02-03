@extends('layouts.app')

@section('title', 'Cộng Đồng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
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
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                <div class="product-card h-100">
                    <div class="p-3">
                        <div class="small text-muted mb-2">
                            <i class="far fa-user"></i> {{ $post->user->name ?? 'Thành viên' }}
                            <span class="mx-2">•</span>
                            <i class="far fa-clock"></i> {{ $post->created_at->format('d/m/Y') }}
                        </div>
                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('community.show', $post) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-3">{{ \Str::limit(strip_tags(html_entity_decode($post->content, ENT_QUOTES, 'UTF-8')), 120) }}</p>
                        <a href="{{ route('community.show', $post) }}" class="btn btn-sm btn-outline-primary">Đọc thêm</a>
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
