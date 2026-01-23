@extends('layouts.app')

@section('title', 'Quản lý Sản phẩm - Admin')

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

    .product-image-small {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
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

    .custom-table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .custom-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
    }

    .custom-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .custom-table thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    .custom-table tbody tr {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .custom-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
    }

    .custom-table tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
    }

    .custom-table tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }

    .badge-category {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
    }

    .badge-tech {
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        color: white;
    }

    .badge-fashion {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
    }

    .badge-doc {
        background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Admin Navigation -->
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.features') }}">
                        <i class="fas fa-star me-2"></i>Tính năng
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Products Management -->
        <div class="admin-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-box text-primary me-3"></i>Quản lý Sản phẩm
                </h3>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </a>
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="btn-group" role="group" aria-label="Category Filter">
                    <a href="{{ route('admin.products') }}" 
                       class="btn {{ !request('category') || request('category') == 'all' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 me-2">
                        <i class="fas fa-list me-2"></i>Tất cả
                    </a>
                    <a href="{{ route('admin.products', ['category' => 'tech']) }}" 
                       class="btn {{ request('category') == 'tech' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 me-2">
                        <i class="fas fa-laptop-code me-2"></i>Công nghệ
                    </a>
                    <a href="{{ route('admin.products', ['category' => 'ebooks']) }}" 
                       class="btn {{ request('category') == 'ebooks' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 me-2">
                        <i class="fas fa-book me-2"></i>Ebooks
                    </a>
                    <a href="{{ route('admin.products', ['category' => 'doc']) }}" 
                       class="btn {{ request('category') == 'doc' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">
                        <i class="fas fa-file-alt me-2"></i>Tài liệu
                    </a>
                </div>
            </div>

            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Loại</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><strong>#{{ $product->id }}</strong></td>
                            <td>
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/60' }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image-small">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>
                                @if($product->category === 'tech')
                                    <span class="badge-category badge-tech">Công nghệ</span>
                                @elseif($product->category === 'fashion')
                                    <span class="badge-category badge-fashion">Thời trang</span>
                                @else
                                    <span class="badge-category badge-doc">Tài liệu</span>
                                @endif
                            </td>
                            <td>
                                @if($product->delivery_type === 'digital')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-qrcode me-1"></i>QR
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-shipping-fast me-1"></i>Ship
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                            </td>
                            <td>
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-start">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.delete', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-end">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có sản phẩm nào</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                </a>
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
