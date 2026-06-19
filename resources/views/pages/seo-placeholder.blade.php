@extends('layouts.app')

@section('title', __('Mua tài khoản :title giá rẻ | Bản quyền chính hãng - DungThu.com', ['title' => $keywordTitle]))
@section('meta_description', __('Dịch vụ cấp tài khoản :title Pro giá rẻ, uy tín, bảo hành đầy đủ tại DungThu.com. Đăng ký nhận thông báo pre-order ngay hôm nay.', ['title' => $keywordTitle]))
@section('meta_keywords', 'mua tai khoan ' . strtolower($keywordTitle) . ', cap tai khoan ' . strtolower($keywordTitle) . ', ' . strtolower($keywordTitle) . ' gia re, ' . strtolower($keywordTitle) . ' pro')

@push('styles')
<style>
    .seo-preorder-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #311042 50%, #0f172a 100%);
        color: #ffffff;
        border-radius: 24px;
        padding: 60px 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        position: relative;
        overflow: hidden;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    .seo-preorder-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .text-gradient-purple {
        background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }

    .preorder-form-container {
        max-width: 550px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 30px;
        border-radius: 18px;
    }

    .preorder-input {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        padding: 14px 20px;
        border-radius: 12px;
        font-weight: 500;
        color: #1e293b;
        font-size: 1rem;
    }

    .preorder-input:focus {
        box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.4);
        outline: none;
    }

    .preorder-submit-btn {
        background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 14px 28px;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    }

    .preorder-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(236, 72, 153, 0.5);
    }

    /* Product card style */
    .related-title {
        font-weight: 800;
        font-size: 1.6rem;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-card-custom {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .ai-card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .ai-card-img-wrap {
        height: 160px;
        overflow: hidden;
        position: relative;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ai-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .ai-card-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1e293b;
        margin-bottom: 8px;
        line-clamp: 2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 48px;
    }

    .ai-card-price-row {
        margin-top: auto;
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 15px;
    }

    .ai-card-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ef4444;
    }

    .ai-card-price-old {
        font-size: 0.85rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .ai-card-btn {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: none;
    }

    .ai-card-custom:hover .ai-card-btn {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Hero Banner Section -->
    <div class="seo-preorder-hero text-center" data-aos="fade-up">
        <div class="mb-4">
            <span class="badge bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem; background: rgba(99, 102, 241, 0.15);">
                <i class="fa-solid fa-hourglass-half me-1"></i> {{ __('Dự án / Sản phẩm sắp ra mắt') }}
            </span>
        </div>
        
        <h1 class="display-4 fw-bold mb-3">
            {{ __('Tài Khoản') }} <span class="text-gradient-purple">{{ $keywordTitle }} Pro</span>
        </h1>
        
        <p class="lead mx-auto mb-5 text-slate-300" style="max-width: 700px; color: #cbd5e1; font-size: 1.1rem; line-height: 1.6;">
            DungThu.com đang tiến hành thử nghiệm, liên hệ nhà phát hành và chuẩn bị nguồn hàng bản quyền chất lượng cao nhất cho sản phẩm <strong>{{ $keywordTitle }}</strong>. {{ __('Đăng ký thông tin của bạn bên dưới để nằm trong danh sách nhận thông báo sớm nhất và nhận mã giảm giá 10% khi có hàng!') }}
        </p>

        <!-- Subscription Form -->
        <div class="preorder-form-container">
            <form id="preorderForm" action="{{ route('seo.router.subscribe', $slug) }}" method="POST">
                @csrf
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <input type="email" name="email" class="form-control preorder-input" placeholder="{{ __('Nhập địa chỉ email của bạn...') }}" required>
                    <button type="submit" class="btn preorder-submit-btn flex-shrink-0">
                        <i class="fa-solid fa-bell me-1"></i> {{ __('Đăng Ký Chờ') }}
                    </button>
                </div>
            </form>
            <div class="text-start mt-2 px-1 text-slate-400" style="font-size: 0.8rem; color: #94a3b8;">
                <i class="fa-solid fa-lock me-1"></i> {{ __('Chúng tôi cam kết bảo mật 100% email và không spam quảng cáo.') }}
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($popularProducts) && $popularProducts->count() > 0)
    <div class="my-5" data-aos="fade-up" data-aos-delay="200">
        <div class="related-title">
            <i class="fa-solid fa-fire text-danger"></i>
            <h2>{{ __('Sản phẩm AI bán chạy trong khi chờ đợi') }}</h2>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($popularProducts as $p)
            <div class="col">
                <div class="ai-card-custom">
                    <div class="ai-card-img-wrap">
                        <img src="{{ $p->image ?? 'https://via.placeholder.com/300x225?text=DungThu+AI' }}" alt="{{ $p->name }}" loading="lazy">
                    </div>
                    <div class="ai-card-body">
                        <h3 class="ai-card-title">{{ $p->name }}</h3>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-bottom: 12px;">
                            {{ __('Còn lại') }}: <span class="{{ $p->stock <= 0 ? 'text-danger' : 'text-success' }}">{{ $p->stock > 0 ? $p->stock . ' ' . __('tài khoản') : __('Đặt trước') }}</span>
                        </div>
                        
                        <div class="ai-card-price-row">
                            <span class="ai-card-price">{{ $p->formatted_price }}</span>
                            @if($p->is_on_sale)
                                <span class="ai-card-price-old">{{ $p->formatted_original_price }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('product.show', $p->slug) }}" class="ai-card-btn">
                            <i class="fa-solid fa-cart-shopping"></i> {{ __('Xem Chi Tiết') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const preorderForm = document.getElementById('preorderForm');
    
    if (preorderForm) {
        preorderForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const action = this.getAttribute('action');
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>' + @json(__('Đang xử lý...'));
            
            fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: @json(__('Đăng ký chờ thành công!')),
                        text: data.message,
                        confirmButtonText: @json(__('Đóng')),
                        confirmButtonColor: '#6366f1'
                    });
                    preorderForm.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: @json(__('Có lỗi xảy ra')),
                        text: data.message || @json(__('Vui lòng kiểm tra lại email.')),
                        confirmButtonText: @json(__('Đóng')),
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                
                Swal.fire({
                    icon: 'error',
                    title: @json(__('Lỗi kết nối')),
                    text: @json(__('Không thể kết nối đến máy chủ. Vui lòng thử lại sau.')),
                    confirmButtonText: @json(__('Đóng')),
                    confirmButtonColor: '#ef4444'
                });
                console.error('Error:', error);
            });
        });
    }
});
</script>
@endpush
