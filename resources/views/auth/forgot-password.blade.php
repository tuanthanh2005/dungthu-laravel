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
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 750px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 1;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .avatar-circle {
            width: 170px;
            height: 170px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 6px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 70px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 10px;
            text-align: center;
        }

        .auth-subtitle {
            text-align: center;
            color: #8c98a4;
            margin-bottom: 30px;
            font-size: 13px;
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

        .auth-input {
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

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.12);
        }

        .auth-btn {
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
            margin-top: 5px;
            box-shadow: 0 8px 16px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px rgba(87, 184, 70, 0.25);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #8c98a4;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                max-width: 400px;
            }
            .auth-left {
                padding: 30px 20px;
            }
            .avatar-circle {
                width: 130px;
                height: 130px;
            }
            .avatar-circle i {
                font-size: 60px;
            }
            .auth-right {
                padding: 30px 25px;
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Left Side -->
        <div class="auth-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-key"></i>
            </div>
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <h2 class="auth-title">Quên Mật Khẩu?</h2>
            <p class="auth-subtitle">Nhập email để nhận link đặt lại mật khẩu</p>

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-3 py-2 px-3 small shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success border-0 rounded-4 mb-3 py-2 px-3 small shadow-sm" role="alert">
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
