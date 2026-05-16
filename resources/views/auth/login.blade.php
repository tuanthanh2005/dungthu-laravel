@extends('layouts.app')

@section('title', 'Đăng Nhập - DungThu.com')

@push('styles')
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 780px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-left {
            flex: 1;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .avatar-circle {
            width: 180px;
            height: 180px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 6px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 80px;
            color: #dee2e6;
        }

        .login-right {
            flex: 1.2;
            padding: 45px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 26px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 18px;
            color: #adb5bd;
            font-size: 16px;
        }

        .login-input {
            width: 100%;
            padding: 13px 20px 13px 50px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            color: #495057;
        }

        .login-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.12);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            box-shadow: 0 8px 16px rgba(87, 184, 70, 0.2);
        }

        .login-btn:hover {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px rgba(87, 184, 70, 0.25);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #8c98a4;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #667eea;
        }

        .google-login-btn {
            width: 100%;
            padding: 12px;
            background: white;
            color: #495057;
            border: 1.5px solid #e9ecef;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .google-login-btn:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
        }

        .register-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #8c98a4;
        }

        .register-text a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 400px;
            }
            .login-left {
                padding: 30px 20px;
            }
            .avatar-circle {
                width: 140px;
                height: 140px;
            }
            .avatar-circle i {
                font-size: 60px;
            }
            .login-right {
                padding: 30px 25px;
            }
            .login-title {
                font-size: 22px;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-card">
        <!-- Left Side -->
        <div class="login-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <h2 class="login-title">User Login</h2>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-3 py-2 px-3 small shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="login-input" placeholder="Email Id" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="login-input" placeholder="Password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember" style="font-size: 12px;">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>

                <a href="{{ route('password.request') }}" class="forgot-link">
                    Forgot Username / Password?
                </a>

                <div class="position-relative my-3">
                    <hr class="text-muted opacity-25">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted" style="font-size: 11px;">OR</span>
                </div>

                <a href="{{ url('/auth/google/redirect') }}" class="google-login-btn">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="16" alt="Google">
                    Login with Google
                </a>
            </form>

            <p class="register-text">
                Don't have an account? <a href="{{ route('register') }}">Sign up now</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // No extra scripts needed for now
    </script>
@endpush