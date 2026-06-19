@extends('layouts.app')

@section('title', __('Thanh Toán Đơn Buff') . ' - DungThu.com')

@push('styles')
<style>
    .payment-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .payment-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .order-code {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 700;
        color: #6c5ce7;
        display: inline-block;
        font-size: 0.95rem;
    }

    .order-summary {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .summary-row strong {
        font-weight: 600;
    }

    .summary-total {
        border-top: 2px solid #ddd;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        font-size: 1.2rem;
        font-weight: 800;
        color: #dc3545;
    }

    .payment-method-section {
        margin-bottom: 2rem;
    }

    .payment-method-section h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #2d3436;
    }

    .payment-method-group {
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
    }

    .payment-option {
        flex: 1;
        min-width: 120px;
        padding: 1.25rem;
        border-right: 2px solid #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        text-align: center;
    }

    .payment-option:last-child {
        border-right: none;
    }

    .payment-option.active {
        background: rgba(108, 92, 231, 0.1);
        box-shadow: inset 0 0 0 2px #6c5ce7;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-option-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .payment-option-label {
        font-weight: 600;
        color: #2d3436;
        display: block;
        margin-bottom: 0.25rem;
    }

    .payment-option-text {
        font-size: 0.85rem;
        color: #666;
    }

    .qr-section {
        text-align: center;
        padding: 2rem;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: none;
    }

    .qr-section.show {
        display: block;
    }

    .qr-section h4 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #2d3436;
    }

    .qr-code {
        display: inline-block;
        padding: 1rem;
        background: white;
        border: 2px solid #6c5ce7;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .qr-code img {
        max-width: 100%;
        height: auto;
    }

    .qr-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #555;
        margin-top: 1rem;
    }

    .qr-amount {
        font-size: 1.3rem;
        font-weight: 800;
        color: #dc3545;
        margin: 1rem 0;
    }

    .confirm-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .confirm-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
    }

    .confirm-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .btn-confirm {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-confirm:hover:not(:disabled) {
        background: linear-gradient(135deg, #5f4ec7 0%, #9080d8 100%);
        transform: scale(1.02);
    }

    .btn-confirm:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .back-link {
        text-align: center;
        margin-top: 1.5rem;
    }

    .back-link a {
        color: #6c5ce7;
        text-decoration: none;
        font-weight: 600;
    }

    .back-link a:hover {
        text-decoration: underline;
    }

    .timer {
        text-align: center;
        color: #dc3545;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        color: #0c5aa0;
    }

    @media (max-width: 768px) {
        .payment-container {
            margin-top: 50px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .payment-method-group {
            flex-direction: column;
        }

        .payment-option {
            padding: 1rem;
            border-right: none;
            border-bottom: 2px solid #ddd;
            flex: 1 1 auto;
        }

        .payment-option:last-child {
            border-bottom: none;
        }

        .payment-option-icon {
            font-size: 1.5rem;
        }

        .payment-option-label {
            font-size: 0.9rem;
        }

        .payment-option-text {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $locale = app()->getLocale();
    $exchangeRate = doubleval(\App\Models\SiteSetting::getValue('usd_exchange_rate', '25000'));
    $formatPrice = function($amount) use ($locale, $exchangeRate) {
        if ($locale === 'en') {
            $usd = $amount / $exchangeRate;
            if ($usd < 0.01 && $usd > 0) {
                return '$' . number_format($usd, 4, '.', ',');
            }
            return '$' . number_format($usd, 2, '.', ',');
        }
        $locale = app()->getLocale();
    if ($locale === 'en') {
        return '$' . number_format($amount / $exchangeRate, 2);
    }
    return number_format($amount, 0, ',', '.') . 'đ';
    };
@endphp
<main>
    <div class="container" style="margin-top: 100px; margin-bottom: 3rem;">
        <div class="payment-container">
            <div class="payment-header">
                <h1>{{ __('Xác Nhận Thanh Toán') }}</h1>
                <p class="mb-2">{{ __('Đơn') }} #<span class="order-code">{{ $buffOrder->order_code }}</span></p>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-row">
                    <strong>{{ __('Dịch vụ:') }}</strong>
                    <span>{{ __($buffOrder->buffService->name) }}</span>
                </div>
                <div class="summary-row">
                    <strong>{{ __('Server:') }}</strong>
                    <span>{{ __($buffOrder->buffServer->name) }}</span>
                </div>
                <div class="summary-row">
                    <strong>{{ __('Số lượng:') }}</strong>
                    <span>{{ number_format($buffOrder->quantity) }}</span>
                </div>
                <div class="summary-row">
                    <strong>{{ __('Giá/đơn vị:') }}</strong>
                    <span>{{ $formatPrice($buffOrder->unit_price) }}</span>
                </div>
                <div class="summary-total">
                    <div class="summary-row">
                        <span>{{ __('TỔNG CỘNG:') }}</span>
                        <span>{{ $formatPrice($buffOrder->total_price) }}</span>
                    </div>
                </div>
            </div>

            <div class="info-box">
                ℹ️ {{ __('Vui lòng chuyển khoản đủ số tiền. Nếu chuyển thiếu, đơn hàng sẽ bị hủy.') }}
            </div>

            <!-- Payment Method Selection -->
            <form id="paymentForm" method="POST" action="{{ route('buff.confirm-payment', $buffOrder) }}">
                @csrf

                <div class="payment-method-section">
                    <h3>{{ __('Chọn Phương Thức Thanh Toán') }}</h3>
                    <div class="payment-method-group">
                        @if(app()->getLocale() !== 'en')
                        <label class="payment-option active">
                            <input type="radio" name="payment_method" value="qr_code" checked>
                            <div class="payment-option-icon">📱</div>
                            <span class="payment-option-label">{{ __('Quét QR Code') }}</span>
                            <span class="payment-option-text">VietQR / Momo</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <div class="payment-option-icon">🏦</div>
                            <span class="payment-option-label">{{ __('Chuyển Khoản') }}</span>
                            <span class="payment-option-text">{{ config('services.vietqr.bank_name') }}</span>
                        </label>
                        @endif
                        <label class="payment-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <input type="radio" name="payment_method" value="crypto" {{ app()->getLocale() === 'en' ? 'checked' : '' }}>
                            <div class="payment-option-icon">🪙</div>
                            <span class="payment-option-label">{{ __('Ví Crypto') }}</span>
                            <span class="payment-option-text">USDT, BTC...</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="binance_uid">
                            <div class="payment-option-icon">💳</div>
                            <span class="payment-option-label">Binance UID</span>
                            <span class="payment-option-text">Binance Pay</span>
                        </label>
                    </div>
                </div>

                @if(app()->getLocale() !== 'en')
                <!-- QR Section -->
                <div id="qrSection" class="qr-section show">
                    <h4>📲 {{ __('Quét QR Code Để Thanh Toán') }}</h4>
                    <p style="color: #666; margin-bottom: 1rem;">
                        {{ __('Sử dụng ứng dụng Momo hoặc ngân hàng để quét mã QR bên dưới') }}
                    </p>
                    <div class="qr-code">
                        <img src="{{ $qrUrl }}" alt="VietQR Code" style="max-width: 250px; width: 100%;">
                    </div>

                    <div class="qr-amount">
                        {{ $formatPrice($buffOrder->total_price) }}
                    </div>

                    <div class="qr-info">
                        ✓ {{ __('Số tiền:') }} {{ $formatPrice($buffOrder->total_price) }}<br>
                        ✓ {{ __('Nội dung:') }} DungThu Buff - {{ $buffOrder->order_code }}<br>
                        ✓ {{ __('Bạn sẽ nhận thông báo khi thanh toán thành công') }}
                    </div>
                </div>

                <!-- Bank Transfer Section -->
                <div id="bankSection" class="qr-section">
                    <h4>🏦 {{ __('Hướng dẫn chuyển khoản VN') }}</h4>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; text-align: left;">
                        <p><strong>{{ __('Ngân Hàng:') }}</strong> {{ config('services.vietqr.bank_name') }}</p>
                        <p><strong>{{ __('Tên TK:') }}</strong> {{ config('services.vietqr.account_name') }}</p>
                        <p><strong>{{ __('Số TK:') }}</strong> {{ config('services.vietqr.account_number') }}</p>
                        <p><strong>{{ __('Số Tiền:') }}</strong> {{ $formatPrice($buffOrder->total_price) }}</p>
                        <p style="margin-bottom: 0;"><strong>{{ __('Nội Dung:') }}</strong> DungThu Buff - {{ $buffOrder->order_code }}</p>
                    </div>
                </div>
                @endif

                <!-- Crypto Section -->
                <div id="cryptoSection" class="qr-section {{ app()->getLocale() === 'en' ? 'show' : '' }}">
                    <h4>🪙 {{ __('Ví Crypto (USDT, BTC, ETH...)') }}</h4>
                    <p style="color: #666; margin-bottom: 1rem;">
                        {{ __('Chuyển tiền điện tử đến địa chỉ ví bên dưới') }}
                    </p>
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9" alt="Crypto QR" style="max-width: 220px; width: 100%;">
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; text-align: left;">
                        <p class="mb-2"><strong>{{ __('Mạng lưới:') }}</strong> {{ __('Hỗ trợ nhiều mạng lưới khác nhau (TRC20, ERC20, BEP20...)') }}</p>
                        <div class="mb-3">
                            <strong>{{ __('Địa chỉ ví (Wallet Address):') }}</strong>
                            <div class="d-flex align-items-center justify-content-between mt-1 bg-white p-2 border rounded">
                                <code class="text-danger text-break" style="font-size: 0.9rem;">0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9</code>
                                <button type="button" class="btn btn-sm btn-secondary ms-2 text-nowrap" onclick="copyToClipboard('0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9', this)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div style="font-size: 0.9rem; border-top: 1px solid #ddd; padding-top: 10px; line-height: 1.5; color: #555;">
                            @if(app()->getLocale() === 'en')
                                Please let me know which cryptocurrency and network you will use. Once the payment is sent, please contact us.
                            @else
                                {{ __('Vui lòng cho biết bạn sử dụng loại tiền điện tử và mạng lưới nào. Sau khi gửi thanh toán, hãy liên hệ với chúng tôi.') }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Binance Pay Section -->
                <div id="binanceSection" class="qr-section">
                    <h4>💳 {{ __('Binance Pay / UID') }}</h4>
                    <p style="color: #666; margin-bottom: 1rem;">
                        {{ __('Chuyển khoản nội bộ Binance Pay qua UID bên dưới') }}
                    </p>
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=490696268" alt="Binance Pay QR" style="max-width: 220px; width: 100%;">
                    </div>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; text-align: left;">
                        <div class="mb-3">
                            <strong>{{ __('Binance UID:') }}</strong>
                            <div class="d-flex align-items-center justify-content-between mt-1 bg-white p-2 border rounded">
                                <span class="text-danger fw-bold">490696268</span>
                                <button type="button" class="btn btn-sm btn-secondary ms-2 text-nowrap" onclick="copyToClipboard('490696268', this)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div style="font-size: 0.9rem; border-top: 1px solid #ddd; padding-top: 10px; line-height: 1.5; color: #555;">
                            @if(app()->getLocale() === 'en')
                                Once the payment is sent, please contact us.
                            @else
                                {{ __('Sau khi chuyển khoản thành công, hãy liên hệ với chúng tôi.') }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Confirmation -->
                <div class="confirm-section">
                    <div class="confirm-checkbox">
                        <input type="checkbox" id="confirmPayment" required>
                        <label for="confirmPayment" style="margin: 0; cursor: pointer;">
                            {{ __('Tôi đã chuyển khoản đủ :amount', ['amount' => $formatPrice($buffOrder->total_price)]) }}
                        </label>
                    </div>
                    <button type="submit" class="btn-confirm" id="btnConfirm">
                        ✓ {{ __('Hoàn Thành Thanh Toán') }}
                    </button>
                </div>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="{{ route('buff.history') }}">← {{ __('Quay lại lịch sử đơn hàng') }}</a>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<script>
// Clipboard copy function
window.copyToClipboard = function(text, btnElement) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fas fa-check"></i> ' + @json(__('Copied!'));
        btnElement.classList.replace('btn-secondary', 'btn-success');
        setTimeout(() => {
            btnElement.innerHTML = originalText;
            btnElement.classList.replace('btn-success', 'btn-secondary');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const qrSection = document.getElementById('qrSection');
    const bankSection = document.getElementById('bankSection');
    const cryptoSection = document.getElementById('cryptoSection');
    const binanceSection = document.getElementById('binanceSection');
    const confirmPayment = document.getElementById('confirmPayment');

    // Payment method toggle
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active class from all
            paymentOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to selected
            this.closest('.payment-option').classList.add('active');

            // Hide all sections first
            if (qrSection) qrSection.classList.remove('show');
            if (bankSection) bankSection.classList.remove('show');
            if (cryptoSection) cryptoSection.classList.remove('show');
            if (binanceSection) binanceSection.classList.remove('show');

            // Show selected section
            if (this.value === 'qr_code') {
                if (qrSection) qrSection.classList.add('show');
            } else if (this.value === 'bank_transfer') {
                if (bankSection) bankSection.classList.add('show');
            } else if (this.value === 'crypto') {
                if (cryptoSection) cryptoSection.classList.add('show');
            } else if (this.value === 'binance_uid') {
                if (binanceSection) binanceSection.classList.add('show');
            }
        });
    });

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!confirmPayment.checked) {
            alert(@json(__('Vui lòng xác nhận rằng bạn đã thanh toán!')));
            return;
        }

        const confirmBtn = document.getElementById('btnConfirm');
        confirmBtn.disabled = true;
        confirmBtn.textContent = '⏳ ' + @json(__('Đang xử lý...'));

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                transaction_id: null,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(@json(__('Lỗi:')) + ' ' + (data.error || 'Unknown'));
                confirmBtn.disabled = false;
                confirmBtn.textContent = '✓ ' + @json(__('Hoàn Thành Thanh Toán'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(@json(__('Lỗi kết nối. Vui lòng thử lại!')));
            confirmBtn.disabled = false;
            confirmBtn.textContent = '✓ ' + @json(__('Hoàn Thành Thanh Toán'));
        });
    });
});
</script>
@endpush
@endsection
