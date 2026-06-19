@extends('layouts.app')

@section('title', __('Quên Mật Khẩu') . ' - DungThu.com')

@push('styles')
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 650px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 0.8;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .avatar-circle {
            width: 130px;
            height: 130px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            border: 5px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 55px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 35px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 8px;
            text-align: center;
        }

        .auth-subtitle {
            text-align: center;
            color: #8c98a4;
            margin-bottom: 25px;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 15px;
            color: #adb5bd;
            font-size: 14px;
        }

        .auth-input {
            width: 100%;
            padding: 11px 15px 11px 42px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.1);
        }

        .auth-btn {
            width: 100%;
            padding: 12px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 3px;
            box-shadow: 0 6px 12px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(87, 184, 70, 0.25);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #8c98a4;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                max-width: 380px;
            }
            .auth-left {
                padding: 25px 15px;
            }
            .avatar-circle {
                width: 100px;
                height: 100px;
            }
            .avatar-circle i {
                font-size: 45px;
            }
            .auth-right {
                padding: 25px 20px;
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
            <h2 class="auth-title">{{ __('Quên Mật Khẩu?') }}</h2>
            <p class="auth-subtitle">{{ __('Nhập email để nhận link đặt lại mật khẩu') }}</p>

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-2 py-1 px-3 small shadow-sm" role="alert" style="font-size: 11px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success border-0 rounded-4 mb-2 py-1 px-3 small shadow-sm" role="alert" style="font-size: 11px;">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="auth-input" placeholder="{{ __('Email của bạn') }}" value="{{ old('email') }}" required>
                    </div>
                </div>

                <button type="submit" class="auth-btn">
                    {{ __('Gửi Link Đặt Lại') }}
                </button>

                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> {{ __('Quay lại Đăng nhập') }}
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
