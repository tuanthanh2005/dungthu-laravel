@extends('layouts.app')

@section('title', 'Đăng nhập Cộng tác viên | DungThu')
@section('meta_description', 'Đăng nhập vào trang quản lý cộng tác viên DungThu.com')

@push('styles')
<style>
:root {
    --aff-primary: #6c63ff;
    --aff-secondary: #48cfad;
    --aff-dark: #1a1a2e;
    --aff-card: rgba(255,255,255,0.08);
}

.aff-auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 20px 60px;
    position: relative;
    overflow: hidden;
}

.aff-auth-wrapper::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(108,99,255,0.15) 0%, transparent 70%);
    top: -100px; right: -100px;
    border-radius: 50%;
    pointer-events: none;
}

.aff-auth-wrapper::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(72,207,173,0.1) 0%, transparent 70%);
    bottom: -80px; left: -80px;
    border-radius: 50%;
    pointer-events: none;
}

.aff-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 24px;
    padding: 48px 40px;
    width: 100%;
    max-width: 460px;
    position: relative;
    z-index: 2;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
}

.aff-logo {
    text-align: center;
    margin-bottom: 32px;
}

.aff-logo .badge-ctv {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #6c63ff, #48cfad);
    color: white;
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.aff-logo h1 {
    color: white;
    font-size: 26px;
    font-weight: 800;
    margin: 0;
}

.aff-logo p {
    color: rgba(255,255,255,0.6);
    font-size: 14px;
    margin-top: 6px;
}

.aff-form-group {
    margin-bottom: 20px;
}

.aff-form-group label {
    color: rgba(255,255,255,0.8);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    letter-spacing: 0.5px;
}

.aff-input {
    background: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    color: white !important;
    border-radius: 12px !important;
    padding: 14px 16px !important;
    font-size: 15px !important;
    transition: all 0.3s ease;
    width: 100%;
}

.aff-input:focus {
    background: rgba(255,255,255,0.12) !important;
    border-color: #6c63ff !important;
    box-shadow: 0 0 0 3px rgba(108,99,255,0.2) !important;
    outline: none;
}

.aff-input::placeholder {
    color: rgba(255,255,255,0.35) !important;
}

.aff-btn-primary {
    background: linear-gradient(135deg, #6c63ff, #48cfad);
    border: none;
    color: white;
    font-weight: 700;
    padding: 14px;
    border-radius: 12px;
    font-size: 16px;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.aff-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(108,99,255,0.4);
}

.aff-divider {
    text-align: center;
    margin: 24px 0;
    color: rgba(255,255,255,0.4);
    font-size: 13px;
    position: relative;
}

.aff-divider::before, .aff-divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: rgba(255,255,255,0.1);
}
.aff-divider::before { left: 0; }
.aff-divider::after { right: 0; }

.aff-link {
    color: #48cfad;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}
.aff-link:hover { color: #6c63ff; }

.aff-alert-error {
    background: rgba(255,75,75,0.15);
    border: 1px solid rgba(255,75,75,0.3);
    color: #ff9999;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    margin-bottom: 20px;
}

.float-shape {
    position: absolute;
    border-radius: 50%;
    animation: floatShape 6s ease-in-out infinite;
    pointer-events: none;
}
.float-shape-1 {
    width: 80px; height: 80px;
    background: rgba(108,99,255,0.2);
    top: 15%; left: 5%;
    animation-delay: 0s;
}
.float-shape-2 {
    width: 50px; height: 50px;
    background: rgba(72,207,173,0.2);
    top: 60%; right: 8%;
    animation-delay: 2s;
}
.float-shape-3 {
    width: 100px; height: 100px;
    background: rgba(108,99,255,0.1);
    bottom: 15%; left: 15%;
    animation-delay: 4s;
}
@keyframes floatShape {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}
</style>
@endpush

@section('content')
<div class="aff-auth-wrapper">
    <div class="float-shape float-shape-1"></div>
    <div class="float-shape float-shape-2"></div>
    <div class="float-shape float-shape-3"></div>

    <div class="aff-card" data-aos="fade-up">
        <div class="aff-logo">
            <div class="badge-ctv">
                <i class="fas fa-handshake"></i>
                CỘNG TÁC VIÊN
            </div>
            <h1>Đăng nhập</h1>
            <p>Chào mừng trở lại! Quản lý thu nhập của bạn.</p>
        </div>

        @if(session('error'))
            <div class="aff-alert-error">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="aff-alert-error">
                @foreach($errors->all() as $err)
                    <div><i class="fas fa-times-circle me-2"></i>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('affiliate.login.post') }}">
            @csrf
            <div class="aff-form-group">
                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                <input type="email" id="email" name="email" class="aff-input"
                       placeholder="email@example.com" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="aff-form-group">
                <label for="password"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                <input type="password" id="password" name="password" class="aff-input"
                       placeholder="Nhập mật khẩu" required autocomplete="current-password">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-24" style="margin-bottom:20px;">
                <label class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.7);font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:#6c63ff;"> Ghi nhớ đăng nhập
                </label>
            </div>

            <button type="submit" class="aff-btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
            </button>
        </form>

        <div class="aff-divider">hoặc</div>

        <div class="text-center" style="color:rgba(255,255,255,0.6);font-size:14px;">
            Chưa có tài khoản?
            <a href="javascript:void(0)" class="aff-link ms-1" data-bs-toggle="modal" data-bs-target="#contactAdminModal">Đăng ký ngay</a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="aff-link" style="font-size:13px;opacity:0.7;">
                <i class="fas fa-arrow-left me-1"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>
<!-- Contact Admin Modal -->
<div class="modal fade" id="contactAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #1a1a2e; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-info-circle me-2" style="color: #48cfad;"></i>Thông báo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="fas fa-user-plus fa-4x" style="color: #6c63ff;"></i>
                </div>
                <h5 class="text-white mb-3">Đăng ký Cộng Tác Viên</h5>
                <p style="color: rgba(255,255,255,0.7); font-size: 15px;">Bạn hãy liên hệ Admin để cấp tài khoản nhé!</p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="https://t.me/specademy" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #6c63ff, #48cfad); border: none;">
                        <i class="fab fa-telegram me-2"></i>Liên hệ Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
