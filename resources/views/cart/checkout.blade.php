@extends('layouts.app')

@section('title', 'Thanh Toán - DungThu.com')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold text-center">Thanh Toán</h1>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @php $total = 0 @endphp
                        @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">{{ $details['name'] }}</h6>
                                <small class="text-muted">x {{ $details['quantity'] }}</small>
                            </div>
                            <span class="text-muted">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tổng cộng (VND)</span>
                            <strong class="text-primary">{{ number_format($total, 0, ',', '.') }}đ</strong>
                        </li>
                    </ul>
                    
                    <button type="submit" form="checkout-form" class="btn btn-primary w-100 btn-lg rounded-pill shadow">Đặt Hàng Ngay</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
