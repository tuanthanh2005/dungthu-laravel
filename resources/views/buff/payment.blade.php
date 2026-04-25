@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Buff - DungThu.com')

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
    }

    .payment-option {
        padding: 1.25rem;
        border-right: 2px solid #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .payment-option:last-child {
        border-right: none;
    }

    .payment-option.active {
        background: rgba(108, 92, 231, 0.1);
        border-right: 3px solid #6c5ce7;
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
            margin-top: 80px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .payment-option {
            padding: 1rem;
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
<main>
    <div class="container" style="margin-top: 100px; margin-bottom: 3rem;">
        <div class="payment-container">
            <div class="payment-header">
                <h1>Xác Nhận Thanh Toán</h1>
                <p class="mb-2">Đơn #<span class="order-code">{{ $buffOrder->order_code }}</span></p>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-row">
                    <strong>Dịch vụ:</strong>
                    <span>{{ $buffOrder->buffService->name }}</span>
                </div>
                <div class="summary-row">
                    <strong>Server:</strong>
                    <span>{{ $buffOrder->buffServer->name }}</span>
                </div>
                <div class="summary-row">
                    <strong>Số lượng:</strong>
                    <span>{{ number_format($buffOrder->quantity) }}</span>
                </div>
                <div class="summary-row">
                    <strong>Giá/đơn vị:</strong>
                    <span>{{ number_format($buffOrder->unit_price, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-total">
                    <div class="summary-row">
                        <span>TỔNG CỘNG:</span>
                        <span>{{ number_format($buffOrder->total_price, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>

            <div class="info-box">
                ℹ️ Vui lòng chuyển khoản đủ số tiền. Nếu chuyển thiếu, đơn hàng sẽ bị hủy.
            </div>

            <!-- Payment Method Selection -->
            <form id="paymentForm" method="POST" action="{{ route('buff.confirm-payment', $buffOrder) }}">
                @csrf

                <div class="payment-method-section">
                    <h3>Chọn Phương Thức Thanh Toán</h3>
                    <div class="payment-method-group">
                        <label class="payment-option active">
                            <input type="radio" name="payment_method" value="qr_code" checked>
                            <div class="payment-option-icon">📱</div>
                            <span class="payment-option-label">Quét QR Code</span>
                            <span class="payment-option-text">Qua Momo, VietQR</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <div class="payment-option-icon">🏦</div>
                            <span class="payment-option-label">Chuyển Khoản</span>
                            <span class="payment-option-text">Các ngân hàng</span>
                        </label>
                    </div>
                </div>

                <!-- QR Section -->
                <div id="qrSection" class="qr-section show">
                    <h4>📲 Quét QR Code Để Thanh Toán</h4>
                    <p style="color: #666; margin-bottom: 1rem;">
                        Sử dụng ứng dụng Momo hoặc ngân hàng để quét mã QR bên dưới
                    </p>
                    {{-- Real VietQR Code --}}
                    <div class="qr-code">
                        <img src="{{ $qrUrl }}" alt="VietQR Code" style="max-width: 250px; width: 100%;">
                    </div>

                    <div class="qr-amount">
                        {{ number_format($buffOrder->total_price, 0, ',', '.') }}đ
                    </div>

                    <div class="qr-info">
                        ✓ Số tiền: {{ number_format($buffOrder->total_price, 0, ',', '.') }}đ<br>
                        ✓ Nội dung: DungThu Buff - {{ $buffOrder->order_code }}<br>
                        ✓ Bạn sẽ nhận thông báo khi thanh toán thành công
                    </div>
                </div>

                <!-- Bank Transfer Section -->
                <div id="bankSection" class="qr-section">
                    <h4>🏦 Thông Tin Chuyển Khoản</h4>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; text-align: left;">
                        <p><strong>Ngân Hàng:</strong> MB Bank (Ngân hàng Quân đội)</p>
                        <p><strong>Tên TK:</strong> TRAN THANH TUAN</p>
                        <p><strong>Số TK:</strong> 0783704196</p>
                        <p><strong>Số Tiền:</strong> {{ number_format($buffOrder->total_price, 0, ',', '.') }}đ</p>
                        <p style="margin-bottom: 0;"><strong>Nội Dung:</strong> DungThu Buff - {{ $buffOrder->order_code }}</p>
                    </div>
                </div>

                <!-- Confirmation -->
                <div class="confirm-section">
                    <div class="confirm-checkbox">
                        <input type="checkbox" id="confirmPayment" required>
                        <label for="confirmPayment" style="margin: 0; cursor: pointer;">
                            Tôi đã chuyển khoản đủ {{ number_format($buffOrder->total_price, 0, ',', '.') }}đ
                        </label>
                    </div>
                    <button type="submit" class="btn-confirm" id="btnConfirm">
                        ✓ Hoàn Thành Thanh Toán
                    </button>
                </div>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="{{ route('buff.history') }}">← Quay lại lịch sử đơn hàng</a>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const qrSection = document.getElementById('qrSection');
    const bankSection = document.getElementById('bankSection');
    const confirmPayment = document.getElementById('confirmPayment');

    // Payment method toggle
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active class from all
            paymentOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to selected
            this.closest('.payment-option').classList.add('active');

            // Show/hide sections
            if (this.value === 'qr_code') {
                qrSection.classList.add('show');
                bankSection.classList.remove('show');
            } else {
                qrSection.classList.remove('show');
                bankSection.classList.add('show');
            }
        });
    });

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!confirmPayment.checked) {
            alert('Vui lòng xác nhận rằng bạn đã thanh toán!');
            return;
        }

        const confirmBtn = document.getElementById('btnConfirm');
        confirmBtn.disabled = true;
        confirmBtn.textContent = '⏳ Đang xử lý...';

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
                alert('Lỗi: ' + (data.error || 'Không xác định'));
                confirmBtn.disabled = false;
                confirmBtn.textContent = '✓ Hoàn Thành Thanh Toán';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi kết nối. Vui lòng thử lại!');
            confirmBtn.disabled = false;
            confirmBtn.textContent = '✓ Hoàn Thành Thanh Toán';
        });
    });
});
</script>
@endpush
@endsection
