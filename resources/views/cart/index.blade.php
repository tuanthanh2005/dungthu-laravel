@extends('layouts.app')

@section('title', 'Giỏ Hàng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .cart-item {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        .cart-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 100px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.4rem;
            font-weight: bold;
            padding-top: 20px;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            border-radius: 8px;
            border: 2px solid var(--primary);
        }

        .qty-control {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .btn-qty {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .btn-delete {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            transition: transform 0.2s;
        }
        .btn-delete:hover {
            transform: scale(1.05);
            color: white;
        }
        .empty-cart {
            background: white;
            border-radius: 20px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
    </style>
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12 text-center">
            <span class="text-primary fw-bold text-uppercase ls-1">Thanh toán</span>
            <h1 class="fw-bold mb-3">Giỏ Hàng Của Bạn</h1>
        </div>
    </div>
    
    @if(session('cart') && count(session('cart')) > 0)
    <div class="row">
        <div class="col-lg-8">
            @php $total = 0 @endphp
            @foreach(session('cart') as $id => $details)
            @php $total += $details['price'] * $details['quantity'] @endphp
            <div class="cart-item" data-aos="fade-up">
                <div class="row align-items-center g-1">
                    <div class="col-2">
                        <img src="{{ $details['image'] ?? 'https://via.placeholder.com/100' }}" 
                             class="cart-item-image w-100" 
                             alt="{{ $details['name'] }}">
                    </div>
                    <div class="col-5">
                        <h6 class="fw-bold mb-1" style="font-size: 0.85rem;">{{ Str::limit($details['name'], 25) }}</h6>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ number_format($details['price'], 0, ',', '.') }}đ</p>
                    </div>
                    <div class="col-5 text-end">
                        <span class="fw-bold text-primary" style="font-size: 0.85rem;">
                            {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-1 align-items-center mt-2">
                    <form action="{{ route('cart.decrement', $id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" style="width: 24px; height: 24px; padding: 0; font-size: 0.7rem;" aria-label="Giảm">
                            <i class="fas fa-minus"></i>
                        </button>
                    </form>

                    <input type="number"
                           value="{{ $details['quantity'] }}"
                           class="form-control"
                           readonly
                           style="width: 35px; height: 24px; font-size: 0.75rem; padding: 2px; text-align: center;">

                    <form action="{{ route('cart.increment', $id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" style="width: 24px; height: 24px; padding: 0; font-size: 0.7rem;" aria-label="Tăng">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>
                    
                    <div class="ms-auto">
                        <a href="{{ route('cart.remove', $id) }}" 
                           class="btn btn-delete btn-sm"
                           style="padding: 4px 10px; font-size: 0.75rem;"
                           onclick="return confirm('Xóa?')">
                            <i class="fas fa-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="mt-3" data-aos="fade-up">
                <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="cart-summary" data-aos="fade-left">
                <h4 class="fw-bold mb-4"><i class="fas fa-receipt me-2"></i> Tóm tắt đơn hàng</h4>
                
                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                
                <div class="summary-row">
                    <span>Phí vận chuyển</span>
                    <span class="text-warning">Miễn phí</span>
                </div>
                
                <div class="summary-row">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-light btn-lg w-100 mt-4 fw-bold shadow">
                    <i class="fas fa-credit-card me-2"></i> Thanh toán
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>

                <div class="mt-4 pt-3 border-top border-light border-opacity-25">
                    <p class="small mb-2"><i class="fas fa-shield-alt me-2"></i> Thanh toán an toàn & bảo mật</p>
                    <p class="small mb-2"><i class="fas fa-truck me-2"></i> Giao hàng nhanh toàn quốc</p>
                    <p class="small mb-0"><i class="fas fa-undo me-2"></i> Đổi trả trong 7 ngày</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="empty-cart" data-aos="zoom-in">
        <div style="font-size: 80px; color: var(--primary); margin-bottom: 20px;">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h3 class="fw-bold mb-3">Giỏ hàng của bạn đang trống</h3>
        <p class="text-muted mb-4">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
            <i class="fas fa-shopping-bag me-2"></i> Mua sắm ngay
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
@endpush
