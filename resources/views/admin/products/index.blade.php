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

    .badge-ebooks {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

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
        <!-- Admin Navigation -->
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-nowrap overflow-auto pb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>ul::-webkit-scrollbar { display: none; }</style>
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
                    <a class="nav-link" href="{{ route('admin.categories') }}">
                        <i class="fas fa-list me-2"></i>Danh mục
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.features') }}">
                        <i class="fas fa-star me-2"></i>Tính năng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.abandoned-carts') }}">
                        <i class="fas fa-shopping-basket me-2"></i>Gio bo quen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.google-indexing.index') }}">
                        <i class="fab fa-google me-2"></i>Google Indexing
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Products Management -->
        <div class="admin-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-box text-primary me-3"></i>Quản lý Sản phẩm
                    </h3>
                    
                    <form action="{{ route('admin.products') }}" method="GET" class="compact-search-form">
                        <i class="fas fa-search compact-search-icon"></i>
                        <input type="text" name="search" class="compact-search-input" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                        <button type="submit" class="d-none"></button>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <form action="{{ route('admin.flash-sale.toggle') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn {{ $flashSaleEnabled ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill px-4">
                            <i class="fas fa-bolt me-2"></i>{{ $flashSaleEnabled ? 'Tắt' : 'Bật' }} Flash Sale
                        </button>
                    </form>
                    <button type="button" class="btn btn-success rounded-pill px-4 btn-submit-all-index" data-url="{{ route('admin.google-indexing.submit-all-products') }}">
                        <i class="fab fa-google me-2"></i>Gửi Index Hàng Loạt
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i>Thêm
                    </a>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <div class="btn-group" role="group" aria-label="Category Filter">
                    <a href="{{ route('admin.products') }}" 
                       class="btn {{ !request('category') || request('category') == 'all' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 me-2">
                        <i class="fas fa-list me-2"></i>Tất cả
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('page'), ['flash_sale' => 1])) }}" 
                       class="btn {{ request('flash_sale') ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-4 me-2">
                        <i class="fas fa-bolt me-2"></i>Flash Sale
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
                            <th>Flash Sale</th>
                            <th>Tồn kho</th>
                            <th>Gán Home</th>
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
                                @if($product->categoryRelation)
                                    <span class="badge-category badge-{{ $product->categoryRelation->type }}">
                                        {{ $product->categoryRelation->name }}
                                    </span>
                                @else
                                    @if($product->category === 'tech')
                                        <span class="badge-category badge-tech">Công nghệ</span>
                                    @elseif($product->category === 'ebooks')
                                        <span class="badge-category badge-ebooks">Ebooks</span>
                                    @else
                                        <span class="badge-category badge-doc">Tài liệu</span>
                                    @endif
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
                                <form action="{{ route('admin.products.toggle-flash-sale', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $product->is_flash_sale ? 'btn-danger' : 'btn-outline-danger' }}">
                                        <i class="fas fa-bolt me-1"></i>{{ $product->is_flash_sale ? 'Đang bật' : 'Bật' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 {{ $product->is_featured ? 'btn-warning' : 'btn-outline-warning' }}" style="font-size: 10px; padding: 2px 5px;">
                                            <i class="fas fa-star me-1"></i>Nổi bật
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.toggle-exclusive', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 {{ $product->is_exclusive ? 'btn-info' : 'btn-outline-info' }}" style="font-size: 10px; padding: 2px 5px;">
                                            <i class="fas fa-gem me-1"></i>Độc quyền
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.toggle-combo-ai', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 {{ $product->is_combo_ai ? 'btn-success' : 'btn-outline-success' }}" style="font-size: 10px; padding: 2px 5px;">
                                            <i class="fas fa-robot me-1"></i>Combo AI
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-start"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-0 btn-submit-index" data-url="{{ route('admin.google-indexing.submit-url') }}" data-target-url="{{ url('/product/' . $product->slug) }}" title="Gửi Index Google">
                                        <i class="fab fa-google"></i>
                                    </button>
                                    <form action="{{ route('admin.products.clone', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn nhân bản sản phẩm này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-0" title="Nhân bản (Clone)">
                                            <i class="fas fa-clone"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.delete', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-end" title="Xóa">
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

    // Gửi index sản phẩm thủ công qua AJAX
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
                        text: 'Gửi Index sản phẩm thành công!',
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

    // Gửi index hàng loạt sản phẩm
    const btnSubmitAll = document.querySelector('.btn-submit-all-index');
    if (btnSubmitAll) {
        btnSubmitAll.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const button = this;
            const originalHtml = button.innerHTML;
            
            const pin = window.prompt('Nhập mã xác nhận (PIN admin) để gửi INDEX HÀNG LOẠT tất cả sản phẩm lên Google:');
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
                        text: body.message || 'Gửi Index hàng loạt sản phẩm thành công!',
                        confirmButtonText: 'Đồng ý'
                    });
                } else {
                    let failedDetails = '';
                    if (body.failed && body.failed.length > 0) {
                        failedDetails = '\n\nChi tiết lỗi:\n' + body.failed.map(f => `- ${f.slug || f.product_id}: ${f.message}`).join('\n');
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
