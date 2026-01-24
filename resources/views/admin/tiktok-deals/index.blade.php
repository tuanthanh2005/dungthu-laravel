@extends('layouts.app')

@section('title', 'Quản Lý Săn Sale Tiktok')

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
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Admin Navigation -->
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-wrap">
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
                    <a class="nav-link active" href="{{ route('admin.tiktok-deals.index') }}">
                        <i class="fab fa-tiktok me-2"></i>Săn Sale TikTok
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" title="Tính năng sắp có">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" title="Tính năng sắp có">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
            </ul>
        </nav>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fab fa-tiktok text-dark"></i> Quản Lý Săn Sale Tiktok Shop
        </h2>
        <a href="{{ route('admin.tiktok-deals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Deal Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($deals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">STT</th>
                                <th width="80">Hình</th>
                                <th>Tên Deal</th>
                                <th width="120">Giá Gốc</th>
                                <th width="120">Giá Sale</th>
                                <th width="80">Giảm</th>
                                <th width="100">Trạng Thái</th>
                                <th width="80">Thứ Tự</th>
                                <th width="180" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deals as $deal)
                            <tr>
                                <td>{{ $deal->order }}</td>
                                <td>
                                    @if($deal->image)
                                        <img src="{{ asset('images/products/' . $deal->image) }}" 
                                             alt="{{ $deal->name }}" 
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $deal->name }}</strong>
                                    @if($deal->description)
                                        <br><small class="text-muted">{{ Str::limit($deal->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($deal->original_price)
                                        <span class="text-muted text-decoration-line-through">
                                            {{ $deal->formatted_original_price }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($deal->sale_price)
                                        <span class="text-danger fw-bold">{{ $deal->formatted_sale_price }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($deal->discount_percent)
                                        <span class="badge bg-danger">-{{ $deal->discount_percent }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.tiktok-deals.toggle', $deal) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $deal->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fas fa-{{ $deal->is_active ? 'check' : 'times' }}"></i>
                                            {{ $deal->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $deal->order }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ $deal->tiktok_link }}" 
                                           target="_blank" 
                                           class="btn btn-info"
                                           title="Xem trên Tiktok">
                                            <i class="fab fa-tiktok"></i>
                                        </a>
                                        <a href="{{ route('admin.tiktok-deals.edit', $deal) }}" 
                                           class="btn btn-warning"
                                           title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete({{ $deal->id }})"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $deal->id }}" 
                                          action="{{ route('admin.tiktok-deals.destroy', $deal) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $deals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fab fa-tiktok fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Chưa có Deal Tiktok nào</h4>
                    <p class="text-muted">Hãy thêm deal đầu tiên của bạn!</p>
                    <a href="{{ route('admin.tiktok-deals.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Thêm Deal Mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa deal này?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
    </div>
</div>
@endsection
