@extends('layouts.app')

@section('title', 'Chi tiết Đơn hàng #' . $order->id . ' - Admin')

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

    .info-section {
        background: #f7fafc;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .product-item {
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 0;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .status-timeline {
        position: relative;
        padding-left: 40px;
    }

    .status-timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .status-timeline-item:before {
        content: '';
        position: absolute;
        left: -29px;
        top: 8px;
        width: 2px;
        height: 100%;
        background: #e2e8f0;
    }

    .status-timeline-item:last-child:before {
        display: none;
    }

    .status-timeline-dot {
        position: absolute;
        left: -35px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
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

    .action-guide {
        background: #fff7ed;
        border-left: 4px solid #f59e0b;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-2">
                            <i class="fas fa-file-invoice text-primary me-3"></i>Đơn hàng #{{ $order->id }}
                        </h3>
                        <span class="order-type-badge type-{{ $order->order_type }}">
                            @if($order->order_type == 'qr')
                                <i class="fas fa-qrcode me-1"></i>Đơn QR (TikTok Deal)
                            @elseif($order->order_type == 'document')
                                <i class="fas fa-file-pdf me-1"></i>Đơn Tài liệu
                            @elseif($order->order_type == 'shipping')
                                <i class="fas fa-shipping-fast me-1"></i>Đơn Ship
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

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Order Status Alert -->
            @if($order->status == 'completed')
                <div class="alert alert-success mb-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-1 text-white">✅ Đơn hàng đã được xác nhận</h5>
                            <p class="mb-0" style="font-size: 0.95rem;">Admin đã xác nhận đơn hàng và gửi tài khoản demo cho khách hàng</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Type Specific Guide -->
            <div class="action-guide">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-lightbulb me-2"></i>Hướng dẫn xử lý đơn {{ $order->order_type }}:
                </h6>
                @if($order->order_type == 'qr')
                    <ul class="mb-0">
                        <li>Xác nhận thanh toán từ khách hàng</li>
                        <li>Gửi mã QR code/voucher TikTok qua email hoặc SMS</li>
                        <li>Đánh dấu "Hoàn thành" sau khi gửi thành công</li>
                    </ul>
                @elseif($order->order_type == 'document')
                    <ul class="mb-0">
                        <li>Kiểm tra thanh toán đã được xác nhận</li>
                        <li>Khách hàng có thể tải file ngay từ trang sản phẩm</li>
                        <li>Không cần giao hàng vật lý - đánh dấu "Hoàn thành" ngay</li>
                    </ul>
                @elseif($order->order_type == 'shipping')
                    <ul class="mb-0">
                        <li>Đóng gói sản phẩm và chuẩn bị giao hàng</li>
                        <li>Cập nhật trạng thái "Đã giao hàng" khi bàn giao cho đơn vị vận chuyển</li>
                        <li>Liên hệ khách hàng xác nhận khi nhận hàng</li>
                        <li>Đánh dấu "Đã nhận hàng" hoặc "Hoàn thành"</li>
                    </ul>
                @else
                    <ul class="mb-0">
                        <li>Xác nhận thanh toán</li>
                        <li>Gửi link download hoặc kích hoạt tài khoản</li>
                        <li>Đánh dấu "Hoàn thành" sau khi giao hàng</li>
                    </ul>
                @endif
            </div>

            <div class="row">
                <!-- Customer Information -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-user text-primary me-2"></i>Thông tin khách hàng
                        </h5>
                        <div class="mb-2">
                            <strong>Họ tên:</strong> {{ $order->customer_name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $order->customer_email }}
                        </div>
                        <div class="mb-2">
                            <strong>Số điện thoại:</strong> {{ $order->customer_phone }}
                        </div>
                        @if($order->order_type == 'shipping')
                            <div class="mb-2">
                                <strong>Địa chỉ giao hàng:</strong><br>
                                {{ $order->customer_address }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Information -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>Thông tin đơn hàng
                        </h5>
                        <div class="mb-2">
                            <strong>Mã đơn:</strong> #{{ $order->id }}
                        </div>
                        <div class="mb-2">
                            <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>Tổng tiền:</strong> 
                            <span class="text-primary fw-bold fs-5">{{ $order->formatted_total }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Số lượng sản phẩm:</strong> {{ $order->orderItems->sum('quantity') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="info-section mt-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-box text-primary me-2"></i>Sản phẩm trong đơn
                </h5>
                @foreach($order->orderItems as $item)
                    <div class="product-item">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="max-height: 80px;">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">{{ $item->product->name }}</div>
                                <small class="text-muted">
                                    @if($item->product->category == 'ebooks')
                                        <i class="fas fa-file-pdf text-danger me-1"></i>Tài liệu số
                                    @elseif($item->product->category == 'tiktok')
                                        <i class="fas fa-qrcode text-primary me-1"></i>TikTok Deal
                                    @elseif($item->product->delivery_type == 'physical')
                                        <i class="fas fa-box text-success me-1"></i>Giao hàng vật lý
                                    @else
                                        <i class="fas fa-download text-info me-1"></i>Digital
                                    @endif
                                </small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="fw-bold">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Update Status Form -->
            <div class="info-section mt-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-tasks text-primary me-2"></i>Cập nhật trạng thái
                </h5>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Trạng thái mới:</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                @if($order->order_type == 'shipping')
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã giao hàng</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã nhận hàng</option>
                                @endif
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Delete Order -->
            <div class="mt-4 text-end">
                <form action="{{ route('admin.orders.delete', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Xóa đơn hàng
                    </button>
                </form>
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
