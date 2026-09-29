@extends('layouts.app')

@section('title', __('Chi tiết Đơn hàng') . ' #' . $order->id . ($order->order_code ? ' (' . $order->order_code . ')' : ''))

@push('styles')
<style>
    .order-detail-page {
        background-color: #f8fafc;
        min-height: calc(100vh - 80px);
        padding: 85px 0 50px;
    }

    .order-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .order-box-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .order-item-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        background: #ffffff;
        margin-bottom: 10px;
        transition: border-color 0.15s ease;
    }

    .order-item-card:hover {
        border-color: #cbd5e1;
    }

    .order-item-img {
        width: 54px;
        height: 54px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }

    .info-list-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 9px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.92rem;
    }

    .info-list-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-list-label {
        color: #64748b;
        font-weight: 500;
        min-width: 130px;
        flex-shrink: 0;
    }

    .info-list-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    .customer-note-highlight {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #ff5e00;
        border-radius: 8px;
        padding: 12px 14px;
        margin-top: 10px;
    }

    .delivery-handover-box {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .admin-note-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .copy-btn-mini {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .copy-btn-mini:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .copy-btn-mini.copied {
        background: #22c55e !important;
        border-color: #22c55e !important;
        color: #ffffff !important;
    }

    /* Basic Order Tracking Timeline */
    .basic-timeline {
        position: relative;
        padding-left: 24px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -18px;
        top: 7px;
        bottom: -7px;
        width: 2px;
        background-color: #e2e8f0;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-item.active::before {
        background-color: #ff5e00;
    }

    .timeline-dot {
        position: absolute;
        left: -23px;
        top: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #cbd5e1;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 1px #cbd5e1;
    }

    .timeline-item.active .timeline-dot {
        background-color: #ff5e00;
        box-shadow: 0 0 0 2px #ff5e00;
    }

    .timeline-item.cancelled .timeline-dot {
        background-color: #ef4444;
        box-shadow: 0 0 0 2px #ef4444;
    }

    /* Quick Action Buttons */
    .support-btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 9px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }

    .btn-zalo-direct {
        background-color: #0068ff;
        color: #ffffff;
    }
    .btn-zalo-direct:hover {
        background-color: #0056d6;
        color: #ffffff;
    }

    .btn-telegram-direct {
        background-color: #229ed9;
        color: #ffffff;
    }
    .btn-telegram-direct:hover {
        background-color: #1a8cc2;
        color: #ffffff;
    }

    .btn-support-copy {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .btn-support-copy:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .order-detail-page {
            padding: 72px 10px 40px;
        }

        .order-box {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 14px;
        }

        .order-header-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .info-list-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 3px;
        }

        .info-list-value {
            text-align: left;
            width: 100%;
        }

        .order-item-card {
            padding: 10px;
        }

        .order-item-img {
            width: 46px;
            height: 46px;
        }
    }
</style>
@endpush

