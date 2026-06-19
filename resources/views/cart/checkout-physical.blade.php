@extends('layouts.app')

@section('title', __('Thanh Toán & Giao Hàng') . ' - DungThu.com')

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
    .shipping-card {
        border-left: 4px solid #06d6a0;
    }
    .delivery-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
</style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-shipping-fast text-success me-2"></i>{{ __('Thanh Toán & Giao Hàng') }}
                </h1>
                <p class="text-muted">{{ __('Vui lòng điền đầy đủ thông tin để nhận hàng') }}</p>
            </div>

            <div class="row g-4">
                <!-- Form thông tin -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm shipping-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-map-marker-alt text-success me-2"></i>{{ __('Thông tin giao hàng') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>{{ __('Họ và tên người nhận') }}
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>{{ __('Email') }}
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-phone me-2 text-primary"></i>{{ __('Số điện thoại') }}
                                        </label>
                                        <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                    </div>
                                </div>
                                

                                <div class="row g-3 mb-4">
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
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-home me-2 text-primary"></i>{{ __('Địa chỉ giao hàng') }}
                                    </label>
                                    <textarea class="form-control form-control-lg" 
                                              name="customer_address" 
                                              rows="3" 
                                              placeholder="{{ __('Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố') }}"
                                              required></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('Vui lòng nhập địa chỉ đầy đủ để đảm bảo giao hàng chính xác') }}
                                    </small>
                                </div>

                                <div class="alert alert-success">
                                    <div class="d-flex align-items-center">
                                        <div class="delivery-icon me-3">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ __('Giao hàng miễn phí') }}</h6>
                                            <small>{{ __('Dự kiến giao trong 3-5 ngày làm việc') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('Ghi chú (tùy chọn)') }}</label>
                                    <textarea class="form-control" name="note" rows="2" placeholder="{{ __('Thời gian giao hàng mong muốn, ghi chú đặc biệt...') }}"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-shopping-bag me-2"></i>{{ __('Đơn hàng của bạn') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                @php $total = 0 @endphp
                                @foreach($cart as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $details['image'] ?? 'https://via.placeholder.com/50' }}" 
                                             alt="{{ $details['name'] }}" 
                                             class="rounded me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div style="max-width: 220px;">
                                            <h6 class="mb-0 text-break">{{ $details['name'] }}</h6>
                                            <small class="text-muted">{{ __('SL') }}: {{ $details['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="text-muted">{{ $formatPrice($details['price'] * $details['quantity']) }}</span>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="border-top pt-3">
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

                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('Tạm tính:') }}</span>
                                    <span>{{ $formatPrice($total) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2 {{ isset($discountAmount) && $discountAmount > 0 ? '' : 'd-none' }}" id="discount-row">
                                    <span class="text-danger fw-bold">{{ __('Giảm giá:') }}</span>
                                    <strong class="text-danger" id="discount-display">-{{ $formatPrice($discountAmount ?? 0) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('Phí vận chuyển:') }}</span>
                                    <span class="text-success fw-bold">{{ __('Miễn phí') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="mb-0">{{ __('Tổng cộng:') }}</h5>
                                    <h5 class="mb-0 text-primary" id="checkout-total-display">{{ $formatPrice($finalTotal ?? $total) }}</h5>
                                </div>
                            </div>
                            
                            <button type="submit" form="checkout-form" class="btn btn-success w-100 btn-lg rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>{{ __('Đặt Hàng Ngay') }}
                            </button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ __('Thanh toán an toàn & bảo mật') }}
                                </small>
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
</script>
@endpush
