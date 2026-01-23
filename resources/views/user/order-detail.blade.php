@extends('layouts.app')

@section('title', 'Chi tiết Đơn hàng #' . $order->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .order-detail-wrapper {
        padding: 100px 0 60px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .order-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .info-section {
        background: #f7fafc;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 2px solid #e2e8f0;
    }

    .product-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        margin-right: 20px;
    }

    .tracking-timeline {
        position: relative;
        padding-left: 40px;
    }

    .tracking-step {
        position: relative;
        padding-bottom: 30px;
    }

    .tracking-step:before {
        content: '';
        position: absolute;
        left: -29px;
        top: 8px;
        width: 2px;
        height: 100%;
        background: #e2e8f0;
    }

    .tracking-step.completed:before {
        background: #667eea;
    }

    .tracking-step:last-child:before {
        display: none;
    }

    .tracking-dot {
        position: absolute;
        left: -35px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #e2e8f0;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e2e8f0;
    }

    .tracking-step.completed .tracking-dot {
        background: #667eea;
        box-shadow: 0 0 0 2px #667eea;
    }

    .order-type-badge {
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
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
<div class="order-detail-wrapper">
    <div class="container">
        <div class="order-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-2">
                            <i class="fas fa-file-invoice text-primary me-3"></i>Đơn hàng #{{ $order->id }}
                        </h3>
                        <span class="order-type-badge type-{{ $order->order_type }}">
                            @if($order->order_type == 'qr')
                                <i class="fas fa-qrcode me-1"></i>Đơn QR Deal
                            @elseif($order->order_type == 'document')
                                <i class="fas fa-file-pdf me-1"></i>Đơn Tài liệu
                            @elseif($order->order_type == 'shipping')
                                <i class="fas fa-shipping-fast me-1"></i>Đơn Giao hàng
                            @else
                                <i class="fas fa-download me-1"></i>Đơn Digital
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="badge bg-{{ $order->status_color }} fs-5 px-4 py-2">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            @if($order->status == 'completed')
                <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-left: 4px solid #667eea !important;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-camera fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">
                                <i class="fas fa-check-circle text-success me-1"></i>Đơn hàng đã hoàn thành!
                            </h6>
                            <p class="mb-0">
                                Hãy <strong>chụp màn hình đơn hàng này</strong> và gửi cho Admin để được cấp tài khoản hoặc hỗ trợ khi gặp lỗi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Order Information -->
                <div class="col-md-8">
                    <!-- Products -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-box text-primary me-2"></i>Sản phẩm
                        </h5>
                        @foreach($order->orderItems as $item)
                            <div class="product-item">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                                @else
                                    <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 10px; margin-right: 20px;"></div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-bold mb-1">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                                    <small class="text-muted">
                                        @if($item->product)
                                            @if($item->product->category == 'ebooks')
                                                <i class="fas fa-file-pdf text-danger me-1"></i>Tài liệu số
                                            @elseif($item->product->category == 'tiktok')
                                                <i class="fas fa-qrcode text-primary me-1"></i>TikTok Deal
                                            @elseif($item->product->delivery_type == 'physical')
                                                <i class="fas fa-box text-success me-1"></i>Giao hàng
                                            @else
                                                <i class="fas fa-download text-info me-1"></i>Digital
                                            @endif
                                        @endif
                                    </small>
                                </div>
                                <div class="text-center me-4">
                                    <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                                </div>
                            </div>

                            @if($order->status == 'completed' && $item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                                <div class="alert alert-success">
                                    <i class="fas fa-download me-2"></i>
                                    <a href="{{ route('product.download', $item->product) }}" class="fw-bold text-success">
                                        Click để tải file: {{ $item->product->name }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Customer Info -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-user text-primary me-2"></i>Thông tin người nhận
                        </h5>
                        <div class="info-row">
                            <span class="text-muted">Họ tên:</span>
                            <span class="fw-bold">{{ $order->customer_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Email:</span>
                            <span class="fw-bold">{{ $order->customer_email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Số điện thoại:</span>
                            <span class="fw-bold">{{ $order->customer_phone }}</span>
                        </div>
                        @if($order->order_type == 'shipping')
                            <div class="info-row">
                                <span class="text-muted">Địa chỉ giao hàng:</span>
                                <span class="fw-bold">{{ $order->customer_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Tracking -->
                <div class="col-md-4">
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>Thông tin đơn hàng
                        </h5>
                        <div class="info-row">
                            <span class="text-muted">Mã đơn:</span>
                            <span class="fw-bold">#{{ $order->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Ngày đặt:</span>
                            <span class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">Tổng tiền:</span>
                            <span class="fw-bold text-primary fs-5">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <!-- Tracking Timeline -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i>Theo dõi đơn hàng
                        </h5>
                        <div class="tracking-timeline">
                            <div class="tracking-step {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">Đã đặt hàng</div>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>

                            <div class="tracking-step {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">Đang xử lý</div>
                                <small class="text-muted">Đơn hàng đang được xử lý</small>
                            </div>

                            @if($order->order_type == 'shipping')
                                <div class="tracking-step {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="tracking-dot"></div>
                                    <div class="fw-bold">Đã giao hàng</div>
                                    <small class="text-muted">Đơn vị vận chuyển đang giao</small>
                                </div>

                                <div class="tracking-step {{ in_array($order->status, ['delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="tracking-dot"></div>
                                    <div class="fw-bold">Đã nhận hàng</div>
                                    <small class="text-muted">Giao hàng thành công</small>
                                </div>
                            @endif

                            <div class="tracking-step {{ $order->status == 'completed' ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">Hoàn thành</div>
                                <small class="text-muted">Đơn hàng đã hoàn thành</small>
                            </div>

                            @if($order->status == 'cancelled')
                                <div class="tracking-step completed">
                                    <div class="tracking-dot bg-danger"></div>
                                    <div class="fw-bold text-danger">Đã hủy</div>
                                    <small class="text-muted">Đơn hàng đã bị hủy</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
