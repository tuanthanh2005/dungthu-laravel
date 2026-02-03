@extends('layouts.app')

@section('title', 'Quản lý Danh mục - Admin')

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
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover,
    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .category-image {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .type-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
    }

    .type-tech { background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%); color: #fff; }
    .type-ebooks { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; }
    .type-doc { background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%); color: #fff; }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.categories') }}">
                        <i class="fas fa-list me-2"></i>Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.features') }}">
                        <i class="fas fa-star me-2"></i>Tính năng
                    </a>
                </li>
            </ul>
        </nav>

        <div class="admin-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-list text-primary me-2"></i>Quản lý Danh mục
                </h3>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Thêm danh mục
                </a>
            </div>

            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session()->pull('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session()->pull('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($categories->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có danh mục nào</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên danh mục</th>
                            <th>Loại</th>
                            <th>Số sản phẩm</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <img src="{{ $category->image ?? 'https://via.placeholder.com/64' }}" class="category-image" alt="{{ $category->name }}">
                            </td>
                            <td>
                                <strong>{{ $category->name }}</strong><br>
                                <small class="text-muted">{{ $category->slug }}</small>
                            </td>
                            <td>
                                <span class="type-badge type-{{ $category->type }}">{{ $category->type }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Đang bật</span>
                                @else
                                    <span class="badge bg-secondary">Tắt</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.delete', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
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
