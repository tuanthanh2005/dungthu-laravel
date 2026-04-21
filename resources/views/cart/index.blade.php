@extends('layouts.app')

@section('title', 'Giỏ Hàng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        /* Modern Cart Layout */
        .cart-header {
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.05) 0%, rgba(0, 206, 201, 0.05) 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .cart-header h1 {
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }
        .cart-header p {
            color: #636e72;
            margin: 0;
        }
        
        /* Cart Item Styling */
        .cart-item {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .cart-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.08);
            border-color: rgba(108, 92, 231, 0.2);
        }
        
        .cart-item-img-wrap {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
        }
        .cart-item-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .cart-item-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .cart-item-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 0.3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .cart-item-price {
            color: #636e72;
            font-size: 0.95rem;
        }
        
        /* Quantity Controls */
        .qty-wrapper {
            display: flex;
            align-items: center;
            background: #f1f2f6;
            border-radius: 10px;
            padding: 4px;
        }
        .btn-qty {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fff;
            border: none;
            color: #6c5ce7;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .btn-qty:hover {
            background: #6c5ce7;
            color: #fff;
        }
        .qty-input {
            width: 40px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 700;
            color: #2d3436;
        }
        .qty-input:focus {
            outline: none;
        }
        
        /* Total & Actions */
        .cart-item-total {
            font-size: 1.15rem;
            font-weight: 800;
            color: #6c5ce7;
            min-width: 120px;
            text-align: right;
        }
        
        .btn-delete {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background: #ff4757;
            color: #fff;
            transform: scale(1.05) rotate(5deg);
        }

        /* Summary Section */
        .cart-summary {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(108, 92, 231, 0.2);
            position: sticky;
            top: 100px;
        }
        .summary-title {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            font-size: 1.05rem;
        }
        .summary-row.total-row {
            border-bottom: none;
            font-size: 1.4rem;
            font-weight: 800;
            padding-top: 1.5rem;
            margin-top: 0.5rem;
        }
        .btn-checkout {
            background: #fff;
            color: #6c5ce7;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 800;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s;
            margin-top: 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
            background: #f8f9fa;
        }
        
        .trust-badges {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        /* Empty Cart */
        .empty-cart {
            background: #fff;
            border-radius: 24px;
            padding: 5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
            margin: 2rem 0;
        }
        .empty-icon {
            font-size: 6rem;
            background: linear-gradient(135deg, #dfe4ea, #ced6e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        /* --- MOBILE RESPONSIVE TWEAKS --- */
        @media (max-width: 768px) {
            .cart-header {
                padding: 2rem 1rem;
                margin-top: 70px;
                border-radius: 16px;
                margin-bottom: 1.5rem;
            }
            .cart-header h1 {
                font-size: 1.8rem;
            }
            .cart-header p {
                font-size: 0.9rem;
            }
            
            .cart-item {
                flex-direction: column;
                align-items: stretch;
                padding: 1.2rem;
                gap: 1rem;
                border-radius: 16px;
            }
            
            .cart-item-top {
                display: flex;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .cart-item-img-wrap {
                width: 80px;
                height: 80px;
            }
            
            .cart-item-title {
                font-size: 1rem;
            }
            
            .cart-item-price {
                font-size: 0.9rem;
                color: #6c5ce7;
                font-weight: 600;
            }
            
            .cart-item-bottom {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 1rem;
                border-top: 1px dashed #eee;
            }
            
            .cart-item-total {
                font-size: 1.1rem;
                min-width: auto;
                text-align: left;
            }
            
            .qty-wrapper {
                padding: 3px;
            }
            .btn-qty {
                width: 28px;
                height: 28px;
                border-radius: 6px;
            }
            .qty-input {
                width: 35px;
                font-size: 0.9rem;
            }
            
            .btn-delete {
                width: 35px;
                height: 35px;
                border-radius: 8px;
            }
            
            .cart-summary {
                padding: 1.5rem;
                border-radius: 16px;
            }
            .summary-title {
                font-size: 1.2rem;
            }
            .summary-row {
                font-size: 0.95rem;
                padding: 10px 0;
            }
            .summary-row.total-row {
                font-size: 1.25rem;
            }
            .btn-checkout {
                padding: 12px;
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-4" style="margin-top: 80px;">
    <!-- Header -->
    <div class="cart-header" data-aos="fade-down">
        <h1>Giỏ Hàng Của Bạn</h1>
        <p>Kiểm tra lại các sản phẩm trước khi tiến hành thanh toán</p>
    </div>
    
    @if(session('cart') && count(session('cart')) > 0)
    <div class="row">
        <!-- Cart Items List -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            @php $total = 0 @endphp
            @foreach(session('cart') as $id => $details)
            @php $total += $details['price'] * $details['quantity'] @endphp
            <div class="cart-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <!-- Desktop view combines these, Mobile view splits them using CSS classes -->
                <div class="cart-item-top w-100 d-flex gap-3 align-items-center">
                    <div class="cart-item-img-wrap">
                        <img src="{{ $details['image'] ?? 'https://via.placeholder.com/200' }}" alt="{{ $details['name'] }}">
                    </div>
                    
                    <div class="cart-item-details">
                        <a href="{{ route('product.show', \Str::slug($details['name'])) }}" class="text-decoration-none">
                            <h3 class="cart-item-title">{{ $details['name'] }}</h3>
                        </a>
                        <div class="cart-item-price">{{ number_format($details['price'], 0, ',', '.') }}đ</div>
                    </div>
                    
                    <!-- Hide on mobile, show on desktop -->
                    <div class="d-none d-md-flex align-items-center gap-4 ms-auto">
                        <div class="qty-wrapper">
                            <form action="{{ route('cart.decrement', $id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="btn-qty" aria-label="Giảm"><i class="fas fa-minus"></i></button>
                            </form>
                            <input type="number" value="{{ $details['quantity'] }}" class="qty-input" readonly>
                            <form action="{{ route('cart.increment', $id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="btn-qty" aria-label="Tăng"><i class="fas fa-plus"></i></button>
                            </form>
                        </div>
                        
                        <div class="cart-item-total">
                            {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ
                        </div>
                        
                        <a href="{{ route('cart.remove', $id) }}" class="btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Show ONLY on mobile -->
                <div class="cart-item-bottom d-flex d-md-none w-100">
                    <div class="qty-wrapper">
                        <form action="{{ route('cart.decrement', $id) }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="btn-qty"><i class="fas fa-minus"></i></button>
                        </form>
                        <input type="number" value="{{ $details['quantity'] }}" class="qty-input" readonly>
                        <form action="{{ route('cart.increment', $id) }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="btn-qty"><i class="fas fa-plus"></i></button>
                        </form>
                    </div>
                    
                    <div class="cart-item-total">
                        {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ
                    </div>
                    
                    <a href="{{ route('cart.remove', $id) }}" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>
            @endforeach

            <!-- Continue Shopping Button -->
            <div class="mt-4" data-aos="fade-up">
                <a href="{{ route('shop') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" style="color: #636e72;">
                    <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="cart-summary" data-aos="fade-left" data-aos-delay="100">
                <div class="summary-title">
                    <i class="fas fa-receipt"></i> Tóm tắt đơn hàng
                </div>
                
                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                
                <div class="summary-row">
                    <span>Phí vận chuyển</span>
                    <span class="text-warning fw-bold">Miễn phí</span>
                </div>
                
                <div class="summary-row total-row">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-checkout">
                    <i class="fas fa-credit-card me-2"></i> Thanh Toán Ngay
                </a>

                <div class="trust-badges">
                    <div class="trust-badge">
                        <i class="fas fa-shield-alt fs-5 text-warning"></i>
                        <span>Thanh toán an toàn & bảo mật</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-bolt fs-5 text-warning"></i>
                        <span>Giao hàng / Xử lý cực nhanh</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-headset fs-5 text-warning"></i>
                        <span>Hỗ trợ khách hàng 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart State -->
    <div class="empty-cart" data-aos="zoom-in">
        <i class="fas fa-shopping-basket empty-icon"></i>
        <h2 class="fw-bold mb-3" style="color: #2d3436;">Giỏ hàng trống</h2>
        <p class="text-muted mb-4 fs-5">Bạn chưa chọn sản phẩm nào vào giỏ hàng.</p>
        <a href="{{ route('shop') }}" class="btn btn-lg rounded-pill px-5 shadow-sm" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: white; border: none; font-weight: 700;">
            <i class="fas fa-shopping-bag me-2"></i> Khám Phá Sản Phẩm Ngay
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 800, once: true });
            }
        });
    </script>
@endpush
