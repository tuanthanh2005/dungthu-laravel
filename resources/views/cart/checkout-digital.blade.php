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
</style>
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">
                    <i class="fas fa-qrcode text-primary me-2"></i>Thanh Toán QR
                </h1>
                <p class="text-muted">Sản phẩm số - Thanh toán nhanh chóng qua QR Code</p>
                            <p class="lead opacity-75 mb-4">Khi bạn thanh toán xong NHÂN VIÊN sẽ liên lạc với bạn hoặc bạn chủ động nhắn tin với thông tin liên hệ bên shop.</p>
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
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">Tổng cộng:</h5>
                                        <h5 class="mb-0 text-primary">{{ number_format($total, 0, ',', '.') }}đ</h5>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- QR Payment -->
                <div class="col-md-7">
                    <div class="qr-payment-card">
                        <div class="mb-3">
                            <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                            <h3 class="fw-bold">Quét mã QR để thanh toán</h3>
                            <p class="opacity-75">Sử dụng ứng dụng ngân hàng của bạn để quét mã</p>
                        </div>

                        <div class="qr-code-box">
                            <img src="https://img.vietqr.io/image/970422-0783704196-print2D.png?amount={{ $total ?? 0 }}&addInfo=AI%20GIA%20RE%20THUDUNG&accountName=TRAN%20THANH%20TUAN" 
                                 alt="QR Code" 
                                 class="img-fluid"
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
                                    <div class="fw-bold text-warning">{{ number_format($total ?? 0, 0, ',', '.') }}đ</div>
                                </div>
                                <div class="col-12">
                                    <small class="opacity-75">Nội dung:</small>
                                    <div class="fw-bold">AI GIA RE THUDUNG</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light mt-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Sau khi thanh toán, vui lòng nhấn nút "Xác nhận đã thanh toán" bên dưới
                        </div>

                        <button type="submit" form="checkout-form" class="btn btn-warning btn-lg px-5 rounded-pill shadow-lg">
                            <i class="fas fa-check-circle me-2"></i>Xác nhận đã thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
