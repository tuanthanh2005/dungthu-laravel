@extends('layouts.app')

@section('title', __('Thanh Toán - DungThu.com'))

@section('content')
@php
    $locale = app()->getLocale();
    $exchangeRate = doubleval(\App\Models\SiteSetting::getValue('usd_exchange_rate', '25000'));
    $formatPrice = function($amount) use ($locale, $exchangeRate) {
        if ($locale === 'en') {
            return '$' . number_format($amount / $exchangeRate, 2, '.', ',');
        }
        $locale = app()->getLocale();
    if ($locale === 'en') {
        return '$' . number_format($amount / 25000, 2, '.', ',');
    }
    return number_format($amount, 0, ',', '.') . 'đ';
    };
@endphp

<div class="container py-2">
    <h1 class="mb-4 fw-bold text-center">{{ __('Thanh Toán') }}</h1>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">{{ __('Thông tin giao hàng') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">{{ __('Họ và tên') }}</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">{{ __('Số điện thoại') }}</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">{{ __('Địa chỉ giao hàng') }}</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">{{ __('Đơn hàng của bạn') }}</h5>
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
                            <span class="text-muted">{{ $formatPrice($details['price'] * $details['quantity']) }}</span>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Tổng cộng') }}</span>
                            <strong class="text-primary">{{ $formatPrice($total) }}</strong>
                        </li>
                    </ul>
                    
                    <button type="submit" form="checkout-form" class="btn btn-primary w-100 btn-lg rounded-pill shadow">{{ __('Đặt Hàng Ngay') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
