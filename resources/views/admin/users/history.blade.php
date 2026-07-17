extends('layouts.admin')

@section('title', 'Lịch Sử Mua Hàng - ' . $user->name)

@push('styles')
<style>

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .user-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .user-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .stat-box {
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
    }

    .stat-box i {
        font-size: 2rem;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .order-item {
        background: #f7fafc;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .order-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .order-id {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .order-date {
        color: #718096;
        font-size: 0.9rem;
    }

    .product-list {
        margin-top: 15px;
    }

    .product-mini {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .product-mini:last-child {
        border-bottom: none;
    }

    .product-mini img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        margin-right: 15px;
    }

    .order-total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        text-align: right;
        margin-top: 15px;
    }

    .back-btn {
        background: white;
        color: #667eea;
        border: none;
        padding: 10px 25px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.9);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,255,255,0.3);
    }

    .alert-warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 10px;
    }

    .fraud-warning {
        background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        <a href="{{ route('admin.users') }}" class="back-btn mb-3">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>

        <!-- User Header -->
        <div class="user-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-user-circle me-2"></i>{{ $user->name }}
                    </h2>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                        @if($user->phone)
                            | <i class="fas fa-phone me-2"></i>{{ $user->phone }}
                        @endif
                    </p>
                    <p class="mb-0 mt-2 opacity-75">
                        <i class="fas fa-calendar me-2"></i>Tham gia: {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="user-stats">
                <div class="stat-box">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-number">{{ $user->orders_count }}</div>
                    <div class="stat-label">Tổng đơn hàng</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="stat-number">{{ number_format($user->orders_sum_total_amount ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stat-label">Tổng chi tiêu</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-chart-line"></i>
                    <div class="stat-number">{{ $user->orders_count > 0 ? number_format(($user->orders_sum_total_amount ?? 0) / $user->orders_count, 0, ',', '.') : 0 }}đ</div>
                    <div class="stat-label">Trung bình/đơn</div>
                </div>
            </div>
        </div>

        <!-- Fraud Warning -->
        @if($user->orders_count > 5)
            <div class="fraud-warning">
                <h5 class="fw-bold mb-2">
                    <i class="fas fa-shield-alt me-2"></i>Lưu Ý Phòng Chống Gian Lận
                </h5>
                <p class="mb-0">
                    Người dùng này đã có <strong>{{ $user->orders_count }} đơn hàng</strong>. 
                    Hãy kiểm tra kỹ lịch sử giao dịch và đảm bảo không có hành vi gian lận hoặc lạm dụng hệ thống.
                </p>
            </div>
        @endif

        <!-- Orders List -->
        <div class="admin-card">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-history text-primary me-2"></i>Lịch Sử Mua Hàng
            </h4>

            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="order-item">
                        <div class="order-header">
                            <div>
                                <div class="order-id">
                                    <i class="fas fa-file-invoice me-2 text-primary"></i>Đơn hàng #{{ $order->id }}
                                </div>
                                <div class="order-date">
                                    <i class="fas fa-clock me-1"></i>{{ $order->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $order->status_color }} px-3 py-2">
                                    {{ $order->status_label }}
                                </span>
                                <div class="mt-2">
                                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        {{ $order->order_type == 'qr' ? '🎫 QR Deal' : ($order->order_type == 'document' ? '📄 Tài liệu' : ($order->order_type == 'shipping' ? '🚚 Giao hàng' : '💾 Digital')) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="product-list">
                            @foreach($order->orderItems as $item)
                                <div class="product-mini">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 10px; margin-right: 15px;"></div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                                        <small class="text-muted">Số lượng: {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}đ</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="order-total">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Tổng tiền:</span>
                                <span class="fs-4 fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mt-3 p-3" style="background: white; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <small class="text-muted">
                                <i class="fas fa-user me-2"></i>{{ $order->customer_name }} | 
                                <i class="fas fa-envelope me-2"></i>{{ $order->customer_email }} | 
                                <i class="fas fa-phone me-2"></i>{{ $order->customer_phone }}
                                @if($order->customer_address && $order->customer_address !== 'Sản phẩm số - không cần giao hàng')
                                    <br><i class="fas fa-map-marker-alt me-2"></i>{{ $order->customer_address }}
                                @endif
                            </small>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Người dùng chưa có đơn hàng nào</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
