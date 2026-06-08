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

    /* Compact Search Form */
    .compact-search-form {
        display: flex;
        align-items: center;
        background: #f1f2f6;
        border-radius: 30px;
        padding: 5px 15px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        width: 250px;
    }
    .compact-search-form:focus-within {
        background: #fff;
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        width: 320px;
    }
    .compact-search-input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.9rem;
        color: #2d3436;
        width: 100%;
        font-weight: 500;
    }
    .compact-search-icon {
        color: #667eea;
        font-size: 0.85rem;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-nowrap overflow-auto pb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>ul::-webkit-scrollbar { display: none; }</style>
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.google-indexing.index') }}">
                        <i class="fab fa-google me-2"></i>Google Indexing
                    </a>
                </li>
            </ul>
        </nav>

        <div class="admin-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-list text-primary me-2"></i>Quản lý Danh mục
                    </h3>

                    <form action="{{ route('admin.categories') }}" method="GET" class="compact-search-form">
                        <i class="fas fa-search compact-search-icon"></i>
                        <input type="text" name="search" class="compact-search-input" placeholder="Tìm danh mục..." value="{{ request('search') }}">
                        <button type="submit" class="d-none"></button>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-success rounded-pill px-4 btn-submit-all-index" data-url="{{ route('admin.google-indexing.submit-all-categories') }}">
                        <i class="fab fa-google me-2"></i>Gửi Index Hàng Loạt
                    </button>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i>Thêm danh mục
                    </a>
                </div>
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
                                <button type="button" class="btn btn-sm btn-outline-success btn-submit-index me-1" data-url="{{ route('admin.google-indexing.submit-url') }}" data-target-url="{{ url('/shop?category_id=' . $category->id) }}" title="Gửi Index Google">
                                    <i class="fab fa-google"></i>
                                </button>
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

    // Gửi index danh mục thủ công qua AJAX
    document.querySelectorAll('.btn-submit-index').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const targetUrl = this.getAttribute('data-target-url');
            const button = this;
            const originalHtml = button.innerHTML;
            
            const pin = window.prompt('Nhập mã xác nhận (PIN admin) để gửi Index lên Google:');
            if (pin === null) return;
            if (!/^\d{3}$/.test(pin)) {
                alert('Mã xác nhận phải đúng 3 số.');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    url: targetUrl,
                    admin_pin: pin
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Gửi Index danh mục thành công!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại!',
                        text: body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối!',
                    text: 'Không thể kết nối đến server.'
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    });

    // Gửi index hàng loạt danh mục
    const btnSubmitAll = document.querySelector('.btn-submit-all-index');
    if (btnSubmitAll) {
        btnSubmitAll.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const button = this;
            const originalHtml = button.innerHTML;
            
            const pin = window.prompt('Nhập mã xác nhận (PIN admin) để gửi INDEX HÀNG LOẠT tất cả danh mục lên Google:');
            if (pin === null) return;
            if (!/^\d{3}$/.test(pin)) {
                alert('Mã xác nhận phải đúng 3 số.');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi hàng loạt...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    admin_pin: pin
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: body.message || 'Gửi Index hàng loạt danh mục thành công!',
                        confirmButtonText: 'Đồng ý'
                    });
                } else {
                    let failedDetails = '';
                    if (body.failed && body.failed.length > 0) {
                        failedDetails = '\n\nChi tiết lỗi:\n' + body.failed.map(f => `- ${f.name || f.category_id}: ${f.message}`).join('\n');
                    }
                    Swal.fire({
                        icon: body.submitted > 0 ? 'warning' : 'error',
                        title: body.submitted > 0 ? 'Hoàn thành một phần!' : 'Thất bại!',
                        text: (body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.') + failedDetails
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối!',
                    text: 'Không thể kết nối đến server.'
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    }
</script>
@endpush
