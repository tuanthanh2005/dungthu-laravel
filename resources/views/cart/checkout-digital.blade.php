@extends('layouts.app')

@section('title', __('Thanh Toán QR') . ' - DungThu.com')

@push('styles')
<style>
    .qr-payment-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        text-align: center;
    }
    .qr-code-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        display: inline-block;
        margin: 20px 0;
    }
    .payment-info {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
    }

    /* Premium Custom Payment Tabs */
    .payment-tabs {
        display: flex;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 5px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .payment-tab-btn {
        flex: 1;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        padding: 10px 5px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
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
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
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

    /* Premium Confirmation Modal */
    .confirm-modal-content {
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        background: #ffffff;
    }
    .confirm-modal-header {
        border-bottom: none;
        padding: 20px 24px 0;
        display: flex;
        justify-content: flex-end;
    }
    .confirm-modal-header .btn-close {
        background-color: #f3f4f6;
        padding: 8px;
        border-radius: 50%;
        font-size: 0.75rem;
        transition: all 0.2s ease;
        margin: 0;
        opacity: 0.8;
    }
    .confirm-modal-header .btn-close:hover {
        background-color: #e5e7eb;
        transform: rotate(90deg);
        opacity: 1;
    }
    .confirm-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 193, 7, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 4px solid rgba(255, 193, 7, 0.05);
        animation: pulse-warning 2s infinite;
    }
    @keyframes pulse-warning {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(255, 193, 7, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
        }
    }
    .confirm-icon-wrapper i {
        font-size: 2.5rem;
        color: #ffc107;
    }
    .confirm-modal-title {
        font-size: 1.35rem;
        font-weight: 750;
        color: #1f2937;
        margin-bottom: 12px;
    }
    .confirm-modal-text {
        font-size: 0.92rem;
        color: #4b5563;
        line-height: 1.6;
        padding: 0 15px;
    }
    .confirm-modal-footer {
        border-top: none;
        padding: 15px 24px 25px;
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .btn-confirm-cancel {
        background-color: #f3f4f6;
        color: #4b5563;
        border: none;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        flex: 1;
        font-size: 0.9rem;
    }
    .btn-confirm-cancel:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }
    .btn-confirm-ok {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.2);
        transition: all 0.2s ease;
        flex: 1;
        font-size: 0.9rem;
    }
    .btn-confirm-ok:hover {
        background: linear-gradient(135deg, #ffb300 0%, #f57c00 100%);
        box-shadow: 0 6px 16px rgba(255, 152, 0, 0.3);
        transform: translateY(-1px);
        color: white;
    }
    .btn-confirm-ok:active {
        transform: translateY(1px);
    }
    .pulse-orange-btn {
        animation: pulseOrange 2s infinite;
    }
    @keyframes pulseOrange {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 159, 0, 0.6);
        }
        70% {
            transform: scale(1.02);
            box-shadow: 0 0 0 15px rgba(255, 159, 0, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 159, 0, 0);
        }
    }
</style>
@endpush

@php
    $formatPrice = function($amount) {
        if (app()->getLocale() === 'en') {
            return '$' . number_format($amount, 2);
        }
        return number_format($amount, 0, ',', '.') . 'đ';
    };
