@extends('layouts.app')

@section('title', 'Giỏ Hàng - DungThu.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
:root {
    --primary: #667eea; --secondary: #764ba2;
    --dark: #1a1a2e; --muted: #6b7280;
    --bg: #f8faff; --card-radius: 16px;
    --card-shadow: 0 4px 20px rgba(102,126,234,0.09);
}
body { background: var(--bg); }

/* Breadcrumb */
.breadcrumb-bar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 10px 0; }
.breadcrumb { margin: 0; background: transparent; padding: 0; font-size: 0.82rem; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--primary); }
.breadcrumb-item a { color: var(--primary); text-decoration: none; font-weight: 600; }

/* Page header */
.cart-page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 22px 0 20px; border-bottom: 3px solid var(--primary);
}
.cart-page-header h1 {
    font-size: 1.6rem; font-weight: 900;
    background: linear-gradient(90deg, #fff, #a78bfa);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin: 0;
}

/* Layout */
.cart-layout {
    display: grid; grid-template-columns: 1fr 320px;
    gap: 24px; max-width: 1100px;
    margin: 0 auto; padding: 24px 16px 48px;
}
@media (max-width: 991px) { .cart-layout { grid-template-columns: 1fr; } }

/* Cart item */
.cart-item-card {
    background: #fff; border-radius: 14px;
    padding: 14px 16px; margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid rgba(102,126,234,0.07);
    transition: transform .2s, box-shadow .2s;
}
.cart-item-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(102,126,234,0.12); }
.cart-item-img { width: 72px; height: 72px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }
.cart-item-name { font-size: 0.9rem; font-weight: 700; color: var(--dark); margin-bottom: 2px; word-break: break-word; }
.cart-item-unit-price { font-size: 0.78rem; color: var(--muted); }
.cart-item-total { font-size: 1rem; font-weight: 800; color: var(--primary); white-space: nowrap; }

