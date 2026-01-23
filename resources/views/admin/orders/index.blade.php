@extends('layouts.app')

@section('title', 'Quản lý Đơn hàng - Admin')

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
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #4a5568;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .filter-tab:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
    }

    .order-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102,126,234,0.2);
        transform: translateY(-2px);
    }

    .order-type-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .type-qr {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .type-document {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .type-shipping {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .type-digital {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-shopping-cart text-primary me-3"></i>Quản lý Đơn hàng
                </h3>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Tabs by Order Type -->
            <div class="filter-tabs">
                <a href="{{ route('admin.orders') }}" class="filter-tab {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                    <i class="fas fa-th-list me-2"></i>Tất cả
                </a>
                <a href="{{ route('admin.orders', ['type' => 'qr']) }}" class="filter-tab {{ request('type') == 'qr' ? 'active' : '' }}">
                    <i class="fas fa-qrcode me-2"></i>Đơn QR (TikTok)
                </a>
                <a href="{{ route('admin.orders', ['type' => 'document']) }}" class="filter-tab {{ request('type') == 'document' ? 'active' : '' }}">
                    <i class="fas fa-file-pdf me-2"></i>Đơn Tài liệu
                </a>
                <a href="{{ route('admin.orders', ['type' => 'shipping']) }}" class="filter-tab {{ request('type') == 'shipping' ? 'active' : '' }}">
                    <i class="fas fa-shipping-fast me-2"></i>Đơn Ship
                </a>
                <a href="{{ route('admin.orders', ['type' => 'digital']) }}" class="filter-tab {{ request('type') == 'digital' ? 'active' : '' }}">
                    <i class="fas fa-download me-2"></i>Đơn Digital
                </a>
            </div>

            <!-- Filter by Status -->
            <div class="filter-tabs">
                <a href="{{ route('admin.orders', array_merge(request()->all(), ['status' => 'all'])) }}" class="filter-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
                    Tất cả trạng thái
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->all(), ['status' => 'pending'])) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                    Chờ xử lý
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->all(), ['status' => 'processing'])) }}" class="filter-tab {{ request('status') == 'processing' ? 'active' : '' }}">
                    Đang xử lý
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->all(), ['status' => 'completed'])) }}" class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
                    Hoàn thành
                </a>
            </div>

            <!-- Orders List -->
            @forelse($orders as $order)
                <div class="order-card">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="mb-2">
                                <strong>#{{ $order->id }}</strong>
                            </div>
                            <span class="order-type-badge type-{{ $order->order_type }}">
                                @if($order->order_type == 'qr')
                                    <i class="fas fa-qrcode me-1"></i>QR
                                @elseif($order->order_type == 'document')
                                    <i class="fas fa-file-pdf me-1"></i>Tài liệu
                                @elseif($order->order_type == 'shipping')
                                    <i class="fas fa-shipping-fast me-1"></i>Ship
                                @else
                                    <i class="fas fa-download me-1"></i>Digital
                                @endif
                            </span>
                        </div>
                        <div class="col-md-3">
                            <div><i class="fas fa-user me-2 text-primary"></i>{{ $order->customer_name }}</div>
                            <small class="text-muted"><i class="fas fa-phone me-2"></i>{{ $order->customer_phone }}</small>
                        </div>
                        <div class="col-md-2">
                            <div class="fw-bold text-primary">{{ $order->formatted_total }}</div>
                            <small class="text-muted">{{ $order->orderItems->count() }} sản phẩm</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-{{ $order->status_color }} px-3 py-2">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng nào</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
