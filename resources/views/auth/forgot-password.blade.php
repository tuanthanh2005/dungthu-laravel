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
            <h2 class="auth-title">{{ __('Khôi Phục Mật Khẩu') }}</h2>
            <p class="auth-subtitle mb-3">{{ __('Liên hệ Quản trị viên để cấp lại mật khẩu') }}</p>

            <div class="alert alert-info border-0 rounded-4 mb-3 py-2 px-3 small shadow-sm text-start" style="font-size: 13px; background: #f0fdf4; border: 1px solid #bbf7d0 !important; color: #166534;">
                <i class="fas fa-shield-alt text-success me-1"></i>
                {{ __('Để bảo mật tối đa cho tài khoản, tính năng tự động gửi link đặt lại mật khẩu qua email đã tạm khóa. Vui lòng liên hệ Admin qua Zalo hoặc Telegram để được cấp lại mật khẩu nhanh chóng (1-3 phút)!') }}
            </div>

            <div class="d-flex flex-column gap-2 mb-3">
                <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="btn btn-primary fw-bold rounded-pill shadow-sm py-2" style="background: #0068ff; border: none; font-size: 0.95rem;">
                    <i class="fas fa-comment-dots me-2"></i>{{ __('Chat Zalo Admin') }}
                </a>
                <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" class="btn btn-info fw-bold rounded-pill shadow-sm py-2 text-white" style="background: #0088cc; border: none; font-size: 0.95rem;">
                    <i class="fab fa-telegram-plane me-2"></i>{{ __('Telegram Admin') }}
                </a>
            </div>

            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left me-2"></i> {{ __('Quay lại Đăng nhập') }}
            </a>
        </div>
    </div>
</div>
@endsection
