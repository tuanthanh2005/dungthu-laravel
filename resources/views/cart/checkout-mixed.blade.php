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
                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <div class="card-body text-center p-4">
                            <h6 class="fw-bold mb-3">Thanh toán qua QR Code</h6>
                            <div class="bg-white p-3 rounded mb-3" style="display: inline-block;">
                                <img src="https://img.vietqr.io/image/970422-0783704196-print2D.png?amount={{ $finalTotal ?? $total ?? 0 }}&addInfo=AI%20GIA%20RE%20THUDUNG&accountName=TRAN%20THANH%20TUAN" 
                                     alt="QR Code" 
                                     id="qr-code-image"
                                     style="max-width: 200px;">
                            </div>
                            <div class="text-start" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px;">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="opacity-75">Ngân hàng:</small>
                                        <div class="fw-bold">MB BANK</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="opacity-75">STK:</small>
                                        <div class="fw-bold">0783704196</div>
                                    </div>
                                    <div class="col-12">
                                        <small class="opacity-75">Số tiền:</small>
                                        <div class="fw-bold text-warning" id="transfer-amount-display">{{ number_format($finalTotal ?? $total ?? 0, 0, ',', '.') }}đ</div>
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
