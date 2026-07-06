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
                    <a class="nav-link position-relative protected-link @if(request()->routeIs('admin.seo-keywords*')) active @endif" href="javascript:void(0)" data-url="{{ route('admin.seo-keywords') }}">
                        <i class="fas fa-search me-2"></i>Từ khóa SEO
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative protected-link @if(request()->routeIs('admin.blog-topics*')) active @endif" href="javascript:void(0)" data-url="{{ route('admin.blog-topics') }}">
                        <i class="fas fa-tags me-2"></i>Chủ đề Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link protected-link" href="javascript:void(0)" data-url="{{ route('admin.google-indexing.index') }}">
                        <i class="fab fa-google me-2"></i>Google Indexing
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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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

                        <!-- Matching Product Alert/Status -->
                        <div class="mb-4">
                            @if($matchedProduct)
                                <div class="alert alert-success border-0 shadow-sm d-flex flex-wrap align-items-center justify-content-between p-3 mb-0" style="border-radius: 15px;">
                                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                                        @if($matchedProduct->image)
                                            <img src="{{ Str::startsWith($matchedProduct->image, ['http://', 'https://']) ? $matchedProduct->image : url($matchedProduct->image) }}" alt="{{ $matchedProduct->name }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #d1e7dd;">
                                        @else
                                            <div class="rounded me-3 bg-success d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-1 text-success" style="font-size: 0.95rem;">Sản phẩm liên kết sẵn sàng</h6>
                                            <p class="mb-0 text-dark fw-semibold" style="font-size: 0.85rem;">
                                                {{ $matchedProduct->name }} <span class="text-danger">({{ $matchedProduct->formatted_price }})</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @php
                                        // Count pending preorders for this keyword
                                        $pendingCount = \App\Models\PreOrder::where('keyword', $filterKeyword)->where('status', 'pending')->count();
                                    @endphp
                                    
                                    @if($pendingCount > 0)
                                        <form action="{{ route('admin.preorders.notify-keyword') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gửi email thông báo hàng đã có cho TẤT CẢ {{ $pendingCount }} khách hàng đang chờ của từ khóa này?');">
                                            @csrf
                                            <input type="hidden" name="keyword" value="{{ $filterKeyword }}">
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold text-white border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);">
                                                <i class="fas fa-paper-plane me-1"></i> Gửi thông báo tất cả ({{ $pendingCount }})
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm rounded-pill px-3 py-2 fw-bold text-white" disabled style="cursor: not-allowed;">
                                            <i class="fas fa-check-double me-1"></i> Đã thông báo hết
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning border-0 shadow-sm p-3 mb-0" style="border-radius: 15px; background-color: #fffaf0; border-left: 4px solid #dd6b20 !important;">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning me-3 mt-1"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-warning" style="color: #dd6b20 !important;">Chưa có sản phẩm phù hợp</h6>
                                            <p class="mb-0 text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                                                Không tìm thấy sản phẩm có slug trùng khớp hoặc chứa từ khóa <strong>"{{ $filterKeyword }}"</strong>.<br>
                                                Vui lòng tạo sản phẩm có slug là <strong>"{{ $filterKeyword }}"</strong> để kích hoạt tính năng gửi thông báo.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                        <th class="text-end" style="min-width: 180px;">Hành động</th>
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
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                @if($matchedProduct)
                                                    @if($po->status === 'pending')
                                                        <form action="{{ route('admin.preorders.notify', $po->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1" style="font-size: 0.75rem; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                                                                <i class="fas fa-paper-plane me-1"></i> Gửi thông báo
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.preorders.notify', $po->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem;" title="Gửi lại thông báo">
                                                                <i class="fas fa-redo me-1"></i> Gửi lại
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem; cursor: not-allowed; opacity: 0.6;" disabled title="Vui lòng tạo sản phẩm trước">
                                                        <i class="fas fa-ban me-1"></i> Gửi thông báo
                                                    </button>
                                                @endif
                                                
                                                <form action="{{ route('admin.preorders.delete', $po->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lượt đăng ký này?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa" style="text-decoration: none;">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
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
