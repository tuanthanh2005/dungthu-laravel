@extends('layouts.app')

@section('title', 'Quên Mật Khẩu - DungThu.com')

@push('styles')
<style>
    .forgot-password-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .forgot-password-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        padding: 50px 40px;
        width: 100%;
        max-width: 500px;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
    }
</style>
@endpush

@section('content')
<div class="forgot-password-container">
    <div class="forgot-password-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">🔐 Quên Mật Khẩu?</h2>
            <p class="text-muted">Nhập email của bạn để nhận link đặt lại mật khẩu</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Lỗi!</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✅ Thành công!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-envelope me-2 text-primary"></i>Email của bạn
                </label>
                <input type="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="example@gmail.com"
                       required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill mb-3">
                <i class="fas fa-paper-plane me-2"></i>Gửi Link Đặt Lại Mật Khẩu
            </button>

            <div class="text-center">
                <p class="mb-0">
                    Nhớ mật khẩu rồi? 
                    <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Đăng nhập</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