.qty-ctrl { display: inline-flex; align-items: center; gap: 6px; }
.qty-btn {
    width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid var(--primary);
    background: #f0f4ff; color: var(--primary); font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .15s;
}
.qty-btn:hover { background: var(--primary); color: #fff; }
.qty-display {
    width: 36px; height: 28px; text-align: center;
    border: 1.5px solid #e5e7eb; border-radius: 8px;
    font-size: 0.85rem; font-weight: 700; color: var(--dark);
}
.del-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 999px; border: none;
    background: linear-gradient(90deg, #f093fb, #f5576c);
    color: #fff; font-size: 0.75rem; font-weight: 700;
    cursor: pointer; transition: opacity .15s;
}
.del-btn:hover { opacity: 0.85; }

/* Ad block */
.ad-block { background: #fff; border: 1px dashed rgba(102,126,234,0.18); border-radius: 12px; overflow: hidden; margin: 14px 0; }
.ad-label { font-size: 0.62rem; color: #bbb; text-align: center; padding: 3px 0; background: #fafafa; border-bottom: 1px solid #eee; letter-spacing: 0.1em; text-transform: uppercase; }

/* Summary card */
.summary-card {
    background: #fff; border-radius: var(--card-radius);
    box-shadow: var(--card-shadow); border: 1px solid rgba(102,126,234,0.1);
    overflow: hidden; position: sticky; top: 92px;
}
.summary-card-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 16px 20px; color: #fff;
}
.summary-card-header h4 { font-size: 1rem; font-weight: 800; margin: 0; }
.summary-card-body { padding: 18px 20px; }
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.88rem;
}
.summary-row:last-child { border-bottom: none; }
.summary-row.total { font-size: 1.1rem; font-weight: 900; color: var(--dark); padding-top: 14px; }
.summary-row.total span:last-child { color: var(--primary); }

.checkout-btn {
    display: block; width: 100%; padding: 14px;
    border: none; border-radius: 999px; cursor: pointer;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    color: #fff; font-weight: 800; font-size: 1rem;
    text-align: center; text-decoration: none;
    transition: opacity .15s, transform .15s;
    margin-bottom: 10px;
}
.checkout-btn:hover { opacity: 0.88; transform: translateY(-1px); color: #fff; }
.continue-btn {
    display: block; text-align: center; padding: 10px;
    border-radius: 999px; border: 2px solid #e5e7eb;
    color: var(--muted); font-weight: 700; font-size: 0.875rem;
    text-decoration: none; transition: border-color .15s, color .15s;
}
.continue-btn:hover { border-color: var(--primary); color: var(--primary); }

.trust-mini { padding: 14px 20px; border-top: 1px solid #f0f4ff; }
.trust-mini-item { display: flex; gap: 8px; align-items: center; font-size: 0.78rem; color: var(--muted); margin-bottom: 6px; }
.trust-mini-item:last-child { margin-bottom: 0; }
.trust-mini-item i { color: var(--primary); width: 16px; flex-shrink: 0; }

/* Empty cart */
.empty-cart {
    background: #fff; border-radius: 20px; padding: 56px 20px;
    text-align: center; box-shadow: var(--card-shadow);
    border: 1px solid rgba(102,126,234,0.08);
}

/* Sidebar */
.sidebar-widget { background: #fff; border-radius: var(--card-radius); padding: 18px; box-shadow: var(--card-shadow); border: 1px solid rgba(102,126,234,0.08); margin-bottom: 20px; }
.sidebar-widget-title { font-size: 0.88rem; font-weight: 800; color: var(--dark); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f4ff; display: flex; align-items: center; gap: 7px; }
.sidebar-widget-title i { color: var(--primary); }
.sidebar-prod { display: flex; gap: 10px; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-decoration: none; color: inherit; overflow: hidden; }
.sidebar-prod:last-child { border-bottom: none; }
.sidebar-prod:hover { opacity: 0.85; }
.sidebar-prod img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sidebar-prod > div { min-width: 0; flex: 1; overflow: hidden; }
.sidebar-prod .sp-name { font-size: 0.8rem; font-weight: 700; color: var(--dark); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word; }
.sidebar-prod .sp-price { font-size: 0.8rem; font-weight: 800; color: var(--primary); margin-top: 2px; }

@media (max-width: 576px) {
    .cart-page-header h1 { font-size: 1.2rem; }
    .cart-item-card { padding: 10px; }
    .cart-item-img { width: 56px; height: 56px; }
}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="cart-page-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);margin-bottom:4px;">
                    <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.5);text-decoration:none;">Trang chủ</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('shop') }}" style="color:rgba(255,255,255,0.5);text-decoration:none;">Cửa hàng</a>
                    <span class="mx-1">/</span>
                    <span style="color:rgba(255,255,255,0.8);">Giỏ hàng</span>
                </div>
                <h1>🛒 Giỏ Hàng Của Bạn</h1>
            </div>
            {{-- AdSense header --}}
            <div class="d-none d-md-block">
                <div style="font-size:0.6rem;color:rgba(255,255,255,0.35);text-align:center;letter-spacing:0.08em;margin-bottom:2px;">QUẢNG CÁO</div>
                <ins class="adsbygoogle" style="display:inline-block;width:250px;height:80px;"
                    data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>
    </div>
</div>

{{-- CART LAYOUT --}}
<div class="cart-layout">

    @if(session('cart') && count(session('cart')) > 0)

    {{-- CART ITEMS --}}
    <div>

        {{-- AdSense top --}}
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <span style="width:4px;height:22px;border-radius:4px;background:linear-gradient(180deg,var(--primary),var(--secondary));flex-shrink:0;display:inline-block;"></span>
            <h2 style="font-size:1.05rem;font-weight:800;color:var(--dark);margin:0;">
                🛍️ Sản phẩm trong giỏ ({{ count(session('cart')) }})
            </h2>
        </div>

        @php $total = 0; @endphp
        @foreach(session('cart') as $id => $details)
        @php $total += $details['price'] * $details['quantity']; @endphp

        {{-- AdSense giữa danh sách sau 3 sản phẩm --}}
        @if($loop->iteration === 4)
        <div class="ad-block">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>
        @endif

        <div class="cart-item-card">
            <div class="d-flex gap-3 align-items-start">
                <img src="{{ $details['image'] ?? 'https://via.placeholder.com/80' }}"
                     class="cart-item-img" alt="{{ $details['name'] }}">
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="cart-item-name">{{ $details['name'] }}</div>
                    <div class="cart-item-unit-price">{{ number_format($details['price'], 0, ',', '.') }}đ / sp</div>
                    <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                        {{-- Giảm --}}
                        <form action="{{ route('cart.decrement', $id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="qty-btn"><i class="fas fa-minus" style="font-size:10px;"></i></button>
                        </form>
                        <input type="number" value="{{ $details['quantity'] }}" class="qty-display" readonly>
                        {{-- Tăng --}}
                        <form action="{{ route('cart.increment', $id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="qty-btn"><i class="fas fa-plus" style="font-size:10px;"></i></button>
                        </form>

                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="cart-item-total">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</span>
                            <a href="{{ route('cart.remove', $id) }}" class="del-btn" onclick="return confirm('Xóa sản phẩm này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Tiếp tục mua --}}
        <div class="mt-3">
            <a href="{{ route('shop') }}" style="display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:999px;border:2px solid #e5e7eb;color:var(--muted);font-weight:700;font-size:0.88rem;text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>

        {{-- AdSense cuối cart --}}
        <div class="ad-block mt-3">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>

        {{-- Blog gợi ý --}}
        <div class="sidebar-widget mt-3">
            <div class="sidebar-widget-title"><i class="fas fa-lightbulb"></i> Có thể bạn muốn đọc</div>
            @php $cartBlogs = \App\Models\Blog::published()->orderBy('views','desc')->take(3)->get(); @endphp
            @foreach($cartBlogs as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" style="display:flex;gap:10px;align-items:flex-start;padding:8px 0;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;">
                <img src="{{ $blog->image ?? 'https://via.placeholder.com/56x44' }}" alt="{{ $blog->title }}" style="width:56px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                <div>
                    <div style="font-size:0.8rem;font-weight:600;color:var(--dark);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.3;">{{ $blog->title }}</div>
                    <div style="font-size:0.7rem;color:var(--muted);margin-top:2px;"><i class="far fa-eye"></i> {{ number_format($blog->views) }}</div>
                </div>
            </a>
            @endforeach
        </div>

    </div>

    {{-- ORDER SUMMARY --}}
    <div>
        <div class="summary-card">
            <div class="summary-card-header">
                <h4><i class="fas fa-receipt me-2"></i> Tóm Tắt Đơn Hàng</h4>
            </div>
            <div class="summary-card-body">
                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển</span>
                    <span style="color:#10b981;font-weight:700;">✓ Miễn phí</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
            </div>

            {{-- AdSense trong summary --}}
            <div style="padding:0 20px;">
                <div class="ad-block" style="margin:0 0 14px;">
                    <div class="ad-label">Quảng Cáo</div>
                    <ins class="adsbygoogle" style="display:block;min-height:120px;"
                        data-ad-client="ca-pub-3065867660863139"
                        data-ad-slot="4989157975" data-ad-format="auto"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                </div>
            </div>

            <div style="padding:0 20px 20px;">
                <a href="{{ route('checkout') }}" class="checkout-btn">
                    <i class="fas fa-credit-card me-2"></i> Thanh toán ngay →
                </a>
                <a href="{{ route('shop') }}" class="continue-btn">
                    ← Tiếp tục mua sắm
                </a>
            </div>

            <div class="trust-mini">
                <div class="trust-mini-item"><i class="fas fa-shield-alt"></i> Thanh toán an toàn & bảo mật 100%</div>
                <div class="trust-mini-item"><i class="fas fa-bolt"></i> Giao hàng số tức thì sau thanh toán</div>
                <div class="trust-mini-item"><i class="fas fa-undo"></i> Đổi trả trong 7 ngày nếu lỗi</div>
                <div class="trust-mini-item"><i class="fas fa-headset"></i> Hỗ trợ 24/7 qua chat</div>
            </div>
        </div>

        {{-- AdSense sidebar dưới summary --}}
        <div class="sidebar-widget p-0 mt-4">
            <div class="ad-label" style="border-radius:16px 16px 0 0;">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:250px;border-radius:0 0 16px 16px;overflow:hidden;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-slot="4989157975" data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>
    </div>

    @else

    {{-- EMPTY CART --}}
    <div class="empty-cart" style="grid-column:1/-1;">
        <div style="font-size:72px;color:#e5e7eb;margin-bottom:20px;"><i class="fas fa-shopping-cart"></i></div>
        <h3 style="font-size:1.4rem;font-weight:800;color:var(--dark);margin-bottom:10px;">Giỏ hàng của bạn đang trống</h3>
        <p style="color:var(--muted);margin-bottom:20px;">Hãy khám phá các sản phẩm số chất lượng của chúng tôi!</p>
        <a href="{{ route('shop') }}" style="display:inline-flex;align-items:center;gap:7px;padding:12px 28px;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;font-weight:800;font-size:0.95rem;text-decoration:none;">
            <i class="fas fa-shopping-bag"></i> Mua sắm ngay
        </a>

        {{-- AdSense empty cart --}}
        <div class="ad-block mt-5" style="max-width:600px;margin:28px auto 0;">
            <div class="ad-label">Quảng Cáo</div>
            <ins class="adsbygoogle" style="display:block;min-height:90px;"
                data-ad-client="ca-pub-3065867660863139"
                data-ad-format="auto" data-full-width-responsive="true"
                data-ad-slot="4989157975"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        </div>
    </div>

    @endif

</div>{{-- end cart-layout --}}

@endsection

@push('scripts')
<script>AOS.init({ duration: 700, once: true });</script>
@endpush
