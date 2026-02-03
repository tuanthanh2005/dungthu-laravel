@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 15px;
    }

    .stats-icon.products {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stats-icon.orders {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stats-icon.users {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stats-icon.blogs {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stats-number {
        font-size: 42px;
        font-weight: 700;
        color: #2d3748;
        margin: 10px 0;
    }

    .stats-label {
        font-size: 16px;
        color: #718096;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
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
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.categories') }}">
                        <i class="fas fa-list me-2"></i>Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products', ['flash_sale' => 1]) }}">
                        <i class="fas fa-bolt me-2"></i>Flash Sale
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.tiktok-deals.index') }}">
                        <i class="fab fa-tiktok me-2"></i>Săn Sale TikTok
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                        @if(($pendingOrdersCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                {{ $pendingOrdersCount }}
                                <span class="visually-hidden">đơn hàng chờ xử lý</span>
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.blogs') }}">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('admin.card-exchanges') }}">
                        <i class="fas fa-credit-card me-2"></i>Đổi thẻ cào
                        @if(($pendingCardExchangeCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                                  style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                {{ $pendingCardExchangeCount }}
                                <span class="visually-hidden">yêu cầu đổi thẻ chờ xử lý</span>
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('admin.chat.index') }}">
                        <i class="fas fa-comments me-2"></i>Chat
                        @if(($unreadChatCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                {{ $unreadChatCount }}
                                <span class="visually-hidden">tin nhắn chưa đọc</span>
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('admin.abandoned-carts') }}">
                        <i class="fas fa-shopping-basket me-2"></i>Giỏ bỏ quên
                        @if(($abandonedCartsCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info"
                                  style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                {{ $abandonedCartsCount }}
                                <span class="visually-hidden">giỏ hàng bị bỏ quên</span>
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card text-center">
                    <div class="stats-icon products mx-auto">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stats-number">{{ $stats['products'] }}</div>
                    <div class="stats-label">Sản phẩm</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card text-center">
                    <div class="stats-icon orders mx-auto">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stats-number">{{ $stats['orders'] }}</div>
                    <div class="stats-label">Đơn hàng</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card text-center">
                    <div class="stats-icon users mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">{{ $stats['users'] }}</div>
                    <div class="stats-label">Người dùng</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="stats-card text-center">
                    <div class="stats-icon blogs mx-auto">
                        <i class="fas fa-blog"></i>
                    </div>
                    <div class="stats-number">{{ $stats['blogs'] }}</div>
                    <div class="stats-label">Bài viết</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="admin-card">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-bolt text-warning me-2"></i>Thao tác nhanh
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary w-100 py-3 rounded-3">
                                <i class="fas fa-plus-circle d-block fs-3 mb-2"></i>
                                Thêm sản phẩm
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.products') }}" class="btn btn-outline-success w-100 py-3 rounded-3">
                                <i class="fas fa-list d-block fs-3 mb-2"></i>
                                Quản lý sản phẩm
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="javascript:void(0)" class="btn btn-outline-info w-100 py-3 rounded-3" title="Tính năng sắp có">
                                <i class="fas fa-chart-bar d-block fs-3 mb-2"></i>
                                Xem báo cáo
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="javascript:void(0)" class="btn btn-outline-danger w-100 py-3 rounded-3" title="Tính năng sắp có">
                                <i class="fas fa-cog d-block fs-3 mb-2"></i>
                                Cài đặt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Orders -->
        @if(isset($latestOrders) && $latestOrders->count() > 0)
        <div class="admin-card" data-aos="fade-up">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-shopping-cart text-primary me-2"></i>Đơn hàng mới nhất
            </h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name ?? 'admin' }}</td>
                            <td><strong class="text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong></td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="admin-card text-center py-5" data-aos="fade-up">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
            <p class="text-muted">Đơn hàng mới sẽ hiển thị ở đây</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
