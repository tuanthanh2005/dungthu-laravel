@extends('layouts.app')

@section('title', 'Quên Mật Khẩu - DungThu.com')

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
            margin-bottom: 20px;
            text-align: center;
        }

        .auth-subtitle {
            text-align: center;
            color: #636e72;
            margin-bottom: 40px;
            font-size: 15px;
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

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

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

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #6c757d;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                max-width: 450px;
            }
            .auth-left {
                padding: 40px 20px;
            }
            .avatar-circle {
                width: 150px;
                height: 150px;
            }
            .avatar-circle i {
                font-size: 70px;
            }
            .auth-right {
                padding: 40px 30px;
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Left Side: Icon -->
        <div class="auth-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-key"></i>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-right">
            <h2 class="auth-title">Quên Mật Khẩu?</h2>
            <p class="auth-subtitle">Nhập email của bạn để nhận link đặt lại mật khẩu</p>

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="auth-input" placeholder="Email của bạn" value="{{ old('email') }}" required>
                    </div>
                </div>

                <button type="submit" class="auth-btn">
                    Gửi Link Đặt Lại
                </button>

                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại Đăng nhập
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