@endphp

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-qrcode text-primary me-2"></i>{{ __('Thanh Toán QR') }}
                </h1>

            </div>

            <div class="row g-4">
                <!-- Thông tin khách hàng -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user me-2"></i>{{ __('Thông tin của bạn') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method_input" value="{{ app()->getLocale() === 'en' ? 'crypto' : 'vietqr' }}">
                                <input type="hidden" name="order_code" value="{{ $orderCode }}">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>{{ __('Họ và tên') }}
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                    <small class="text-muted">{{ __('Mã kích hoạt sẽ được gửi qua email') }}</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-phone me-2 text-primary"></i>{{ __('Số điện thoại') }}
                                    </label>
                                    <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-comment-dots me-2 text-primary"></i>Zalo <small class="text-danger fw-normal" id="zalo-label">{{ __('(Bắt buộc nếu không có Facebook)') }}</small>
                                    </label>
                                    <input type="text" class="form-control form-control-lg contact-input" name="customer_zalo" placeholder="{{ __('Nhập số Zalo của bạn') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fab fa-facebook me-2 text-primary"></i>Link Facebook <small class="text-danger fw-normal" id="facebook-label">{{ __('(Bắt buộc nếu không có Zalo)') }}</small>
                                    </label>
                                    <input type="url" class="form-control form-control-lg contact-input" name="customer_facebook" placeholder="{{ __('Ví dụ: https://facebook.com/username') }}">
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="use_boxchat" id="use_boxchat" value="1">
                                    <label class="form-check-label fw-bold text-primary" for="use_boxchat" style="cursor: pointer;">
                                        {{ __('Tôi sẽ tự liên hệ qua Boxchat (Không cần để lại Zalo/FB)') }}
                                    </label>
                                </div>

                                <div id="contact-error" class="alert alert-danger py-2 mb-3 d-none" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Vui lòng để lại ít nhất 1 thông tin liên hệ hoặc chọn "Tự liên hệ qua Boxchat"!') }}
                                </div>

                                <!-- Mã giảm giá -->
                                <div class="card bg-light border-0 rounded-3 p-3 mb-3 text-dark">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="fas fa-ticket-alt text-primary me-2"></i>{{ __('Mã giảm giá (nếu có)') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="coupon_code" class="form-control" placeholder="{{ __('Nhập mã giảm giá của bạn') }}" value="{{ $couponCode ?? '' }}" {{ isset($couponCode) ? 'disabled' : '' }}>
                                        <button class="btn btn-primary" type="button" id="apply-coupon-btn" style="{{ isset($couponCode) ? 'display:none;' : '' }}">{{ __('Áp dụng') }}</button>
                                        <button class="btn btn-danger" type="button" id="remove-coupon-btn" style="{{ isset($couponCode) ? '' : 'display:none;' }}">{{ __('Hủy') }}</button>
                                    </div>
                                    <div id="coupon-feedback" class="small mt-2 d-none"></div>
                                </div>

                                <!-- Đơn hàng -->
                                <div class="alert alert-info mt-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-shopping-bag me-2"></i>{{ __('Sản phẩm đã chọn') }}
                                    </h6>
                                    @php $total = 0 @endphp
                                    @foreach($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-break" style="max-width: 75%;">{{ $details['name'] }} x{{ $details['quantity'] }}</span>
                                        <strong class="text-nowrap">{{ $formatPrice($details['price'] * $details['quantity']) }}</strong>
                                    </div>
                                    @endforeach
                                    
                                    <div class="d-flex justify-content-between mb-2 {{ isset($discountAmount) && $discountAmount > 0 ? '' : 'd-none' }}" id="discount-row">
                                        <span class="text-danger fw-bold">{{ __('Giảm giá:') }}</span>
                                        <strong class="text-danger" id="discount-display">-{{ $formatPrice($discountAmount ?? 0) }}</strong>
                                    </div>
                                    
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">{{ __('Tổng cộng:') }}</h5>
                                        <h5 class="mb-0 text-primary" id="checkout-total-display">{{ $formatPrice($finalTotal ?? $total) }}</h5>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- QR Payment -->
                <div class="col-md-7">
                    <div class="qr-payment-card">
                        <!-- Navigation Tabs -->
                        <div class="payment-tabs">
                            @if(app()->getLocale() !== 'en')
                            <button type="button" class="payment-tab-btn active" data-target="vietqr-panel" data-method="vietqr">
                                <i class="fas fa-university me-1"></i> VietQR (VN)
                            </button>
                            @endif
                            <button type="button" class="payment-tab-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-target="crypto-panel" data-method="crypto">
                                <i class="fas fa-coins me-1"></i> {{ __('Ví Crypto') }}
                            </button>
                            <button type="button" class="payment-tab-btn" data-target="binance-panel" data-method="binance_uid">
                                <i class="fab fa-bitcoin me-1"></i> Binance UID
                            </button>
                            <button type="button" class="payment-tab-btn" data-target="paypal-panel" data-method="paypal">
                                <i class="fab fa-paypal me-1"></i> PayPal
                            </button>
                        </div>

                        @if(app()->getLocale() !== 'en')
                        <!-- PANEL 1: VietQR -->
                        <div id="vietqr-panel" class="payment-tab-content active">
                            <div class="mb-3">
                                <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                                <h3 class="fw-bold">{{ __('Quét mã QR để thanh toán') }}</h3>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://img.vietqr.io/image/{{ config('services.vietqr.bank_code') }}-{{ config('services.vietqr.account_number') }}-print2D.png?amount={{ $finalTotal ?? $total ?? 0 }}&addInfo={{ urlencode(config('services.vietqr.add_info') . ' ' . $orderCode) }}&accountName={{ urlencode(config('services.vietqr.account_name')) }}" 
                                     alt="QR Code" 
                                     class="img-fluid"
                                     id="qr-code-image"
                                     style="max-width: 250px;">
                            </div>

                            <!-- Nút Xác nhận đã chuyển khoản -->
                            <div class="px-2 mb-3">
                                <button type="button" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg pulse-orange-btn" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" style="background: linear-gradient(135deg, #ff9f00 0%, #ff5e00 100%); border: none; color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)') }}
                                </button>
                            </div>

                            <div class="payment-info">
                                <h5 class="fw-bold mb-3">{{ __('Thông tin chuyển khoản') }}</h5>
                                <div class="row g-3 text-start">
                                    <div class="col-12 col-sm-6">
                                        <small class="opacity-75">{{ __('Ngân hàng:') }}</small>
                                        <div class="fw-bold text-white mt-1" style="font-size: 0.95rem;">{{ config('services.vietqr.bank_name') }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <small class="opacity-75">{{ __('Số tài khoản:') }}</small>
                                        <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                            <span class="fw-bold text-white text-break" style="font-size: 0.95rem;">{{ config('services.vietqr.account_number') }}</span>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('{{ config('services.vietqr.account_number') }}', this)" style="font-size: 0.75rem; padding: 2px 6px;">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <small class="opacity-75">{{ __('Chủ tài khoản:') }}</small>
                                        <div class="fw-bold text-white mt-1" style="font-size: 0.95rem;">{{ config('services.vietqr.account_name') }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <small class="opacity-75">{{ __('Số tiền:') }}</small>
                                        <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                            <span class="fw-bold text-warning text-break" style="font-size: 0.95rem;" id="transfer-amount-display">{{ $formatPrice($finalTotal ?? $total ?? 0) }}</span>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('{{ $finalTotal ?? $total ?? 0 }}', this)" style="font-size: 0.75rem; padding: 2px 6px;">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <small class="opacity-75">{{ __('Nội dung chuyển khoản:') }}</small>
                                        <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                            <span class="fw-bold text-warning text-break" style="font-size: 0.95rem;">{{ config('services.vietqr.add_info') }} {{ $orderCode }}</span>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('{{ config('services.vietqr.add_info') }} {{ $orderCode }}', this)" style="font-size: 0.75rem; padding: 2px 6px;">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- PANEL 2: Crypto Wallet -->
                        <div id="crypto-panel" class="payment-tab-content {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <div class="mb-3">
                                <i class="fas fa-wallet fa-3x mb-3"></i>
                                <h3 class="fw-bold">{{ __('Ví Crypto (USDT,...)') }}</h3>
                                <p class="opacity-75">Pay using Cryptocurrency (USDT/BTC/ETH...)</p>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9" 
                                     alt="Crypto QR" 
                                     class="img-fluid"
                                     style="max-width: 220px;">
                            </div>

                            <!-- Nút Xác nhận đã chuyển khoản -->
                            <div class="px-2 mb-3">
                                <button type="button" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg pulse-orange-btn" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" style="background: linear-gradient(135deg, #ff9f00 0%, #ff5e00 100%); border: none; color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)') }}
                                </button>
                            </div>

                            <div class="payment-info text-start">
                                <h5 class="fw-bold mb-3 text-center">{{ __('Thông tin ví nhận tiền / Wallet Details') }}</h5>
                                <div class="mb-3">
                                    <small class="opacity-75 d-block">{{ __('Địa chỉ ví (Wallet Address):') }}</small>
                                    <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                        <code class="text-warning text-break" style="font-size: 0.85rem;">0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9</code>
                                        <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9', this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem; background: rgba(255, 193, 7, 0.15); border: none; color: #fff;">
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        Please let us know which cryptocurrency and network you will use. Once the payment is sent, please contact us.
                                    @else
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>English:</strong> Please let me know which cryptocurrency and network you will use. Once the payment is sent, please contact us.<br>
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>{{ __("Lưu ý") }}:</strong> Vui lòng cho biết bạn sử dụng loại tiền điện tử và mạng lưới nào. Sau khi gửi thanh toán, hãy liên hệ với chúng tôi.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 3: Binance UID -->
                        <div id="binance-panel" class="payment-tab-content">
                            <div class="mb-3">
                                <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                                <h3 class="fw-bold">Binance Pay / UID</h3>
                                <p class="opacity-75">Pay internally via Binance UID</p>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=490696268" 
                                     alt="Binance Pay QR" 
                                     class="img-fluid"
                                     style="max-width: 220px;">
                            </div>

                            <!-- Nút Xác nhận đã chuyển khoản -->
                            <div class="px-2 mb-3">
                                <button type="button" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg pulse-orange-btn" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" style="background: linear-gradient(135deg, #ff9f00 0%, #ff5e00 100%); border: none; color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)') }}
                                </button>
                            </div>

                            <div class="payment-info text-start">
                                <h5 class="fw-bold mb-3 text-center">{{ __('Thông tin tài khoản / Account Details') }}</h5>
                                <div class="mb-3">
                                    <small class="opacity-75 d-block">Binance UID:</small>
                                    <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                        <span class="text-warning fw-bold">490696268</span>
                                        <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('490696268', this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem; background: rgba(255, 193, 7, 0.15); border: none; color: #fff;">
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        Once the payment is sent, please contact us.
                                    @else
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>{{ __("Lưu ý") }}:</strong> Sau khi chuyển khoản thành công, hãy liên hệ với chúng tôi.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 4: PayPal -->
                        <div id="paypal-panel" class="payment-tab-content">
                            <div class="mb-3">
                                <i class="fab fa-paypal fa-3x mb-3 text-info"></i>
                                <h3 class="fw-bold">PayPal</h3>
                                <p class="opacity-75">Pay via PayPal / PayPal.Me</p>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=https://paypal.me/{{ config('services.paypal.username') }}" 
                                     alt="PayPal QR" 
                                     class="img-fluid"
                                     style="max-width: 220px;">
                            </div>

                            <!-- Nút Xác nhận đã chuyển khoản -->
                            <div class="px-2 mb-3">
                                <button type="button" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg pulse-orange-btn" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" style="background: linear-gradient(135deg, #ff9f00 0%, #ff5e00 100%); border: none; color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('TÔI ĐÃ CHUYỂN KHOẢN XONG (XÁC NHẬN)') }}
                                </button>
                            </div>

                            <div class="payment-info text-start">
                                <h5 class="fw-bold mb-3 text-center">{{ __('Thông tin tài khoản / Account Details') }}</h5>
                                <div class="mb-3">
                                    <small class="opacity-75 d-block">PayPal Email:</small>
                                    <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                        <span class="text-warning fw-bold">{{ config('services.paypal.email') }}</span>
                                        <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('{{ config('services.paypal.email') }}', this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="opacity-75 d-block">PayPal.Me Link:</small>
                                    <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                        <span class="text-warning fw-bold">https://paypal.me/{{ config('services.paypal.username') }}</span>
                                        <a href="https://paypal.me/{{ config('services.paypal.username') }}" target="_blank" class="btn btn-sm btn-info text-white text-nowrap" style="font-size: 0.8rem; padding: 4px 8px; border-radius: 4px;">
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
                                <div class="mb-3 p-2 bg-dark bg-opacity-25 rounded text-center">
                                    <span class="opacity-75 d-block" style="font-size: 0.8rem;">{{ __('Số tiền cần thanh toán / Amount to Pay:') }}</span>
                                    @if(app()->getLocale() === 'en')
                                        <strong class="text-warning fs-5">${{ number_format($totalAmountUsd, 2) }} USD</strong>
                                    @else
                                        <strong class="text-warning fs-5">{{ number_format($totalAmountVnd, 0, ',', '.') }}đ</strong>
                                        <span class="d-block text-white opacity-75 mt-1" style="font-size: 0.85rem;">(≈ ${{ number_format($totalAmountUsd, 2) }} USD)</span>
                                    @endif
                                </div>

                                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem; background: rgba(255, 193, 7, 0.15); border: none; color: #fff;">
                                    @if(app()->getLocale() === 'en')
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        Once the payment is sent, please contact us.
                                    @else
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                        <i class="fas fa-info-circle me-1 text-warning"></i>
                                        <strong>{{ __("Lưu ý") }}:</strong> Sau khi thanh toán thành công, hãy liên hệ với chúng tôi.
                                    @endif
                                </div>
                            </div>
                        </div>




                        <button type="button" class="btn btn-warning btn-lg px-5 rounded-pill shadow-lg" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                            <i class="fas fa-check-circle me-2"></i>{{ __('Xác nhận đã thanh toán') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận thanh toán -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content confirm-modal-content">
            <div class="confirm-modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-2 pt-0">
                <div class="confirm-icon-wrapper">
                    <i class="fas fa-question"></i>
                </div>
                <h3 class="confirm-modal-title">{{ __('Bạn đã thanh toán chưa?') }}</h3>
                <p class="confirm-modal-text">{{ __('Vui lòng đảm bảo bạn đã chuyển khoản thành công trước khi xác nhận. Đơn hàng ảo hoặc chưa thanh toán sẽ bị hủy tự động.') }}</p>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="btn btn-confirm-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>{{ __('Chưa, để tôi kiểm tra lại') }}
                </button>
                <button type="submit" form="checkout-form" class="btn btn-confirm-ok">
                    <i class="fas fa-check me-2"></i>{{ __('Có, tôi đã thanh toán') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Cảnh báo Boxchat -->
<div class="modal fade" id="boxchatWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Cảnh báo Quan Trọng') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-comment-slash text-danger fa-4x"></i>
                </div>
                <h4 class="fw-bold mb-3">{{ __('Bạn chọn tự liên hệ qua Boxchat?') }}</h4>
                <div class="text-start alert alert-warning mb-0">
                    <ul class="mb-0">
                        <li>{{ __('Bạn không cần để lại Zalo/Facebook cá nhân.') }}</li>
                        <li><strong>{{ __('Bắt buộc:') }}</strong> {{ __('Bạn phải chủ động nhắn tin cho Boxchat của Admin ngay sau khi thanh toán.') }}</li>
                        <li><strong>{{ __('Lưu ý:') }}</strong> {{ __('Nếu bạn không nhắn tin, Admin sẽ không có cách nào để gửi mã/hỗ trợ bạn. Chúng tôi sẽ không chịu trách nhiệm trong trường hợp này.') }}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">{{ __('Tôi hiểu rồi') }}</button>
            </div>
        </div>
    </div>
</div>

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

    const useBoxchat = document.getElementById('use_boxchat');
    const boxchatModal = new bootstrap.Modal(document.getElementById('boxchatWarningModal'));
    const contactInputs = document.querySelectorAll('.contact-input');
    const zaloLabel = document.getElementById('zalo-label');
    const facebookLabel = document.getElementById('facebook-label');

    useBoxchat.addEventListener('change', function() {
        if (this.checked) {
            boxchatModal.show();
            contactInputs.forEach(input => {
                input.disabled = true;
                input.value = '';
            });
            zaloLabel.textContent = '{{ __("(Đã tắt)") }}';
            facebookLabel.textContent = '{{ __("(Đã tắt)") }}';
            zaloLabel.classList.replace('text-danger', 'text-muted');
            facebookLabel.classList.replace('text-danger', 'text-muted');
        } else {
            contactInputs.forEach(input => input.disabled = false);
            zaloLabel.textContent = '{{ __("(Bắt buộc nếu không có Facebook)") }}';
            facebookLabel.textContent = '{{ __("(Bắt buộc nếu không có Zalo)") }}';
            zaloLabel.classList.replace('text-muted', 'text-danger');
            facebookLabel.classList.replace('text-muted', 'text-danger');
        }
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const isUsingBoxchat = document.getElementById('use_boxchat').checked;
        const zalo = document.getElementsByName('customer_zalo')[0].value.trim();
        const facebook = document.getElementsByName('customer_facebook')[0].value.trim();
        const errorDiv = document.getElementById('contact-error');
        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmPaymentModal'));

        if (!isUsingBoxchat && !zalo && !facebook) {
            e.preventDefault();
            errorDiv.classList.remove('d-none');
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            if (modal) {
                modal.hide();
            }
            return false;
        } else {
            errorDiv.classList.add('d-none');
        }
    });

    document.querySelectorAll('.contact-input').forEach(input => {
        input.addEventListener('input', () => {
            document.getElementById('contact-error').classList.add('d-none');
        });
    });

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
        feedbackEl.className = 'small mt-2 ' + className;
        feedbackEl.classList.remove('d-none');
    }

    function formatNumber(num) {
        const locale = '{{ app()->getLocale() }}';
        if (locale === 'en') {
            return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        return num.toLocaleString('vi-VN') + 'đ';
    }
</script>
@endpush
@endsection
