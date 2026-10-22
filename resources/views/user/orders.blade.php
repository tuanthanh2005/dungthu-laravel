@extends('layouts.app')

@section('title', __('Đơn hàng của tôi'))

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

    /* Nút & Modal Đánh giá */
    .btn-warning-gold {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border: none;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        transition: all 0.25s ease;
    }
    .btn-warning-gold:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4);
        color: #ffffff !important;
    }
    .btn-outline-warning-gold {
        border: 1.5px solid #f59e0b;
        color: #d97706 !important;
        background: rgba(245, 158, 11, 0.06);
        transition: all 0.25s ease;
    }
    .btn-outline-warning-gold:hover {
        background: #fef3c7;
        color: #b45309 !important;
        border-color: #d97706;
    }
    .review-modal .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    .review-modal .modal-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 18px 24px;
    }
    .review-modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .star-rating-select {
        display: inline-flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 12px;
    }
    .star-rating-select input {
        display: none;
    }
    .star-rating-select label {
        font-size: 32px;
        color: #cbd5e1;
        cursor: pointer;
        transition: transform 0.15s ease, color 0.15s ease;
        margin: 0;
        padding: 0 4px;
        line-height: 1;
    }
    .star-rating-select label:hover,
    .star-rating-select label:hover ~ label,
    .star-rating-select input:checked ~ label {
        color: #f59e0b;
        text-shadow: 0 2px 10px rgba(245, 158, 11, 0.4);
    }
    .star-rating-select label:hover {
        transform: scale(1.25);
    }
    .rating-hint-text {
        font-size: 0.9rem;
        font-weight: 600;
        min-height: 22px;
        color: #d97706;
    }
    .reviewed-card {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 16px;
        padding: 16px;
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
                <i class="fas fa-shopping-bag text-primary me-3"></i>{{ __('Đơn hàng của tôi') }}
            </h3>

            @forelse($orders as $order)
                <div class="order-item">
                    <div class="order-header">
                        <div class="row align-items-center g-0">
                            <div class="col-6 col-md-3">
                                <div class="fw-bold mb-2">
                                    {{ __('Đơn hàng') }} #{{ $order->id }}
                                    @if($order->order_code)
                                        <span class="text-primary ms-1" style="font-size: 0.85rem; font-family: monospace;">({{ $order->order_code }})</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-6 col-md-3 mobile-align-right">
                                <span class="order-type-badge type-{{ $order->order_type }}">
                                    @if($order->order_type == 'qr')
                                        <i class="fas fa-qrcode"></i> {{ __('QR Deal') }}
                                    @elseif($order->order_type == 'document')
                                        <i class="fas fa-file-pdf"></i> {{ __('Tài liệu') }}
                                    @elseif($order->order_type == 'shipping')
                                        <i class="fas fa-shipping-fast"></i> {{ __('Giao hàng') }}
                                    @else
                                        <i class="fas fa-download"></i> {{ __('Digital') }}
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
                                    <div class="fw-bold">{{ $item->product->name ?? __('Sản phẩm không tồn tại') }}</div>
                                    <small class="text-muted">{{ __('Số lượng') }}: x{{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">
                                        @if($order->currency === 'USD')
                                            ${{ number_format($item->price, 2) }}
                                        @else
                                            {{ number_format($item->price, 0, ',', '.') }}{{ app()->getLocale() === 'en' ? ' USD' : 'đ' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="text-end order-actions">
                        <a href="{{ route('user.orders.detail', $order) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-eye me-2"></i>{{ __('Xem chi tiết') }}
                        </a>

                        <button type="button" class="btn btn-info text-white rounded-pill px-4 ms-2 fw-bold" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-headset me-2"></i>{{ __('Hỗ trợ') }}
                        </button>

                        {{-- Nút Đánh giá sản phẩm: Hiển thị khi đã mua hàng xong, ẩn khi hủy đơn --}}
                        @if($order->status !== 'cancelled' && $order->status !== 'pending')
                            @php
                                $validItems = $order->orderItems->filter(fn($i) => !empty($i->product));
                                $hasProducts = $validItems->isNotEmpty();
                                $allReviewed = $hasProducts && $validItems->every(fn($i) => $i->product->comments->where('user_id', auth()->id())->isNotEmpty());
                            @endphp

                            @if($hasProducts)
                                @if($allReviewed)
                                    <button type="button" class="btn btn-outline-warning-gold rounded-pill px-4 ms-2 fw-bold btn-review-order" data-bs-toggle="modal" data-bs-target="#reviewModal_{{ $order->id }}">
                                        <i class="fas fa-star text-warning me-2"></i>{{ __('Đã đánh giá') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-warning-gold rounded-pill px-4 ms-2 fw-bold shadow-sm btn-review-order" data-bs-toggle="modal" data-bs-target="#reviewModal_{{ $order->id }}">
                                        <i class="fas fa-star me-2"></i>{{ __('Đánh giá') }}
                                    </button>
                                @endif
                            @endif

                            @foreach($order->orderItems as $item)
                                @if($item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                                    <a href="{{ route('product.download', $item->product) }}" class="btn btn-success rounded-pill px-4 ms-2 fw-bold">
                                        <i class="fas fa-download me-2"></i>{{ __('Tải file') }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-box-open text-muted opacity-50"></i>
                    <h5 class="fw-bold text-secondary">{{ __('Chưa có đơn hàng nào') }}</h5>
                    <p class="text-muted mb-4">{{ __('Bạn chưa thực hiện giao dịch nào trên hệ thống.') }}</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-shopping-cart me-2"></i> {{ __('Mua sắm ngay') }}
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
                <i class="fas fa-exchange-alt text-success me-3"></i>{{ __('Lịch sử đổi thẻ cào') }}
            </h3>

            @forelse($cardExchanges as $exchange)
                    <div class="order-item">
                        <div class="order-header">
                            <div class="row align-items-center g-0">
                                <div class="col-6 col-md-3">
                                    <div class="fw-bold mb-1">{{ __('Giao dịch') }} #{{ $exchange->id }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $exchange->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-6 col-md-3 mobile-align-right">
                                    <span class="order-type-badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                        <i class="fas fa-credit-card"></i> {{ __('Đổi thẻ') }}
                                    </span>
                                </div>
                                <div class="col-6 col-md-3 mt-md-0 mt-2">
                                    <span class="badge rounded-pill
                                        @if($exchange->status == 'pending') bg-warning
                                        @elseif($exchange->status == 'approved') bg-success
                                        @elseif($exchange->status == 'rejected') bg-danger
                                        @endif px-3 py-1">
                                        @if($exchange->status == 'pending') {{ __('Đang xử lý') }}
                                        @elseif($exchange->status == 'approved') {{ __('Hoàn tất') }}
                                        @elseif($exchange->status == 'rejected') {{ __('Từ chối') }}
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
                                    <p class="mb-1"><i class="fas fa-sim-card me-2 text-muted"></i><strong>{{ __('Loại thẻ:') }}</strong> <span>{{ $exchange->card_type }}</span></p>
                                    <p class="mb-1"><i class="fas fa-hashtag me-2 text-muted"></i><strong>{{ __('Seri:') }}</strong> <span>{{ $exchange->card_serial }}</span></p>
                                    <p class="mb-1"><i class="fas fa-key me-2 text-muted"></i><strong>{{ __('Mã thẻ:') }}</strong> <span>{{ substr($exchange->card_code, 0, 4) }}****</span></p>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <p class="mb-1"><i class="fas fa-university me-2 text-muted"></i><strong>{{ __('Ngân hàng:') }}</strong> <span>{{ $exchange->bank_name }}</span></p>
                                    <p class="mb-1"><i class="fas fa-credit-card me-2 text-muted"></i><strong>{{ __('STK:') }}</strong> <span>{{ $exchange->bank_account_number }}</span></p>
                                    <p class="mb-1"><i class="fas fa-user me-2 text-muted"></i><strong>{{ __('Chủ TK:') }}</strong> <span>{{ $exchange->bank_account_name }}</span></p>
                                </div>
                            </div>
                            @if($exchange->admin_note)
                                <div class="alert alert-info mt-3 mb-0 py-2 px-3" style="font-size: 0.9rem;">
                                    <i class="fas fa-info-circle me-1"></i><strong>{{ __('Ghi chú từ Admin:') }}</strong> {{ $exchange->admin_note }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-money-check text-muted opacity-50"></i>
                        <h6 class="fw-bold text-secondary mt-3">{{ __('Chưa có lịch sử đổi thẻ') }}</h6>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ __('Bạn chưa thực hiện giao dịch đổi thẻ cào nào trên hệ thống.') }}</p>
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
                    <i class="fas fa-headset me-2"></i>{{ __('Hỗ trợ đơn hàng') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-4 py-2 px-3" style="font-size: 0.9rem;">
                    <i class="fas fa-camera me-1"></i>
                    {{ __('Mẹo: Chụp màn hình đơn hàng và gửi cho admin để được hỗ trợ cấp tốc!') }}
                </div>
                <p class="text-center mb-3 fw-medium">{{ __('Chọn phương thức liên hệ:') }}</p>
                
                <div class="d-flex flex-column gap-2">
                    <a href="mailto:tranthanhtuanfix@gmail.com" class="contact-option email m-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #f5576c;">
                                <i class="fas fa-envelope fa-fw fs-5 m-0"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ __('Email hỗ trợ') }}</h6>
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
                                <h6 class="fw-bold mb-0">{{ __('Telegram Admin') }}</h6>
                                <small class="text-muted">{{ __('Phản hồi 24/7') }}</small>
                            </div>
                        </div>
                    </a>

                    <a href="https://zalo.me/0708910952" target="_blank" class="contact-option zalo m-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #0068ff;">
                                <i class="fas fa-comments fa-fw fs-5 m-0"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ __('Zalo Hỗ trợ') }}</h6>
                                <small class="text-muted">{{ __('Phản hồi giờ hành chính') }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modals -->
@foreach($orders as $order)
    @if($order->status !== 'cancelled' && $order->status !== 'pending')
        @php
            $validItems = $order->orderItems->filter(fn($i) => !empty($i->product));
        @endphp
        @if($validItems->isNotEmpty())
            <div class="modal fade review-modal" id="reviewModal_{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title fw-bold mb-0">
                                    <i class="fas fa-star text-warning me-2"></i>{{ __('Đánh giá sản phẩm') }}
                                </h5>
                                <small class="text-white-50">{{ __('Đơn hàng') }} #{{ $order->id }} @if($order->order_code)({{ $order->order_code }})@endif</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            @foreach($validItems as $index => $item)
                                @php
                                    $product = $item->product;
                                    $userComment = $product->comments->where('user_id', auth()->id())->first();
                                @endphp

                                <div class="product-review-block {{ !$loop->first ? 'pt-4 mt-4 border-top' : '' }}" id="product_review_block_{{ $order->id }}_{{ $product->id }}">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="rounded-3 border" style="width: 55px; height: 55px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 55px; height: 55px;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1 text-dark">{{ $product->name }}</h6>
                                            <div class="small text-muted">
                                                @if($item->variant_name)
                                                    <span class="badge bg-light text-secondary border me-1">{{ $item->variant_name }}</span>
                                                @endif
                                                <span>{{ __('Số lượng:') }} x{{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($userComment)
                                        {{-- Đã đánh giá --}}
                                        <div class="reviewed-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="badge bg-success text-white px-2.5 py-1 rounded-pill me-2">
                                                        <i class="fas fa-check-circle me-1"></i>{{ __('Đã đánh giá') }}
                                                    </span>
                                                    @for($s = 1; $s <= 5; $s++)
                                                        <i class="fas fa-star {{ $s <= $userComment->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                                    @endfor
                                                    <span class="fw-bold text-dark ms-1">({{ $userComment->rating }}/5)</span>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>{{ $userComment->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="p-2.5 bg-white rounded-3 border text-secondary fst-italic">
                                                "{{ $userComment->comment }}"
                                            </div>
                                        </div>
                                    @else
                                        {{-- Form gửi đánh giá --}}
                                        <form action="{{ route('product.comment', $product->id) }}" method="POST" class="ajax-review-form" data-order-id="{{ $order->id }}" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <div class="text-center py-2 px-3 mb-3 bg-light rounded-3">
                                                <label class="form-label fw-bold text-dark small mb-1 d-block">{{ __('Mức độ hài lòng của bạn:') }}</label>
                                                <div class="star-rating-select my-1">
                                                    <input type="radio" name="rating" value="5" id="star_{{ $order->id }}_{{ $product->id }}_5" checked required>
                                                    <label for="star_{{ $order->id }}_{{ $product->id }}_5" data-hint="⭐⭐⭐⭐⭐ {{ __('Tuyệt vời') }}" title="5 sao"><i class="fas fa-star"></i></label>
                                                    <input type="radio" name="rating" value="4" id="star_{{ $order->id }}_{{ $product->id }}_4">
                                                    <label for="star_{{ $order->id }}_{{ $product->id }}_4" data-hint="⭐⭐⭐⭐ {{ __('Hài lòng') }}" title="4 sao"><i class="fas fa-star"></i></label>
                                                    <input type="radio" name="rating" value="3" id="star_{{ $order->id }}_{{ $product->id }}_3">
                                                    <label for="star_{{ $order->id }}_{{ $product->id }}_3" data-hint="⭐⭐⭐ {{ __('Bình thường') }}" title="3 sao"><i class="fas fa-star"></i></label>
                                                    <input type="radio" name="rating" value="2" id="star_{{ $order->id }}_{{ $product->id }}_2">
                                                    <label for="star_{{ $order->id }}_{{ $product->id }}_2" data-hint="⭐⭐ {{ __('Không hài lòng') }}" title="2 sao"><i class="fas fa-star"></i></label>
                                                    <input type="radio" name="rating" value="1" id="star_{{ $order->id }}_{{ $product->id }}_1">
                                                    <label for="star_{{ $order->id }}_{{ $product->id }}_1" data-hint="⭐ {{ __('Rất tệ') }}" title="1 sao"><i class="fas fa-star"></i></label>
                                                </div>
                                                <div class="rating-hint-text">⭐⭐⭐⭐⭐ {{ __('Tuyệt vời') }}</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-dark small mb-1">
                                                    {{ __('Nhận xét về sản phẩm') }} <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="comment" class="form-control rounded-3" rows="3" 
                                                          placeholder="{{ __('Chia sẻ trải nghiệm của bạn về chất lượng dịch vụ, sản phẩm...') }}" required maxlength="1000"></textarea>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-warning-gold rounded-pill px-4 fw-bold submit-review-btn">
                                                    <i class="fas fa-paper-plane me-2"></i>{{ __('Gửi đánh giá') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endforeach

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Star rating hint interactions
        document.querySelectorAll('.star-rating-select').forEach(function(box) {
            const hint = box.parentElement.querySelector('.rating-hint-text');
            const labels = box.querySelectorAll('label');
            const inputs = box.querySelectorAll('input[type="radio"]');

            labels.forEach(function(lbl) {
                lbl.addEventListener('mouseenter', function() {
                    if (hint) hint.textContent = lbl.getAttribute('data-hint') || '';
                });
            });

            box.addEventListener('mouseleave', function() {
                const checked = box.querySelector('input[type="radio"]:checked');
                if (checked && hint) {
                    const checkedLbl = box.querySelector('label[for="' + checked.id + '"]');
                    if (checkedLbl) hint.textContent = checkedLbl.getAttribute('data-hint') || '';
                }
            });

            inputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    const checkedLbl = box.querySelector('label[for="' + input.id + '"]');
                    if (checkedLbl && hint) hint.textContent = checkedLbl.getAttribute('data-hint') || '';
                });
            });
        });

        // AJAX Review Submission
        document.querySelectorAll('.ajax-review-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('.submit-review-btn');
                const originalHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
                }

                const formData = new FormData(form);
                const action = form.getAttribute('action');
                const orderId = form.getAttribute('data-order-id');
                const productId = form.getAttribute('data-product-id');

                fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    }).catch(function() {
                        return { status: res.status, ok: res.ok, data: {} };
                    });
                })
                .then(function(res) {
                    if (res.ok && res.data.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đánh giá thành công!',
                                text: res.data.message || 'Cảm ơn bạn đã đánh giá sản phẩm!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else if (typeof Toast !== 'undefined') {
                            Toast.fire({ icon: 'success', title: res.data.message });
                        } else {
                            alert(res.data.message || 'Cảm ơn bạn đã đánh giá sản phẩm!');
                        }

                        // Replace form with reviewed card
                        const block = document.getElementById('product_review_block_' + orderId + '_' + productId);
                        if (block) {
                            const rating = parseInt(formData.get('rating')) || 5;
                            const comment = (formData.get('comment') || '').replace(/</g, "&lt;").replace(/>/g, "&gt;");
                            let starsHtml = '';
                            for (let s = 1; s <= 5; s++) {
                                starsHtml += '<i class="fas fa-star ' + (s <= rating ? 'text-warning' : 'text-muted opacity-25') + '"></i> ';
                            }
                            
                            form.outerHTML = `
                                <div class="reviewed-card mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill me-2">
                                                <i class="fas fa-check-circle me-1"></i>Đã đánh giá
                                            </span>
                                            ${starsHtml}
                                            <span class="fw-bold text-dark ms-1">(${rating}/5)</span>
                                        </div>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i>Vừa xong</small>
                                    </div>
                                    <div class="p-2.5 bg-white rounded-3 border text-secondary fst-italic">
                                        "${comment}"
                                    </div>
                                </div>
                            `;
                        }

                        // Check if all forms in this modal have been submitted
                        const modal = document.getElementById('reviewModal_' + orderId);
                        if (modal) {
                            const remainingForms = modal.querySelectorAll('.ajax-review-form');
                            if (remainingForms.length === 0) {
                                const orderBtn = document.querySelector(`.btn-review-order[data-bs-target="#reviewModal_${orderId}"]`);
                                if (orderBtn) {
                                    orderBtn.className = 'btn btn-outline-warning-gold rounded-pill px-4 ms-2 fw-bold btn-review-order';
                                    orderBtn.innerHTML = '<i class="fas fa-star text-warning me-2"></i>Đã đánh giá';
                                }
                            }
                        }
                    } else {
                        const errMsg = res.data.message || 'Có lỗi xảy ra, vui lòng thử lại!';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Thông báo', text: errMsg });
                        } else {
                            alert(errMsg);
                        }
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHtml;
                        }
                    }
                })
                .catch(function(err) {
                    form.submit();
                });
            });
        });
    });
</script>
@endpush
