@extends('layouts.app')

@section('title', 'Quản lý Tính Năng Nổi Bật - Admin')

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

    .admin-nav .nav-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .feature-card {
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        background: white;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-right: 15px;
    }

    .category-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .category-tech {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .category-fashion {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .category-doc {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Admin Navigation -->
        <div class="admin-nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.features') }}">
                        <i class="fas fa-star me-2"></i>Tính năng
                    </a>
                </li>
            </ul>
        </div>

        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-star text-warning me-2"></i>
                    Quản lý Tính Năng Nổi Bật
                </h2>
                <a href="{{ route('admin.features.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-2"></i>Thêm tính năng mới
                </a>
            </div>

            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session()->pull('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($features->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Chưa có tính năng nào</h4>
                <p class="text-muted">Hãy thêm tính năng đầu tiên cho sản phẩm của bạn!</p>
            </div>
            @else
            <div class="row">
                @foreach($features as $feature)
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon" style="background: linear-gradient(135deg, {{ $feature->color }}, {{ $feature->color }}dd);">
                                <i class="{{ $feature->icon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $feature->name }}</h5>
                                <span class="category-badge category-{{ $feature->category }}">
                                    {{ $feature->category === 'tech' ? 'Công nghệ' : ($feature->category === 'fashion' ? 'Thời trang' : 'Văn phòng phẩm') }}
                                </span>
                            </div>
                        </div>

                        @if($feature->description)
                        <p class="text-muted mb-3">{{ $feature->description }}</p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                {{ $feature->created_at->diffForHumans() }}
                            </div>
                            <div>
                                <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-edit btn-sm">
                                    <i class="fas fa-edit me-1"></i>Sửa
                                </a>
                                <form action="{{ route('admin.features.delete', $feature) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tính năng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-sm">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $features->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
