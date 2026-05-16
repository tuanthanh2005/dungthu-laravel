@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu - DungThu.com')

@push('styles')
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 900px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .avatar-circle {
            width: 250px;
            height: 250px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 8px solid #f1f3f5;
        }

        .avatar-circle i {
            font-size: 120px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 30px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 20px;
            color: #adb5bd;
            font-size: 18px;
        }

        .auth-input {
            width: 100%;
            padding: 14px 20px 14px 55px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            color: #495057;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            cursor: pointer;
            color: #adb5bd;
            z-index: 10;
        }

        .password-strength {
            height: 6px;
            background: #e9ecef;
            border-radius: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            overflow: hidden;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.4s ease;
        }

        .strength-weak { background: #ff4757; width: 33%; }
        .strength-medium { background: #ffa502; width: 66%; }
        .strength-strong { background: #2ed573; width: 100%; }

        .auth-btn {
            width: 100%;
            padding: 16px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(87, 184, 70, 0.3);
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                max-width: 450px;
            }
            .auth-left { padding: 40px 20px; }
            .avatar-circle { width: 150px; height: 150px; }
            .avatar-circle i { font-size: 70px; }
            .auth-right { padding: 40px 30px; }
        }
    </style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Left Side -->
        <div class="auth-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-lock-open"></i>
            </div>
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <h2 class="auth-title">Đặt Lại Mật Khẩu</h2>

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="auth-input" value="{{ $email ?? old('email') }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="auth-input" placeholder="Mật khẩu mới" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)"></i>
                    </div>
                </div>

                <div class="password-strength">
                    <div id="strengthMeter" class="password-strength-bar"></div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="auth-input" placeholder="Xác nhận mật khẩu" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePass('password_confirmation', this)"></i>
                    </div>
                </div>

                <button type="submit" class="auth-btn">
                    Cập Nhật Mật Khẩu
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePass(id, icon) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const meter = document.getElementById('strengthMeter');
        
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        meter.className = 'password-strength-bar';
        if (password.length === 0) {
            // no bar
        } else if (strength < 2) {
            meter.classList.add('strength-weak');
        } else if (strength < 4) {
            meter.classList.add('strength-medium');
        } else {
            meter.classList.add('strength-strong');
        }
    });
</script>
@endsection
