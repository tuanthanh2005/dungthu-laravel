@extends('layouts.app')

@section('title', 'Cổng bảo mật Admin')

@push('styles')
<style>
    .verify-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 20px;
    }

    .verify-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    .lock-icon {
        width: 80px;
        height: 80px;
        background: #f0f4f8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #2a5298;
        margin: 0 auto 25px;
        border: 2px solid #d9e2ec;
    }

    .custom-input {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s;
        width: 100%;
    }

    .custom-input:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.15);
        outline: none;
    }

    .btn-verify {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        width: 100%;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
    }

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(42, 82, 152, 0.3);
        color: white;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="verify-wrapper">
    <div class="verify-card">
        <div class="lock-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h3 class="fw-bold mb-2">Cổng bảo mật Admin</h3>
        <p class="text-muted mb-4">Vui lòng hoàn thành xác thực 2 lớp để truy cập trang quản trị.</p>

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4 text-start">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.verify-pin.post') }}" method="POST">
            @csrf
            
            <div class="mb-3 text-start">
                <label class="form-label fw-semibold text-dark">Bước 1: Mật khẩu cổng bảo mật</label>
                <input type="password" name="gate_password" class="custom-input" placeholder="Nhập mật khẩu bí mật" required autofocus>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label fw-semibold text-dark">Bước 2: Xác nhận vai trò</label>
                <input type="password" name="role_name" class="custom-input" placeholder="Nhập vai trò của bạn" required autocomplete="off">
            </div>

            <button type="submit" class="btn-verify">
                <i class="fas fa-key me-1"></i> Xác thực & Vào hệ thống
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Quay lại Trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