@section('content')
@php
    // Parse thông tin khách hàng từ customer_address
    $rawAddress = (string) ($order->customer_address ?? '');
    $addressLines = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $rawAddress))), fn($l) => $l !== ''));
    
    $parsedAddress = [
        'shipping_address' => null,
        'zalo' => null,
        'facebook' => null,
        'customer_note' => null,
        'payment_method' => null,
        'extra_notes' => [],
    ];

    $isRecordingNote = false;
    $accumulatedNotes = [];

    foreach ($addressLines as $line) {
        if (preg_match('/^Zalo:\s*(.*)$/iu', $line, $m)) {
            $parsedAddress['zalo'] = trim($m[1]);
            $isRecordingNote = false;
        } elseif (preg_match('/^Facebook:\s*(.*)$/iu', $line, $m)) {
            $parsedAddress['facebook'] = trim($m[1]);
            $isRecordingNote = false;
        } elseif (preg_match('/^(Email cần nâng cấp\/Ghi chú|Upgrade Email\/Note):\s*(.*)$/iu', $line, $m)) {
            $accumulatedNotes[] = trim($m[2]);
            $isRecordingNote = true;
        } elseif (preg_match('/^(Phương thức thanh toán|Payment Method):\s*(.*)$/iu', $line, $m)) {
            $parsedAddress['payment_method'] = trim($m[2]);
            $isRecordingNote = false;
        } elseif (preg_match('/(Digital product|Sản phẩm số)/iu', $line)) {
            $isRecordingNote = false;
            if (!$parsedAddress['shipping_address']) {
                $parsedAddress['shipping_address'] = $line;
            }
        } else {
            if ($isRecordingNote) {
                $accumulatedNotes[] = $line;
            } elseif ($parsedAddress['shipping_address'] === null) {
                $parsedAddress['shipping_address'] = $line;
            } else {
                $parsedAddress['extra_notes'][] = $line;
            }
        }
    }

    if (!empty($accumulatedNotes)) {
        $parsedAddress['customer_note'] = implode("\n", array_filter($accumulatedNotes));
    }

    // Tên các sản phẩm trong đơn để copy
    $productNamesList = $order->orderItems
        ->map(fn($item) => optional($item->product)->name . ($item->variant_name ? ' (' . $item->variant_name . ')' : ''))
        ->filter()
        ->implode(', ');

    $supportCopyText = "Mã đơn: " . ($order->order_code ?: '#' . $order->id) . "\n"
        . "Khách hàng: " . $order->customer_name . " (" . $order->customer_phone . ")\n"
        . "Sản phẩm: " . ($productNamesList ?: 'Sản phẩm #' . $order->id) . "\n"
        . "Tổng tiền: " . $order->formatted_total;
@endphp

