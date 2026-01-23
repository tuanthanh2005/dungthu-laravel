@extends('layouts.app')

@section('title', 'Quản Lý Bài Viết')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .admin-nav {
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .admin-nav a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.3s;
        margin-right: 10px;
        display: inline-block;
    }

    .admin-nav a:hover {
        background: rgba(255,255,255,0.2);
    }

    .admin-nav a.active {
        background: white;
        color: #667eea;
    }

    .blog-table {
        width: 100%;
    }

    .blog-table th {
        background: #f7fafc;
        padding: 15px;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }

    .blog-table td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .blog-table tr:hover {
        background: #f7fafc;
    }

    .blog-img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
    }

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        border-radius: 20px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #4a5568;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 500;
    }

    .filter-tab:hover {
        background: #f7fafc;
        border-color: #667eea;
        color: #667eea;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Navigation -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.products') }}">
                <i class="fas fa-box me-2"></i>Sản phẩm
            </a>
            <a href="{{ route('admin.orders') }}">
                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
            </a>
            <a href="{{ route('admin.users') }}">
                <i class="fas fa-users me-2"></i>Người dùng
            </a>
            <a href="{{ route('admin.blogs') }}" class="active">
                <i class="fas fa-blog me-2"></i>Bài viết
            </a>
        </div>

        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-blog text-primary me-2"></i>Quản Lý Bài Viết
                </h3>
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Thêm bài viết
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="{{ route('admin.blogs') }}" class="filter-tab {{ !request('category') || request('category') == 'all' ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i>Tất cả
                </a>
                <a href="{{ route('admin.blogs', ['category' => 'tech']) }}" class="filter-tab {{ request('category') == 'tech' ? 'active' : '' }}">
                    <i class="fas fa-laptop-code me-2"></i>Công nghệ
                </a>
                <a href="{{ route('admin.blogs', ['category' => 'lifestyle']) }}" class="filter-tab {{ request('category') == 'lifestyle' ? 'active' : '' }}">
                    <i class="fas fa-heart me-2"></i>Lifestyle
                </a>
                <a href="{{ route('admin.blogs', ['category' => 'business']) }}" class="filter-tab {{ request('category') == 'business' ? 'active' : '' }}">
                    <i class="fas fa-briefcase me-2"></i>Kinh doanh
                </a>
                <a href="{{ route('admin.blogs', ['category' => 'other']) }}" class="filter-tab {{ request('category') == 'other' ? 'active' : '' }}">
                    <i class="fas fa-ellipsis-h me-2"></i>Khác
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($blogs->count() > 0)
                <div class="table-responsive">
                    <table class="blog-table">
                        <thead>
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 10%">Hình ảnh</th>
                                <th style="width: 30%">Tiêu đề</th>
                                <th style="width: 12%">Danh mục</th>
                                <th style="width: 8%">Lượt xem</th>
                                <th style="width: 15%">Ngày tạo</th>
                                <th style="width: 10%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($blogs as $blog)
                                <tr>
                                    <td class="fw-bold">#{{ $blog->id }}</td>
                                    <td>
                                        @if($blog->image)
                                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="blog-img">
                                        @else
                                            <div class="blog-img" style="background: #e2e8f0;"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($blog->title, 50) }}</div>
                                        <small class="text-muted">{{ Str::limit($blog->excerpt, 70) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($blog->category) }}</span>
                                    </td>
                                    <td>
                                        <i class="fas fa-eye text-muted me-1"></i>{{ number_format($blog->views) }}
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $blog->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.delete', $blog) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-blog fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có bài viết nào</h5>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                        <i class="fas fa-plus me-2"></i>Thêm bài viết đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
