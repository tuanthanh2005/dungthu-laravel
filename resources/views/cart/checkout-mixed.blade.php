@extends('layouts.app')

@section('title', __('Thanh Toán') . ' - DungThu.com')

@php
    $formatPrice = function($amount) {
        if (app()->getLocale() === 'en') {
            return '$' . number_format($amount, 2);
        }
        return number_format($amount, 0, ',', '.') . 'đ';
    };
@endphp

@push('styles')
<style>
    .product-type-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .digital-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .physical-badge {
        background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
        color: white;
    }
    .payment-section {
        border: 2px dashed #dee2e6;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Premium Custom Payment Tabs */
    .payment-tabs {
        display: flex;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 5px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .payment-tab-btn {
        flex: 1;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        padding: 8px 5px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    .payment-tab-btn:hover {
        color: white;
        background: rgba(255, 255, 255, 0.05);
    }
    .payment-tab-btn.active {
        background: white;
        color: #764ba2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .payment-tab-content {
        display: none;
    }
    .payment-tab-content.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .copy-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .copy-btn:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    /* QR Scanner animation */
    .qr-code-box {
        position: relative;
        overflow: hidden;
        display: inline-block;
        padding: 10px;
        background: #fff;
        border-radius: 8px;
    }
    .qr-scanner-line {
        position: absolute;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, transparent, #28a745 80%, transparent);
        box-shadow: 0 0 8px #28a745;
        animation: scan 3s linear infinite;
        z-index: 5;
    }
    @keyframes scan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }
</style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-shopping-cart text-primary me-2"></i>{{ __('Thanh Toán') }}
                </h1>
                <p class="text-muted">{{ __('Đơn hàng của bạn bao gồm cả sản phẩm số và sản phẩm vật lý') }}</p>
            </div>

            <!-- Step 1: Customer Details -->
            <div class="row justify-content-center" id="checkout-step-1">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-circle me-2 text-primary"></i>{{ __('Thông tin của bạn') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method_input" value="{{ app()->getLocale() === 'en' ? 'crypto' : 'vietqr' }}">
                                <input type="hidden" name="order_code" value="{{ $orderCode }}">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>{{ __('Họ và tên') }}
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>{{ __('Email') }}
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                        <small class="text-muted">{{ __('Để nhận mã sản phẩm số') }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-phone me-2 text-primary"></i>{{ __('Số điện thoại') }}
                                        </label>
                                        <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                    </div>
                                </div>
                                

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-comment-dots me-2 text-primary"></i>Zalo <small class="fw-normal text-muted">({{ __('Không bắt buộc') }})</small>
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="customer_zalo" placeholder="{{ __('Nhập số Zalo của bạn') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fab fa-facebook me-2 text-primary"></i>Link Facebook <small class="fw-normal text-muted">({{ __('Không bắt buộc') }})</small>
                                        </label>
                                        <input type="url" class="form-control form-control-lg" name="customer_facebook" placeholder="{{ __('Ví dụ: https://facebook.com/username') }}">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-success"></i>{{ __('Địa chỉ giao hàng') }}
                                        <span class="badge bg-success ms-2">{{ __('Bắt buộc') }}</span>
                                    </label>
                                    <textarea class="form-control form-control-lg" 
                                              name="customer_address" 
                                              rows="3" 
                                              placeholder="{{ __('Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố') }}"
                                              required></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('Địa chỉ để giao sản phẩm vật lý (sản phẩm số sẽ gửi qua email)') }}
                                    </small>
                                </div>
                            </form>

                            <button type="button" id="btn-proceed-payment" class="btn btn-primary w-100 btn-lg rounded-pill shadow py-3 fw-bold mt-4">
                                {{ __('Tiến hành thanh toán') }} <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Hướng dẫn thanh toán -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-info-circle me-2"></i>{{ __('Hướng dẫn thanh toán') }}
                        </h6>
                        <ol class="mb-0 ps-3">
                            <li>{{ __('Nhập đầy đủ thông tin và địa chỉ giao hàng') }}</li>
                            <li>{{ __('Nhấn "Tiến hành thanh toán"') }}</li>
                            <li>{{ __('Quét mã QR hoặc chuyển khoản theo thông tin hiển thị') }}</li>
                            <li>{{ __('Hệ thống sẽ tự động xác nhận đơn hàng sau khi nhận được thanh toán') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Step 2: Summary & Payment -->
            <div class="row g-4 d-none" id="checkout-step-2">
                <!-- Summary Card -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #1b9aaa 0%, #06d6a0 100%);">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-check me-2"></i>{{ __('Thông tin đơn hàng') }}
                            </h5>
                        </div>
                        <div class="card-body" style="font-size: 0.95rem; line-height: 1.6;">
                            <div class="mb-3">
                                <small class="text-muted d-block">{{ __('Họ và tên người nhận:') }}</small>
                                <strong id="summary-name" class="text-dark"></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">{{ __('Email:') }}</small>
                                <strong id="summary-email" class="text-dark"></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">{{ __('Số điện thoại:') }}</small>
                                <strong id="summary-phone" class="text-dark"></strong>
                            </div>
                            <div class="mb-3" id="summary-zalo-wrapper">
                                <small class="text-muted d-block">{{ __('Số Zalo:') }}</small>
                                <strong id="summary-zalo" class="text-dark"></strong>
                            </div>
                            <div class="mb-3" id="summary-facebook-wrapper">
                                <small class="text-muted d-block">{{ __('Facebook:') }}</small>
                                <strong id="summary-facebook" class="text-dark"></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">{{ __('Địa chỉ nhận hàng:') }}</small>
                                <strong id="summary-address" class="text-dark" style="white-space: pre-line;"></strong>
                            </div>

                            <hr>
                            <div class="mb-2">
                                <small class="text-muted d-block">{{ __('Mã giao dịch:') }}</small>
                                <strong class="text-primary font-monospace">{{ $orderCode }}</strong>
                            </div>

                            <div class="mt-4">
                                <button type="button" id="btn-back-to-step-1" class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-bold">
                                    <i class="fas fa-arrow-left me-2"></i>{{ __('Quay lại sửa thông tin') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Payment Card & Checkout Submit -->
                <div class="col-md-7">
                    <!-- Đơn hàng -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-shopping-bag me-2"></i>{{ __('Chi tiết đơn hàng') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @php 
                            $total = 0;
                            $digitalProducts = [];
                            $physicalProducts = [];
                            @endphp
                            
                            @foreach($cart as $id => $details)
                            @php 
                            $total += $details['price'] * $details['quantity'];
                            $product = \App\Models\Product::find($id);
                            if($product && $product->delivery_type === 'digital') {
                                $digitalProducts[] = $details;
                            } else {
                                $physicalProducts[] = $details;
                            }
                            @endphp
                            @endforeach

                            @if(count($digitalProducts) > 0)
                            <div class="payment-section">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-cloud-download-alt me-2"></i>{{ __('Sản phẩm số') }}
                                </h6>
                                @foreach($digitalProducts as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-break" style="max-width: 75%;">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <strong class="text-nowrap">{{ $formatPrice($item['price'] * $item['quantity']) }}</strong>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if(count($physicalProducts) > 0)
                            <div class="payment-section">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-box me-2"></i>{{ __('Sản phẩm vật lý') }}
                                </h6>
                                @foreach($physicalProducts as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-break" style="max-width: 75%;">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <strong class="text-nowrap">{{ $formatPrice($item['price'] * $item['quantity']) }}</strong>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            <hr>
                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-2 small text-muted">{{ __('Mã giảm giá (nếu có)') }}</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="coupon_code" class="form-control" placeholder="{{ __('Nhập mã giảm giá') }}" value="{{ $couponCode ?? '' }}" {{ isset($couponCode) ? 'disabled' : '' }}>
                                    <button class="btn btn-primary" type="button" id="apply-coupon-btn" style="{{ isset($couponCode) ? 'display:none;' : '' }}">{{ __('Áp dụng') }}</button>
                                    <button class="btn btn-danger" type="button" id="remove-coupon-btn" style="{{ isset($couponCode) ? '' : 'display:none;' }}">{{ __('Hủy') }}</button>
                                </div>
                                <div id="coupon-feedback" class="small mt-1 d-none text-danger"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-2 {{ isset($discountAmount) && $discountAmount > 0 ? '' : 'd-none' }}" id="discount-row">
                                <span class="text-danger fw-bold">{{ __('Giảm giá:') }}</span>
                                <strong class="text-danger" id="discount-display">-{{ $formatPrice($discountAmount ?? 0) }}</strong>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">{{ __('Tổng cộng:') }}</h5>
                                <h5 class="mb-0 text-primary" id="checkout-total-display">{{ $formatPrice($finalTotal ?? $total) }}</h5>
                            </div>
                            
                            <button type="submit" form="checkout-form" id="checkout-submit-btn" class="btn btn-primary w-100 btn-lg rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>{{ __('Xác nhận đặt hàng') }}
                            </button>
                        </div>
                    </div>

                    <!-- QR Payment -->
                    <div class="card border-0 shadow-sm text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; overflow: hidden;">
                        <div class="card-body p-4">
                            <!-- Navigation Tabs -->
                            <div class="payment-tabs">
                                @if(app()->getLocale() !== 'en')
                                <button type="button" class="payment-tab-btn active" data-target="vietqr-panel" data-method="vietqr">
                                    <i class="fas fa-university me-1"></i> VietQR
                                </button>
                                @endif
                                <button type="button" class="payment-tab-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-target="crypto-panel" data-method="crypto">
                                    <i class="fas fa-coins me-1"></i> Crypto
                                </button>
                                <button type="button" class="payment-tab-btn" data-target="binance-panel" data-method="binance_uid">
                                    <i class="fab fa-bitcoin me-1"></i> Binance
                                </button>
                                <button type="button" class="payment-tab-btn" data-target="paypal-panel" data-method="paypal">
                                    <i class="fab fa-paypal me-1"></i> PayPal
                                </button>
                            </div>

                            @if(app()->getLocale() !== 'en')
                            <!-- PANEL 1: VietQR -->
                            <div id="vietqr-panel" class="payment-tab-content active">
                                <h6 class="fw-bold mb-2">{{ __('Thanh toán qua QR Code') }}</h6>
                                <div class="bg-white p-3 rounded mb-3 position-relative qr-code-box" style="display: inline-block;">
                                    <div class="qr-scanner-line" id="qr-scanner-line"></div>
                                    <img src="https://img.vietqr.io/image/{{ config('services.vietqr.bank_code') }}-{{ config('services.vietqr.account_number') }}-print2D.png?amount={{ $finalTotal ?? $total ?? 0 }}&addInfo={{ urlencode(config('services.vietqr.add_info') . ' ' . $orderCode) }}&accountName={{ urlencode(config('services.vietqr.account_name')) }}" 
                                         alt="QR Code" 
                                         id="qr-code-image"
                                         style="max-width: 200px;">
                                    <div id="success-watermark" class="position-absolute top-50 start-50 translate-middle d-none" style="background: rgba(40, 167, 69, 0.95); color: white; padding: 10px 20px; border-radius: 20px; font-weight: bold; border: 3px solid white; transform: translate(-50%, -50%) rotate(-10deg) !important; font-size: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 10; width: max-content;">
                                        <i class="fas fa-check-circle me-2"></i>ĐÃ THANH TOÁN
                                    </div>
                                    <div id="expired-watermark" class="position-absolute top-50 start-50 translate-middle d-none" style="background: rgba(220, 53, 69, 0.95); color: white; padding: 10px 20px; border-radius: 20px; font-weight: bold; border: 3px solid white; transform: translate(-50%, -50%) rotate(-10deg) !important; font-size: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 10; width: max-content;">
                                        <i class="fas fa-times-circle me-2"></i>HẾT HẠN
                                    </div>
                                </div>
                                <!-- Đồng hồ đếm ngược 5 phút hết hạn thanh toán -->
                                <div class="alert alert-danger py-2 mb-3 border-0 rounded text-white text-center" style="background: rgba(220, 53, 69, 0.25); font-size: 0.8rem; margin: 0 10px;">
                                    <i class="far fa-clock me-2"></i>{{ __('Thời gian thanh toán còn lại: ') }}<strong id="expiry-timer">05:00</strong>
                                </div>
                                <!-- Trạng thái quét thanh toán tự động -->
                                <div id="payment-status-notice" class="alert alert-warning py-2 mb-3 border-0 rounded text-white text-center" style="background: rgba(255, 193, 7, 0.2); font-size: 0.8rem; margin: 0 10px;">
                                    <span class="spinner-border spinner-border-sm me-2 text-warning animate-spin" role="status" aria-hidden="true"></span>
                                    <span>Hệ thống đang quét giao dịch tự động...</span>
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.9rem;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="opacity-75">{{ __('Ngân hàng:') }}</small>
                                            <div class="fw-bold">{{ config('services.vietqr.bank_name') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="opacity-75">{{ __('STK:') }}</small>
                                            <div class="fw-bold">{{ config('services.vietqr.account_number') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="opacity-75">{{ __('Chủ tài khoản:') }}</small>
                                            <div class="fw-bold">{{ config('services.vietqr.account_name') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="opacity-75">{{ __('Số tiền:') }}</small>
                                            <div class="fw-bold text-warning" id="transfer-amount-display">{{ $formatPrice($finalTotal ?? $total ?? 0) }}</div>
                                        </div>
                                        <div class="col-12">
                                            <small class="opacity-75">{{ __('Nội dung:') }}</small>
                                            <div class="fw-bold">{{ config('services.vietqr.add_info') }} {{ $orderCode }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- PANEL 2: Crypto Wallet -->
                            <div id="crypto-panel" class="payment-tab-content {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                <h6 class="fw-bold mb-2">{{ __('Thanh toán qua Ví Crypto (USDT,...)') }}</h6>
                                <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9" 
                                         alt="Crypto QR" 
                                         style="max-width: 180px;">
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.85rem;">
                                    <div class="mb-2">
                                        <small class="opacity-75 d-block">{{ __('Địa chỉ ví (Wallet Address):') }}</small>
                                        <div class="d-flex align-items-center justify-content-between mt-1" style="background: rgba(0,0,0,0.2); padding: 5px; border-radius: 5px;">
                                            <code class="text-warning text-break" style="font-size: 0.8rem; font-family: monospace;">0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9</code>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9', this)">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 8px; line-height: 1.4;">
                                        @if(app()->getLocale() === 'en')
                                            Please let us know cryptocurrency & network. Contact us after sending.
                                        @else
                                            <strong>English:</strong> Please let me know cryptocurrency & network. Contact us after sending.<br>
                                            <strong>{{ __("Lưu ý") }}:</strong> Vui lòng cho biết loại tiền & mạng lưới. Liên hệ sau khi thanh toán.
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL 3: Binance UID -->
                            <div id="binance-panel" class="payment-tab-content">
                                <h6 class="fw-bold mb-2">{{ __('Thanh toán qua Binance Pay / UID') }}</h6>
                                <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=490696268" 
                                         alt="Binance Pay QR" 
                                         style="max-width: 180px;">
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.85rem;">
                                    <div class="mb-2">
                                        <small class="opacity-75 d-block">Binance UID:</small>
                                        <div class="d-flex align-items-center justify-content-between mt-1" style="background: rgba(0,0,0,0.2); padding: 5px; border-radius: 5px;">
                                            <span class="text-warning fw-bold">490696268</span>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('490696268', this)">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 8px; line-height: 1.4;">
                                        @if(app()->getLocale() === 'en')
                                            Once the payment is sent, please contact us.
                                        @else
                                            <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                            <strong>{{ __("Lưu ý") }}:</strong> Sau khi chuyển khoản thành công, hãy liên hệ với chúng tôi.
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL 4: PayPal -->
                            <div id="paypal-panel" class="payment-tab-content">
                                <h6 class="fw-bold mb-2">PayPal</h6>
                                <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=https://paypal.me/{{ config('services.paypal.username') }}" 
                                         alt="PayPal QR" 
                                         style="max-width: 180px;">
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.85rem;">
                                    <div class="mb-2">
                                        <small class="opacity-75 d-block">PayPal Email:</small>
                                        <div class="d-flex align-items-center justify-content-between mt-1" style="background: rgba(0,0,0,0.2); padding: 5px; border-radius: 5px;">
                                            <span class="text-warning fw-bold">{{ config('services.paypal.email') }}</span>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('{{ config('services.paypal.email') }}', this)">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="opacity-75 d-block">PayPal.Me Link:</small>
                                        <div class="d-flex align-items-center justify-content-between mt-1" style="background: rgba(0,0,0,0.2); padding: 5px; border-radius: 5px;">
                                            <span class="text-warning fw-bold" style="font-size: 0.8rem; word-break: break-all;">https://paypal.me/{{ config('services.paypal.username') }}</span>
                                            <a href="https://paypal.me/{{ config('services.paypal.username') }}" target="_blank" class="btn btn-sm btn-info text-white text-nowrap ms-1" style="font-size: 0.75rem; padding: 2px 6px;">
                                                <i class="fas fa-external-link-alt"></i> Open
                                            </a>
                                        </div>
                                    </div>

                                    @php
                                        $rate = doubleval(\App\Models\SiteSetting::getValue('usd_exchange_rate', '25000'));
                                        if (app()->getLocale() === 'en') {
                                            $totalAmountUsd = $finalTotal ?? $total ?? 0;
                                            $totalAmountVnd = $totalAmountUsd * $rate;
                                        } else {
                                            $totalAmountVnd = $finalTotal ?? $total ?? 0;
                                            $totalAmountUsd = $totalAmountVnd / $rate;
                                        }
                                    @endphp
                                    <div class="mb-2 p-2 rounded text-center" style="background: rgba(0,0,0,0.15);">
                                        <small class="opacity-75 d-block" style="font-size: 0.75rem;">{{ __('Số tiền cần thanh toán / Amount to Pay:') }}</small>
                                        @if(app()->getLocale() === 'en')
                                            <strong class="text-warning font-weight-bold">${{ number_format($totalAmountUsd, 2) }} USD</strong>
                                        @else
                                            <strong class="text-warning font-weight-bold">{{ number_format($totalAmountVnd, 0, ',', '.') }}đ</strong>
                                            <span class="d-block text-white opacity-75 mt-1" style="font-size: 0.75rem;">(≈ ${{ number_format($totalAmountUsd, 2) }} USD)</span>
                                        @endif
                                    </div>

                                    <div style="font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 8px; line-height: 1.4;">
                                        @if(app()->getLocale() === 'en')
                                            Once the payment is sent, please contact us.
                                        @else
                                            <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                            <strong>{{ __("Lưu ý") }}:</strong> Sau khi thanh toán thành công, hãy liên hệ với chúng tôi.
                                        @endif
                                    </div>
                                </div>
                            </div>
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
    // Tab switching logic
    document.querySelectorAll('.payment-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.payment-tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
            
            const method = this.getAttribute('data-method');
            document.getElementById('payment_method_input').value = method;
        });
    });

    // Copy to clipboard utility
    window.copyToClipboard = function(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btnElement.style.background = 'rgba(40, 167, 69, 0.8)';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.style.background = '';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    };

    // Coupon Code logic
    const applyBtn = document.getElementById('apply-coupon-btn');
    const removeBtn = document.getElementById('remove-coupon-btn');
    const couponInput = document.getElementById('coupon_code');
    const feedbackEl = document.getElementById('coupon-feedback');
    const discountRow = document.getElementById('discount-row');
    const discountDisplay = document.getElementById('discount-display');
    const totalDisplay = document.getElementById('checkout-total-display');
    const qrImage = document.getElementById('qr-code-image');
    const transferAmountDisplay = document.getElementById('transfer-amount-display');

    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const code = couponInput.value.trim();
            if (!code) {
                showFeedback('{{ __("Vui lòng nhập mã giảm giá!") }}', 'text-danger');
                return;
            }

            applyBtn.disabled = true;
            applyBtn.textContent = '...';

            fetch('{{ route('coupons.apply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw err; });
                }
                return res.json();
            })
            .then(data => {
                showFeedback(data.message, 'text-success');
                couponInput.disabled = true;
                applyBtn.style.display = 'none';
                removeBtn.style.display = 'inline-block';
                
                // Update pricing row
                discountDisplay.textContent = '-' + formatNumber(data.discount_amount);
                discountRow.classList.remove('d-none');
                totalDisplay.textContent = formatNumber(data.final_total);
                if (transferAmountDisplay) {
                    transferAmountDisplay.textContent = formatNumber(data.final_total);
                }
                
                // Update QR image src
                if (qrImage) {
                    const originalSrc = qrImage.src;
                    qrImage.src = originalSrc.replace(/amount=\d+/, 'amount=' + data.final_total);
                }
            })
            .catch(err => {
                showFeedback(err.message || '{{ __("Mã giảm giá không hợp lệ!") }}', 'text-danger');
            })
            .finally(() => {
                applyBtn.disabled = false;
                applyBtn.textContent = '{{ __("Áp dụng") }}';
            });
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            removeBtn.disabled = true;
            
            fetch('{{ route('coupons.remove') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                showFeedback(data.message, 'text-muted');
                couponInput.disabled = false;
                couponInput.value = '';
                applyBtn.style.display = 'inline-block';
                removeBtn.style.display = 'none';
                
                // Reset pricing row
                discountRow.classList.add('d-none');
                totalDisplay.textContent = formatNumber(data.final_total);
                if (transferAmountDisplay) {
                    transferAmountDisplay.textContent = formatNumber(data.final_total);
                }
                
                // Update QR image src
                if (qrImage) {
                    const originalSrc = qrImage.src;
                    qrImage.src = originalSrc.replace(/amount=\d+/, 'amount=' + data.final_total);
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                removeBtn.disabled = false;
            });
        });
    }

    function showFeedback(msg, className) {
        feedbackEl.textContent = msg;
        feedbackEl.className = 'small mt-1 ' + className;
        feedbackEl.classList.remove('d-none');
    }

    function formatNumber(num) {
        const locale = '{{ app()->getLocale() }}';
        if (locale === 'en') {
            return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        return num.toLocaleString('vi-VN') + 'đ';
    }

    // SePay Webhook Auto-check and manual polling logic
    const orderCode = '{{ $orderCode }}';
    let checkInterval = null;
    let countdownInterval = null;
    let expiryInterval = null;
    let paymentVerified = false;
    let secondsLeft = 60;
    let expirySeconds = 300; // 5 minutes expiration

    const submitBtn = document.getElementById('checkout-submit-btn');
    const paymentMethodInput = document.getElementById('payment_method_input');
    const originalSubmitBtnHtml = submitBtn.innerHTML;
    let confirmBtnMode = 'submit'; // 'submit', 'countdown', 'webhook'

    // Step 1 to Step 2 Transition
    document.getElementById('btn-proceed-payment').addEventListener('click', function(e) {
        const name = document.getElementsByName('customer_name')[0].value.trim();
        const email = document.getElementsByName('customer_email')[0].value.trim();
        const phone = document.getElementsByName('customer_phone')[0].value.trim();
        const address = document.getElementsByName('customer_address')[0].value.trim();
        const zalo = document.getElementsByName('customer_zalo')[0].value.trim();
        const facebook = document.getElementsByName('customer_facebook')[0].value.trim();

        // Form HTML5 Validation
        const form = document.getElementById('checkout-form');
        if (!form.reportValidity()) {
            return;
        }

        // Populate Step 2 Summary details
        document.getElementById('summary-name').textContent = name;
        document.getElementById('summary-email').textContent = email;
        document.getElementById('summary-phone').textContent = phone;
        document.getElementById('summary-address').textContent = address;

        const zaloWrapper = document.getElementById('summary-zalo-wrapper');
        if (zalo) {
            document.getElementById('summary-zalo').textContent = zalo;
            zaloWrapper.classList.remove('d-none');
        } else {
            zaloWrapper.classList.add('d-none');
        }

        const facebookWrapper = document.getElementById('summary-facebook-wrapper');
        if (facebook) {
            document.getElementById('summary-facebook').textContent = facebook;
            facebookWrapper.classList.remove('d-none');
        } else {
            facebookWrapper.classList.add('d-none');
        }

        // Toggle screens
        document.getElementById('checkout-step-1').classList.add('d-none');
        document.getElementById('checkout-step-2').classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Start payment verification & polling & 5-min timer
        startPaymentVerification();
    });

    // Step 2 to Step 1 Back Navigation
    document.getElementById('btn-back-to-step-1').addEventListener('click', function() {
        // Toggle screens
        document.getElementById('checkout-step-2').classList.add('d-none');
        document.getElementById('checkout-step-1').classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Clear intervals to save resources
        clearInterval(countdownInterval);
        clearInterval(checkInterval);
        clearInterval(expiryInterval);
    });

    function updateSubmitButtonState() {
        if (paymentVerified) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Đã xác nhận thanh toán!';
            submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
            submitBtn.type = 'button';
            return;
        }

        if (expirySeconds <= 0) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-times-circle me-2"></i>Giao dịch đã hết hạn';
            submitBtn.style.background = 'linear-gradient(135deg, #dc3545 0%, #bd2130 100%)';
            submitBtn.type = 'button';
            return;
        }

        const selectedMethod = paymentMethodInput.value;
        if (selectedMethod === 'vietqr') {
            confirmBtnMode = 'countdown';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-sync fa-spin me-2"></i>Đang kiểm tra thanh toán tự động...';
            submitBtn.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
            submitBtn.type = 'button';
        } else {
            confirmBtnMode = 'submit';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalSubmitBtnHtml;
            submitBtn.style.background = ''; // reset to CSS default
            submitBtn.type = 'submit';
        }
    }

    // Tab switching event listener extension
    document.querySelectorAll('.payment-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            setTimeout(updateSubmitButtonState, 50);
        });
    });

    // Starts countdown and polling after step 2 is active
    function startPaymentVerification() {
        if (paymentVerified) return;

        // Reset state
        expirySeconds = 300;
        updateSubmitButtonState();

        // 1. Five minutes payment expiration timer
        const timerEl = document.getElementById('expiry-timer');
        if (timerEl) {
            timerEl.textContent = "05:00";
        }
        if (expiryInterval) clearInterval(expiryInterval);
        expiryInterval = setInterval(() => {
            expirySeconds--;
            if (expirySeconds <= 0) {
                handlePaymentExpiry();
            } else {
                const mins = Math.floor(expirySeconds / 60).toString().padStart(2, '0');
                const secs = (expirySeconds % 60).toString().padStart(2, '0');
                if (timerEl) {
                    timerEl.textContent = `${mins}:${secs}`;
                }
            }
        }, 1000);

        // 2. Auto polling (runs every 5 seconds)
        if (checkInterval) clearInterval(checkInterval);
        checkInterval = setInterval(pollPaymentStatus, 5000);
    }

    // Handles payment expiration
    function handlePaymentExpiry() {
        clearInterval(checkInterval);
        clearInterval(expiryInterval);
        expirySeconds = 0;

        // Show red expired watermark on QR
        const expiredWatermark = document.getElementById('expired-watermark');
        if (expiredWatermark) expiredWatermark.classList.remove('d-none');

        // Hide scanner line
        const scannerLine = document.getElementById('qr-scanner-line');
        if (scannerLine) scannerLine.classList.add('d-none');

        // Update status notice
        const statusNotice = document.getElementById('payment-status-notice');
        if (statusNotice) {
            statusNotice.className = 'alert alert-danger py-2 mb-3 border-0 rounded text-white text-center';
            statusNotice.style.background = 'rgba(220, 53, 69, 0.2)';
            statusNotice.innerHTML = '<i class="fas fa-times-circle me-2 text-danger"></i>Thời gian thanh toán đã hết hạn! Vui lòng quay lại sửa đổi thông tin để làm mới đơn hàng.';
        }

        // Disable confirm button
        updateSubmitButtonState();
    }

    // Function to handle successful payment auto-trigger
    function handlePaymentSuccess(message = '') {
        paymentVerified = true;
        clearInterval(checkInterval);
        clearInterval(expiryInterval);

        // Show green success watermark on QR
        const watermark = document.getElementById('success-watermark');
        if (watermark) watermark.classList.remove('d-none');

        // Hide scanner line
        const scannerLine = document.getElementById('qr-scanner-line');
        if (scannerLine) scannerLine.classList.add('d-none');

        // Show status notice success
        const statusNotice = document.getElementById('payment-status-notice');
        if (statusNotice) {
            statusNotice.className = 'alert alert-success py-2 mb-3 border-0 rounded text-white text-center';
            statusNotice.style.background = 'rgba(40, 167, 69, 0.2)';
            statusNotice.innerHTML = '<i class="fas fa-check-circle me-2 text-success"></i>Thanh toán thành công! Đang tự động tạo đơn hàng...';
        }

        // Disable button completely and show success
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Đã xác nhận thanh toán!';
        submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
        submitBtn.type = 'button';

        // Auto submit checkout form to complete
        setTimeout(() => {
            const form = document.getElementById('checkout-form');
            if (form.reportValidity()) {
                form.submit();
            } else {
                alert(message || 'Thanh toán của bạn đã được ghi nhận thành công! Vui lòng điền đầy đủ thông tin liên hệ và nhấn Xác nhận đặt hàng.');
                // Adjust button to allow final submit
                confirmBtnMode = 'submit';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Xác nhận đặt hàng ngay';
                submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
                submitBtn.type = 'submit';
            }
        }, 1500);
    }

    // Polling function
    function pollPaymentStatus() {
        if (paymentVerified) return;

        fetch(`/api/payment/check-status/${orderCode}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    handlePaymentSuccess('Thanh toán thành công! Vui lòng điền thông tin để hoàn tất.');
                } else if (data.status === 'expired') {
                    handlePaymentExpiry();
                }
            })
            .catch(err => console.error('Polling error:', err));
    }

    // Initialize the button state
    updateSubmitButtonState();
</script>
@endpush
