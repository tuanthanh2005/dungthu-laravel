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

        .login-left {
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

        .login-right {
            flex: 1.2;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 40px;
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

        .login-input {
            width: 100%;
            padding: 16px 20px 16px 55px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 16px;
            font-weight: 500;
            color: #495057;
        }

        .login-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .login-btn {
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

        .login-btn:hover {
            background: #4cae4c;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(87, 184, 70, 0.3);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #667eea;
        }

        .google-login-btn {
            width: 100%;
            padding: 14px;
            background: white;
            color: #495057;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .google-login-btn:hover {
            background: #f8f9fa;
            border-color: #ced4da;
        }

        .register-text {
            text-align: center;
            margin-top: 30px;
            font-size: 15px;
            color: #6c757d;
        }

        .register-text a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
            }
            .login-left {
                padding: 40px 20px;
            }
            .avatar-circle {
                width: 150px;
                height: 150px;
            }
            .avatar-circle i {
                font-size: 70px;
            }
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-card">
        <!-- Left Side: Icon -->
        <div class="login-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-right">
            <h2 class="login-title">User Login</h2>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm" role="alert">
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

                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">
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

                <div class="position-relative my-4">
                    <hr class="text-muted opacity-25">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">OR</span>
                </div>

                <a href="{{ url('/auth/google/redirect') }}" class="google-login-btn">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google">
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