@extends('layouts.app')

@section('title', 'Đăng Ký - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .auth-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 80px;
        }
        .auth-container > .container {
            display: flex;
            justify-content: center;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
        }
        .auth-left {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-right {
            padding: 60px 40px;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }
        @media (max-width: 991.98px) {
            .auth-container {
                padding: 100px 16px 60px;
                min-height: calc(100vh - 80px);
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-card" data-aos="zoom-in">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-left h-100">
                        <div>
                            <h2 class="fw-bold mb-3">Tham gia cùng chúng tôi!</h2>
                            <p class="mb-4">Tạo tài khoản để nhận những ưu đãi độc quyền và trải nghiệm mua sắm tuyệt vời.</p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-gift fs-5 me-3"></i>
                                <span>Voucher 100.000đ cho đơn đầu tiên</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-shipping-fast fs-5 me-3"></i>
                                <span>Miễn phí vận chuyển đơn từ 300k</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star fs-5 me-3"></i>
                                <span>Tích điểm đổi quà hấp dẫn</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <h3 class="fw-bold mb-4">Đăng Ký Tài Khoản</h3>
                        
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Nguyễn Văn A"
                                           value="{{ old('name') }}"
                                           required>
                                </div>
                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="email@example.com"
                                           value="{{ old('email') }}"
                                           required>
                                </div>
                                @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Tối thiểu 6 ký tự"
                                           required>
                                </div>
                                @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a> 
                                    và <a href="#" class="text-primary">Chính sách bảo mật</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                                <i class="fas fa-user-plus me-2"></i> Đăng Ký
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a>
                            </p>
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
        AOS.init({ duration: 800, once: true });
    </script>
@endpush
