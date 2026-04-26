@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu - DungThu.com')

@push('styles')
<style>
    .reset-password-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .reset-password-card {
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
    .password-strength {
        height: 4px;
        background: #e9ecef;
        border-radius: 4px;
        margin-top: 8px;
        overflow: hidden;
    }
    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
    }
    .password-strength.weak .password-strength-bar { width: 33%; background: #dc3545; }
    .password-strength.medium .password-strength-bar { width: 66%; background: #ffc107; }
    .password-strength.strong .password-strength-bar { width: 100%; background: #28a745; }
    @media (max-width: 991.98px) {
        .reset-password-container {
            padding: 20px 15px;
            min-height: auto;
            align-items: flex-start;
        }
        .reset-password-card {
            margin-top: 10px;
            padding: 25px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .reset-password-card h2 {
            font-size: 22px;
            margin-bottom: 15px !important;
        }
        .btn.btn-lg {
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
            font-size: 15px;
        }
        .form-control-lg {
            font-size: 14px;
            padding: 0.6rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="reset-password-container">
    <div class="reset-password-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">🔑 Đặt Lại Mật Khẩu</h2>
            <p class="text-muted">Nhập mật khẩu mới để bảo vệ tài khoản của bạn</p>
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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-envelope me-2 text-primary"></i>Email
                </label>
                <input type="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ $email ?? old('email') }}"
                       readonly>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-lock me-2 text-primary"></i>Mật Khẩu Mới
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           id="password"
                           name="password" 
                           placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength weak" id="strengthMeter">
                    <div class="password-strength-bar"></div>
                </div>
                <small class="text-muted d-block mt-2">
                    ✓ Mật khẩu mạnh nên có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số
                </small>
                @error('password')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-check-circle me-2 text-primary"></i>Xác Nhận Mật Khẩu
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation"
                           name="password_confirmation" 
                           placeholder="Nhập lại mật khẩu mới"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill mb-3">
                <i class="fas fa-check me-2"></i>Cập Nhật Mật Khẩu
            </button>

            <div class="text-center">
                <p class="mb-0">
                    <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">
                        ← Quay lại đăng nhập
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = this.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

document.getElementById('toggleConfirm').addEventListener('click', function() {
    const input = document.getElementById('password_confirmation');
    const icon = this.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Password strength meter
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const meter = document.getElementById('strengthMeter');
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    meter.classList.remove('weak', 'medium', 'strong');
    
    if (strength < 2) {
        meter.classList.add('weak');
    } else if (strength < 4) {
        meter.classList.add('medium');
    } else {
        meter.classList.add('strong');
    }
});
</script>
@endsection
