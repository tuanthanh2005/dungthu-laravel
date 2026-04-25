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
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .order-item {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .order-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.1);
        border-color: rgba(102,126,234,0.3);
    }

    .order-header {
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .order-type-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .type-qr { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .type-document { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    .type-shipping { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    .type-digital { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }

    .product-mini {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .product-mini:last-child {
        border-bottom: none;
    }

    .product-mini img {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 12px;
        margin-right: 15px;
        border: 1px solid #e2e8f0;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 4rem;
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
        padding: 20px 25px;
    }
    .contact-modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .contact-option {
        padding: 15px;
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
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        text-decoration: none;
    }

    /* --- MOBILE RESPONSIVE TWEAKS --- */
    @media (max-width: 768px) {
        .orders-wrapper {
            padding: 80px 0 30px;
        }
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        .orders-card {
            padding: 20px 15px;
            border-radius: 16px;
        }
        .order-item {
            padding: 15px;
            border-radius: 14px;
            margin-bottom: 15px;
        }
        
        /* Arrange the header cleanly in a 2x2 grid */
        .order-header .row {
            gap: 10px 0;
        }
        .mobile-align-right {
            text-align: right !important;
        }
        
        .fw-bold.mb-2 {
            font-size: 0.95rem;
            margin-bottom: 4px !important;
        }
        .text-muted i {
            font-size: 0.8rem;
        }
        
        .product-mini img {
            width: 55px;
            height: 55px;
            margin-right: 12px;
            border-radius: 10px;
        }
        .product-mini .fw-bold {
            font-size: 0.9rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }
        .product-mini .text-end {
            min-width: 75px;
        }
        .product-mini .text-end .fw-bold {
            font-size: 0.95rem;
        }
        
        /* Action buttons stacked on mobile */
        .order-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }
        .order-actions .btn {
            width: 100%;
            margin-left: 0 !important;
        }
        
        /* Card Exchange details */
        .card-exchange-details {
            font-size: 0.9rem;
        }
        .card-exchange-details p {
            margin-bottom: 6px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-exchange-details p strong {
            font-weight: 500;
            color: #64748b;
        }
        .card-exchange-details p span {
            font-weight: 600;
            color: #0f172a;
        }
        .card-exchange-details i {
            display: none; /* Hide icons to save horizontal space on mobile */
        }
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
                        <div class="row align-items-center g-0">
                            <div class="col-6 col-md-3">
                                <div class="fw-bold mb-2">Đơn hàng #{{ $order->id }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-6 col-md-3 mobile-align-right">
                                <span class="order-type-badge type-{{ $order->order_type }}">
                                    @if($order->order_type == 'qr')
                                        <i class="fas fa-qrcode"></i> QR Deal
                                    @elseif($order->order_type == 'document')
                                        <i class="fas fa-file-pdf"></i> Tài liệu
                                    @elseif($order->order_type == 'shipping')
                                        <i class="fas fa-shipping-fast"></i> Giao hàng
                                    @else
                                        <i class="fas fa-download"></i> Digital
                                    @endif
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mt-md-0 mt-2">
                                <span class="badge bg-{{ $order->status_color }} px-2 py-1 rounded-pill">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mt-md-0 mt-2 mobile-align-right">
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
                                <div class="flex-grow-1 pe-2">
                                    <div class="fw-bold">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                                    <small class="text-muted">Số lượng: x{{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="text-end order-actions">
                        <a href="{{ route('user.orders.detail', $order) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                        </a>

                        @if($order->status == 'completed')
                            <button type="button" class="btn btn-info text-white rounded-pill px-4 ms-2 fw-bold" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="fas fa-headset me-2"></i>Hỗ trợ
                            </button>

                            @foreach($order->orderItems as $item)
                                @if($item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                                    <a href="{{ route('product.download', $item->product) }}" class="btn btn-success rounded-pill px-4 ms-2 fw-bold">
                                        <i class="fas fa-download me-2"></i>Tải file
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-box-open text-muted opacity-50"></i>
                    <h5 class="fw-bold text-secondary">Chưa có đơn hàng nào</h5>
                    <p class="text-muted mb-4">Bạn chưa thực hiện giao dịch nào trên hệ thống.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-shopping-cart me-2"></i> Mua sắm ngay
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif

            <!-- Card Exchange History -->
            <h3 class="fw-bold mb-4 mt-5 pt-3 border-top">
                <i class="fas fa-exchange-alt text-success me-3"></i>Lịch sử đổi thẻ cào
            </h3>

            @forelse($cardExchanges as $exchange)
                    <div class="order-item">
                        <div class="order-header">
                            <div class="row align-items-center g-0">
                                <div class="col-6 col-md-3">
                                    <div class="fw-bold mb-1">Giao dịch #{{ $exchange->id }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $exchange->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-6 col-md-3 mobile-align-right">
                                    <span class="order-type-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                        <i class="fas fa-credit-card"></i> Đổi thẻ
                                    </span>
                                </div>
                                <div class="col-6 col-md-3 mt-md-0 mt-2">
                                    <span class="badge rounded-pill
                                        @if($exchange->status == 'pending') bg-warning
                                        @elseif($exchange->status == 'approved') bg-success
                                        @elseif($exchange->status == 'rejected') bg-danger
                                        @endif px-3 py-1">
                                        @if($exchange->status == 'pending') Đang xử lý
                                        @elseif($exchange->status == 'approved') Hoàn tất
                                        @elseif($exchange->status == 'rejected') Từ chối
                                        @endif
                                    </span>
                                </div>
                                <div class="col-6 col-md-3 mt-md-0 mt-2 mobile-align-right">
                                    <div class="fw-bold text-success fs-5">+{{ number_format($exchange->card_value, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 card-exchange-details">
                            <div class="row g-3">
                                <div class="col-md-6 border-end-md">
                                    <p class="mb-1"><i class="fas fa-sim-card me-2 text-muted"></i><strong>Loại thẻ:</strong> <span>{{ $exchange->card_type }}</span></p>
                                    <p class="mb-1"><i class="fas fa-hashtag me-2 text-muted"></i><strong>Seri:</strong> <span>{{ $exchange->card_serial }}</span></p>
                                    <p class="mb-1"><i class="fas fa-key me-2 text-muted"></i><strong>Mã thẻ:</strong> <span>{{ substr($exchange->card_code, 0, 4) }}****</span></p>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <p class="mb-1"><i class="fas fa-university me-2 text-muted"></i><strong>Ngân hàng:</strong> <span>{{ $exchange->bank_name }}</span></p>
                                    <p class="mb-1"><i class="fas fa-credit-card me-2 text-muted"></i><strong>STK:</strong> <span>{{ $exchange->bank_account_number }}</span></p>
                                    <p class="mb-1"><i class="fas fa-user me-2 text-muted"></i><strong>Chủ TK:</strong> <span>{{ $exchange->bank_account_name }}</span></p>
                                </div>
                            </div>
                            @if($exchange->admin_note)
                                <div class="alert alert-info mt-3 mb-0 py-2 px-3" style="font-size: 0.9rem;">
                                    <i class="fas fa-info-circle me-1"></i><strong>Ghi chú từ Admin:</strong> {{ $exchange->admin_note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-money-check text-muted opacity-50"></i>
                        <h6 class="fw-bold text-secondary mt-3">Chưa có lịch sử đổi thẻ</h6>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Bạn chưa thực hiện giao dịch đổi thẻ cào nào trên hệ thống.</p>
                    </div>
                @endforelse

                @if($cardExchanges->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $cardExchanges->links() }}
                    </div>
                @endif
        </div>
    </div>
</div>

<!-- Contact Admin Modal -->
<div class="modal fade contact-modal" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-headset me-2"></i>Hỗ trợ đơn hàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-4 py-2 px-3" style="font-size: 0.9rem;">
                    <i class="fas fa-camera me-1"></i>
                    Mẹo: Chụp màn hình đơn hàng và gửi cho admin để được hỗ trợ cấp tốc!
                </div>
                <p class="text-center mb-3 fw-medium">Chọn phương thức liên hệ:</p>
                
                <div class="d-flex flex-column gap-2">
                    <a href="mailto:tranthanhtuanfix@gmail.com" class="contact-option email m-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #f5576c;">
                                <i class="fas fa-envelope fa-fw fs-5 m-0"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Email hỗ trợ</h6>
                                <small class="text-muted">tranthanhtuanfix@gmail.com</small>
                            </div>
                        </div>
                    </a>

                    <a href="https://t.me/specademy" target="_blank" class="contact-option telegram m-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #0088cc;">
                                <i class="fab fa-telegram fa-fw fs-5 m-0"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Telegram Admin</h6>
                                <small class="text-muted">Phản hồi 24/7</small>
                            </div>
                        </div>
                    </a>

                    <a href="https://zalo.me/0708910952" target="_blank" class="contact-option zalo m-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #0068ff;">
                                <i class="fas fa-comments fa-fw fs-5 m-0"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Zalo Hỗ trợ</h6>
                                <small class="text-muted">Phản hồi giờ hành chính</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }
    });
</script>
@endpush
