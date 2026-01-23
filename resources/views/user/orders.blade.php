@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .orders-wrapper {
        padding: 100px 0 60px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .orders-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .order-item {
        background: #f7fafc;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(102,126,234,0.2);
    }

    .order-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .order-type-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .contact-modal .modal-content {
        border-radius: 20px;
        border: none;
    }

    .contact-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        border: none;
        padding: 25px 30px;
    }

    .contact-modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .contact-option {
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .contact-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .contact-option.email {
        background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);
        border-color: #f5576c;
    }

    .contact-option.telegram {
        background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);
        border-color: #0088cc;
    }

    .contact-option.zalo {
        background: linear-gradient(135deg, #43e97b15 0%, #38f9d715 100%);
        border-color: #0068ff;
    }

    .contact-option i {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .contact-option .icon-email {
        color: #f5576c;
    }

    .contact-option .icon-telegram {
        color: #0088cc;
    }

    .contact-option .icon-zalo {
        color: #0068ff;
    }
</style>
@endpush

@section('content')
<div class="orders-wrapper">
    <div class="container">
        <div class="orders-card" data-aos="fade-up">
            <h3 class="fw-bold mb-4">
                <i class="fas fa-shopping-bag text-primary me-3"></i>Đơn hàng của tôi
            </h3>

            @forelse($orders as $order)
                <div class="order-item">
                    <div class="order-header">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="fw-bold mb-2">Đơn hàng #{{ $order->id }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-3">
                                <span class="order-type-badge type-{{ $order->order_type }}">
                                    @if($order->order_type == 'qr')
                                        <i class="fas fa-qrcode me-1"></i>QR Deal
                                    @elseif($order->order_type == 'document')
                                        <i class="fas fa-file-pdf me-1"></i>Tài liệu
                                    @elseif($order->order_type == 'shipping')
                                        <i class="fas fa-shipping-fast me-1"></i>Giao hàng
                                    @else
                                        <i class="fas fa-download me-1"></i>Digital
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-{{ $order->status_color }} px-3 py-2">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="fw-bold text-primary fs-5">{{ $order->formatted_total }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Products in order -->
                    <div class="mb-3">
                        @foreach($order->orderItems as $item)
                            <div class="product-mini">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                                @else
                                    <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 10px; margin-right: 15px;"></div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                                    <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('user.orders.detail', $order) }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                        </a>

                        @if($order->status == 'completed')
                            <button type="button" class="btn btn-info rounded-pill px-4 ms-2" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="fas fa-headset me-2"></i>Liên hệ Admin
                            </button>

                            @foreach($order->orderItems as $item)
                                @if($item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                                    <a href="{{ route('product.download', $item->product) }}" class="btn btn-success rounded-pill px-4 ms-2">
                                        <i class="fas fa-download me-2"></i>Tải file
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-shopping-bag"></i>
                    <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                    <p class="text-muted mb-4">Bắt đầu mua sắm ngay để tạo đơn hàng đầu tiên!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-5">
                        <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif

            <!-- Card Exchange History -->
            @if($cardExchanges->isNotEmpty())
                <h3 class="fw-bold mb-4 mt-5">
                    <i class="fas fa-credit-card text-success me-3"></i>Lịch sử đổi thẻ cào
                </h3>

                @foreach($cardExchanges as $exchange)
                    <div class="order-item">
                        <div class="order-header">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="fw-bold mb-2">Giao dịch #{{ $exchange->id }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $exchange->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-3">
                                    <span class="order-type-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                        <i class="fas fa-credit-card me-1"></i>Đổi thẻ cào
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge 
                                        @if($exchange->status == 'pending') bg-warning
                                        @elseif($exchange->status == 'approved') bg-success
                                        @elseif($exchange->status == 'rejected') bg-danger
                                        @endif px-3 py-2">
                                        @if($exchange->status == 'pending') Đang xử lý
                                        @elseif($exchange->status == 'approved') Đã chuyển tiền
                                        @elseif($exchange->status == 'rejected') Từ chối
                                        @endif
                                    </span>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="fw-bold text-success fs-5">{{ number_format($exchange->card_value, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><i class="fas fa-sim-card me-2 text-muted"></i><strong>Loại thẻ:</strong> {{ $exchange->card_type }}</p>
                                    <p class="mb-1"><i class="fas fa-hashtag me-2 text-muted"></i><strong>Seri:</strong> {{ $exchange->card_serial }}</p>
                                    <p class="mb-1"><i class="fas fa-key me-2 text-muted"></i><strong>Mã thẻ:</strong> {{ substr($exchange->card_code, 0, 4) }}****</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><i class="fas fa-university me-2 text-muted"></i><strong>Ngân hàng:</strong> {{ $exchange->bank_name }}</p>
                                    <p class="mb-1"><i class="fas fa-credit-card me-2 text-muted"></i><strong>STK:</strong> {{ $exchange->bank_account_number }}</p>
                                    <p class="mb-1"><i class="fas fa-user me-2 text-muted"></i><strong>Chủ TK:</strong> {{ $exchange->bank_account_name }}</p>
                                </div>
                            </div>
                            @if($exchange->admin_note)
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle me-2"></i><strong>Ghi chú:</strong> {{ $exchange->admin_note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($cardExchanges->hasPages())
                    <div class="mt-4">
                        {{ $cardExchanges->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Contact Admin Modal -->
<div class="modal fade contact-modal" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-headset me-2"></i>Liên hệ Admin để cấp tài khoản
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-camera me-2"></i>
                    <strong>Lưu ý:</strong> Hãy chụp màn hình đơn hàng và gửi cho admin để được hỗ trợ nhanh hơn!
                </div>
                <p class="text-center mb-4">Vui lòng chọn phương thức liên hệ bên dưới để được hỗ trợ:</p>
                
                <!-- Email Contact -->
                <a href="mailto:admin@dungthu.com?subject=Yêu cầu cấp tài khoản&body=Xin chào Admin,%0A%0ATôi đã thanh toán đơn hàng QR thành công và muốn được cấp tài khoản.%0A%0AThông tin đơn hàng:%0A- Mã đơn hàng: [Mã đơn hàng]%0A- Email: [Email của bạn]%0A%0AXin cảm ơn!" class="contact-option email">
                    <div class="text-center">
                        <i class="fas fa-envelope icon-email"></i>
                        <h6 class="fw-bold mt-2 mb-1">Liên hệ qua Email</h6>
                        <small class="text-muted">admin@dungthu.com</small>
                    </div>
                </a>

                <!-- Telegram Contact -->
                <a href="https://t.me/dungthu_support" target="_blank" class="contact-option telegram">
                    <div class="text-center">
                        <i class="fab fa-telegram icon-telegram"></i>
                        <h6 class="fw-bold mt-2 mb-1">Liên hệ qua Telegram</h6>
                        <small class="text-muted">@dungthu_support</small>
                    </div>
                </a>

                <!-- Zalo Contact -->
                <a href="https://zalo.me/0123456789" target="_blank" class="contact-option zalo">
                    <div class="text-center">
                        <i class="fas fa-comments icon-zalo"></i>
                        <h6 class="fw-bold mt-2 mb-1">Liên hệ qua Zalo</h6>
                        <small class="text-muted">0123456789</small>
                    </div>
                </a>

                <div class="alert alert-info mt-4 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Admin sẽ phản hồi trong thời gian sớm nhất. Vui lòng cung cấp đầy đủ thông tin đơn hàng khi liên hệ.</small>
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
