@extends('layouts.app')

@section('title', __('VPN Chuyên Nghiệp - DungThu.com'))
@section('meta_description', __('Dịch vụ VPN tốc độ cao, ổn định, bảo mật. Đa dạng quốc gia, IP sạch, phù hợp cho cá nhân và doanh nghiệp.'))

@push('styles')
<style>
    .product-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .product-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3436;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        color: #e11d48;
        font-weight: 800;
        font-size: 1.2rem;
    }

    .product-original-price {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 0.9rem;
        margin-left: 10px;
    }

    .section-title {
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
        text-align: center;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, #00c6ff, #0072ff);
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h2 class="section-title" data-aos="fade-up">{{ __('Dịch Vụ VPN Chuyên Nghiệp') }}</h2>
    
    @if($vpnProducts->count() > 0)
        <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
            @foreach($vpnProducts as $product)
                <div class="col-md-4 col-lg-3">
                    <div class="card product-card">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="product-img" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                <h3 class="product-title">{{ $product->name }}</h3>
                            </a>
                            <div class="mt-auto pt-3 border-top">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="product-price">{{ $product->formatted_price }}</span>
                                    @if($product->is_on_sale)
                                        <span class="product-original-price">{{ $product->formatted_original_price }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="fas fa-shopping-cart me-2"></i>{{ __('Xem Chi Tiết') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-3 shadow-sm border" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('Chưa có phần mềm VPN nào được thêm...') }}</h5>
        </div>
    @endif
</div>
@endsection
