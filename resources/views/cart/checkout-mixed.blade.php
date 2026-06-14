@extends('layouts.app')

@section('title', 'Thanh Toán - DungThu.com')

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
</style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-shopping-cart text-primary me-2"></i>Thanh Toán
                </h1>
                <p class="text-muted">Đơn hàng của bạn bao gồm cả sản phẩm số và sản phẩm vật lý</p>
            </div>

            <div class="row g-4">
                <!-- Form -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-circle me-2"></i>Thông tin của bạn
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method_input" value="vietqr">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>Họ và tên
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>Email
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                        <small class="text-muted">Để nhận mã sản phẩm số</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-phone me-2 text-primary"></i>Số điện thoại
                                        </label>
                                        <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info py-2 mb-3" style="font-size: 14px;">
                                    <i class="fas fa-headset me-2"></i><strong>Hãy để lại thông tin, Bộ phận hỗ trợ sẽ kết nối và hỗ trợ bạn ngay !</strong>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-comment-dots me-2 text-primary"></i>Zalo <small class="fw-normal text-muted">(Không bắt buộc)</small>
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="customer_zalo" placeholder="Nhập số Zalo của bạn">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fab fa-facebook me-2 text-primary"></i>Link Facebook <small class="fw-normal text-muted">(Không bắt buộc)</small>
                                        </label>
                                        <input type="url" class="form-control form-control-lg" name="customer_facebook" placeholder="Ví dụ: https://facebook.com/username">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-success"></i>Địa chỉ giao hàng
                                        <span class="badge bg-success ms-2">Bắt buộc</span>
                                    </label>
                                    <textarea class="form-control form-control-lg" 
                                              name="customer_address" 
                                              rows="3" 
                                              placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                              required></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Địa chỉ để giao sản phẩm vật lý (sản phẩm số sẽ gửi qua email)
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Hướng dẫn thanh toán -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-info-circle me-2"></i>Hướng dẫn thanh toán
                        </h6>
                        <ol class="mb-0 ps-3">
                            <li>Quét mã QR hoặc chuyển khoản theo thông tin bên phải</li>
                            <li>Nhập đầy đủ thông tin và địa chỉ giao hàng</li>
                            <li>Nhấn "Xác nhận đặt hàng"</li>
                            <li>Mã sản phẩm số sẽ được gửi qua email sau khi xác nhận thanh toán</li>
                            <li>Sản phẩm vật lý sẽ được giao trong 3-5 ngày</li>
                        </ol>
                    </div>
                </div>

                <!-- Order Summary & QR Payment -->
                <div class="col-md-5">
                    <!-- Đơn hàng -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
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
                                    <i class="fas fa-cloud-download-alt me-2"></i>Sản phẩm số
                                </h6>
                                @foreach($digitalProducts as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-break" style="max-width: 75%;">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <strong class="text-nowrap">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</strong>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if(count($physicalProducts) > 0)
                            <div class="payment-section">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-box me-2"></i>Sản phẩm vật lý
                                </h6>
                                @foreach($physicalProducts as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-break" style="max-width: 75%;">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <strong class="text-nowrap">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</strong>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            <hr>
                            <!-- Mã giảm giá -->
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-2 small text-muted">Mã giảm giá (nếu có)</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="coupon_code" class="form-control" placeholder="Nhập mã giảm giá" value="{{ $couponCode ?? '' }}" {{ isset($couponCode) ? 'disabled' : '' }}>
                                    <button class="btn btn-primary" type="button" id="apply-coupon-btn" style="{{ isset($couponCode) ? 'display:none;' : '' }}">Áp dụng</button>
                                    <button class="btn btn-danger" type="button" id="remove-coupon-btn" style="{{ isset($couponCode) ? '' : 'display:none;' }}">Hủy</button>
                                </div>
                                <div id="coupon-feedback" class="small mt-1 d-none text-danger"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-2 {{ isset($discountAmount) && $discountAmount > 0 ? '' : 'd-none' }}" id="discount-row">
                                <span class="text-danger fw-bold">Giảm giá:</span>
                                <strong class="text-danger" id="discount-display">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</strong>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">Tổng cộng:</h5>
                                <h5 class="mb-0 text-primary" id="checkout-total-display">{{ number_format($finalTotal ?? $total, 0, ',', '.') }}đ</h5>
                            </div>
                            
                            <button type="submit" form="checkout-form" class="btn btn-primary w-100 btn-lg rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>Xác nhận đặt hàng
                            </button>
                        </div>
                    </div>

                    <!-- QR Payment -->
                    <div class="card border-0 shadow-sm text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; overflow: hidden;">
                        <div class="card-body p-4">
                            <!-- Navigation Tabs -->
                            <div class="payment-tabs">
                                <button type="button" class="payment-tab-btn active" data-target="vietqr-panel" data-method="vietqr">
                                    <i class="fas fa-university me-1"></i> VietQR
                                </button>
                                <button type="button" class="payment-tab-btn" data-target="crypto-panel" data-method="crypto">
                                    <i class="fas fa-coins me-1"></i> Crypto
                                </button>
                                <button type="button" class="payment-tab-btn" data-target="binance-panel" data-method="binance_uid">
                                    <i class="fab fa-bitcoin me-1"></i> Binance
                                </button>
                            </div>

                            <!-- PANEL 1: VietQR -->
                            <div id="vietqr-panel" class="payment-tab-content active">
                                <h6 class="fw-bold mb-2">Thanh toán qua QR Code</h6>
                                <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                    <img src="https://img.vietqr.io/image/970422-0783704196-print2D.png?amount={{ $finalTotal ?? $total ?? 0 }}&addInfo=AI%20GIA%20RE%20THUDUNG&accountName=TRAN%20THANH%20TUAN" 
                                         alt="QR Code" 
                                         id="qr-code-image"
                                         style="max-width: 200px;">
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.9rem;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="opacity-75">Ngân hàng:</small>
                                            <div class="fw-bold">MB BANK</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="opacity-75">STK:</small>
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
                                <h6 class="fw-bold mb-2">Thanh toán qua Ví Crypto (USDT,...)</h6>
                                <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9" 
                                         alt="Crypto QR" 
                                         style="max-width: 180px;">
                                </div>
                                <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px; font-size: 0.85rem;">
                                    <div class="mb-2">
                                        <small class="opacity-75 d-block">Địa chỉ ví (Wallet Address):</small>
                                        <div class="d-flex align-items-center justify-content-between mt-1" style="background: rgba(0,0,0,0.2); padding: 5px; border-radius: 5px;">
                                            <code class="text-warning text-break" style="font-size: 0.8rem; font-family: monospace;">0xB890Ed41f9De4412c2...</code>
                                            <button type="button" class="copy-btn text-nowrap" onclick="copyToClipboard('0xB890Ed41f9De4412c219CaB2254FD8c0Aa56dEE9', this)">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 8px; line-height: 1.4;">
                                        <strong>English:</strong> Please let me know cryptocurrency & network. Contact us after sending.<br>
                                        <strong>Tiếng Việt:</strong> Vui lòng cho biết loại tiền & mạng lưới. Liên hệ sau khi thanh toán.
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL 3: Binance UID -->
                            <div id="binance-panel" class="payment-tab-content">
                                <h6 class="fw-bold mb-2">Thanh toán qua Binance Pay / UID</h6>
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
                                        <strong>English:</strong> Once the payment is sent, please contact us.<br>
                                        <strong>Tiếng Việt:</strong> Sau khi chuyển khoản thành công, hãy liên hệ với chúng tôi.
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
        feedbackEl.className = 'small mt-1 ' + className;
        feedbackEl.classList.remove('d-none');
    }

    function formatNumber(num) {
        return num.toLocaleString('vi-VN');
    }
</script>
@endpush
