@extends('layouts.app')

@section('title', 'Thanh Toán & Giao Hàng - DungThu.com')

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
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-shipping-fast text-success me-2"></i>Thanh Toán & Giao Hàng
                </h1>
                <p class="text-muted">Vui lòng điền đầy đủ thông tin để nhận hàng</p>
            </div>

            <div class="row g-4">
                <!-- Form thông tin -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm shipping-card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-map-marker-alt text-success me-2"></i>Thông tin giao hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>Họ và tên người nhận
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="customer_name" required>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-envelope me-2 text-primary"></i>Email
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="customer_email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-phone me-2 text-primary"></i>Số điện thoại
                                        </label>
                                        <input type="tel" class="form-control form-control-lg" name="customer_phone" required>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-home me-2 text-primary"></i>Địa chỉ giao hàng
                                    </label>
                                    <textarea class="form-control form-control-lg" 
                                              name="customer_address" 
                                              rows="3" 
                                              placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                              required></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Vui lòng nhập địa chỉ đầy đủ để đảm bảo giao hàng chính xác
                                    </small>
                                </div>

                                <div class="alert alert-success">
                                    <div class="d-flex align-items-center">
                                        <div class="delivery-icon me-3">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Giao hàng miễn phí</h6>
                                            <small>Dự kiến giao trong 3-5 ngày làm việc</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control" name="note" rows="2" placeholder="Thời gian giao hàng mong muốn, ghi chú đặc biệt..."></textarea>
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
                                <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn
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
                                        <div>
                                            <h6 class="mb-0">{{ $details['name'] }}</h6>
                                            <small class="text-muted">SL: {{ $details['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="text-muted">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</span>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span class="text-success fw-bold">Miễn phí</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="mb-0">Tổng cộng:</h5>
                                    <h5 class="mb-0 text-primary">{{ number_format($total, 0, ',', '.') }}đ</h5>
                                </div>
                            </div>
                            
                            <button type="submit" form="checkout-form" class="btn btn-success w-100 btn-lg rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>Đặt Hàng Ngay
                            </button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Thanh toán an toàn & bảo mật
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
