@extends('layouts.app')

@section('title', 'Đăng Nhập - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .auth-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .social-login {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .social-btn {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
            transition: all 0.3s;
        }
        .social-btn:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
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
                            <h2 class="fw-bold mb-3">Chào mừng trở lại!</h2>
                            <p class="mb-4">Đăng nhập để trải nghiệm mua sắm tuyệt vời với hàng ngàn sản phẩm chất lượng.</p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle fs-5 me-3"></i>
                                <span>Ưu đãi độc quyền cho thành viên</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle fs-5 me-3"></i>
                                <span>Theo dõi đơn hàng dễ dàng</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fs-5 me-3"></i>
                                <span>Hỗ trợ khách hàng 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <h3 class="fw-bold mb-4">Đăng Nhập</h3>
                        
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
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
                                           placeholder="••••••••"
                                           required>
                                </div>
                                @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="javascript:void(0)" class="text-decoration-none" title="Liên hệ admin để reset mật khẩu">Quên mật khẩu?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng Nhập
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                            </p>
                        </div>

                        <div class="position-relative my-4">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">Hoặc đăng nhập bằng</span>
                        </div>

                        <a href="{{ url('/auth/google/redirect') }}" class="btn btn-outline-danger w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="fab fa-google me-2"></i> Đăng Nhập Bằng Google
                        </a>
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
