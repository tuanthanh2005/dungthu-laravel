@extends('layouts.app')

@section('title', 'Quản Lý Đăng Ký Pre-order')

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
        margin-bottom: 30px;
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
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.categories') }}">
                        <i class="fas fa-list me-2"></i>Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.products', ['flash_sale' => 1]) }}">
                        <i class="fas fa-bolt me-2"></i>Flash Sale
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative protected-link" href="javascript:void(0)" data-url="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.users') }}">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.blogs') }}">
                        <i class="fas fa-blog me-2"></i>Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative protected-link" href="javascript:void(0)" data-url="{{ route('admin.card-exchanges') }}">
                        <i class="fas fa-credit-card me-2"></i>Đổi thẻ cào
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative protected-link" href="javascript:void(0)" data-url="{{ route('admin.chat.index') }}">
                        <i class="fas fa-comments me-2"></i>Chat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative @if(request()->routeIs('admin.affiliates.*')) active @endif" href="{{ route('admin.affiliates.index') }}">
                        <i class="fas fa-handshake me-2"></i>Cộng tác viên
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative protected-link" href="javascript:void(0)" data-url="{{ route('admin.abandoned-carts') }}">
                        <i class="fas fa-shopping-basket me-2"></i>Giỏ bỏ quên
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.preorders') }}">
                        <i class="fas fa-hourglass-half me-2"></i>Pre-orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.system-notifications') }}">
                        <i class="fas fa-bullhorn me-2"></i>Thông báo hệ thống
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="javascript:void(0)" onclick="adminLockManual()">
                        <i class="fas fa-lock me-2"></i>Khóa Admin
                    </a>
                </li>
            </ul>
        </nav>

        <div class="row">
            <!-- Left Column: Keywords and Stats -->
            <div class="col-lg-5 col-md-12">
                <div class="admin-card" data-aos="fade-right">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Thống kê theo từ khóa
                    </h4>
                    
                    @if($keywords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Từ khóa (Slug)</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($keywords as $k)
                                <tr class="{{ $filterKeyword === $k->keyword ? 'table-primary' : '' }}">
                                    <td>
                                        <strong class="text-dark">{{ Str::headline(str_replace('-', ' ', $k->keyword)) }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">/go/{{ $k->keyword }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill" style="font-size: 0.85rem; padding: 6px 12px;">{{ $k->count }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.preorders', ['keyword' => $k->keyword]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $keywords->links() }}
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p class="mb-0">Chưa có lượt đăng ký pre-order nào.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Detail List of Selected Keyword -->
            <div class="col-lg-7 col-md-12">
                <div class="admin-card" data-aos="fade-left">
                    @if($filterKeyword)
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="fw-bold mb-0">
                                <i class="fas fa-list text-success me-2"></i>Danh sách: {{ Str::headline(str_replace('-', ' ', $filterKeyword)) }}
                            </h4>
                            <span class="badge bg-success">{{ $preorders->total() }} email</span>
                        </div>

                        @if($preorders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đăng ký</th>
                                        <th class="text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preorders as $po)
                                    <tr>
                                        <td>#{{ $po->id }}</td>
                                        <td><strong>{{ $po->email }}</strong></td>
                                        <td>
                                            @if($po->status === 'pending')
                                                <span class="badge bg-warning text-dark">Đang chờ</span>
                                            @else
                                                <span class="badge bg-success">Đã thông báo</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.8rem; color: #718096;">
                                            {{ $po->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.preorders.delete', $po->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lượt đăng ký này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $preorders->appends(['keyword' => $filterKeyword])->links() }}
                        </div>
                        @else
                        <p class="text-muted text-center py-4">Không có email nào trong danh sách này.</p>
                        @endif
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-hand-pointer fa-4x mb-3 text-slate-300"></i>
                            <h5>Xem chi tiết danh sách đăng ký</h5>
                            <p class="mb-0">Chọn một từ khóa ở cột bên trái để hiển thị danh sách email khách hàng đã đăng ký chờ.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    function unlockEverything() {
        sessionStorage.setItem('admin_unlocked', 'true');
    }

    // Protection for other links
    document.querySelectorAll('.protected-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            
            if (sessionStorage.getItem('admin_unlocked') === 'true') {
                window.location.href = url;
            } else {
                const pass = prompt('Đây là khu vực bảo mật. Vui lòng nhập mật khẩu để tiếp tục:');
                if (pass === '113') {
                    unlockEverything();
                    window.location.href = url;
                } else if (pass !== null) {
                    alert('Mật khẩu không chính xác!');
                }
            }
        });
    });

    function adminLockManual() {
        if (confirm('Bạn có muốn khóa lại các khu vực bảo mật không?')) {
            sessionStorage.removeItem('admin_unlocked');
            sessionStorage.removeItem('revenue_unlocked');
            window.location.href = "{{ route('admin.lock') }}";
        }
    }
</script>
@endpush
