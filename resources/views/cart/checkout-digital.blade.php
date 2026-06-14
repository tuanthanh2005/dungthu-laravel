@extends('layouts.app')

@section('title', 'Thanh Toán QR - DungThu.com')

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
</style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-qrcode text-primary me-2"></i>Thanh Toán QR
                </h1>

            </div>

            <div class="row g-4">
                <!-- Thông tin khách hàng -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user me-2"></i>Thông tin của bạn
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method_input" value="vietqr">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>Họ và tên
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                    <small class="text-muted">Mã kích hoạt sẽ được gửi qua email</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-phone me-2 text-primary"></i>Số điện thoại
                                    </label>
                                    <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                </div>
                                
                                <div class="alert alert-info py-2 mb-3" style="font-size: 14px;">
                                    <i class="fas fa-headset me-2"></i><strong>Hãy để lại thông tin, Bộ phận hỗ trợ sẽ kết nối và hỗ trợ bạn ngay !</strong>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-comment-dots me-2 text-primary"></i>Zalo <small class="text-danger fw-normal" id="zalo-label">(Bắt buộc nếu không có Facebook)</small>
                                    </label>
                                    <input type="text" class="form-control form-control-lg contact-input" name="customer_zalo" placeholder="Nhập số Zalo của bạn">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fab fa-facebook me-2 text-primary"></i>Link Facebook <small class="text-danger fw-normal" id="facebook-label">(Bắt buộc nếu không có Zalo)</small>
                                    </label>
                                    <input type="url" class="form-control form-control-lg contact-input" name="customer_facebook" placeholder="Ví dụ: https://facebook.com/username">
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="use_boxchat" id="use_boxchat" value="1">
                                    <label class="form-check-label fw-bold text-primary" for="use_boxchat" style="cursor: pointer;">
                                        Tôi sẽ tự liên hệ qua Boxchat (Không cần để lại Zalo/FB)
                                    </label>
                                </div>

                                <div id="contact-error" class="alert alert-danger py-2 mb-3 d-none" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Vui lòng để lại ít nhất 1 thông tin liên hệ hoặc chọn "Tự liên hệ qua Boxchat"!
                                </div>

                                <!-- Mã giảm giá -->
                                <div class="card bg-light border-0 rounded-3 p-3 mb-3 text-dark">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="fas fa-ticket-alt text-primary me-2"></i>Mã giảm giá (nếu có)
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="coupon_code" class="form-control" placeholder="Nhập mã giảm giá của bạn" value="{{ $couponCode ?? '' }}" {{ isset($couponCode) ? 'disabled' : '' }}>
                                        <button class="btn btn-primary" type="button" id="apply-coupon-btn" style="{{ isset($couponCode) ? 'display:none;' : '' }}">Áp dụng</button>
                                        <button class="btn btn-danger" type="button" id="remove-coupon-btn" style="{{ isset($couponCode) ? '' : 'display:none;' }}">Hủy</button>
                                    </div>
                                    <div id="coupon-feedback" class="small mt-2 d-none"></div>
                                </div>

                                <!-- Đơn hàng -->
                                <div class="alert alert-info mt-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-shopping-bag me-2"></i>Sản phẩm đã chọn
                                    </h6>
                                    @php $total = 0 @endphp
                                    @foreach($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-break" style="max-width: 75%;">{{ $details['name'] }} x{{ $details['quantity'] }}</span>
                                        <strong class="text-nowrap">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</strong>
                                    </div>
                                    @endforeach
                                    
                                    <div class="d-flex justify-content-between mb-2 {{ isset($discountAmount) && $discountAmount > 0 ? '' : 'd-none' }}" id="discount-row">
                                        <span class="text-danger fw-bold">Giảm giá:</span>
                                        <strong class="text-danger" id="discount-display">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</strong>
                                    </div>
                                    
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">Tổng cộng:</h5>
                                        <h5 class="mb-0 text-primary" id="checkout-total-display">{{ number_format($finalTotal ?? $total, 0, ',', '.') }}đ</h5>
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
                            <button type="button" class="payment-tab-btn active" data-target="vietqr-panel" data-method="vietqr">
                                <i class="fas fa-university me-1"></i> VietQR (VN)
                            </button>
                            <button type="button" class="payment-tab-btn" data-target="crypto-panel" data-method="crypto">
                                <i class="fas fa-coins me-1"></i> Ví Crypto
                            </button>
                            <button type="button" class="payment-tab-btn" data-target="binance-panel" data-method="binance_uid">
                                <i class="fab fa-bitcoin me-1"></i> Binance UID
                            </button>
                        </div>

                        <!-- PANEL 1: VietQR -->
                        <div id="vietqr-panel" class="payment-tab-content active">
                            <div class="mb-3">
                                <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                                <h3 class="fw-bold">Quét mã QR để thanh toán</h3>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://img.vietqr.io/image/970422-0783704196-print2D.png?amount={{ $finalTotal ?? $total ?? 0 }}&addInfo=AI%20GIA%20RE%20THUDUNG&accountName=TRAN%20THANH%20TUAN" 
                                     alt="QR Code" 
                                     class="img-fluid"
                                     id="qr-code-image"
                                     style="max-width: 250px;">
                            </div>

                            <div class="payment-info">
                                <h5 class="fw-bold mb-3">Thông tin chuyển khoản</h5>
                                <div class="row g-3 text-start">
                                    <div class="col-6">
                                        <small class="opacity-75">Ngân hàng:</small>
                                        <div class="fw-bold">MB BANK</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="opacity-75">Số tài khoản:</small>
                                        <div class="fw-bold">0783704196</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="opacity-75">Chủ tài khoản:</small>
                                        <div class="fw-bold">TRAN THANH TUAN</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="opacity-75">Số tiền:</small>
                                        <div class="fw-bold text-warning" id="transfer-amount-display">{{ number_format($finalTotal ?? $total ?? 0, 0, ',', '.') }}đ</div>
                                    </div>
                                    <div class="col-12">
                                        <small class="opacity-75">Nội dung:</small>
                                        <div class="fw-bold">AI GIA RE THUDUNG</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 2: Crypto Wallet -->
                        <div id="crypto-panel" class="payment-tab-content">
                            <div class="mb-3">
                                <i class="fas fa-wallet fa-3x mb-3"></i>
                                <h3 class="fw-bold">Ví Crypto (USDT,...)</h3>
                                <p class="opacity-75">Pay using Cryptocurrency (USDT/BTC/ETH...)</p>
                            </div>

                            <div class="qr-code-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9" 
                                     alt="Crypto QR" 
                                     class="img-fluid"
                                     style="max-width: 220px;">
                            </div>

                            <div class="payment-info text-start">
                                <h5 class="fw-bold mb-3 text-center">Thông tin ví nhận tiền / Wallet Details</h5>
                                <div class="mb-3">
                                    <small class="opacity-75 d-block">Địa chỉ ví (Wallet Address):</small>
                                    <div class="d-flex align-items-center justify-content-between bg-dark bg-opacity-25 p-2 rounded mt-1">
                                        <code class="text-warning text-break" style="font-size: 0.85rem;">0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9</code>
                                        <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9', this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem; background: rgba(255, 193, 7, 0.15); border: none; color: #fff;">
                                    <i class="fas fa-info-circle me-1 text-warning"></i>
                                    <strong>English:</strong> Please let me know which cryptocurrency and network you will use. Once the payment is sent, please contact us.<br>
                                    <i class="fas fa-info-circle me-1 text-warning"></i>
                                    <strong>Tiếng Việt:</strong> Vui lòng cho biết bạn sử dụng loại tiền điện tử và mạng lưới nào. Sau khi gửi thanh toán, hãy liên hệ với chúng tôi.
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

                            <div class="payment-info text-start">
                                <h5 class="fw-bold mb-3 text-center">Thông tin tài khoản / Account Details</h5>
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
                                    <i class="fas fa-info-circle me-1 text-warning"></i>
                                    <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                    <i class="fas fa-info-circle me-1 text-warning"></i>
                                    <strong>Tiếng Việt:</strong> Sau khi chuyển khoản thành công, hãy liên hệ với chúng tôi.
                                </div>
                            </div>
                        </div>



                        <button type="button" class="btn btn-warning btn-lg px-5 rounded-pill shadow-lg" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                            <i class="fas fa-check-circle me-2"></i>Xác nhận đã thanh toán
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
                <h3 class="confirm-modal-title">Bạn đã thanh toán chưa?</h3>
                <p class="confirm-modal-text">Vui lòng đảm bảo bạn đã chuyển khoản thành công trước khi xác nhận. Đơn hàng ảo hoặc chưa thanh toán sẽ bị hủy tự động.</p>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="btn btn-confirm-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Chưa, để tôi kiểm tra lại
                </button>
                <button type="submit" form="checkout-form" class="btn btn-confirm-ok">
                    <i class="fas fa-check me-2"></i>Có, tôi đã thanh toán
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
                    <i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo Quan Trọng
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-comment-slash text-danger fa-4x"></i>
                </div>
                <h4 class="fw-bold mb-3">Bạn chọn tự liên hệ qua Boxchat?</h4>
                <div class="text-start alert alert-warning mb-0">
                    <ul class="mb-0">
                        <li>Bạn không cần để lại Zalo/Facebook cá nhân.</li>
                        <li><strong>Bắt buộc:</strong> Bạn phải chủ động nhắn tin cho Boxchat của Admin ngay sau khi thanh toán.</li>
                        <li><strong>Lưu ý:</strong> Nếu bạn không nhắn tin, Admin sẽ không có cách nào để gửi mã/hỗ trợ bạn. Chúng tôi sẽ không chịu trách nhiệm trong trường hợp này.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tôi hiểu rồi</button>
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
            zaloLabel.textContent = '(Đã tắt)';
            facebookLabel.textContent = '(Đã tắt)';
            zaloLabel.classList.replace('text-danger', 'text-muted');
            facebookLabel.classList.replace('text-danger', 'text-muted');
        } else {
            contactInputs.forEach(input => input.disabled = false);
            zaloLabel.textContent = '(Bắt buộc nếu không có Facebook)';
            facebookLabel.textContent = '(Bắt buộc nếu không có Zalo)';
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
                showFeedback('Vui lòng nhập mã giảm giá!', 'text-danger');
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
                discountDisplay.textContent = '-' + formatNumber(data.discount_amount) + 'đ';
                discountRow.classList.remove('d-none');
                totalDisplay.textContent = formatNumber(data.final_total) + 'đ';
                if (transferAmountDisplay) {
                    transferAmountDisplay.textContent = formatNumber(data.final_total) + 'đ';
                }
                
                // Update QR image src
                if (qrImage) {
                    const originalSrc = qrImage.src;
                    qrImage.src = originalSrc.replace(/amount=\d+/, 'amount=' + data.final_total);
                }
            })
            .catch(err => {
                showFeedback(err.message || 'Mã giảm giá không hợp lệ!', 'text-danger');
            })
            .finally(() => {
                applyBtn.disabled = false;
                applyBtn.textContent = 'Áp dụng';
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
                totalDisplay.textContent = formatNumber(data.final_total) + 'đ';
                if (transferAmountDisplay) {
                    transferAmountDisplay.textContent = formatNumber(data.final_total) + 'đ';
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
        return num.toLocaleString('vi-VN');
    }
</script>
@endpush
@endsection