<div class="order-detail-page">
    <div class="container" style="max-width: 1060px;">
        
        {{-- Navigation & Top Back Button --}}
        <div class="mb-3">
            <a href="{{ route('user.orders') }}" class="btn btn-sm btn-white bg-white border text-secondary rounded-pill px-3 py-1.5 shadow-sm text-decoration-none d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>{{ __('Quay lại danh sách đơn hàng') }}</span>
            </a>
        </div>

        {{-- Order Header Card --}}
        <div class="order-box">
            <div class="d-flex justify-content-between align-items-center order-header-flex">
                <div>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ __('Đơn hàng') }} #{{ $order->id }}
                        </h4>
                        @if($order->order_code)
                            <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 0.9rem;">
                                {{ $order->order_code }}
                            </span>
                            <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $order->order_code }}" title="{{ __('Sao chép mã đơn hàng') }}">
                                <i class="fa-regular fa-copy"></i>
                                <span>Copy</span>
                            </button>
                        @endif
                    </div>
                    
                    <div class="text-muted small d-flex align-items-center flex-wrap gap-3 mt-1">
                        <span>
                            <i class="fa-regular fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span>
                            <i class="fa-solid fa-tag me-1 text-secondary"></i>
                            @if($order->order_type == 'qr')
                                {{ __('Đơn TikTok Deal') }}
                            @elseif($order->order_type == 'document')
                                {{ __('Đơn Tài liệu / Ebook') }}
                            @elseif($order->order_type == 'shipping')
                                {{ __('Đơn Giao hàng vật lý') }}
                            @else
                                {{ __('Đơn Digital / Phần mềm') }}
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    @php
                        $badgeBg = match($order->status) {
                            'completed' => 'bg-success text-white',
                            'processing' => 'bg-primary text-white',
                            'pending' => 'bg-warning text-dark',
                            'cancelled' => 'bg-danger text-white',
                            'shipped', 'delivered' => 'bg-info text-white',
                            default => 'bg-secondary text-white'
                        };
                    @endphp
                    <span class="badge {{ $badgeBg }} fs-6 px-3 py-2 rounded-pill shadow-sm">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Digital Delivery Box (Nếu có bàn giao tài khoản / Key / Ghi chú từ Admin) --}}
        @if($order->delivery_account || $order->delivery_key || $order->delivery_note || ($order->status == 'completed' && $order->orderItems->contains(fn($i) => optional($i->product)->category == 'ebooks' && optional($i->product)->file_path)))
            <div class="delivery-handover-box">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-success border-opacity-25">
                    <i class="fa-solid fa-circle-check text-success fs-5"></i>
                    <h5 class="fw-bold mb-0 text-success">
                        {{ __('Thông tin bàn giao sản phẩm') }}
                    </h5>
                </div>

                {{-- Account info --}}
                @if($order->delivery_account)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold text-dark">
                                <i class="fa-solid fa-user-lock text-success me-1"></i>{{ __('Tài khoản / Mật khẩu:') }}
                            </span>
                            <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $order->delivery_account }}">
                                <i class="fa-regular fa-copy"></i>
                                <span>{{ __('Copy tài khoản') }}</span>
                            </button>
                        </div>
                        <div class="bg-white border rounded-3 p-2.5 font-monospace text-dark" style="word-break: break-all;">
                            {{ $order->delivery_account }}
                        </div>
                    </div>
                @endif

                {{-- License Key --}}
                @if($order->delivery_key)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold text-dark">
                                <i class="fa-solid fa-key text-warning me-1"></i>{{ __('Mã kích hoạt / License Key:') }}
                            </span>
                            <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $order->delivery_key }}">
                                <i class="fa-regular fa-copy"></i>
                                <span>{{ __('Copy mã key') }}</span>
                            </button>
                        </div>
                        <div class="bg-white border rounded-3 p-2.5 font-monospace text-dark" style="word-break: break-all;">
                            {{ $order->delivery_key }}
                        </div>
                    </div>
                @endif

                {{-- Admin Instruction / Delivery Note --}}
                @if($order->delivery_note)
                    <div class="mb-2">
                        <span class="small fw-bold text-dark d-block mb-1">
                            <i class="fa-solid fa-clipboard-list text-primary me-1"></i>{{ __('Hướng dẫn kích hoạt & Lưu ý:') }}
                        </span>
                        <div class="bg-white border rounded-3 p-3 text-secondary" style="white-space: pre-line; font-size: 0.92rem; line-height: 1.6;">
                            {!! nl2br(e($order->delivery_note)) !!}
                        </div>
                    </div>
                @endif

                {{-- Ebook Downloads --}}
                @foreach($order->orderItems as $item)
                    @if($order->status == 'completed' && $item->product && $item->product->category == 'ebooks' && $item->product->file_path)
                        <div class="mt-3 pt-2 border-top border-success border-opacity-25">
                            <a href="{{ route('product.download', $item->product) }}" class="btn btn-success fw-bold px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-download"></i>
                                <span>{{ __('Tải file:') }} {{ $item->product->name }}</span>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Admin Status Note --}}
        @if($order->status_note)
            <div class="admin-note-box">
                <div class="d-flex align-items-center gap-2 mb-1 text-dark fw-bold">
                    <i class="fa-solid fa-circle-exclamation text-warning fs-5"></i>
                    <span>{{ __('Thông báo từ Admin về đơn hàng:') }}</span>
                </div>
                <div class="text-secondary small mt-1" style="white-space: pre-line; line-height: 1.5;">
                    {{ $order->status_note }}
                </div>
            </div>
        @endif

        <div class="row g-3">
            {{-- Left Column: Products & Customer Details --}}
            <div class="col-lg-8">
                
                {{-- Box 1: Purchased Products --}}
                <div class="order-box">
                    <div class="order-box-title">
                        <i class="fa-solid fa-box text-primary"></i>
                        <span>{{ __('Sản phẩm trong đơn') }} ({{ $order->orderItems->sum('quantity') }})</span>
                    </div>

                    <div class="order-products-list">
                        @foreach($order->orderItems as $item)
                            <div class="order-item-card">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="order-item-img">
                                @else
                                    <div class="order-item-img d-flex align-items-center justify-content-center text-muted">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif

                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.95rem;">
                                        @if($item->product)
                                            <a href="{{ route('product.show', $item->product->slug ?? $item->product->id) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            {{ __('Sản phẩm không còn khả dụng') }}
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center flex-wrap gap-2 text-muted small">
                                        @if($item->variant_name)
                                            <span class="badge bg-light text-secondary border">
                                                {{ $item->variant_name }}
                                            </span>
                                        @endif

                                        <span>
                                            {{ __('Số lượng:') }} <strong class="text-dark">x{{ $item->quantity }}</strong>
                                        </span>

                                        <span>•</span>

                                        <span>
                                            {{ __('Đơn giá:') }} {{ $order->currency === 'USD' ? '$' . number_format($item->price, 2) : number_format($item->price, 0, ',', '.') . 'đ' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end ps-2 flex-shrink-0">
                                    <div class="fw-bold text-primary" style="font-size: 0.98rem;">
                                        @php
                                            $itemTotal = $item->price * $item->quantity;
                                        @endphp
                                        {{ $order->currency === 'USD' ? '$' . number_format($itemTotal, 2) : number_format($itemTotal, 0, ',', '.') . 'đ' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Box 2: Full Customer Information Filled at Checkout --}}
                <div class="order-box">
                    <div class="order-box-title">
                        <i class="fa-solid fa-address-card text-primary"></i>
                        <span>{{ __('Thông tin khách hàng & Ghi chú khi mua') }}</span>
                    </div>

                    {{-- Customer Identity Rows --}}
                    <div class="info-list-row">
                        <span class="info-list-label"><i class="fa-solid fa-user me-1.5 text-muted"></i>{{ __('Họ và tên:') }}</span>
                        <span class="info-list-value">{{ $order->customer_name }}</span>
                    </div>

                    <div class="info-list-row">
                        <span class="info-list-label"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ __('Số điện thoại:') }}</span>
                        <span class="info-list-value">
                            <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none text-dark me-2">
                                {{ $order->customer_phone }}
                            </a>
                            <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $order->customer_phone }}" title="{{ __('Copy số điện thoại') }}">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </span>
                    </div>

                    <div class="info-list-row">
                        <span class="info-list-label"><i class="fa-solid fa-envelope me-1.5 text-muted"></i>{{ __('Email:') }}</span>
                        <span class="info-list-value">
                            <span class="me-2">{{ $order->customer_email }}</span>
                            <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $order->customer_email }}" title="{{ __('Copy email') }}">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </span>
                    </div>

                    @if($parsedAddress['zalo'])
                        <div class="info-list-row">
                            <span class="info-list-label"><i class="fa-solid fa-comment-dots me-1.5 text-primary"></i>{{ __('Zalo liên hệ:') }}</span>
                            <span class="info-list-value text-primary fw-bold">{{ $parsedAddress['zalo'] }}</span>
                        </div>
                    @endif

                    @if($parsedAddress['facebook'])
                        <div class="info-list-row">
                            <span class="info-list-label"><i class="fa-brands fa-facebook me-1.5 text-primary"></i>{{ __('Facebook:') }}</span>
                            <span class="info-list-value">
                                <a href="{{ Str::startsWith($parsedAddress['facebook'], 'http') ? $parsedAddress['facebook'] : 'https://' . $parsedAddress['facebook'] }}" target="_blank" class="text-primary text-decoration-none">
                                    {{ $parsedAddress['facebook'] }}
                                </a>
                            </span>
                        </div>
                    @endif

                    @if($parsedAddress['payment_method'])
                        <div class="info-list-row">
                            <span class="info-list-label"><i class="fa-solid fa-wallet me-1.5 text-muted"></i>{{ __('Phương thức TT:') }}</span>
                            <span class="info-list-value">{{ $parsedAddress['payment_method'] }}</span>
                        </div>
                    @endif

                    @if($order->order_type == 'shipping' && $parsedAddress['shipping_address'])
                        <div class="info-list-row">
                            <span class="info-list-label"><i class="fa-solid fa-location-dot me-1.5 text-danger"></i>{{ __('Địa chỉ nhận hàng:') }}</span>
                            <span class="info-list-value text-start">{{ $parsedAddress['shipping_address'] }}</span>
                        </div>
                    @endif

                    {{-- Customer Note or Upgrade Account Info --}}
                    @if($parsedAddress['customer_note'])
                        <div class="customer-note-highlight">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold text-dark">
                                    <i class="fa-regular fa-pen-to-square text-primary me-1"></i>{{ __('Email nâng cấp / Ghi chú bạn đã nhập:') }}
                                </span>
                                <button type="button" class="copy-btn-mini js-copy-btn" data-copy="{{ $parsedAddress['customer_note'] }}">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>{{ __('Copy ghi chú') }}</span>
                                </button>
                            </div>
                            <div class="fw-bold text-dark" style="white-space: pre-wrap; word-break: break-word; font-size: 0.92rem; line-height: 1.5;">
                                {{ $parsedAddress['customer_note'] }}
                            </div>
                        </div>
                    @endif

                    {{-- Extra notes if any --}}
                    @if(!empty($parsedAddress['extra_notes']))
                        <div class="mt-2 p-2 bg-light rounded text-muted small" style="white-space: pre-line;">
                            <strong>{{ __('Ghi chú thêm:') }}</strong>
                            {{ implode("\n", $parsedAddress['extra_notes']) }}
                        </div>
                    @endif

                    @if(!$parsedAddress['customer_note'] && empty($parsedAddress['extra_notes']) && $order->order_type != 'shipping')
                        <div class="mt-2 text-muted small fst-italic">
                            <i class="fa-solid fa-circle-info me-1"></i>{{ __('Đơn hàng kỹ thuật số (không yêu cầu vận chuyển vật lý).') }}
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Payment Summary, Support & Tracking --}}
            <div class="col-lg-4">
                
                {{-- Payment Summary Box --}}
                <div class="order-box">
                    <div class="order-box-title">
                        <i class="fa-solid fa-receipt text-primary"></i>
                        <span>{{ __('Chi tiết thanh toán') }}</span>
                    </div>

                    @php
                        $subtotal = $order->orderItems->sum(fn($i) => $i->price * $i->quantity);
                        $discount = (float) ($order->discount_amount ?? 0);
                    @endphp

                    <div class="info-list-row">
                        <span class="info-list-label">{{ __('Tạm tính:') }}</span>
                        <span class="info-list-value text-muted">
                            {{ $order->currency === 'USD' ? '$' . number_format($subtotal, 2) : number_format($subtotal, 0, ',', '.') . 'đ' }}
                        </span>
                    </div>

                    @if($discount > 0 || $order->coupon_code)
                        <div class="info-list-row">
                            <span class="info-list-label text-success">
                                {{ __('Giảm giá:') }}
                                @if($order->coupon_code)
                                    <small class="badge bg-success-subtle text-success border border-success-subtle ms-1">{{ $order->coupon_code }}</small>
                                @endif
                            </span>
                            <span class="info-list-value text-success">
                                -{{ $order->currency === 'USD' ? '$' . number_format($discount, 2) : number_format($discount, 0, ',', '.') . 'đ' }}
                            </span>
                        </div>
                    @endif

                    <div class="info-list-row pt-2 mt-1 border-top border-2">
                        <span class="info-list-label fw-bold text-dark fs-6">{{ __('Tổng tiền:') }}</span>
                        <span class="info-list-value fw-bold text-primary fs-5">
                            {{ $order->formatted_total }}
                        </span>
                    </div>
                </div>

                {{-- Fast Support Actions Box --}}
                <div class="order-box">
                    <div class="order-box-title">
                        <i class="fa-solid fa-headset text-primary"></i>
                        <span>{{ __('Hỗ trợ đơn hàng') }}</span>
                    </div>

                    <p class="text-muted small mb-3" style="line-height: 1.45;">
                        {{ __('Nếu cần hỗ trợ hoặc kích hoạt nhanh, bạn có thể copy mã đơn hàng và nhắn qua Zalo/Telegram Admin.') }}
                    </p>

                    <div class="d-flex flex-column gap-2">
                        {{-- Copy Order Info --}}
                        <button type="button" class="support-btn-pill btn-support-copy js-copy-btn" data-copy="{{ $supportCopyText }}">
                            <i class="fa-regular fa-copy text-primary"></i>
                            <span>{{ __('Copy mã & thông tin đơn') }}</span>
                        </button>

                        {{-- Direct Zalo Admin --}}
                        <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" rel="noopener noreferrer" class="support-btn-pill btn-zalo-direct">
                            <i class="fa-solid fa-comment-dots"></i>
                            <span>{{ __('Nhắn Zalo Admin') }}</span>
                        </a>

                        {{-- Direct Telegram Admin --}}
                        @if(\App\Helpers\SupportHelper::getTelegramLink())
                            <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" rel="noopener noreferrer" class="support-btn-pill btn-telegram-direct">
                                <i class="fa-brands fa-telegram"></i>
                                <span>{{ __('Telegram Admin') }}</span>
                            </a>
                        @endif

                        {{-- Zalo Group --}}
                        <a href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank" rel="noopener noreferrer" class="support-btn-pill btn-light border text-primary">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ __('Tham gia nhóm Zalo') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Clean Simple Order Tracking Timeline --}}
                <div class="order-box">
                    <div class="order-box-title">
                        <i class="fa-solid fa-timeline text-primary"></i>
                        <span>{{ __('Tiến trình xử lý') }}</span>
                    </div>

                    <div class="basic-timeline mt-2">
                        {{-- Step 1: Placed --}}
                        <div class="timeline-item active">
                            <div class="timeline-dot"></div>
                            <div class="fw-bold text-dark small">{{ __('Đã đặt hàng') }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        {{-- Step 2: Processing --}}
                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="fw-bold text-dark small">{{ __('Đang xử lý') }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">{{ __('Hệ thống kiểm tra & chuẩn bị giao') }}</div>
                        </div>

                        {{-- Shipping Step (Only for physical orders) --}}
                        @if($order->order_type == 'shipping')
                            <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'active' : '' }}">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold text-dark small">{{ __('Đang giao hàng') }}</div>
                                <div class="text-muted" style="font-size: 0.8rem;">{{ __('Đơn vị vận chuyển đang phát') }}</div>
                            </div>
                        @endif

                        {{-- Completed Step --}}
                        @if($order->status == 'completed')
                            <div class="timeline-item active">
                                <div class="timeline-dot bg-success" style="box-shadow: 0 0 0 2px #22c55e;"></div>
                                <div class="fw-bold text-success small">{{ __('Đã hoàn thành') }}</div>
                                <div class="text-muted" style="font-size: 0.8rem;">{{ __('Đơn hàng đã bàn giao thành công') }}</div>
                            </div>
                        @elseif($order->status == 'cancelled')
                            <div class="timeline-item cancelled">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold text-danger small">{{ __('Đã hủy đơn') }}</div>
                                <div class="text-muted" style="font-size: 0.8rem;">{{ __('Đơn hàng đã bị hủy') }}</div>
                            </div>
                        @else
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold text-muted small">{{ __('Hoàn tất bàn giao') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Universal One-Click Copy Buttons
        const copyButtons = document.querySelectorAll('.js-copy-btn');
        copyButtons.forEach(btn => {
            btn.addEventListener('click', async function (e) {
                e.preventDefault();
                const textToCopy = this.getAttribute('data-copy');
                if (!textToCopy) return;

                const originalHtml = this.innerHTML;
                let success = false;

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(textToCopy);
                        success = true;
                    } else {
                        const ta = document.createElement('textarea');
                        ta.value = textToCopy;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.focus();
                        ta.select();
                        success = document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                } catch (err) {
                    console.error('Copy failed:', err);
                }

                if (success) {
                    this.classList.add('copied');
                    this.innerHTML = '<i class="fa-solid fa-check"></i> <span>{{ __("Đã chép") }}</span>';
                    setTimeout(() => {
                        this.classList.remove('copied');
                        this.innerHTML = originalHtml;
                    }, 1800);
                }
            });
        });
    });
</script>
@endpush
