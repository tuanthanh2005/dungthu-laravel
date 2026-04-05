@extends('layouts.app')

@section('title', 'Blog - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="fw-bold">Blog Chia Sẻ</h1>
            <p class="text-muted">Cập nhật xu hướng công nghệ, mẹo phối đồ và hướng dẫn dùng tool.</p>
        </div>
    </div>

    <div class="row">
        @foreach($blogs as $blog)
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
                        <i class="far fa-clock"></i> {{ $blog->formatted_date }} • 
                        <i class="far fa-eye"></i> {{ $blog->views }} lượt xem
                    </div>
                    <h5 class="fw-bold mb-2">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none text-dark">
                            {{ $blog->title }}
                        </a>
                    </h5>
                    <p class="text-muted small mb-3">{{ Str::limit($blog->excerpt, 100) }}</p>
                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary">Đọc thêm</a>
                </div>
            </div>
        </div>
        @endforeach
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
    </script>
@endpush
