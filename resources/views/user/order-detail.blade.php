@extends('layouts.app')

@section('title', __('Chi tiết Đơn hàng') . ' #' . $order->id)

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

    .support-icon-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ff4d00 0%, #ffb800 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(255, 77, 0, 0.25);
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        vertical-align: middle;
    }

    .support-icon-btn:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(255, 77, 0, 0.32);
    }

    .support-copy-card {
        border: 1px solid #ffe0cc;
        background: linear-gradient(135deg, #fff7ed 0%, #fff 100%);
    }

    .support-copy-text {
        background: white;
        border: 1px dashed #ffb07a;
        border-radius: 10px;
        padding: 12px;
        color: #4a5568;
        font-size: 0.92rem;
        line-height: 1.5;
        white-space: pre-line;
    }

    .copy-support-btn {
        border: 0;
        border-radius: 999px;
        background: linear-gradient(135deg, #ff4d00 0%, #ffb800 100%);
        color: white;
        font-weight: 700;
        padding: 10px 16px;
        width: 100%;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .copy-support-btn:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .order-detail-wrapper {
            padding: 80px 0 40px;
        }
        .order-card {
            padding: 20px 15px;
            border-radius: 15px;
        }
        .info-section {
            padding: 15px;
            border-radius: 10px;
        }
        .order-card > .mb-4 > .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 15px;
        }
        .product-item {
            flex-wrap: wrap;
            padding: 15px;
        }
        .product-item img, 
        .product-item > div[style*="width: 80px"] {
            width: 60px !important;
            height: 60px !important;
            margin-right: 15px !important;
            flex: 0 0 60px;
        }
        .product-item .flex-grow-1 {
            flex: 0 0 calc(100% - 75px);
            max-width: calc(100% - 75px);
            margin-bottom: 10px;
            word-break: break-word;
        }
        .product-item .text-center.me-4 {
            flex: 0 0 50%;
            max-width: 50%;
            text-align: left !important;
            margin-right: 0 !important;
        }
        .product-item .text-end {
            flex: 0 0 50%;
            max-width: 50%;
            text-align: right !important;
        }
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        .alert-info .d-flex {
            align-items: flex-start !important;
        }
        .alert-info .fa-camera {
            margin-top: 5px;
            font-size: 1.5rem;
        }
        .support-icon-btn {
            width: 38px;
            height: 38px;
        }
    }
</style>
@endpush

@section('content')
@php
    $supportProductNames = $order->orderItems
        ->map(fn($item) => optional($item->product)->name)
        ->filter()
        ->values();

    $isEn = app()->getLocale() === 'en';
    $supportCopyMessage = $isEn 
        ? ("Please copy all of this content and send it to the admin for faster processing\n"
           . "Order Code: " . ($order->order_code ? $order->order_code : "#{$order->id}") . "\n"
           . "Order Items: " . ($supportProductNames->isNotEmpty() ? $supportProductNames->implode(', ') : 'No products'))
        : ("Bạn hãy copy all nội dung này gửi admin để đơn hàng xử lý nhanh hơn\n"
           . "Mã đơn hàng: " . ($order->order_code ? $order->order_code : "#{$order->id}") . "\n"
           . "Tên đơn hàng: " . ($supportProductNames->isNotEmpty() ? $supportProductNames->implode(', ') : 'Không có tên sản phẩm'));
@endphp
<div class="order-detail-wrapper">
    <div class="container">
        <div class="order-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Quay lại danh sách') }}
                </a>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-2">
                            <i class="fas fa-file-invoice text-primary me-3"></i>{{ __('Đơn hàng') }} #{{ $order->id }} @if($order->order_code) <span class="text-primary" style="font-family: monospace;">({{ $order->order_code }})</span> @endif
                            <a href="#order-support-copy" class="support-icon-btn ms-2" title="{{ __('Hỗ trợ đơn hàng') }}" aria-label="{{ __('Hỗ trợ đơn hàng') }}">
                                <i class="fas fa-headset"></i>
                            </a>
                        </h3>
                        <span class="order-type-badge type-{{ $order->order_type }}">
                            @if($order->order_type == 'qr')
                                <i class="fas fa-qrcode me-1"></i>{{ __('Đơn QR Deal') }}
                            @elseif($order->order_type == 'document')
                                <i class="fas fa-file-pdf me-1"></i>{{ __('Đơn Tài liệu') }}
                            @elseif($order->order_type == 'shipping')
                                <i class="fas fa-shipping-fast me-1"></i>{{ __('Đơn Giao hàng') }}
                            @else
                                <i class="fas fa-download me-1"></i>{{ __('Đơn Digital') }}
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
                                <i class="fas fa-check-circle text-success me-1"></i>{{ __('Đơn hàng đã hoàn thành!') }}
                            </h6>
                            <p class="mb-0">
                                {!! __('Hãy <strong>chụp màn hình đơn hàng này</strong> và gửi cho Admin để được cấp tài khoản hoặc hỗ trợ khi gặp lỗi.') !!}
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
                            <i class="fas fa-box text-primary me-2"></i>{{ __('Sản phẩm') }}
                        </h5>
                        @foreach($order->orderItems as $item)
                            <div class="product-item">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                                @else
                                    <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 10px; margin-right: 20px;"></div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-bold mb-1">{{ $item->product->name ?? __('Sản phẩm không tồn tại') }}</div>
                                    <small class="text-muted">
                                        @if($item->product)
                                            @if($item->product->category == 'ebooks')
                                                <i class="fas fa-file-pdf text-danger me-1"></i>{{ __('Tài liệu số') }}
                                            @elseif($item->product->category == 'tiktok')
                                                <i class="fas fa-qrcode text-primary me-1"></i>{{ __('TikTok Deal') }}
                                            @elseif($item->product->delivery_type == 'physical')
                                                <i class="fas fa-box text-success me-1"></i>{{ __('Giao hàng') }}
                                            @else
                                                <i class="fas fa-download text-info me-1"></i>{{ __('Digital') }}
                                            @endif
                                        @endif
                                    </small>
                                </div>
                                <div class="text-center me-4">
                                    <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">
                                        @if($order->currency === 'USD')
                                            ${{ number_format($item->price, 2) }}
                                        @else
                                            {{ $order->currency === 'USD' ? '$' . number_format($item->price, 2) : number_format($item->price, 0, ',', '.') . 'đ' }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($order->status == 'completed' && $item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                                <div class="alert alert-success">
                                    <i class="fas fa-download me-2"></i>
                                    <a href="{{ route('product.download', $item->product) }}" class="fw-bold text-success">
                                        {{ __('Click để tải file:') }} {{ $item->product->name }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Customer Info -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-user text-primary me-2"></i>{{ __('Thông tin người nhận') }}
                        </h5>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Họ tên:') }}</span>
                            <span class="fw-bold">{{ $order->customer_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Email:') }}</span>
                            <span class="fw-bold">{{ $order->customer_email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Số điện thoại:') }}</span>
                            <span class="fw-bold">{{ $order->customer_phone }}</span>
                        </div>
                        @if($order->order_type == 'shipping')
                            <div class="info-row">
                                <span class="text-muted">{{ __('Địa chỉ giao hàng:') }}</span>
                                <span class="fw-bold">{{ $order->customer_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Tracking -->
                <div class="col-md-4">
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>{{ __('Thông tin đơn hàng') }}
                        </h5>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Mã đơn:') }}</span>
                            <span class="fw-bold">#{{ $order->id }} @if($order->order_code) <span class="text-primary" style="font-family: monospace;">({{ $order->order_code }})</span> @endif</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Ngày đặt:') }}</span>
                            <span class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-muted">{{ __('Tổng tiền:') }}</span>
                            <span class="fw-bold text-primary fs-5">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <div class="info-section support-copy-card" id="order-support-copy">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-headset text-primary me-2"></i>{{ __('Hỗ trợ đơn hàng') }}
                        </h5>
                        <p class="text-muted mb-3">
                            {{ __('Bạn hãy copy all nội dung này gửi admin để đơn hàng xử lý nhanh hơn') }}
                        </p>
                        <div class="support-copy-text mb-3" id="support-copy-content">{{ $supportCopyMessage }}</div>
                        <button type="button" class="copy-support-btn" id="copy-support-order">
                            <i class="fas fa-copy me-2"></i>{{ __('Copy mã đơn hàng + tên đơn hàng') }}
                        </button>
                        <small class="text-success fw-bold mt-2 d-none" id="copy-support-success">
                            <i class="fas fa-check me-1"></i>{{ __('Đã copy nội dung gửi admin') }}
                        </small>
                    </div>

                    <!-- Tracking Timeline -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i>{{ __('Theo dõi đơn hàng') }}
                        </h5>
                        <div class="tracking-timeline">
                            <div class="tracking-step {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">{{ __('Đã đặt hàng') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>

                            <div class="tracking-step {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">{{ __('Đang xử lý') }}</div>
                                <small class="text-muted">{{ __('Đơn hàng đang được xử lý') }}</small>
                            </div>

                            @if($order->order_type == 'shipping')
                                <div class="tracking-step {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="tracking-dot"></div>
                                    <div class="fw-bold">{{ __('Đã giao hàng') }}</div>
                                    <small class="text-muted">{{ __('Đơn vị vận chuyển đang giao') }}</small>
                                </div>

                                <div class="tracking-step {{ in_array($order->status, ['delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="tracking-dot"></div>
                                    <div class="fw-bold">{{ __('Đã nhận hàng') }}</div>
                                    <small class="text-muted">{{ __('Giao hàng thành công') }}</small>
                                </div>
                            @endif

                            <div class="tracking-step {{ $order->status == 'completed' ? 'completed' : '' }}">
                                <div class="tracking-dot"></div>
                                <div class="fw-bold">{{ __('Hoàn thành') }}</div>
                                <small class="text-muted">{{ __('Đơn hàng đã hoàn thành') }}</small>
                            </div>

                            @if($order->status == 'cancelled')
                                <div class="tracking-step completed">
                                    <div class="tracking-dot bg-danger"></div>
                                    <div class="fw-bold text-danger">{{ __('Đã hủy') }}</div>
                                    <small class="text-muted">{{ __('Đơn hàng đã bị hủy') }}</small>
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

    const copySupportButton = document.getElementById('copy-support-order');
    const copySupportSuccess = document.getElementById('copy-support-success');
    const supportCopyText = @json($supportCopyMessage);

    if (copySupportButton) {
        copySupportButton.addEventListener('click', async () => {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(supportCopyText);
                } else {
                    const textarea = document.createElement('textarea');
                    textarea.value = supportCopyText;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.focus();
                    textarea.select();
                    document.execCommand('copy');
                    textarea.remove();
                }

                copySupportButton.innerHTML = '<i class="fas fa-check me-2"></i>{{ __("Đã copy nội dung") }}';
                copySupportSuccess.classList.remove('d-none');

                setTimeout(() => {
                    copySupportButton.innerHTML = '<i class="fas fa-copy me-2"></i>{{ __("Copy mã đơn hàng + tên đơn hàng") }}';
                    copySupportSuccess.classList.add('d-none');
                }, 2200);
            } catch (error) {
                copySupportButton.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>{{ __("Không copy được") }}';

                setTimeout(() => {
                    copySupportButton.innerHTML = '<i class="fas fa-copy me-2"></i>{{ __("Copy mã đơn hàng + tên đơn hàng") }}';
                }, 2200);
            }
        });
    }
</script>
@endpush
